<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project'] ? $_GET['project'] : '1';
$filter_division = $_GET['division'] ? $_GET['division'] : 'Khulna';

$divisions = get_division();

$args = array(
    'select_fields' => array(
        'branch_division' => 'dev_targets.branch_division',
        'project_short_name' => 'dev_projects.project_short_name',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'division' => $filter_division,
    'project' => $filter_project,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
);

$results = $this->get_project_division_reports($args);

$args = array(
    'select_fields' => array(
        'branch_division' => 'dev_targets.branch_division',
        'project_short_name' => 'dev_projects.project_short_name',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'division' => $filter_division,
    'project' => $filter_project,
);
$count_results = $this->get_project_division_reports($args);

$results['total'] = $count_results['result_total'];
$pagination = pagination($results['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_project_division_reports($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Project_and_Division_Wise_MIS_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Project and Division Wise MIS Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Division Name',
        'Project Name',
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
                , $value['branch_division']
                , $value['project_short_name']
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
    <h1>Project and Division MIS Reports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project=' . $filter_project . '&division=' . $filter_division,
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
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control division" name="division" style="text-transform: capitalize">
            <?php if ($filter_division) : ?>
                <option value="<?php echo $filter_division ?>"><?php echo $filter_division ?></option>
            <?php endif ?>
            <option value="">Select One</option>
            <?php foreach ($divisions as $division) : ?>
                <option id="<?php echo $division['id'] ?>" value="<?php echo $division['name'] ?>"><?php echo $division['name'] ?></option>
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Project and Division MIS Report') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project</th>
                <th>Division</th>
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
                    <td><?php echo $value['project_short_name']; ?></td>
                    <td><?php echo $value['branch_division']; ?></td>
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