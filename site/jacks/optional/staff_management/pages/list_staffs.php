<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$filter_designation = $_GET['designation'] ? $_GET['designation'] : null;

$args = array(
    'name' => $filter_name,
    'branch' => $filter_branch,
    'designation' => $filter_designation,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_user_id',
        'order' => 'DESC'
    ),
);

$data = $this->get_staffs($args);
$pagination = pagination($data['total'], $per_page_items, $start);

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$filter_designation = $_GET['designation'] ? $_GET['designation'] : null;

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_branch)
    $filterString[] = 'Branch: ' . $filter_branch;
if ($filter_designation)
    $filterString[] = 'Designation: ' . $filter_designation;

$branchManagement = jack_obj('dev_branch_management');

$_branches = $branchManagement->get_branches();
$branches = array();
if ($_branches['data']) {
    foreach ($_branches['data'] as $branch) {
        $branches[$branch['pk_branch_id']] = $branch['branch_name'];
    }
}

$_designation = $this->get_lookups('staff_designation');
$designation = array();
if ($_designation['data']) {
    foreach ($_designation['data'] as $i => $v) {
        $designation[$v['pk_lookup_id']] = $v['lookup_value'];
    }
}

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_staffs($args);

    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'staff-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Staff Report', '');
    fputcsv($fh, $report_title);

    $filtered_with = array('', 'Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district, '');
    fputcsv($fh, $filtered_with);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Staff ID', 'Name', 'Branch', 'Designation', 'Gender', 'Mobile', 'Email');

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['user_name']
                , $user['user_fullname']
                , $user['branch_name']
                , $user['designation']
                , $user['user_gender']
                , $user['user_mobile'] . "\r"
                , $user['user_email']);
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder . "*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if (function_exists('apache_setenv'))
        @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename=' . basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile, "rb");
    while (!feof($file)) {
        print(@fread($file, 1024 * 8));
        ob_flush();
        flush();
    }
    @fclose($file);
    exit;
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Staffs</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            if (has_permission('add_staff')) {
                echo linkButtonGenerator(array(
                    'href' => build_url(array('action' => 'add_edit_staff')),
                    'action' => 'add',
                    'icon' => 'icon_add',
                    'text' => 'New Staff',
                    'title' => 'New Staff',
                ));
            }
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&name=' . $filter_name . '&branch=' . $filter_branch . '&designation=' . $filter_designation . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download',
                'title' => 'Download',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('name', 'name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
echo formProcessor::form_elements('branch', 'branch', array(
    'width' => 4, 'type' => 'select', 'label' => 'Branch',
    'data' => array('static' => $branches)
        ), $filter_branch);
echo formProcessor::form_elements('designation', 'designation', array(
    'width' => 4, 'type' => 'select', 'label' => 'Designation',
    'data' => array('static' => $designation)
        ), $filter_designation);
?>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($data['total'], $start, $per_page_items, count($data['data']), 'staffs') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Email</th>
                <th>Branch</th>
                <th>Designation</th>
                <th>Login Username</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data['data'] as $i => $item) {
                ?>
                <tr>
                    <td>
                        <div class="oh fl">
                            <div class="fl mrmb5" style="width: 52px; height: 52px;text-align: center;border: 1px solid #fff;">
                                <img src="<?php echo user_picture($item['user_picture']) ?>" alt="<?php echo $item['user_fullname']; ?>" style="max-height: 50px;max-width: 50px"/>
                            </div>
                        </div>
                        <div class="oh">
                            <?php echo $item['user_fullname']; ?>
                        </div>
                    </td>
                    <td><?php echo $item['user_email']; ?></td>
                    <td><?php echo $item['branch_name']; ?></td>
                    <td><?php echo $item['designation']; ?></td>
                    <td><?php echo $item['user_name']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_staff')): ?>
                            <?php
                            echo linkButtonGenerator(array(
                                'href' => build_url(array('action' => 'add_edit_staff', 'edit' => $item['pk_user_id'])),
                                'action' => 'edit',
                                'icon' => 'icon_edit',
                                'text' => 'Update',
                                'title' => 'Update',
                            ));
                            ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="table-footer oh">
        <?php echo $pagination ?>
    </div>
</div>