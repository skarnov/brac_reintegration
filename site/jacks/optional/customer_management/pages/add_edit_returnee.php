<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_returnee', 'edit_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$countries = get_countries();
if (isset($_POST['country_id'])) {
    $cities = get_cities($_POST['country_id']);
    echo "<option value=''>Select One</option>";
    foreach ($cities as $city) :
        echo "<option id='" . $city['id'] . "' value='" . $city['name'] . "' >" . $city['name'] . "</option>";
    endforeach;
    exit;
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

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_returnees(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid returnee, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueNID') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE nid_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueBirth') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE birth_reg_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniquePassport') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE passport_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueMobile') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE mobile_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueEmergencyMobile') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE emergency_mobile = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'fk_branch_id' => 'District Centre / Branch Name',
            'fk_project_id' => 'Project Name',
            'returnee_id' => 'ID',
            'person_type' => 'Type of person',
            'full_name' => 'Name of person',
            'returnee_gender' => 'Gender',
            'father_name' => 'Father\'s Name',
            'destination_country' => 'Country of Destination',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    if ($data['form_data']['nid_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE nid_number = '" . $data['form_data']['nid_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['nid'] = "This NID holder is already in our Database";
        }
    }
    if ($data['form_data']['birth_reg_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE birth_reg_number = '" . $data['form_data']['birth_reg_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['birth'] = "This Birth Registration holder is already in our Database";
        }
    }
    if ($data['form_data']['passport_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE passport_number = '" . $data['form_data']['passport_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['passport'] = "This Passport holder is already in our Database";
        }
    }
    if ($data['form_data']['mobile_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE mobile_number = '" . $data['form_data']['mobile_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['mobile'] = "This mobile holder is already in our Database";
        }
    }
    if ($data['form_data']['emergency_mobile']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE emergency_mobile = '" . $data['form_data']['emergency_mobile'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['mobile'] = "This emergency mobile holder is already in our Database";
        }
    }

    $message = implode('.<br>', $msg);
    if ($message) {
        add_notification($message, 'error');
        header('location: ' . url('admin/dev_returnee_management/manage_returnees'));
        exit();
    }

    $ret = $this->add_edit_returnee($data);

    if ($ret['success']) {
        $returnee_id = $edit ? $edit : $ret['success'];
        $returnee_data = $this->get_returnees(array('id' => $returnee_id, 'single' => true));

        $msg = "Basic information of returnee profile " . $returnee_data['full_name'] . " (ID: " . $returnee_data['returnee_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_returnees?action=add_edit_returnee&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_returnees'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Returnee</h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Returnee : <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['returnee_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Returnees',
                'title' => 'Manage Returnees',
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
                                <label>District Centre / Branch Name (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="fk_branch_id" required>
                                        <option value="">Select One</option>
                                        <?php foreach ($all_branches['data'] as $branch) : ?>
                                            <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Project Name</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="fk_project_id" required>
                                        <option value="">Select One</option>
                                        <?php foreach ($all_projects['data'] as $project) : ?>
                                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>ID (*)</label>
                                <input class="form-control" type="text" required name="returnee_id" value="<?php echo $pre_data['returnee_id'] ? $pre_data['returnee_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Entry Date/Date of Data Collection</label>
                                <div class="input-group">
                                    <input id="collection_date" type="text" class="form-control" name="collection_date" value="<?php echo $pre_data['collection_date'] && $pre_data['collection_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['collection_date'])) : ''; ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('collection_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Type of Person (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="person_type" value="trafficked_survivor" <?php echo $pre_data && $pre_data['person_type'] == 'trafficked_survivor' ? 'checked' : '' ?>><span class="lbl">Trafficked Survivor</span></label>
                                        <label><input class="px" type="radio" name="person_type" value="returnee_migrant" <?php echo $pre_data && $pre_data['person_type'] == 'returnee_migrant' ? 'checked' : '' ?>><span class="lbl">Returnee Migrant</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name of The Person (*)</label>
                                <input class="form-control" type="text" required name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Gender (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $all_gender = array(
                                            'male' => 'Men (>=18)',
                                            'female' => 'Women (>=18)');
                                        foreach ($all_gender as $key => $value) :
                                            $allGender[] = $key;
                                            ?>
                                            <label><input class="px oldGender" type="radio" name="returnee_gender" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['returnee_gender'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['returnee_gender']):
                                            ?>
                                            <label><input class="px" type="radio" name="returnee_gender" <?php
                                                if (!in_array($pre_data['returnee_gender'], $allGender)): echo 'checked';
                                                endif;
                                                ?> id="newGender"><span class="lbl">Other</span></label>
                                                      <?php else : ?>
                                            <label><input class="px" type="radio" name="returnee_gender" id="newGender" value=""><span class="lbl">Other</span></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['returnee_gender'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newGender').is(':checked');

                                    if (isChecked == true) {
                                        $('#newGenderType').show();
                                    }

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
                                <label>Marital Status</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="single" <?php echo $pre_data && $pre_data['marital_status'] == 'single' ? 'checked' : '' ?>><span class="lbl">Unmarried</span></label>
                                        <label><input class="px" id="isMarried" type="radio" name="marital_status" value="married" <?php echo $pre_data && $pre_data['marital_status'] == 'married' ? 'checked' : '' ?>><span class="lbl">Married</span></label>
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="divorced" <?php echo $pre_data && $pre_data['marital_status'] == 'divorced' ? 'checked' : '' ?>><span class="lbl">Divorced/Separated</span></label>
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="widowed" <?php echo $pre_data && $pre_data['marital_status'] == 'widowed' ? 'checked' : '' ?>><span class="lbl">Widowed</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="spouse" style="display: none; margin-bottom: 1em;">
                                <label>Enter Spouse Name</label>
                                <input class="form-control" placeholder="Enter Spouse Name" type="text" id="customerSpouse" name="returnee_spouse" value="<?php echo $pre_data['returnee_spouse'] ? $pre_data['returnee_spouse'] : ''; ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#isMarried').is(':checked');

                                    if (isChecked == true) {
                                        $('#spouse').show();
                                    }

                                    $("#isMarried").on("click", function () {
                                        $('#spouse').show();
                                    });

                                    $(".notMarried").on("click", function () {
                                        $('#spouse').hide();
                                        $('#customerSpouse').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Educational Qualification</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $educational_qualification = array(
                                            'illiterate' => 'Illiterate',
                                            'sign' => 'Can Sign only',
                                            'psc' => 'Primary education (Passed Grade 5)',
                                            'not_psc' => 'Did not complete primary education',
                                            'jsc' => 'Completed JSC (Passed Grade 8) or equivalent',
                                            'ssc' => 'Completed School Secondary Certificate or equivalent',
                                            'hsc' => 'Higher Secondary Certificate/Diploma/ equivalent',
                                            'bachelor' => 'Bachelor’s degree or equivalent',
                                            'master' => 'Masters or Equivalent',
                                            'professional_education' => 'Completed Professional education',
                                            'general_education' => 'Completed general Education');
                                        foreach ($educational_qualification as $key => $value) :
                                            $educationQualification[] = $key;
                                            ?>
                                            <label><input class="px educations" type="radio" name="educational_qualification" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['educational_qualification'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['educational_qualification']):
                                            ?>
                                            <label><input class="px" type="radio" name="educational_qualification" <?php
                                                if (!in_array($pre_data['educational_qualification'], $educationQualification)): echo 'checked';
                                                endif;
                                                ?> id="newQualification"><span class="lbl">Others, Please specify…</span></label>
                                                      <?php else: ?>
                                            <label><input class="px" type="radio" name="educational_qualification" value="" id="newQualification"><span class="lbl">Others</span></label> 
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newQualificationType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newQualificationText" name="new_qualification" value="<?php echo $pre_data['educational_qualification'] ?>">
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
                                <label>Mobile Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueMobile" data-error-message="This Mobile holder is already in our Database" class="verifyUnique form-control" id="mobile" type="text" name="mobile_number" value="<?php echo $pre_data['mobile_number'] ? $pre_data['mobile_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Emergency Mobile Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueEmergencyMobile" data-error-message="This Emergency Mobile holder is already in our Database" class="verifyUnique form-control" id="emergency_mobile" type="text" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>NID Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueNID" data-error-message="This NID holder is already in our Database" class="verifyUnique form-control" id="nid" type="text" name="nid_number" value="<?php echo $pre_data['nid_number'] ? $pre_data['nid_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Birth Registration Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueBirth" data-error-message="This Birth Registration holder is already in our Database" class="verifyUnique form-control" id="birth" type="text" name="birth_reg_number" value="<?php echo $pre_data['birth_reg_number'] ? $pre_data['birth_reg_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Passport Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniquePassport" data-error-message="This Passport holder is already in our Database" class="verifyUnique form-control" id="passport" type="text" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block" id="passportArea"></p>
                            </div>
                            <div class="form-group">
                                <label>Father's Name (*)</label>
                                <input type="text" class="form-control" name="father_name" required value="<?php echo $pre_data['father_name'] ? $pre_data['father_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>BRAC Info ID</label>
                                <input class="form-control" type="text" name="brac_info_id" value="<?php echo $pre_data['brac_info_id'] ? $pre_data['brac_info_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Division</label>
                                <div class="select2-primary">
                                    <select class="form-control division" name="permanent_division" style="text-transform: capitalize">
                                        <?php if ($pre_data['permanent_division']) : ?>
                                            <option value="<?php echo strtolower($pre_data['permanent_division']) ?>"><?php echo $pre_data['permanent_division'] ?></option>
                                        <?php else: ?>
                                            <option>Select One</option>
                                        <?php endif ?>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['permanent_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
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
                        </div>
                        <div class="col-sm-6">
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
                                <label>Departure Date</label>
                                <div class="input-group">
                                    <input id="departure_date" type="text" class="form-control" name="departure_date" value="<?php echo $pre_data['departure_date'] && $pre_data['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['departure_date'])) : '' ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('departure_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Return Date</label>
                                <div class="input-group">
                                    <input id="return_date" type="text" class="form-control" name="return_date" value="<?php echo $pre_data['return_date'] && $pre_data['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['return_date'])) : '' ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('return_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Country of Destination (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control country" required name="destination_country" style="text-transform: capitalize">
                                        <option value="">Select One</option>
                                        <?php foreach ($countries as $country) : ?>
                                            <option id="<?php echo $country['id'] ?>" value="<?php echo $country['nicename'] ?>" <?php echo $pre_data && $pre_data['destination_country'] == $country['nicename'] ? 'selected' : '' ?>><?php echo $country['nicename'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">City</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="destination_city" style="text-transform: capitalize" id="cityList">
                                        <?php if ($pre_data['destination_city']) : ?>
                                            <option value="<?php echo $pre_data['destination_city'] ?>"><?php echo $pre_data['destination_city'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <script type="text/javascript">
                                init.push(function () {
                                    $('.country').change(function () {
                                        var countryId = $(this).find('option:selected').attr('id');

                                        $.ajax({
                                            type: 'POST',
                                            data: {country_id: countryId},
                                            beforeSend: function () {
                                                $('#cityList').html("<option value=''>Loading...</option>");
                                            },
                                            success: function (result) {
                                                $('#cityList').html(result);
                                            }}
                                        );
                                    });
                                });
                            </script>
                            <?php
                            $all_legal_documents = array(
                                'Travel document' => 'Travel document',
                                'NID' => 'NID',
                                'Birth Registration' => 'Birth Registration',
                                'Passport' => 'Passport',
                                'Smart Card' => 'Smart Card',
                            );
                            $legal_documents = explode(',', $pre_data['legal_document']);
                            $legal_documents = $legal_documents ? $legal_documents : array($legal_documents);
                            ?>
                            <div class="form-group">
                                <label>Legal Document</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php foreach ($all_legal_documents as $key => $value) :
                                            ?>
                                            <label><input class="px" type="checkbox" name="legal_document[]" value="<?php echo $key ?>" <?php
                                                if (in_array($key, $legal_documents)) :
                                                    echo 'checked';
                                                endif
                                                ?>><span class="lbl"><?php echo $value ?></span></label>
                                                      <?php endforeach ?>        
                                        <label><input class="px" type="checkbox" id="newLegalDocument" <?php echo $pre_data && $pre_data['other_legal_document'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newLegalDocumentType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newLegalDocumentTypeText" name="new_legal_document" value="<?php echo $pre_data['other_legal_document'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newLegalDocument').is(':checked');

                                    if (isChecked == true) {
                                        $('#newLegalDocumentType').show();
                                    }

                                    $("#newLegalDocument").on("click", function () {
                                        $('#newLegalDocumentType').toggle();
                                        $('#newLegalDocumentTypeText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Intention To Remigrate</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="remigrate_intention" value="yes" <?php echo $pre_data && $pre_data['remigrate_intention'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="remigrate_intention" value="no" <?php echo $pre_data && $pre_data['remigrate_intention'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Occupation in Overseas Country</label>
                                <input class="form-control" type="text" name="destination_country_profession" value="<?php echo $pre_data['destination_country_profession'] ? $pre_data['destination_country_profession'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Selected for Profiling</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="profile_selection" value="yes" <?php echo $pre_data && $pre_data['profile_selection'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="profile_selection" value="no" <?php echo $pre_data && $pre_data['profile_selection'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks"><?php echo $pre_data['remarks'] ? $pre_data['remarks'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_returnee_management/manage_returnees') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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