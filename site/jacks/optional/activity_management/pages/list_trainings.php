<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_training_name = $_GET['training_name'] ? $_GET['training_name'] : null;
$filter_start_date = $_GET['start_date'] ? $_GET['start_date'] : null;
$filter_end_date = $_GET['end_date'] ? $_GET['end_date'] : null;

$args = array(
    'project' => $filter_project,
    'training_name' => $filter_training_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_training_id',
        'order' => 'DESC'
    ),
);

if ($filter_start_date && $filter_end_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'training_start_date ' => array(
            'left' => date_to_db($filter_start_date),
            'right' => date_to_db($filter_end_date),
        ),
    );
}

$trainings = $this->get_trainings($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_training_name)
    $filterString[] = 'Training Name: ' . $filter_training_name;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_start_date)
    $filterString[] = 'Start Date: ' . $filter_start_date;
if ($filter_end_date)
    $filterString[] = 'End Date: ' . $filter_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_trainings($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Training_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Training Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Training Held At',
        'Project Name',
        'Start Date',
        'End Date',
        'Name of the training',
        'Duration of training',
        'Division',
        'District',
        'Upazila',
        'Union',
        'Training Venue',
        'Male Participant',
        'Female Participant',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['entry_date'] && $value['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['entry_date'])) : 'N/A'
                , date('d-m-Y', strtotime($value['create_date']))
                , ucfirst($value['training_held'])
                , $value['project_short_name']
                , $value['training_start_date'] && $value['training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['training_start_date'])) : 'N/A'
                , $value['training_end_date'] && $value['training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['training_end_date'])) : 'N/A'
                , $value['training_name']
                , $value['training_duration']
                , ucfirst($value['event_division'])
                , ucfirst($value['event_district'])
                , ucfirst($value['event_upazila'])
                , ucfirst($value['event_union'])
                , $value['training_venue']
                , $value['male_participant'] . "\r"
                , $value['female_participant'] . "\r"
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
    <h1>All Trainings</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_training',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Training',
                'title' => 'New Training',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project_id=' . $filter_project . '&training_name=' . $filter_training_name . '&start_date=' . $filter_start_date . '&end_date=' . $filter_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Training',
                'title' => 'Download Training',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<!--<div class="form-group col-sm-2">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>-->
<?php
echo formProcessor::form_elements('name', 'training_name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Training Name',
        ), $filter_training_name);
?>
<div class="form-group col-sm-3">
    <label>Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="start_date" value="<?php echo $filter_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="end_date" value="<?php echo $filter_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
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
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'trainings') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project</th>-->
                <th>Training Name</th>
                <th>Training Venue</th>
                <th>Training Start Date</th>
                <th>Training End Date</th>
                <th>Training Duration</th>
                <th class="text-right">Male</th>
                <th class="text-right">Female</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
<!--                    <td><?php echo $training['project_short_name']; ?></td>-->
                    <td><?php echo $training['training_name']; ?></td>
                    <td><?php echo $training['training_venue']; ?></td>
                    <td><?php echo $training['training_start_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($training['training_start_date'])) ?></td>
                    <td><?php echo $training['training_end_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($training['training_end_date'])) ?></td>
                    <td><?php echo $training['training_duration']; ?></td>
                    <td class="text-right"><?php echo $training['male_participant']; ?></td>
                    <td class="text-right"><?php echo $training['female_participant']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_trainings?action=add_edit_training&edit=' . $training['pk_training_id']) ?>">Edit</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_trainings?action=add_edit_participant&training_id=' . $training['pk_training_id']) ?>" target="_blank">Add Participant</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_trainings?action=participants_list&training_id=' . $training['pk_training_id']) ?>" target="_blank">Participants List</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_trainings?action=training_validation&training_id=' . $training['pk_training_id']) ?>">Training Validation</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_trainings?action=download_pdf&id=' . $training['pk_training_id']) ?>">Download PDF</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $training['pk_training_id']),
                                    'classes' => 'delete_single_record'));
                                ?>
                            </div>
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
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteTraining&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>