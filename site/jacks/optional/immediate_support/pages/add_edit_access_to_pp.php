<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_access_to_pp', 'edit_access_to_pp')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$countries = get_countries();
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

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'pk_project_id' => 'dev_projects.pk_project_id',
            'project_short_name' => 'dev_projects.project_short_name',
            'fk_project_id' => 'dev_access_to_pp.fk_project_id',
            'brac_info_id' => 'dev_access_to_pp.brac_info_id',
            'full_name' => 'dev_access_to_pp.full_name',
            'gender' => 'dev_access_to_pp.gender',
            'disability' => 'dev_access_to_pp.disability',
            'mobile' => 'dev_access_to_pp.mobile',
            'division' => 'dev_access_to_pp.division',
            'district' => 'dev_access_to_pp.district',
            'upazilla' => 'dev_access_to_pp.upazilla',
            'permanent_police_station' => 'dev_access_to_pp.permanent_police_station',
            'permanent_post_office' => 'dev_access_to_pp.permanent_post_office',
            'permanent_municipality' => 'dev_access_to_pp.permanent_municipality',
            'permanent_city_corporation' => 'dev_access_to_pp.permanent_city_corporation',
            'user_union' => 'dev_access_to_pp.user_union',
            'village' => 'dev_access_to_pp.village',
            'service_type' => 'dev_access_to_pp.service_type',
            'other_service_type' => 'dev_access_to_pp.other_service_type',
            'rescue_reason' => 'dev_access_to_pp.rescue_reason',
            'destination_country' => 'dev_access_to_pp.destination_country',
            'support_date' => 'dev_access_to_pp.support_date',
            'complain_to' => 'dev_access_to_pp.complain_to',
            'other_complain_to' => 'dev_access_to_pp.other_complain_to',
            'service_result' => 'dev_access_to_pp.service_result',
            'return_date' => 'dev_access_to_pp.return_date',
            'comment' => 'dev_access_to_pp.comment',
            'create_date' => 'dev_access_to_pp.create_date',
            'entry_date' => 'dev_access_to_pp.entry_date',
        ),
        'id' => $edit,
        'single' => true
    );
    $pre_data = $this->get_access_to_pp($args);

    if (!$pre_data) {
        add_notification('Invalid Access To PP, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'brac_info_id' => 'BRAC Info ID',
            'full_name' => 'Full Name',
            'division' => 'Division',
            'district' => 'District',
            'destination_country' => 'Destination Country',
            'support_date' => 'Complain/Support Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    $ret = $this->add_edit_access_to_pp($data);

    if ($ret['success']) {
        $msg = "Access To Public And Private Support information has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/immediate_support/manage_access_to_pp?action=add_edit_access_to_pp&edit=' . $edit));
        } else {
            header('location: ' . url('admin/immediate_support/manage_access_to_pp'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Access To Service</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Access To Service',
                'title' => 'Manage Access To Service',
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
            <div class="tab-pane fade active in" id="personalInfo">
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
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
                            <div class="form-group">
                                <label>Project</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="project_id" required>
                                        <option value="">Select One</option>
                                        <?php foreach ($all_projects['data'] as $project) : ?>
                                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>BRAC Info ID (*)</label>
                                <input class="form-control" type="text" required name="brac_info_id" value="<?php echo $pre_data['brac_info_id'] ? $pre_data['brac_info_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Disability</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="disability" value="yes" <?php echo $pre_data && $pre_data['disability'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="disability" value="no" <?php echo $pre_data && $pre_data['disability'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Division</label>
                                <div class="select2-primary">
                                    <select class="form-control division" name="division" style="text-transform: capitalize">
                                        <option>Select One</option>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['division'] == strtolower($division['name']) ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">District</label>
                                <div class="select2-primary">
                                    <select class="form-control district" name="district" style="text-transform: capitalize" id="districtList">
                                        <?php if ($pre_data['district']) : ?>
                                            <option value="<?php echo $pre_data['district'] ?>"><?php echo $pre_data['district'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Upazila</label>
                                <div class="select2-primary">
                                    <select class="form-control subdistrict" name="upazilla" style="text-transform: capitalize" id="subdistrictList">
                                        <?php if ($pre_data['upazilla']) : ?>
                                            <option value="<?php echo $pre_data['upazilla'] ?>"><?php echo $pre_data['upazilla'] ?></option>
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
                                <label class="control-label input-label">Union</label>
                                <div class="select2-primary">
                                    <select class="form-control union" name="user_union" style="text-transform: capitalize" id="unionList">
                                        <?php if ($pre_data['user_union']) : ?>
                                            <option value="<?php echo $pre_data['user_union'] ?>"><?php echo $pre_data['user_union'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Village</label>
                                <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name (*)</label>
                                <input class="form-control" type="text" required name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="number" name="mobile_number" value="<?php echo $pre_data['mobile'] ? $pre_data['mobile'] : ''; ?>">
                            </div>
                            <?php
                            $rescue_reason = explode(',', $pre_data['rescue_reason']);
                            ?>
                            <div class="form-group">
                                <label>Problem</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        foreach ($this->all_rescue_reasons as $key => $value) :
                                            $allRescueReasons[] = $key;
                                            ?>
                                            <label><input class="px educations" type="radio" name="rescue_reason" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['rescue_reason'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['rescue_reason']):
                                            ?>
                                            <label><input class="px" type="radio" name="rescue_reason" <?php
                                                if (!in_array($pre_data['rescue_reason'], $allRescueReasons)): echo 'checked';
                                                endif;
                                                ?> id="newRescue"><span class="lbl">Others, Please specify…</span></label>
                                                      <?php else: ?>
                                            <label><input class="px" type="radio" name="rescue_reason" value="" id="newRescue"><span class="lbl">Others</span></label> 
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newRescueType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newRescueText" name="new_rescue" value="<?php echo $pre_data['rescue_reason'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newRescue').is(':checked');

                                    if (isChecked == true) {
                                        $('#newRescueType').show();
                                    }

                                    $("#newRescue").on("click", function () {
                                        $('#newRescueType').show();
                                    });

                                    $(".educations").on("click", function () {
                                        $('#newRescueType').hide();
                                        $('#newRescueText').val('');
                                    });
                                });
                            </script>
                            <?php
                            $service_types = explode(',', $pre_data['service_type']);
                            $service_types = $service_types ? $service_types : array($service_types);
                            ?>
                            <div class="form-group">
                                <label>Service Seeking (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php foreach ($this->all_service_type as $key => $value) : ?>
                                            <label><input class="px" type="checkbox" name="service_type[]" value="<?php echo $key ?>" <?php
                                                if (in_array($key, $service_types)) :
                                                    echo 'checked';
                                                endif
                                                ?>><span class="lbl"><?php echo $value ?></span></label>
                                                      <?php endforeach ?>
                                        <label><input class="px" type="checkbox" id="newService" <?php echo $pre_data && $pre_data['other_service_type'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newServiceType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_service_type" id="newServiceTypeText" value="<?php echo $pre_data['other_service_type'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newService').is(':checked');

                                    if (isChecked == true) {
                                        $('#newServiceType').show();
                                    }

                                    $("#newService").on("click", function () {
                                        $('#newServiceType').toggle();
                                        $('#newServiceTypeText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label class="control-label input-label">Destination Country (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control" required name="destination_country" style="text-transform: capitalize">
                                        <option value="">Select One</option>
                                        <?php foreach ($countries as $country) : ?>
                                            <option value="<?php echo $country['nicename'] ?>" <?php echo ($country['nicename'] == $pre_data['destination_country']) ? 'selected' : '' ?>><?php echo $country['nicename'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Complain/Support Date (*)</label>
                                <div class="input-group">
                                    <input id="collection_date" required type="text" class="form-control" name="support_date" value="<?php echo $pre_data['support_date'] && $pre_data['support_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['support_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('collection_date');
                                    });
                                </script>
                            </div>
                            <?php
                            $complain_to = explode(',', $pre_data['complain_to']);
                            $complain_to = $complain_to ? $complain_to : array($complain_to);
                            ?>
                            <div class="form-group">
                                <label>Complain To (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php foreach ($this->all_complains as $key => $value) : ?>
                                            <label><input class="px" type="checkbox" name="complain_to[]" value="<?php echo $key ?>" <?php
                                                if (in_array($key, $complain_to)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl"><?php echo $value ?></span></label>
                                                      <?php endforeach ?>
                                        <label><input class="px" type="checkbox" id="newComplainTo" <?php echo $pre_data && $pre_data['other_complain_to'] != NULL ? 'checked' : '' ?>><span class="lbl">Other (Please Specify)</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newComplainToType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newComplainToTypeText" name="new_complain_to" value="<?php echo $pre_data['other_complain_to'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newComplainTo').is(':checked');

                                    if (isChecked == true) {
                                        $('#newComplainToType').show();
                                    }

                                    $("#newComplainTo").on("click", function () {
                                        $('#newComplainToType').toggle();
                                        $('#newComplainToTypeText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label class="control-label input-label">Service Result</label>
                                <select class="form-control" name="service_result">
                                    <option value="">Select One</option>
                                    <option value="received" <?php echo $pre_data && $pre_data['service_result'] == 'received' ? 'selected' : '' ?>>Received</option>
                                    <option value="not-received" <?php echo $pre_data && $pre_data['service_result'] == 'not-received' ? 'selected' : '' ?>>Not-Received</option>
                                    <option value="under-process" <?php echo $pre_data && $pre_data['service_result'] == 'under-process' ? 'selected' : '' ?>>Under-Process</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Service Received Date</label>
                                <div class="input-group">
                                    <input id="return_date" type="text" class="form-control" name="return_date" value="<?php echo $pre_data['return_date'] && $pre_data['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['return_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('return_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment"><?php echo $pre_data['comment'] ? $pre_data['comment'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/immediate_support/manage_access_to_pp') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
</form>
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
    });
</script>