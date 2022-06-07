<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_event', 'edit_event')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$activities = jack_obj('dev_activity_report');
$all_activities = $activities->get_misactivities();

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . $district['name'] . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . $subdistrict['name'] . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['districtIdForPoliceStation'])) {
    $policeStations = get_policeStation($_POST['districtIdForPoliceStation']);
    echo "<option value=''>Select One</option>";
    foreach ($policeStations as $policeStation) :
        echo "<option id='" . $policeStation['id'] . "' value='" . $policeStation['name'] . "'>" . $policeStation['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['districtIdForPostOffice'])) {
    $postOffices = get_postOffice($_POST['districtIdForPostOffice']);
    echo "<option value=''>Select One</option>";
    foreach ($postOffices as $postOffice) :
        echo "<option id='" . $postOffice['id'] . "' value='" . $postOffice['name'] . "'>" . $postOffice['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['districtIdForMunicipality'])) {
    $municipalities = get_municipality($_POST['districtIdForMunicipality']);
    echo "<option value=''>Select One</option>";
    foreach ($municipalities as $municipality) :
        echo "<option id='" . $municipality['id'] . "' value='" . $municipality['name'] . "'>" . $municipality['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['districtIdForCityCorporation'])) {
    $cityCorporations = get_cityCorporation($_POST['districtIdForCityCorporation']);
    echo "<option value=''>Select One</option>";
    foreach ($cityCorporations as $cityCorporation) :
        echo "<option id='" . $cityCorporation['id'] . "' value='" . $cityCorporation['name'] . "'>" . $cityCorporation['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . $union['name'] . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'fk_branch_id' => 'dev_events.fk_branch_id',
            'fk_project_id' => 'dev_events.fk_project_id',
            'entry_date' => 'dev_events.entry_date',
            'year' => 'dev_events.year',
            'month' => 'dev_events.month',
            'fk_activity_id' => 'dev_events.fk_activity_id',
            'event_start_date' => 'dev_events.event_start_date',
            'event_start_time' => 'dev_events.event_start_time',
            'event_end_date' => 'dev_events.event_end_date',
            'event_end_time' => 'dev_events.event_end_time',
            'event_division' => 'dev_events.event_division',
            'event_district' => 'dev_events.event_district',
            'event_upazila' => 'dev_events.event_upazila',
            'permanent_police_station' => 'dev_events.permanent_police_station',
            'permanent_post_office' => 'dev_events.permanent_post_office',
            'permanent_municipality' => 'dev_events.permanent_municipality',
            'permanent_city_corporation' => 'dev_events.permanent_city_corporation',
            'event_union' => 'dev_events.event_union',
            'event_village' => 'dev_events.event_village',
            'event_ward' => 'dev_events.event_ward',
            'event_location' => 'dev_events.event_location',
            'participant_boy' => 'dev_events.participant_boy',
            'participant_girl' => 'dev_events.participant_girl',
            'participant_male' => 'dev_events.participant_male',
            'participant_female' => 'dev_events.participant_female',
            'preparatory_work' => 'dev_events.preparatory_work',
            'time_management' => 'dev_events.time_management',
            'participants_attention' => 'dev_events.participants_attention',
            'logistical_arrangements' => 'dev_events.logistical_arrangements',
            'relevancy_delivery' => 'dev_events.relevancy_delivery',
            'participants_feedback' => 'dev_events.participants_feedback',
            'event_note' => 'dev_events.event_note',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_events($args);

    if (!$pre_data) {
        add_notification('Invalid event, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'month' => 'Month',
            'year' => 'Year',
            'fk_project_id' => 'Project',
            'fk_branch_id' => 'Branch',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $args = array(
        'month' => $data['form_data']['month'],
        'year' => $data['form_data']['year'],
        'fk_project_id' => $data['form_data']['project_id'],
        'fk_branch_id' => $data['form_data']['branch_id'],
        'single' => true
    );
    $target_check = $this->check_targets($args);

    if ($target_check['error']):
        $pre_data = $_POST;
        print_errors($target_check['error']);
    else:
        $data['target_info'] = $target_check;

        $ret = $this->add_edit_event($data);

        if ($ret['success']) {
            $msg = "Event has been " . ($edit ? 'updated.' : 'saved.');
            add_notification($msg);
            $activityType = $edit ? 'update' : 'create';
            user_activity::add_activity($msg, 'success', $activityType);
            if ($edit) {
                header('location: ' . url('admin/dev_activity_management/manage_events?action=add_edit_event&edit=' . $edit));
            } else {
                header('location: ' . url('admin/dev_activity_management/manage_events?action=add_edit_event'));
            }
            exit();
        } else {
            $pre_data = $_POST;
            print_errors($ret['error']);
        }
    endif;
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'Add ' ?> Event </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Events',
                'title' => 'Manage Events',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Entry Date</label>
                        <div class="input-group">
                            <input id="entry_date" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('entry_date');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Branch</label>
                        <div class="select2-primary">
                            <select class="form-control" name="fk_branch_id" required>
                                <option value="">Select One</option>
                                <?php foreach ($all_branches['data'] as $branch) : ?>
                                    <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Year</label>
                        <div class="select2-primary">
                            <select class="form-control" name="year" required>
                                <option value="">Select One</option>
                                <?php foreach (get_years() as $year) : ?>
                                    <option value="<?php echo $year ?>" <?php echo ($pre_data['year'] == $year) ? 'selected' : '' ?>><?php echo $year ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Month</label>
                        <div class="select2-primary">
                            <select class="form-control" name="month" required>
                                <option value="">Select One</option>
                                <?php foreach (get_months() as $i => $value) :
                                    ?>
                                    <option value="<?php echo $i ?>" <?php echo ($i == $pre_data['month']) ? 'selected' : '' ?>><?php echo $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Select Activity</label>
                        <select class="form-control" name="fk_activity_id" required>
                            <option>Select One</option>
                            <?php foreach ($all_activities['data'] as $activity) : ?>
                                <option value="<?php echo $activity['pk_activity_id'] ?>" <?php echo ($activity['pk_activity_id'] == $pre_data['fk_activity_id']) ? 'selected' : '' ?>><?php echo $activity['activity_name'] ?></option>   
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Project</label>
                        <div class="select2-primary">
                            <select class="form-control" name="fk_project_id" required>
                                <option value="">Select One</option>
                                <?php foreach ($all_projects['data'] as $project) : ?>
                                    <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Event Start Date</label>
                        <div class="input-group">
                            <input id="Datefirstmeeting" type="text" class="form-control" name="event_start_date" value="<?php echo $pre_data['event_start_date'] && $pre_data['event_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['event_start_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('Datefirstmeeting');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label>Event Start Time</label>
                        <div class="input-group date">
                            <input type="text" name="event_start_time"  value="<?php echo $pre_data['event_start_time'] && $pre_data['event_start_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['event_start_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                    <script>
                        init.push(function () {
                            var options2 = {
                                minuteStep: 1,
                                showSeconds: true,
                                showMeridian: false,
                                showInputs: false,
                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                            }
                            $('#bs-timepicker-component').timepicker(options2);
                        });
                    </script>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Event End Date</label>
                        <div class="input-group">
                            <input id="end_date" type="text" class="form-control" name="event_end_date" value="<?php echo $pre_data['event_end_date'] && $pre_data['event_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['event_end_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('end_date');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label>Event End Time</label>
                        <div class="input-group date">
                            <input id="end_time" type="text" name="event_end_time" value="<?php echo $pre_data['event_end_time'] && $pre_data['event_end_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['event_end_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                    <script>
                        init.push(function () {
                            var options2 = {
                                minuteStep: 1,
                                showSeconds: true,
                                showMeridian: false,
                                showInputs: false,
                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                            }
                            $('#end_time').timepicker(options2);
                        });
                    </script>
                </div>
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Section 1: Basic Geographical Information</legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label input-label">Division (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control division" name="event_division" style="text-transform: capitalize">
                                        <option value="">Select One</option>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option id="<?php echo $division['id'] ?>" <?php echo ($division['id'] == $pre_data['event_division']) ? 'selected' : '' ?> value="<?php echo $division['name'] ?>" <?php echo $pre_data && $pre_data['event_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">District (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control district" name="event_district" style="text-transform: capitalize" id="districtList">
                                        <?php if ($pre_data['event_district']) : ?>
                                            <option value="<?php echo $pre_data['event_district'] ?>"><?php echo $pre_data['event_district'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Upazila (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control subdistrict" name="event_upazila" style="text-transform: capitalize" id="subdistrictList">
                                        <?php if ($pre_data['event_upazila']) : ?>
                                            <option value="<?php echo $pre_data['event_upazila'] ?>"><?php echo $pre_data['event_upazila'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Police Station</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="permanent_police_station" style="text-transform: capitalize" id="policeStationList">
                                        <?php if ($pre_data['permanent_police_station']) : ?>
                                            <option value="<?php echo $pre_data['permanent_police_station'] ?>"><?php echo $pre_data['permanent_police_station'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Post Office</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="permanent_post_office" style="text-transform: capitalize" id="postOfficeList">
                                        <?php if ($pre_data['permanent_post_office']) : ?>
                                            <option value="<?php echo $pre_data['permanent_post_office'] ?>"><?php echo $pre_data['permanent_post_office'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Union</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="event_union" style="text-transform: capitalize" id="unionList">
                                        <?php if ($pre_data['event_union']) : ?>
                                            <option value="<?php echo $pre_data['event_union'] ?>"><?php echo $pre_data['event_union'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label input-label">Municipality</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="permanent_municipality" style="text-transform: capitalize" id="municipalityList">
                                        <?php if ($pre_data['permanent_municipality']) : ?>
                                            <option value="<?php echo $pre_data['permanent_municipality'] ?>"><?php echo $pre_data['permanent_municipality'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">City Corporation</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="permanent_city_corporation" style="text-transform: capitalize" id="cityCorporationList">
                                        <?php if ($pre_data['permanent_city_corporation']) : ?>
                                            <option value="<?php echo $pre_data['permanent_city_corporation'] ?>"><?php echo $pre_data['permanent_city_corporation'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Village</label>
                                <input class="form-control" type="text" name="event_village" value="<?php echo $pre_data['event_village'] ? $pre_data['event_village'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Ward</label>
                                <input class="form-control" type="text" name="event_ward" value="<?php echo $pre_data['event_ward'] ? $pre_data['event_ward'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Exact Location (Para, Bazar or School)</label>
                                <textarea class="form-control" type="text" name="event_location"><?php echo $pre_data['event_location'] ? $pre_data['event_location'] : ''; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
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

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForPoliceStation: districtId},
                                    beforeSend: function () {
                                        $('#policeStationList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#policeStationList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForPostOffice: districtId},
                                    beforeSend: function () {
                                        $('#postOfficeList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#postOfficeList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForMunicipality: districtId},
                                    beforeSend: function () {
                                        $('#municipalityList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#municipalityList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForCityCorporation: districtId},
                                    beforeSend: function () {
                                        $('#cityCorporationList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#cityCorporationList').html(result);
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

                            $('.presentDivision').change(function () {
                                var divisionId = $(this).find('option:selected').attr('id');

                                $.ajax({
                                    type: 'POST',
                                    data: {division_id: divisionId},
                                    beforeSend: function () {
                                        $('#presentDistrictList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentDistrictList').html(result);
                                    }}
                                );
                            });
                            $('.presentDistrict').change(function () {
                                var districtId = $(this).find('option:selected').attr('id');

                                $.ajax({
                                    type: 'POST',
                                    data: {district_id: districtId},
                                    beforeSend: function () {
                                        $('#presentSubdistrictList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentSubdistrictList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForPoliceStation: districtId},
                                    beforeSend: function () {
                                        $('#presentPoliceStationList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentPoliceStationList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForPostOffice: districtId},
                                    beforeSend: function () {
                                        $('#presentPostOfficeList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentPostOfficeList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForMunicipality: districtId},
                                    beforeSend: function () {
                                        $('#presentMunicipalityList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentMunicipalityList').html(result);
                                    }}
                                );

                                $.ajax({
                                    type: 'POST',
                                    data: {districtIdForCityCorporation: districtId},
                                    beforeSend: function () {
                                        $('#presentCityCorporationList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentCityCorporationList').html(result);
                                    }}
                                );

                            });
                            $('.presentSubdistrict').change(function () {
                                var subdistrictId = $(this).find('option:selected').attr('id');

                                $.ajax({
                                    type: 'POST',
                                    data: {subdistrict_id: subdistrictId},
                                    beforeSend: function () {
                                        $('#presentUnionList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#presentUnionList').html(result);
                                    }}
                                );
                            });
                        });
                    </script>
                </div>
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Section 2: Number of participants in the Event</legend>
                        <div class="col-md-6">
                            <label class="control-label input-label">Below 18</label>
                            <div class="form-group">
                                <input class="form-control" type="number" name="participant_boy" value="<?php echo $pre_data['participant_boy'] ? $pre_data['participant_boy'] : ''; ?>" placeholder="Boy (<18)"><br />
                                <input class="form-control" type="number" name="participant_girl" value="<?php echo $pre_data['participant_girl'] ? $pre_data['participant_girl'] : ''; ?>" placeholder="Girl (<18)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label input-label">Above 18</label>
                            <div class="form-group">
                                <input class="form-control" type="number" name="participant_male" value="<?php echo $pre_data['participant_male'] ? $pre_data['participant_male'] : ''; ?>" placeholder="Men (>=18)"><br />
                                <input class="form-control" type="number" name="participant_female" value="<?php echo $pre_data['participant_female'] ? $pre_data['participant_female'] : ''; ?>" placeholder="Women (>=18)">
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Section 3: Show Observation Checklist</legend>
                        <?php
                        foreach ($this->evaluationQA as $dbCol => $eachQA) {
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $eachQA['q'] ?></label>
                                    <select class="form-control" required name="<?php echo $dbCol ?>">
                                        <option value="">Select One</option>
                                        <?php
                                        foreach ($eachQA['a'] as $aValue => $aLabel) {
                                            $selected = $pre_data && $pre_data[$dbCol] == $aValue ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $aValue ?>" <?php echo $selected ?>><?php echo $aLabel ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Event Note</label>
                                <textarea class="form-control" name="event_note"><?php echo $pre_data['event_note'] ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_events') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update' : 'Save',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update' : 'Save'
            ))
            ?>
        </div>
    </div>
</form>
<script type="text/javascript">
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>