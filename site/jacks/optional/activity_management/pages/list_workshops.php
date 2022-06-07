<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_workshop_name = $_GET['workshop_name'] ? $_GET['workshop_name'] : null;
$filter_start_date = $_GET['start_date'] ? $_GET['start_date'] : null;
$filter_end_date = $_GET['end_date'] ? $_GET['end_date'] : null;

$args = array(
    'project' => $filter_project,
    'workshop_name' => $filter_workshop_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_workshop_id',
        'order' => 'DESC'
    ),
);

if ($filter_start_date && $filter_end_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'workshop_start_date ' => array(
            'left' => date_to_db($filter_start_date),
            'right' => date_to_db($filter_end_date),
        ),
    );
}

$workshops = $this->get_workshops($args);
$pagination = pagination($workshops['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_workshop_name)
    $filterString[] = 'Workshop Name: ' . $filter_workshop_name;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_start_date)
    $filterString[] = 'Start Date: ' . $filter_start_date;
if ($filter_end_date)
    $filterString[] = 'End Date: ' . $filter_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_workshops($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Workshop_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Workshop Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Workshop Held At',
        'Project Name',
        'Start Date',
        'End Date',
        'Name of the Workshop',
        'Duration of Workshop',
        'Division',
        'District',
        'Upazila',
        'Union',
        'Workshop Venue',
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
                , ucfirst($value['workshop_held'])
                , $value['project_short_name']
                , $value['workshop_start_date'] && $value['workshop_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['workshop_start_date'])) : 'N/A'
                , $value['workshop_end_date'] && $value['workshop_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['workshop_end_date'])) : 'N/A'
                , $value['workshop_name']
                , $value['workshop_duration']
                , ucfirst($value['event_division'])
                , ucfirst($value['event_district'])
                , ucfirst($value['event_upazila'])
                , ucfirst($value['event_union'])
                , $value['workshop_venue']
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
    <h1>All Workshops</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_workshop',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Workshop',
                'title' => 'New Workshop',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project_id=' . $filter_project . '&workshop_name=' . $filter_workshop_name . '&start_date=' . $filter_start_date . '&end_date=' . $filter_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Workshop',
                'title' => 'Download Workshop',
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
echo formProcessor::form_elements('name', 'workshop_name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Workshop Name',
        ), $filter_workshop_name);
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
        <?php echo searchResultText($workshops['total'], $start, $per_page_items, count($workshops['data']), 'workshops') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project</th>-->
                <th>Workshop Name</th>
                <th>Workshop Venue</th>
                <th>Workshop Start Date</th>
                <th>Workshop End Date</th>
                <th>Workshop Duration</th>
                <th class="text-right">Male</th>
                <th class="text-right">Female</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($workshops['data'] as $i => $workshop) {
                ?>
                <tr>
<!--                    <td><?php echo $workshop['project_short_name']; ?></td>-->
                    <td><?php echo $workshop['workshop_name']; ?></td>
                    <td><?php echo $workshop['workshop_venue']; ?></td>
                    <td><?php echo $workshop['workshop_start_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($workshop['workshop_start_date'])) ?></td>
                    <td><?php echo $workshop['workshop_end_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($workshop['workshop_end_date'])) ?></td>
                    <td><?php echo $workshop['workshop_duration']; ?></td>
                    <td class="text-right"><?php echo $workshop['male_participant']; ?></td>
                    <td class="text-right"><?php echo $workshop['female_participant']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_workshop')): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_workshops?action=add_edit_workshop&edit=' . $workshop['pk_workshop_id']) ?>">Edit</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_workshops?action=add_edit_participant&workshop_id=' . $workshop['pk_workshop_id']) ?>" target="_blank">Add Participant</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_workshops?action=participants_list&workshop_id=' . $workshop['pk_workshop_id']) ?>" target="_blank">Participants List</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_workshops?action=workshop_validation&workshop_id=' . $workshop['pk_workshop_id']) ?>">Workshop Validation</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_workshops?action=download_pdf&id=' . $workshop['pk_workshop_id']) ?>">Download PDF</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_workshop')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $workshop['pk_workshop_id']),
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
                        window.location.href = '?action=deleteWorkshop&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>