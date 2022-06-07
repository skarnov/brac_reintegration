<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;

if (!checkPermission($edit, 'add_training', 'edit_training')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$training_info = $this->get_trainings(array('id' => $training_id, 'single' => true));

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_training_participants(array('id' => $edit, 'single' => true));
    $message = explode(',', $pre_data['message']);

    if (!$pre_data) {
        add_notification('Invalid info, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

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

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['fk_training_id'] = $training_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_training_participants($data);

    if ($ret['success']) {
        $msg = "Participants has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_trainings?action=participants_list&edit=' . $edit . '&training_id=' . $training_id));
        } else {
            header('location: ' . url('admin/dev_activity_management/manage_trainings?action=participants_list&edit=' . $edit . '&training_id=' . $training_id));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Training Participants</h1>
    <h4 class="text-primary">Training Name : <?php echo $training_info['training_name'] ?></h4>
    <h4 class="text-primary">Training Duration : <?php echo $training_info['training_duration'] ?></h4>
    <h4 class="text-primary">Training Start Date : <?php echo date('d-m-Y', strtotime($training_info['training_start_date'])) ?></h4>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=participants_list&training_id=' . $training_info['pk_training_id'],
                'action' => 'list',
                'text' => 'All Training Participants',
                'title' => 'Manage Training Participants',
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
            <div class="col-md-6">
                <div class="form-group">
                    <label>Entry Date</label>
                    <div class="input-group">
                        <input id="Date" type="text" class="form-control" required name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : ''; ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('Date');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Beneficiary ID</label>
                    <input type="text" class="form-control" name="beneficiary_id" value="<?php echo $pre_data['beneficiary_id'] ? $pre_data['beneficiary_id'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Organizational Name</label>
                    <input type="text" class="form-control" name="organizational_name" value="<?php echo $pre_data['organizational_name'] ? $pre_data['organizational_name'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Participant Name</label>
                    <input type="text" class="form-control" name="participant_name" value="<?php echo $pre_data['participant_name'] ? $pre_data['participant_name'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Participant Age</label>
                    <input type="number" class="form-control" name="participant_age" value="<?php echo $pre_data['participant_age'] ? $pre_data['participant_age'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Participant Profession</label>
                    <input type="text" class="form-control" name="participant_profession" value="<?php echo $pre_data['participant_profession'] ? $pre_data['participant_profession'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Type of Participant</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <?php
                            $all_participant_type = array(
                                'government' => 'Government',
                                'ngo' => 'NGO',
                                'public_representative' => 'Public Representative',
                                'teacher' => 'Teacher',
                                'political_leader' => 'Political Leader',
                                'religious_leader' => 'Religious Leader',
                                'journalist' => 'Journalist',
                                'social_worker' => 'Social Worker',
                            );
                            foreach ($all_participant_type as $key => $value) :
                                $participantType[] = $key;
                                ?>
                                <label><input class="px educations" type="radio" name="participant_type" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['participant_type'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                <?php
                            endforeach;
                            if ($pre_data['participant_type']):
                                ?>
                                <label><input class="px" type="radio" name="participant_type" <?php
                                    if (!in_array($pre_data['participant_type'], $participantType)): echo 'checked';
                                    endif;
                                    ?> id="newQualification"><span class="lbl">Others, Please specify…</span></label>
                                          <?php else: ?>
                                <label><input class="px" type="radio" name="participant_type" value="" id="newQualification"><span class="lbl">Others</span></label> 
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div id="newQualificationType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newQualificationText" name="new_participant" value="<?php echo $pre_data['participant_type'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newQualification').is(':checked');

                        if (isChecked == true) {
                            $('#newQualificationType').show();
                        }

                        $("#newQualification").on("click", function () {
                            $('#newQualificationType').show();
                        });

                        $(".educations").on("click", function () {
                            $('#newQualificationType').hide();
                            $('#newQualificationText').val('');
                        });
                    });
                </script>
                <div class="form-group">
                    <label>Participant Gender</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="participant_gender" value="male" <?php echo $pre_data && $pre_data['participant_gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                            <label><input class="px oldGender" type="radio" name="participant_gender" value="female" <?php echo $pre_data && $pre_data['participant_gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                            <label><input class="px" type="radio" name="gender" id="newGender"><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>           
                <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['participant_gender'] ?>">
                </div>
                <script>
                    init.push(function () {
                        $("#newGender").on("click", function () {
                            $('#newGenderType').show();
                        });

                        $(".oldGender").on("click", function () {
                            $('#newGenderType').hide();
                            $('#newGenderText').val('');
                        });
                    });
                </script>
                <div class="form-group">
                    <label>Participant Mobile</label>
                    <input type="number" class="form-control" name="participant_mobile" value="<?php echo $pre_data['participant_mobile'] ? $pre_data['participant_mobile'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label input-label">Division</label>
                    <div class="select2-primary">
                        <select class="form-control division" name="permanent_division" style="text-transform: capitalize">
                            <option value="">Select One</option>
                            <?php foreach ($divisions as $division) : ?>
                                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['permanent_division'] == strtolower($division['name']) ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">District</label>
                    <div class="select2-primary">
                        <select class="form-control district" name="permanent_district" style="text-transform: capitalize" id="districtList">
                            <?php if ($pre_data['permanent_district']) : ?>
                                <option value="<?php echo $pre_data['permanent_district'] ?>"><?php echo $pre_data['permanent_district'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Upazila</label>
                    <div class="select2-primary">
                        <select class="form-control subdistrict" name="permanent_sub_district" style="text-transform: capitalize" id="subdistrictList">
                            <?php if ($pre_data['permanent_sub_district']) : ?>
                                <option value="<?php echo $pre_data['permanent_sub_district'] ?>"><?php echo $pre_data['permanent_sub_district'] ?></option>
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
                        <select class="form-control union" name="permanent_union" style="text-transform: capitalize" id="unionList">
                            <?php if ($pre_data['permanent_union']) : ?>
                                <option value="<?php echo $pre_data['permanent_union'] ?>"><?php echo $pre_data['permanent_union'] ?></option>
                            <?php endif ?>
                        </select>
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
                <div class="form-group">
                    <label class="control-label input-label">Village</label>
                    <input class="form-control" type="text" name="permanent_village" value="<?php echo $pre_data['permanent_village'] ? $pre_data['permanent_village'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Ward</label>
                    <input class="form-control" type="text" name="permanent_ward" value="<?php echo $pre_data['permanent_ward'] ? $pre_data['permanent_ward'] : ''; ?>">
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_trainings') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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