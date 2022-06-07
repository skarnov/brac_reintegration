<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'project' => $filter_project,
    'name' => $filter_name,
    'branch' => $filter_branch,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_event_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$events = $this->get_events($args);
$pagination = pagination($events['total'], $per_page_items, $start);

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . strtolower($subdistrict['name']) . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$branchName = $branches->get_branches(array('id' => $filter_branch, 'single' => true));

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_branch)
    $filterString[] = 'Branch: ' . $branchName['branch_name'];
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_union)
    $filterString[] = 'Union: ' . $filter_union;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_csv']) {
    $args['report'] = true;
    $args['select_fields'] = array(
        'entry_date' => 'dev_events.entry_date',
        'create_date' => 'dev_events.create_date',
        'branch_name' => 'dev_branches.branch_name',
        'project_short_name' => 'dev_projects.project_short_name',
        'activity_name' => 'dev_activities.activity_name',
        'year' => 'dev_events.year',
        'month' => 'dev_events.month',
        'event_start_date' => 'dev_events.event_start_date',
        'event_start_time' => 'dev_events.event_start_time',
        'event_end_date' => 'dev_events.event_end_date',
        'event_end_time' => 'dev_events.event_end_time',
        'event_division' => 'dev_events.event_division',
        'event_district' => 'dev_events.event_district',
        'event_upazila' => 'dev_events.event_upazila',
        'event_union' => 'dev_events.event_union',
        'event_location' => 'dev_events.event_location',
        'event_village' => 'dev_events.event_village',
        'event_ward' => 'dev_events.event_ward',
        'participant_male' => 'dev_events.participant_male',
        'participant_female' => 'dev_events.participant_female',
        'participant_boy' => 'dev_events.participant_boy',
        'participant_girl' => 'dev_events.participant_girl',
        'observation_score' => 'dev_events.observation_score',
        'created_by' => 'dev_events.created_by',
    );
    unset($args['limit']);
    $data = $this->get_events($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Event_Management_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Event Management Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Branch Name',
        'Project Name',
        'Activity Name',
        'Year',
        'Month',
        'Start Date',
        'Start Time',
        'End Date',
        'End Time',
        'Division',
        'District',
        'Upazila',
        'Union',
        'Event Location',
        'Event Village',
        'Event Ward',
        'Man Participant',
        'Woman Participant',
        'Boy Participant',
        'Girl Participant',
        'Observation Score',
        'Submitted by',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['entry_date'] && $value['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['entry_date'])) : 'N/A'
                , date('d-m-Y', strtotime($value['create_date']))
                , $value['branch_name']
                , $value['project_short_name']
                , $value['activity_name']
                , $value['year']
                , get_months()[$value['month']]
                , $value['event_start_date'] && $value['event_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['event_start_date'])) : 'N/A'
                , $value['event_start_time']
                , $value['event_end_date'] && $value['event_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['event_end_date'])) : 'N/A'
                , $value['event_end_time']
                , ucfirst($value['event_division'])
                , ucfirst($value['event_district'])
                , ucfirst($value['event_upazila'])
                , ucfirst($value['event_union'])
                , ucfirst($value['event_location'])
                , ucfirst($value['event_village'])
                , ucfirst($value['event_ward'])
                , $value['participant_male']
                , $value['participant_female']
                , $value['participant_boy']
                , $value['participant_girl']
                , $value['observation_score']
                , $value['user_fullname']
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
    <div class="row">
        <div class="col-md-8">
            <h1>All Events</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_event',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'Add Event',
                        'title' => 'Add Event',
                    ));
                    ?>
                </div>
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_csv=1&name=' . $filter_name . '&project_id=' . $filter_project . '&branch_id=' . $filter_branch . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Events',
                        'title' => 'Download Events',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $events['total'] ?></span><br>
                    <span class="text-sm">Stored in Database</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-cell bg-warning padding-sm no-padding-t text-center">
                    <div id="stats-sparklines-2" class="stats-sparklines" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Event Name',
        ), $filter_name);
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
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control division" name="division" style="text-transform: capitalize">
            <?php if ($filter_division) : ?>
                <option value="<?php echo $filter_division ?>"><?php echo $filter_division ?></option>
            <?php else: ?>
                <option value="">Select One</option>
            <?php endif ?>
            <?php foreach ($divisions as $division) : ?>
                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>"><?php echo $division['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
            <?php if ($filter_district) : ?>
                <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Upazila</label>
    <div class="select2-primary">
        <select class="form-control subdistrict" name="sub_district" id="subdistrictList" style="text-transform: capitalize">
            <?php if ($filter_sub_district) : ?>
                <option value="<?php echo $filter_sub_district ?>"><?php echo $filter_sub_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Union</label>
    <div class="select2-primary">
        <select class="form-control union" name="union" id="unionList" style="text-transform: capitalize">
            <?php if ($filter_union) : ?>
                <option value="<?php echo $filter_union ?>"><?php echo $filter_union ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
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
        <input id="endDate" type="text" class="form-control" name="entry_end_date" value="<?php echo $filter_entry_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
</div>
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
        <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'events') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project Name</th>-->
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Union</th>
                <th>Submitted By</th>
                <th>Participant Number</th>
                <th>Event Validation Count</th>
                <th>Observation Score</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events['data'] as $i => $event) {
                ?>
                <tr>
    <!--                    <td><?php echo $event['project_short_name'] ?></td>-->
                    <td><?php echo $event['activity_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_start_date'])) ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_end_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_district']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_upazila']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_union']; ?></td>
                    <td><?php echo $event['user_fullname']; ?></td>
                    <td><?php echo $event['participant_boy'] + $event['participant_girl'] + $event['participant_male'] + $event['participant_female'] ?></td>
                    <td><?php echo $event['validation_count']; ?></td>
                    <td><?php echo $event['observation_score']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_events?action=add_edit_event&edit=' . $event['pk_event_id']) ?>">Edit</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_event_validations&event_id=' . $event['pk_event_id']) ?>">Event Validation</a></li>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_events?action=download_pdf&id=' . $event['pk_event_id']) ?>">Download PDF</a></li>
                                </ul>
                            </div>                         
                        <?php endif ?>
                        <?php if (has_permission('delete_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $event['pk_event_id']),
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
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
                beforeSend: function () {
                    $('#districtList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#districtList').html(result);
                }}
            );
        });
        $('.district').change(function () {
            var districtId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {district_id: districtId},
                beforeSend: function () {
                    $('#subdistrictList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#subdistrictList').html(result);
                }}
            );
        });
        $('.subdistrict').change(function () {
            var subdistrictId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {subdistrict_id: subdistrictId},
                beforeSend: function () {
                    $('#unionList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#unionList').html(result);
                }}
            );
        });
    });
</script>
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
                        window.location.href = '?action=deleteEvent&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>