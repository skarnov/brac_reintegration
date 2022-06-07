<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;

$args = array(
    'select_fields' => array(
        'branch_name' => 'dev_branches.branch_name',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'branch' => $filter_branch,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
);

$results = $this->get_branch_reports($args);

$args = array(
    'select_fields' => array(
        'branch_name' => 'dev_branches.branch_name',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'branch' => $filter_branch,
);
$count_results = $this->get_branch_reports($args);
$results['total'] = $count_results['result_total'];
$pagination = pagination($results['total'], $per_page_items, $start);

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$branchName = $branches->get_branches(array('id' => $filter_branch, 'single' => true));

$filterString = array();
if ($filter_branch)
    $filterString[] = 'Branch: ' . $branchName['branch_name'];

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_branch_reports($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Branch_Wise_MIS_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Branch Wise MIS Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Branch Name',
        'Activity Name',
        'Activity Target',
        'Activity Achievement',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['branch_name']
                , $value['activity_name']
                , $value['activity_target']
                , $value['activity_achievement']
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
    <h1>Branch MIS Reports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&branch=' . $filter_branch,
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
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch">
            <option value="">Select One</option>
            <?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $filter_branch) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header text-capitalize">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Branch MIS Report') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Activity Name</th>
                <th>Target</th>
                <th>Achievement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['branch_name']; ?></td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td><?php echo $value['activity_target']; ?></td>
                    <td><?php echo $value['activity_achievement']; ?></td>
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