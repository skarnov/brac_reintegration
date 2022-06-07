<?php
if ($_GET['ajax_type']) {
    $ret = array();

    if ($_GET['ajax_type'] == 'get_project_form') {
        $ret = $this->get_project_form($_POST['edit']);
    } else if ($_GET['ajax_type'] == 'put_project_form') {
        $ret = $this->add_edit_project($_POST);
    }

    echo json_encode($ret);
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_status = $_GET['status'] ? $_GET['status'] : null;

$args = array(
    'name' => $filter_name,
    'status' => $filter_status,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_project_id',
        'order' => 'DESC'
    ),
);

$data = $this->get_projects($args);
$pagination = pagination($data['total'], $per_page_items, $start);

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_status)
    $filterString[] = 'Status: ' . $filter_status;

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_projects($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'projects-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'MIS Project Report', '');
    fputcsv($fh, $report_title);
    
    $filtered_with = array('', 'Project Status = '.$filter_status, '');
    fputcsv($fh, $filtered_with);
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Name', 'Short Name', 'Code', 'Funded By', 'Start Date', 'End Date', 'Status', 'Target');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['project_name']
                , $user['project_short_name']
                , $user['project_code']. "\r"
                , $user['project_funded_by']
                , $user['project_start']. "\r"
                , $user['project_end']. "\r"
                , $user['project_status']
                , $user['project_target']
            );
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
    <h1>Projects</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            if (has_permission('add_project')):
                echo linkButtonGenerator(array(
                    'classes' => 'add_edit_project',
                    'action' => 'add',
                    'icon' => 'icon_add',
                    'text' => 'Create Project',
                    'title' => 'Create Project',
                ));
            endif;
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&name='.$filter_name.'&status='.$filter_status,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download',
                'title' => 'Download Projects',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Project Short Name',
        ), $filter_name);
?>
<div class="form-group col-sm-2">
    <label>Project Status</label>
    <select class="form-control" name="status">
        <option value="">Select One</option>
        <option value="active" <?php if($filter_status == 'active') echo 'selected' ?>>Active</option>
        <option value="inactive" <?php if($filter_status == 'inactive') echo 'selected' ?>>Inactive</option>
    </select>
</div>
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
        <?php echo searchResultText($data['total'], $start, $per_page_items, count($data['data']), 'projects') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th>Short Name</th>
                <th>Project Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target</th>
                <th>Funded By</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data['data'] as $i => $item) {
                ?>
                <tr>
                    <td><?php echo $item['project_name']; ?></td>
                    <td><?php echo $item['project_short_name']; ?></td>
                    <td><?php echo $item['project_code']; ?></td>
                    <td class="action_column"><?php echo date_to_user($item['project_start']); ?></td>
                    <td class="action_column"><?php echo date_to_user($item['project_end']); ?></td>
                    <td><?php echo nl2br($item['project_target']); ?></td>
                    <td><?php echo nl2br($item['project_funded_by']); ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_project')): ?>
                            <?php
                            echo linkButtonGenerator(array(
                                'classes' => 'add_edit_project',
                                'attributes' => array('data-id' => $item['pk_project_id']),
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
        <div class="pull-left">
            <?php echo $pagination ?>
        </div>
    </div>
</div>
<div class="dn">
    <div id="ajax_form_container"></div>
</div>
<script type="text/javascript">
    var can_add = <?php echo has_permission('add_project') ? '1' : '0' ?>;
    var can_edit = <?php echo has_permission('edit_project') ? '1' : '0' ?>;

    init.push(function () {
        $(document).on('click', '.add_edit_project', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : 0;
            var thsRow = is_update ? ths.closest('tr') : null;

            var form_api = '<?php echo build_url(array('ajax_type' => 'get_project_form')) ?>';
            var form_submit_api = '<?php echo build_url(array('ajax_type' => 'put_project_form')) ?>';

            if (is_update && !can_edit)
                return false;
            else if (!is_update && !can_add)
                return false;

            new in_page_add_event({
                additional_data: {
                    edit: is_update,
                },
                edit_mode: true,
                edit_form_url: form_api,
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Project' : 'Add New Project',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: form_submit_api,
                callback: function (data) {
                    window.location.reload();
                }
            });
        });
    });
</script>