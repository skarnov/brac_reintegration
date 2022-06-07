<?php
$activity_manager = jack_obj('dev_activity_management');

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_training_name = $_GET['training_name'] ? $_GET['training_name'] : null;

$args = array(
    'select_fields' => array(
        'project_short_name' => 'dev_projects.project_short_name',
        'training_name' => 'dev_trainings.training_name',
        '*' => 'dev_training_participants.*',
    ),
    'project' => $filter_project,
    'report' => TRUE,
    'training_name' => $filter_training_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'fk_training_id',
        'order' => 'DESC'
    ),
);

$trainings = $activity_manager->get_training_participants($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_training_name)
    $filterString[] = 'Training Name: ' . $filter_training_name;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $activity_manager->get_training_participants($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Training_Participant_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Training Participant Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Project Name',
        'Entry Date',
        'Data Submission Date',
        'Beneficiary ID',
        'Participant Name',
        'Organizational Name',
        'Participant Age',
        'Participant Profession',
        'Type of Participant',
        'Participant Gender',
        'Participant Mobile',
        'Division',
        'District',
        'Upazila',
        'Police Station',
        'Post Office',
        'Municipality',
        'City Corporation',
        'Union',
        'Ward',
        'Village',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['project_short_name']
                , $value['entry_date'] && $value['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['entry_date'])) : 'N/A'
                , date('d-m-Y', strtotime($value['create_date']))
                , $value['beneficiary_id']
                , $value['participant_name']
                , $value['organizational_name']
                , $value['participant_age'] . "\r"
                , $value['participant_profession']
                , ucwords(str_replace('_', ' ', $value['participant_type']))
                , ucfirst($value['participant_gender'])
                , $value['participant_mobile'] . "\r"
                , $value['permanent_division']
                , $value['permanent_district']
                , $value['permanent_sub_district']
                , $value['permanent_police_station']
                , $value['permanent_post_office']
                , $value['permanent_municipality']
                , $value['permanent_city_corporation']
                , $value['permanent_union']
                , $value['permanent_village']
                , $value['permanent_ward']
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
    <h1>All Training Participants</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&training_name=' . $filter_training_name . '&project_id=' . $filter_project,
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
echo formProcessor::form_elements('name', 'training_name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Training Name',
        ), $filter_training_name);
?>
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
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
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'participants') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Training Name</th>
                <th>Participant Name</th>
                <th>Type</th>
                <th>Profession</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
                    <td><?php echo $training['project_short_name'] ?></td>
                    <td><?php echo $training['training_name'] ?></td>
                    <td><?php echo $training['participant_name'] ?></td>
                    <td class="text-capitalize"><?php echo $training['participant_type'] ?></td>
                    <td><?php echo $training['participant_profession'] ?></td>
                    <td><?php echo ucfirst($training['participant_gender']) ?></td>
                    <td><?php echo $training['participant_age'] ?></td>
                    <td><?php echo $training['participant_mobile'] ?></td>
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