<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_customer', 'edit_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

if (isset($_POST['saveForm'])) {
    $_SESSION['form_data'] = $_POST;

    echo "<span class='text-primary'>(Saved!)</span>";
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();
$pre_data = $_SESSION['form_data'];

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

if ($edit) {
    $args = array(
        'customer_id' => $edit,
        'select_fields' => array(
            'pk_customer_id' => 'dev_customers.pk_customer_id',
            'project_id' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'full_name' => 'dev_customers.full_name',
            'father_name' => 'dev_customers.father_name',
            'mother_name' => 'dev_customers.mother_name',
            'customer_birthdate' => 'dev_customers.customer_birthdate',
            'customer_mobile' => 'dev_customers.customer_mobile',
            'emergency_mobile' => 'dev_customers.emergency_mobile',
            'emergency_name' => 'dev_customers.emergency_name',
            'emergency_relation' => 'dev_customers.emergency_relation',
            'educational_qualification' => 'dev_customers.educational_qualification',
            'customer_religion' => 'dev_customers.customer_religion',
            'entry_date' => 'dev_customers.entry_date',
            'nid_number' => 'dev_customers.nid_number',
            'birth_reg_number' => 'dev_customers.birth_reg_number',
            'passport_number' => 'dev_customers.passport_number',
            'travel_pass' => 'dev_customers.travel_pass',
            'customer_gender' => 'dev_customers.customer_gender',
            'marital_status' => 'dev_customers.marital_status',
            'customer_spouse' => 'dev_customers.customer_spouse',
            'permanent_division' => 'dev_customers.permanent_division',
            'permanent_district' => 'dev_customers.permanent_district',
            'permanent_sub_district' => 'dev_customers.permanent_sub_district',
            'permanent_police_station' => 'dev_customers.permanent_police_station',
            'permanent_post_office' => 'dev_customers.permanent_post_office',
            'permanent_municipality' => 'dev_customers.permanent_municipality',
            'permanent_city_corporation' => 'dev_customers.permanent_city_corporation',
            'permanent_union' => 'dev_customers.permanent_union',
            'permanent_village' => 'dev_customers.permanent_village',
            'permanent_ward' => 'dev_customers.permanent_ward',
            'permanent_house' => 'dev_customers.permanent_house',
            'dev_migrations' => 'dev_migrations.left_port',
            'preferred_country' => 'dev_migrations.preferred_country',
            'preferred_city' => 'dev_migrations.preferred_city',
            'final_destination' => 'dev_migrations.final_destination',
            'final_city' => 'dev_migrations.final_city',
            'migration_type' => 'dev_migrations.migration_type',
            'visa_type' => 'dev_migrations.visa_type',
            'departure_date' => 'dev_migrations.departure_date',
            'departure_media' => 'dev_migrations.departure_media',
            'return_date' => 'dev_migrations.return_date',
            'returned_age' => 'dev_migrations.returned_age',
            'migration_duration' => 'dev_migrations.migration_duration',
            'climate_effect' => 'dev_migrations.climate_effect',
            'natural_disasters' => 'dev_migrations.natural_disasters',
            'other_natural_disaster' => 'dev_migrations.other_natural_disaster',
            'is_disaster_migration' => 'dev_migrations.is_disaster_migration',
            'economic_impacts' => 'dev_migrations.economic_impacts',
            'other_economic_impact' => 'dev_migrations.other_economic_impact',
            'financial_losses' => 'dev_migrations.financial_losses',
            'social_impacts' => 'dev_migrations.social_impacts',
            'other_social_impact' => 'dev_migrations.other_social_impact',
            'is_climate_migration' => 'dev_migrations.is_climate_migration',
            'agency_name' => 'dev_migrations.agency_name',
            'rl_no' => 'dev_migrations.rl_no',
            'agency_know' => 'dev_migrations.agency_know',
            'agency_address' => 'dev_migrations.agency_address',
            'migration_occupation' => 'dev_migrations.migration_occupation',
            'earned_money' => 'dev_migrations.earned_money',
            'migration_reasons' => 'dev_migrations.migration_reasons',
            'other_migration_reason' => 'dev_migrations.other_migration_reason',
            'destination_country_leave_reason' => 'dev_migrations.destination_country_leave_reason',
            'other_destination_country_leave_reason' => 'dev_migrations.other_destination_country_leave_reason',
            'is_cheated' => 'dev_migrations.is_cheated',
            'forced_work' => 'dev_migrations.forced_work',
            'excessive_work' => 'dev_migrations.excessive_work',
            'is_money_deducted' => 'dev_migrations.is_money_deducted',
            'is_movement_limitation' => 'dev_migrations.is_movement_limitation',
            'employer_threatened' => 'dev_migrations.employer_threatened',
            'is_kept_document' => 'dev_migrations.is_kept_document',
            'migration_cost' => 'dev_economic_profile.migration_cost',
            'migration_cost_sources' => 'dev_economic_profile.migration_cost_sources',
            'other_migration_cost_source' => 'dev_economic_profile.other_migration_cost_source',
            'property_name' => 'dev_economic_profile.property_name',
            'property_value' => 'dev_economic_profile.property_value',
            'pre_occupation' => 'dev_economic_profile.pre_occupation',
            'present_occupation' => 'dev_economic_profile.present_occupation',
            'present_income' => 'dev_economic_profile.present_income',
            'returnee_income_source' => 'dev_economic_profile.returnee_income_source',
            'income_source' => 'dev_economic_profile.income_source',
            'family_income' => 'dev_economic_profile.family_income',
            'male_household_member' => 'dev_economic_profile.male_household_member',
            'female_household_member' => 'dev_economic_profile.female_household_member',
            'boy_household_member' => 'dev_economic_profile.boy_household_member',
            'girl_household_member' => 'dev_economic_profile.girl_household_member',
            'female_household_member' => 'dev_economic_profile.female_household_member',
            'personal_savings' => 'dev_economic_profile.personal_savings',
            'personal_debt' => 'dev_economic_profile.personal_debt',
            'current_residence_ownership' => 'dev_economic_profile.current_residence_ownership',
            'current_residence_type' => 'dev_economic_profile.current_residence_type',
            'have_training' => 'dev_economic_profile.have_training',
            'have_skill_trainings' => 'dev_economic_profile.have_skill_trainings',
            'other_have_skill_training' => 'dev_economic_profile.other_have_skill_training',
            'is_interested_training' => 'dev_economic_profile.is_interested_training',
            'interested_trainings' => 'dev_economic_profile.interested_trainings',
            'other_interested_training' => 'dev_economic_profile.other_interested_training',
            'have_earner_skill' => 'dev_customer_skills.have_earner_skill',
            'have_skills' => 'dev_customer_skills.have_skills',
            'vocational_skill' => 'dev_customer_skills.vocational_skill',
            'handicraft_skill' => 'dev_customer_skills.handicraft_skill',
            'other_have_skills' => 'dev_customer_skills.other_have_skills',
            'is_physically_challenged' => 'dev_customer_health.is_physically_challenged',
            'disability_type' => 'dev_customer_health.disability_type',
            'having_chronic_disease' => 'dev_customer_health.having_chronic_disease',
            'is_family_challenged' => 'dev_customer_health.is_family_challenged',
            'family_disability_type' => 'dev_customer_health.family_disability_type',
            'survivor_relationship' => 'dev_customer_health.survivor_relationship',
            'disease_type' => 'dev_customer_health.disease_type',
            'other_disease_type' => 'dev_customer_health.other_disease_type',
        ),
        'single' => true,
    );
    $pre_data = $this->get_customers($args);

    $migration_documents = $this->get_migration_documents(array('customer_id' => $pre_data['pk_customer_id']));

    if (!$pre_data) {
        add_notification('Invalid participant, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueNID') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE nid_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueBirth') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE birth_reg_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
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
            'full_name' => 'Full Name',
            'father_name' => 'Father Name',
            'customer_birthdate' => 'Customer Birthdate',
            'educational_qualification' => 'Educational Qualification',
            'customer_gender' => 'Sex',
            'marital_status' => 'Marital Status',
            'permanent_division' => 'Division',
            'permanent_district' => 'District',
            'permanent_sub_district' => 'Upazila',
            'left_port' => 'Transit/ route of Migration/ Trafficking',
            'preferred_country' => 'Desired destination',
            'final_destination' => 'Final destination',
            'migration_type' => 'Type of Channels',
            'visa_type' => 'Type of visa',
            'return_date' => 'Date of Return to Bangladesh',
            'migration_occupation' => 'Occupation in overseas country',
            'pre_occupation' => 'Main occupation(before trafficking)',
            'present_income' => 'Monthly income of returnee after return (In BDT)',
            'personal_savings' => 'Savings(BDT)',
            'personal_debt' => 'Loan Amount',
            'current_residence_ownership' => 'Ownership of House',
            'current_residence_type' => 'Type of house',
            'have_earner_skill' => 'IGA Skills',
            'is_physically_challenged' => 'Do you have any disability?',
            'having_chronic_disease' => 'Any Chronic Disease?'
        ),
    );
    $data['form_data'] = $_POST;

    $data['edit'] = $edit;

    $msg = array();
    if ($data['form_data']['nid_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE nid_number = '" . $data['form_data']['nid_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['nid'] = "This NID holder is already in our Database";
        }
    }
    if ($data['form_data']['birth_reg_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE birth_reg_number = '" . $data['form_data']['birth_reg_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['birth'] = "This Birth Registration holder is already in our Database";
        }
    }

    $message = implode('.<br> ', $msg);
    if ($message) {
        add_notification($message, ' error');
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    }

    $ret = $this->add_edit_customer($data);

    if ($ret['success']) {
        $customer_id = $edit ? $edit : $ret['customer_insert']['success'];
        $customer_data = $this->get_customers(array('customer_id' => $customer_id, 'single' => true));
        $msg = "Basic information of participant profile " . $customer_data['full_name'] . " (ID: " . $customer_data['customer_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location:' . url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $edit));
        } else {
            header('location:' . url('admin/dev_customer_management/manage_customers'));
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
    /* Conditional Form */
    .help-block{
        color: white;
    }
    .tab{
        display: none;
    }
    .current{
        display: block;
    }
    button {
        background-color: #4CAF50; 
        color: #ffffff; 
        border: none; 
        padding: 10px 20px; 
        font-size: 17px; 
        font-family: Raleway; 
        cursor: pointer; 
    }
    button:hover {
        opacity: 0.8;
    }
    .previous {
        background-color: #bbbbbb;
    }
    .step {
        height: 30px; 
        width: 30px; 
        cursor: pointer;
        margin: 0 2px; 
        color: #fff; 
        background-color: #bbbbbb; 
        border: none; 
        border-radius: 50%; 
        display: inline-block; 
        opacity: 0.8; 
        padding: 5px
    }
    .step.active {
        opacity: 1; 
        background-color: #69c769;
    }
    .step.finish {
        background-color: #4CAF50;
    }
    .error {
        color: #f00; 
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Participant Profile </h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Participant Profile : <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['customer_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Participant Profile',
                'title' => 'Manage Participant Profile',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<div class="panel" id="fullForm" style="">
    <div class="panel-body">
        <form id="myForm" action="" method="POST" enctype="multipart/form-data">
            <h3><?php echo $edit ? 'Update ' : 'New ' ?> Participant Profile Registration <span id="autoSave"></span></h3>
            <div class="tab">
                <fieldset>
                    <legend>Section 1: Personal information</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Project</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="project_id">
                                        <option value="">Select One</option>
                                        <?php foreach ($all_projects['data'] as $project) : ?>
                                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Participant ID/Reference number</label>
                                <input class="form-control" type="text" name="customer_id" id="customer_id" value="<?php echo $pre_data['customer_id'] ? $pre_data['customer_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Full Name <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Father's Name <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" name="father_name" value="<?php echo $pre_data['father_name'] ? $pre_data['father_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Date of Birth <span style="color: red;">*</span></label>
                                <div class="input-group">
                                    <input id="birthdate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : '' ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('birthdate');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="text" name="customer_mobile" value="<?php echo $pre_data['customer_mobile'] ? $pre_data['customer_mobile'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Marital Status <span style="color: red;">*</span></label>
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
                                <input class="form-control" placeholder="Enter Spouse Name" type="text" id="customerSpouse" name="customer_spouse" value="<?php echo $pre_data['customer_spouse'] ? $pre_data['customer_spouse'] : ''; ?>">
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
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Address Information</legend>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label input-label">Division <span style="color: red;">*</span></label>
                                        <div class="select2-primary">
                                            <select class="form-control division" name="permanent_division" style="text-transform: capitalize">
                                                <?php if ($pre_data['permanent_division']) : ?>
                                                    <option value="<?php echo $pre_data['permanent_division'] ?>"><?php echo $pre_data['permanent_division'] ?></option>
                                                <?php else: ?>
                                                    <option value="">Select One</option>
                                                <?php endif ?>
                                                <?php foreach ($divisions as $division) : ?>
                                                    <option id="<?php echo $division['id'] ?>" value="<?php echo $division['name'] ?>" <?php echo $pre_data && $pre_data['permanent_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label input-label">District <span style="color: red;">*</span></label>
                                        <div class="select2-primary">
                                            <select class="form-control district" name="permanent_district" style="text-transform: capitalize" id="districtList">
                                                <?php if ($pre_data['permanent_district']) : ?>
                                                    <option value="<?php echo $pre_data['permanent_district'] ?>"><?php echo $pre_data['permanent_district'] ?></option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label input-label">Upazila <span style="color: red;">*</span></label>
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
                                            <select class="form-control" name="permanent_union" style="text-transform: capitalize" id="unionList">
                                                <?php if ($pre_data['permanent_union']) : ?>
                                                    <option value="<?php echo $pre_data['permanent_union'] ?>"><?php echo $pre_data['permanent_union'] ?></option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div>
                                    <label class="control-label input-label">Village</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="permanent_village" value="<?php echo $pre_data['permanent_village'] ? $pre_data['permanent_village'] : ''; ?>">
                                    </div>
                                    <label class="control-label input-label">Ward No</label>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="permanent_ward" value="<?php echo $pre_data['permanent_ward'] ? $pre_data['permanent_ward'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-12">  
                                    <label class="control-label input-label">Present Address</label>
                                    <div class="form-group">
                                        <textarea type="text" class="form-control" name="permanent_house" /><?php echo $pre_data['permanent_house'] ? $pre_data['permanent_house'] : ''; ?></textarea>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Entry Date</label>
                                <div class="input-group">
                                    <input id="date_of_collection" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y') ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('date_of_collection');
                                    });
                                </script>
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
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Emergency</legend>
                                <label class="control-label input-label">Emergency Mobile No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>" />
                                </div>
                                <label class="control-label input-label">Name of that person</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="emergency_name" value="<?php echo $pre_data['emergency_name'] ? $pre_data['emergency_name'] : ''; ?>">
                                </div>
                                <?php
                                $all_emergency_relations = array(
                                    'Father' => 'Father',
                                    'Mother' => 'Mother',
                                    'Spouse' => 'Spouse',
                                    'Brother' => 'Brother',
                                    'Sister' => 'Sister',
                                    'Son' => 'Son',
                                    'Daughter' => 'Daughter',
                                    'Relative' => 'Relative',
                                    'Father in-law' => 'Father in-law',
                                    'Mother in-law' => 'Mother in-law',
                                    'Uncle (Paternal)' => 'Uncle (Paternal)',
                                    'Uncle (Maternal)' => 'Uncle (Maternal)',
                                );
                                ?>
                                <div class="form-group">
                                    <label class="control-label input-label">Relation with Participant</label>
                                    <div class="select2-primary">
                                        <select class="form-control division" name="emergency_relation">
                                            <option value="">Select One</option>
                                            <?php foreach ($all_emergency_relations as $value) : ?>
                                                <option value="<?php echo $value ?>" <?php echo $pre_data && $pre_data['emergency_relation'] == $value ? 'selected' : '' ?>><?php echo $value ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label>Gender <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $all_gender = array(
                                            'male' => 'Men (>=18)',
                                            'female' => 'Women (>=18)');
                                        foreach ($all_gender as $key => $value) :
                                            $allGender[] = $key;
                                            ?>
                                            <label><input class="px oldGender" type="radio" name="customer_gender" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['customer_gender'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['customer_gender']):
                                            ?>
                                            <label><input class="px" type="radio" name="customer_gender" <?php
                                                if (!in_array($pre_data['customer_gender'], $allGender)): echo 'checked';
                                                endif;
                                                ?> id="newGender"><span class="lbl">Other</span></label>
                                                      <?php else : ?>
                                            <label><input class="px" type="radio" name="customer_gender" id="newGender" value=""><span class="lbl">Other</span></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['customer_gender'] ?>">
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
                                <label>Educational Qualification <span style="color: red;">*</span></label>
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
                                <label>Religion</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        foreach ($this->all_religions as $key => $value) :
                                            $religions[] = $key;
                                            ?>
                                            <label><input class="px religions" type="radio" name="customer_religion" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['customer_religion'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['customer_religion']):
                                            ?>
                                            <label><input class="px" type="radio" name="customer_religion" <?php
                                                if (!in_array($pre_data['customer_religion'], $religions)): echo 'checked';
                                                endif;
                                                ?> id="newReligion"><span class="lbl">Others, Please specify…</span></label>
                                                      <?php else: ?>
                                            <label><input class="px" type="radio" name="customer_religion" value="" id="newReligion"><span class="lbl">Others</span></label> 
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newReligionType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" id="newReligionTypeText" type="text" name="new_religion" value="">
                            </div>
                            <script>
                                init.push(function () {
                                    $("#newReligion").on("click", function () {
                                        $('#newReligionType').show();
                                    });

                                    $(".religions").on("click", function () {
                                        $('#newReligionType').hide();
                                        $('#newReligionTypeText').val('');
                                    });
                                });
                            </script>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Number of Accompany/ Number of Family Member</legend>
                                <div class="col-sm-4">   
                                    <label class="control-label input-label">Men (>=18) <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <input type="number" class="form-control" onchange="calc()" id="maleMember" name="male_household_member" value="<?php echo $pre_data['male_household_member'] ? $pre_data['male_household_member'] : 0 ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Women (>=18) <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <input class="filter form-control" onchange="calc()" id="femaleMember" type="number" name="female_household_member" value="<?php echo $pre_data['female_household_member'] ? $pre_data['female_household_member'] : 0 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">   
                                    <label class="control-label input-label">Boy (<18) <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <input class="filter form-control" onchange="calc()" id="boyMember" type="number" name="boy_household_member" value="<?php echo $pre_data['boy_household_member'] ? $pre_data['boy_household_member'] : 0 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">   
                                    <label class="control-label input-label">Girl (<18) <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <input class="filter form-control" onchange="calc()" id="girlMember" type="number" name="girl_household_member" value="<?php echo $pre_data['girl_household_member'] ? $pre_data['girl_household_member'] : 0 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">   
                                    <label class="control-label input-label">Total</label>
                                    <div class="form-group">
                                        <input class="form-control" id="totalMember" type="number" value="<?php echo $pre_data['male_household_member'] + $pre_data['female_household_member'] + $pre_data['boy_household_member'] + $pre_data['girl_household_member'] ?>">
                                    </div>
                                </div>
                            </fieldset>
                            <script>
                                function calc() {
                                    var maleMember = $('#maleMember').val();
                                    var femaleMember = $('#femaleMember').val();
                                    var boyMember = $('#boyMember').val();
                                    var girlMember = $('#girlMember').val();

                                    var total = Number(maleMember) + Number(femaleMember) + Number(boyMember) + Number(girlMember);
                                    $('#totalMember').val(total);
                                }
                            </script>
                        </div>    
                    </div>
                </fieldset>
            </div>
            <div class="tab">
                <fieldset>
                    <legend>Section 2: Trafficking/ Migration history</legend>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Transit/ route of Migration/ Trafficking <span style="color: red;">*</span></label>
                            <input class="form-control" type="text" name="left_port" value="<?php echo $pre_data['left_port'] ? $pre_data['left_port'] : ''; ?>">
                        </div>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Desired Destination <span style="color: red;">*</span></legend>
                            <div class="form-group">
                                <label class="control-label input-label">Country <span style="color: red;">*</span></label>
                                <div class="select2-primary">
                                    <select class="form-control country" name="preferred_country" style="text-transform: capitalize">
                                        <?php if ($pre_data['preferred_country']) : ?>
                                            <option value="<?php echo $pre_data['preferred_country'] ?>"><?php echo $pre_data['preferred_country'] ?></option>
                                        <?php else: ?>
                                            <option value="">Select One</option>
                                        <?php endif ?>
                                        <?php foreach ($countries as $country) : ?>
                                            <option id="<?php echo $country['id'] ?>" value="<?php echo $country['nicename'] ?>" <?php echo $pre_data && $pre_data['preferred_country'] == $country['nicename'] ? 'selected' : '' ?>><?php echo $country['nicename'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">City</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="preferred_city" style="text-transform: capitalize" id="cityList">
                                        <?php if ($pre_data['preferred_city']) : ?>
                                            <option value="<?php echo $pre_data['preferred_city'] ?>"><?php echo $pre_data['preferred_city'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
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
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Final Destination <span style="color: red;">*</span></legend>
                            <div class="form-group">
                                <label class="control-label input-label">Country <span style="color: red;">*</span></label>
                                <div class="select2-primary">
                                    <select class="form-control finalCountry" name="final_destination" style="text-transform: capitalize">
                                        <?php if ($pre_data['final_destination']) : ?>
                                            <option value="<?php echo $pre_data['final_destination'] ?>"><?php echo $pre_data['final_destination'] ?></option>
                                        <?php else: ?>
                                            <option value="">Select One</option>
                                        <?php endif ?>
                                        <?php foreach ($countries as $country) : ?>
                                            <option id="<?php echo $country['id'] ?>" value="<?php echo $country['nicename'] ?>" <?php echo $pre_data && $pre_data['preferred_country'] == $country['nicename'] ? 'selected' : '' ?>><?php echo $country['nicename'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">City</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="final_city" style="text-transform: capitalize" id="finalCityList">
                                        <?php if ($pre_data['final_city']) : ?>
                                            <option value="<?php echo $pre_data['final_city'] ?>"><?php echo $pre_data['final_city'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <script type="text/javascript">
                            init.push(function () {
                                $('.finalCountry').change(function () {
                                    var countryId = $(this).find('option:selected').attr('id');

                                    $.ajax({
                                        type: 'POST',
                                        data: {country_id: countryId},
                                        beforeSend: function () {
                                            $('#finalCityList').html("<option value=''>Loading...</option>");
                                        },
                                        success: function (result) {
                                            $('#finalCityList').html(result);
                                        }}
                                    );
                                });
                            });
                        </script>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Type of Channels <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px educations" type="radio" name="migration_type" value="regular" <?php echo $pre_data && $pre_data['migration_type'] == 'regular' ? 'checked' : '' ?>><span class="lbl">Regular</span></label>
                                        <label><input class="px educations" type="radio" name="migration_type" value="irregular" <?php echo $pre_data && $pre_data['migration_type'] == 'irregular' ? 'checked' : '' ?>><span class="lbl">Irregular</span></label>
                                        <label><input class="px educations" type="radio" name="migration_type" value="both" <?php echo $pre_data && $pre_data['migration_type'] == 'both' ? 'checked' : '' ?>><span class="lbl">Both</span></label>
                                    </div>
                                </div>
                            </div>      
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Type of visa <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $all_visa = array(
                                            'tourist' => 'Tourist',
                                            'student' => 'Student',
                                            'work' => 'Work');
                                        foreach ($all_visa as $key => $value) :
                                            $allVisa[] = $key;
                                            ?>
                                            <label><input class="px visa" type="radio" name="visa_type" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['visa_type'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['visa_type']):
                                            ?>
                                            <label><input class="px" type="radio" name="visa_type" <?php
                                                if (!in_array($pre_data['visa_type'], $allVisa)): echo 'checked';
                                                endif;
                                                ?> id="newVisa"><span class="lbl">Other</span></label>
                                                      <?php else : ?>
                                            <label><input class="px" type="radio" name="visa_type" id="newVisa" value=""><span class="lbl">Others. Please specify…</span></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newVisaType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newVisaTypeText" name="new_visa" value="<?php echo $pre_data['visa_type'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newVisa').is(':checked');

                                    if (isChecked == true) {
                                        $('#newVisaType').show();
                                    }

                                    $("#newVisa").on("click", function () {
                                        $('#newVisaType').show();
                                    });

                                    $(".visa").on("click", function () {
                                        $('#newVisaType').hide();
                                        $('#newVisaTypeText').val('');
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Media of Departure</legend>
                        <div class="form-group">
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <?php
                                    $all_departure_media = array(
                                        'Middleman' => 'Middleman',
                                        'Recruiting Agency' => 'Recruiting Agency',
                                        'Returnee' => 'Returnee',
                                        'Friend' => 'Friend',
                                        'Relative' => 'Relative'
                                    );
                                    foreach ($all_departure_media as $key => $value) :
                                        $allDepartureMedia[] = $key;
                                        ?>
                                        <label><input class="px departure_media" type="radio" name="departure_media" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['departure_media'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                        <?php
                                    endforeach;
                                    if ($pre_data['departure_media']):
                                        ?>
                                        <label><input class="px" type="radio" name="departure_media" <?php
                                            if (!in_array($pre_data['departure_media'], $allDepartureMedia)): echo 'checked';
                                            endif;
                                            ?> id="newDepartureMedia"><span class="lbl">Other</span></label>
                                                  <?php else : ?>
                                        <label><input class="px" type="radio" name="departure_media" id="newDepartureMedia" value=""><span class="lbl">Others. Please specify…</span></label>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div id="newDepartureMediaType" style="display: none; margin-bottom: 1em;">
                            <input class="form-control" placeholder="Please Specity" type="text" id="newDepartureMediaTypeText" name="new_departure_media" value="<?php echo $pre_data['departure_media'] ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#newDepartureMedia').is(':checked');

                                if (isChecked == true) {
                                    $('#newDepartureMediaType').show();
                                }

                                $("#newDepartureMedia").on("click", function () {
                                    $('#newDepartureMediaType').show();
                                });

                                $(".departure_media").on("click", function () {
                                    $('#newDepartureMediaType').hide();
                                    $('#newDepartureMediaTypeText').val('');
                                });
                            });
                        </script>
                    </fieldset>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Migration Documents (If applicable)</legend>
                        <div class="col-sm-6">
                            <label class="control-label input-label">Passport No</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label input-label">Travel Pass</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="travel_pass" value="<?php echo $pre_data['travel_pass'] ? $pre_data['travel_pass'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="javascript:;" id="addMoreDocument" class="btn btn-success"><i class="btn-label fa fa-plus-circle"></i> Add More Document</a>
                            </div>
                            <?php if ($edit): foreach ($migration_documents['data'] as $document): ?>
                                    <aside class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label input-label">Document Name</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="document_name[]" value="<?php echo $document['document_name'] ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label input-label">Upload Document</label>
                                                <img src="<?php echo image_url($document['document_file']); ?>" class="img img-responsive"/>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="document_old_file[]" value="<?php echo $document['document_file'] ?>" />
                                                    <input type="file" class="form-control" name="document_file[]" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1" style="margin-top:5%;">
                                            <div class="form-group">
                                                <a href="javascript:" data-id="<?php echo $document['pk_document_id'] ?>" data-customer="<?php echo $document['fk_customer_id'] ?>" class="btn btn-danger remove_row">X</a>
                                            </div>
                                        </div>
                                    </aside>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                            <div id="documentUploads" style="display: none">

                            </div>
                        </div>
                    </fieldset>
                    <script>
                        init.push(function () {
                            $(document).on('click', '.remove_row', function () {
                                var ths = $(this);
                                var thisCell = ths.closest('div');
                                var logId = ths.attr('data-id');
                                var customer = ths.attr('data-customer');
                                if (!logId)
                                    return false;

                                show_button_overlay_working(thisCell);
                                bootbox.prompt({
                                    title: 'Delete Record!',
                                    inputType: 'checkbox',
                                    inputOptions: [{
                                            text: 'Delete Migration',
                                            value: 'deleteMigration'
                                        }],
                                    callback: function (result) {
                                        if (result == 'deleteMigration') {
                                            window.location.href = '?action=deleteMigration&id=' + logId + '&customer=' + customer;
                                        }
                                        hide_button_overlay_working(thisCell);
                                    }
                                });
                            });

                            var upload = '\
                                    <aside>\
                                        <div class="col-md-6">\
                                            <label class="control-label input-label">Document Name</label>\
                                            <div class="form-group">\
                                                <input type="text" class="form-control" name="document_name[]" />\
                                            </div>\
                                        </div>\
                                        <div class="col-md-5">\
                                            <label class="control-label input-label">Upload Document</label>\
                                            <div class="form-group">\
                                                <input type="file" class="form-control" name="document_file[]" />\
                                            </div>\
                                        </div>\
                                        <div class="col-md-1" style="margin-top:5%;">\
                                            <div class="form-group">\
                                                <a href="javascript:" class="btn btn-danger remove_row">X</a>\
                                            </div>\
                                        </div>\
                                    </aside>';

                            $("#documentUploads").show();

                            $("#addMoreDocument").on("click", function () {
                                $("#documentUploads").append(upload);
                            });

                            $(document).on('click', '.remove_row', function () {
                                $(this).closest('aside').remove();
                            });
                        });
                    </script>
                    <div class="col-sm-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Climate Change Impact on Migration</legend>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Are you a victim of any kind of natural disaster?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" id="climateYes" name="climate_effect" value="yes" <?php echo $pre_data && $pre_data['climate_effect'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" id="climateNo" name="climate_effect" value="no" <?php echo $pre_data && $pre_data['climate_effect'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $disaster_types = explode(',', $pre_data['natural_disasters']);
                                $disaster_types = $disaster_types ? $disaster_types : array($disaster_types);
                                ?>
                                <fieldset class="scheduler-border climateImpact" id="disaster" style="display: none; margin-bottom: 1em;">
                                    <legend class="scheduler-border">Type of Natural Disasters</legend>
                                    <div class="form-group ">
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php foreach ($this->all_disaster_types as $key => $value) :
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="natural_disasters[]" value="<?php echo $key ?>" <?php
                                                        if (in_array($key, $disaster_types)) :
                                                            echo 'checked';
                                                        endif
                                                        ?>><span class="lbl"><?php echo $value ?></span></label>
                                                              <?php endforeach ?>
                                                <label><input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['other_natural_disaster'] != NULL ? 'checked' : '' ?> id="newDisaster"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newDisasterType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_natural_disaster" id="newDisasterTypeText" value="<?php echo $pre_data['other_natural_disaster'] ?> ">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newDisaster').is(':checked');

                                            if (isChecked == true) {
                                                $('#newDisasterType').show();
                                            }

                                            $("#newDisaster").on("click", function () {
                                                $('#newDisasterType').toggle();
                                                $('#newDisasterTypeText').val('');
                                            });
                                        });
                                    </script>
                                </fieldset>

                                <?php
                                $economic_impact_types = explode(',', $pre_data['economic_impacts']);
                                $economic_impact_types = $economic_impact_types ? $economic_impact_types : array($economic_impact_types);
                                ?>
                                <fieldset class="scheduler-border climateImpact" style="display: none; margin-bottom: 1em;">
                                    <legend class="scheduler-border">Economic Impact of Change</legend>
                                    <div class="form-group ">
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php foreach ($this->all_economic_impact_types as $key => $value) :
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="economic_impacts[]" value="<?php echo $key ?>" <?php
                                                        if (in_array($key, $economic_impact_types)) :
                                                            echo 'checked';
                                                        endif
                                                        ?>><span class="lbl"><?php echo $value ?></span></label>
                                                              <?php endforeach ?>
                                                <label><input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['other_economic_impact'] != NULL ? 'checked' : '' ?> id="newEconomicImpact"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newEconomicImpactType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_economic_impact" id="newEconomicImpactTypeText" value="<?php echo $pre_data['other_economic_impact'] ?> ">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newEconomicImpact').is(':checked');

                                            if (isChecked == true) {
                                                $('#newEconomicImpactType').show();
                                            }

                                            $("#newEconomicImpact").on("click", function () {
                                                $('#newEconomicImpactType').toggle();
                                                $('#newEconomicImpactTypeText').val('');
                                            });
                                        });
                                    </script>
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group climateImpact" style="display: none; margin-bottom: 1em;">
                                    <label>Financial Losses</label>
                                    <input class="form-control" type="number" name="financial_losses" value="<?php echo $pre_data['financial_losses'] ? $pre_data['financial_losses'] : ''; ?>">
                                </div>
                                <?php
                                $social_impact_types = explode(',', $pre_data['social_impacts']);
                                $social_impact_types = $social_impact_types ? $social_impact_types : array($social_impact_types);
                                ?>
                                <fieldset class="scheduler-border climateImpact" style="display: none; margin-bottom: 1em;">
                                    <legend class="scheduler-border">Social Impact of Change</legend>
                                    <div class="form-group ">
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php foreach ($this->all_social_impact_types as $key => $value) :
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="social_impacts[]" value="<?php echo $key ?>" <?php
                                                        if (in_array($key, $social_impact_types)) :
                                                            echo 'checked';
                                                        endif
                                                        ?>><span class="lbl"><?php echo $value ?></span></label>
                                                              <?php endforeach ?>
                                                <label><input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['other_social_impact'] != NULL ? 'checked' : '' ?> id="newSocialImpact"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newSocialImpactType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_social_impact" id="newSocialImpactTypeText" value="<?php echo $pre_data['other_social_impact'] ?> ">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newSocialImpact').is(':checked');

                                            if (isChecked == true) {
                                                $('#newSocialImpactType').show();
                                            }

                                            $("#newSocialImpact").on("click", function () {
                                                $('#newSocialImpactType').toggle();
                                                $('#newSocialImpactTypeText').val('');
                                            });
                                        });
                                    </script>
                                </fieldset>
                                <div class="form-group climateImpact" style="display: none;">
                                    <label>Whether this natural disaster is the main reason for Internal migration?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="is_disaster_migration" value="yes" <?php echo $pre_data && $pre_data['is_disaster_migration'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="is_disaster_migration" value="no" <?php echo $pre_data && $pre_data['is_disaster_migration'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group climateImpact" style="display: none;">
                                    <label>Do you think climate change/natural disaster is the main reason for overseas migration?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="is_climate_migration" value="yes" <?php echo $pre_data && $pre_data['is_climate_migration'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="is_climate_migration" value="no" <?php echo $pre_data && $pre_data['is_climate_migration'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#climateYes').is(':checked');

                                        if (isChecked == true) {
                                            $('.climateImpact').show();
                                        }

                                        $("#climateYes").on("click", function () {
                                            $('.climateImpact').show();
                                        });

                                        $("#climateNo").on("click", function () {
                                            $('.climateImpact').hide();

                                        });
                                    });
                                </script>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Recruiting /Travel Agency Information</legend>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label input-label">Recruiting /Travel Agency Name</label>
                                    <input type="text" class="form-control" name="agency_name" value="<?php echo $pre_data['agency_name'] ? $pre_data['agency_name'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label input-label">RL No</label>
                                    <input type="text" class="form-control" name="rl_no" value="<?php echo $pre_data['rl_no'] ? $pre_data['rl_no'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label input-label">How They Knows About The Recruiting /Travel Agency?</label>
                                    <textarea class="form-control" name="agency_know"><?php echo $pre_data['agency_know'] ? $pre_data['agency_know'] : ''; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label input-label">Agency Address</label>
                                    <textarea class="form-control" name="agency_address"><?php echo $pre_data['agency_address'] ? $pre_data['agency_address'] : ''; ?></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Date of Departure from Bangladesh</label>
                            <div class="input-group">
                                <input id="date_of_depature" type="text" class="form-control" name="departure_date" value="<?php echo $pre_data['departure_date'] && $pre_data['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['departure_date'])) : date('d-m-Y'); ?>">
                            </div>
                            <script type="text/javascript">
                                init.push(function () {
                                    _datepicker('date_of_depature');
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label>Date of Return to Bangladesh</label>
                            <div class="input-group">
                                <input id="date_of_return" type="text" class="form-control" name="return_date" value="<?php echo $pre_data['return_date'] && $pre_data['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['return_date'])) : date('d-m-Y'); ?>">
                            </div>
                            <script type="text/javascript">
                                init.push(function () {
                                    _datepicker('date_of_return');
                                });
                            </script>
                        </div>
                        <?php if ($edit): ?>
                            <div class="form-group">
                                <label>Age (When come to Bangladesh)</label>
                                <input class="form-control" type="text" value="<?php echo $pre_data['returned_age'] ? $pre_data['returned_age'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Duration of Stay Abroad (Months)</label>
                                <input class="form-control" type="text" value="<?php echo $pre_data['migration_duration'] ? $pre_data['migration_duration'] : ''; ?>">
                            </div>
                        <?php endif ?>
                        <div class="form-group">
                            <label>Occupation in overseas country <span style="color: red;">*</span></label>
                            <input class="form-control" type="text" name="migration_occupation" value="<?php echo $pre_data['migration_occupation'] ? $pre_data['migration_occupation'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Income: (If applicable)</label>
                            <input class="form-control" type="number" name="earned_money" value="<?php echo $pre_data['earned_money'] ? $pre_data['earned_money'] : ''; ?>">
                        </div>
                    </div>                           
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Reasons for Migration</label>
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <?php
                                    $all_migration_reasons = array(
                                        'Underemployed' => 'Underemployed',
                                        'Unemployed' => 'Unemployed',
                                        'Higher Income' => 'Higher Income',
                                        'Join Friends or Family Abroad' => 'Join Friends or Family Abroad',
                                        'Want To Leave Home' => 'Want To Leave Home',
                                        'Pay Back Debts' => 'Pay Back Debts',
                                        'Political Reason' => 'Political Reason',
                                        'Education' => 'Education',
                                    );
                                    $migration_reasons = explode(',', $pre_data['migration_reasons']);
                                    $migration_reasons = $migration_reasons ? $migration_reasons : array($migration_reasons);
                                    ?>
                                    <?php foreach ($all_migration_reasons as $key => $value) :
                                        ?>
                                        <label><input class="px" type="checkbox" name="migration_reasons[]" value="<?php echo $key ?>" <?php
                                            if (in_array($key, $migration_reasons)) :
                                                echo 'checked';
                                            endif
                                            ?>><span class="lbl"><?php echo $value ?></span></label>
                                                  <?php endforeach ?>
                                    <label><input class="px" type="checkbox" id="newReasonsMigration" <?php echo $pre_data && $pre_data['other_migration_reason'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                </div>
                            </div>
                        </div>
                        <div id="newReasonsMigrationType" style="display: none; margin-bottom: 1em;">
                            <input class="form-control" placeholder="Please Specity" type="text" id="newReasonsMigrationTypeText" name="new_migration_reason" value="<?php echo $pre_data['other_migration_reason'] ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#newReasonsMigration').is(':checked');

                                if (isChecked == true) {
                                    $('#newReasonsMigrationType').show();
                                }

                                $("#newReasonsMigration").on("click", function () {
                                    $('#newReasonsMigrationType').toggle();
                                    $('#newReasonsMigrationTypeText').val('');
                                });
                            });
                        </script>
                        <?php
                        $all_leave_reasons = array(
                            'No Legal Documents To Stay' => 'No Legal Documents To Stay',
                            'Experienced Violence' => 'Experienced Violence',
                            'Unable To Find A Job' => 'Unable To Find A Job',
                            'Salary Was Too Low' => 'Salary Was Too Low',
                            'No Accommodation (Lived in The Streets)' => 'No Accommodation (Lived in The Streets)',
                            'Sickness' => 'Sickness',
                            'Family Needs' => 'Family Needs',
                        );
                        $leave_reasons = explode(',', $pre_data['destination_country_leave_reason']);
                        $leave_reasons = $leave_reasons ? $leave_reasons : array($leave_reasons);
                        ?>
                        <div class="form-group">
                            <label>Reasons for returning to Bangladesh</label>
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <?php foreach ($all_leave_reasons as $key => $value) : ?>
                                        <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="<?php echo $key ?>" <?php
                                            if (in_array($key, $leave_reasons)) :
                                                echo 'checked';
                                            endif
                                            ?>><span class="lbl"><?php echo $value ?></span></label>
                                                  <?php endforeach ?>
                                    <label><input class="px" type="checkbox" id="newreturningBangladesh" <?php echo $pre_data && $pre_data['other_destination_country_leave_reason'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                </div>
                            </div>
                        </div>
                        <div id="newreturningBangladeshType" style="display: none; margin-bottom: 1em;">
                            <input class="form-control" placeholder="Please Specity" type="text" id="newreturningBangladeshTypeText" name="new_return_reason" value="<?php echo $pre_data['other_destination_country_leave_reason'] ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#newreturningBangladesh').is(':checked');

                                if (isChecked == true) {
                                    $('#newreturningBangladeshType').show();
                                }

                                $("#newreturningBangladesh").on("click", function () {
                                    $('#newreturningBangladeshType').toggle();
                                    $('#newreturningBangladeshTypeText').val('');
                                });
                            });
                        </script>
                    </div>
                    <div class="col-sm-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Did the following happen to you when you in transit or during your stay in the country abroad? </legend>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>False promises about a job prior to arrival at workplace abroad</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="is_cheated" value="yes" <?php echo $pre_data && $pre_data['is_cheated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="is_cheated" value="no" <?php echo $pre_data && $pre_data['is_cheated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Forced to perform work or other activities against your will, after the departure from Bangladesh?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="forced_work" value="yes" <?php echo $pre_data && $pre_data['forced_work'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="forced_work" value="no" <?php echo $pre_data && $pre_data['forced_work'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Experienced excessive working hours (more than 40 hours a week)</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="excessive_work" value="yes" <?php echo $pre_data && $pre_data['excessive_work'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="excessive_work" value="no" <?php echo $pre_data && $pre_data['excessive_work'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Deductions from salary for recruitment fees at workplace</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="is_money_deducted" value="yes" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="is_money_deducted" value="no" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Denied freedom of movement during or between work shifts after your departure from Bangladesh?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="is_movement_limitation" value="yes" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="is_movement_limitation" value="no" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" name="employer_threatened" value="yes" <?php echo $pre_data && $pre_data['employer_threatened'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" name="employer_threatened" value="no" <?php echo $pre_data && $pre_data['employer_threatened'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh?</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="is_kept_document" value="yes" <?php echo $pre_data && $pre_data['is_kept_document'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px" type="radio" name="is_kept_document" value="no" <?php echo $pre_data && $pre_data['is_kept_document'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>
            </div>
            <div class="tab">
                <fieldset>
                    <legend>Section 3: Socio Economic Profile</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount of Migration Cost</label>
                                <input type="number" class="form-control" name="migration_cost" value="<?php echo $pre_data['migration_cost'] ? $pre_data['migration_cost'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Source of Migration Cost</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $all_migration_cost_source = array(
                                            'Loan' => 'Loan',
                                            'Land Sell' => 'Land Sell',
                                            'Gold Sell' => 'Gold Sell',
                                            'Financial assistance from friends' => 'Financial assistance from friends',
                                            'Financial assistance from family' => 'Financial assistance from family',
                                            'Financial assistance from relative' => 'Financial assistance from relative',
                                            'Savings' => 'Savings',
                                            'Asset Mortgage' => 'Asset Mortgage',
                                            'Dowry' => 'Dowry',
                                        );
                                        $migration_cost_sources = explode(',', $pre_data['migration_cost_sources']);
                                        $migration_cost_sources = $migration_cost_sources ? $migration_cost_sources : array($migration_cost_sources);
                                        ?>
                                        <?php foreach ($all_migration_cost_source as $key => $value) :
                                            ?>
                                            <label><input class="px" type="checkbox" name="migration_cost_sources[]" value="<?php echo $key ?>" <?php
                                                if (in_array($key, $migration_cost_sources)) :
                                                    echo 'checked';
                                                endif
                                                ?>><span class="lbl"><?php echo $value ?></span></label>
                                                      <?php endforeach ?>
                                        <label><input class="px" type="checkbox" id="newMigrationCost" <?php echo $pre_data && $pre_data['other_migration_cost_source'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newMigrationCostType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newMigrationCostText" name="new_migration_cost_source" value="<?php echo $pre_data['other_migration_cost_source'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newMigrationCost').is(':checked');

                                    if (isChecked == true) {
                                        $('#newMigrationCostType').show();
                                    }

                                    $("#newMigrationCost").on("click", function () {
                                        $('#newMigrationCostType').toggle();
                                        $('#newMigrationCostText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Any Property/wealth</label>
                                <input type="text" class="form-control" name="property_name" value="<?php echo $pre_data['property_name'] ? $pre_data['property_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Any Property/wealth Value</label>
                                <input type="number" class="form-control" name="property_value" value="<?php echo $pre_data['property_value'] ? $pre_data['property_value'] : '0'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Main occupation (before trafficking) <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php foreach ($this->all_occupations as $key => $value) : $allPreOccupations[] = $key; ?>
                                            <label><input class="px pre_occupations" type="radio" name="pre_occupation" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['pre_occupation'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['pre_occupation']):
                                            ?>
                                            <label><input class="px" type="radio" name="pre_occupation" <?php
                                                if (!in_array($pre_data['pre_occupation'], $allPreOccupations)): echo 'checked';
                                                endif;
                                                ?> id="newPreOccupation"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php else : ?>
                                            <label><input class="px" type="radio" name="pre_occupation" value="" id="newPreOccupation"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newPreOccupationType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_pre_occupation" id="newPreOccupationText" value="<?php echo $pre_data['pre_occupation'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newPreOccupation').is(':checked');

                                    if (isChecked == true) {
                                        $('#newPreOccupationType').show();
                                    }

                                    $("#newPreOccupation").on("click", function () {
                                        $('#newPreOccupationType').show();
                                    });

                                    $(".pre_occupations").on("click", function () {
                                        $('#newPreOccupationType').hide();
                                        $('#newPreOccupationText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Main occupation (after return) <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php foreach ($this->all_occupations as $key => $value) : $allOccupations[] = $key; ?>
                                            <label><input class="px occupations" type="radio" name="present_occupation" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['present_occupation'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['present_occupation']):
                                            ?>
                                            <label><input class="px" type="radio" name="present_occupation" <?php
                                                if (!in_array($pre_data['present_occupation'], $allOccupations)): echo 'checked';
                                                endif;
                                                ?> id="newOccupation"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php else : ?>
                                            <label><input class="px" type="radio" name="present_occupation" value="" id="newOccupation"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newOccupationType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_occupation" id="newOccupationText" value="<?php echo $pre_data['present_occupation'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newOccupation').is(':checked');

                                    if (isChecked == true) {
                                        $('#newOccupationType').show();
                                    }

                                    $("#newOccupation").on("click", function () {
                                        $('#newOccupationType').show();
                                    });

                                    $(".occupations").on("click", function () {
                                        $('#newOccupationType').hide();
                                        $('#newOccupationText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Monthly income of Survivor after return (In BDT) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="present_income" value="<?php echo $pre_data['present_income'] ? $pre_data['present_income'] : '00'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Last Month Income in BDT</label>
                                <input type="text" class="form-control" name="returnee_income_source" value="<?php echo $pre_data['returnee_income_source'] ? $pre_data['returnee_income_source'] : '0'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Source of income</label>
                                <input type="text" class="form-control" name="income_source" value="<?php echo $pre_data['income_source'] ? $pre_data['income_source'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Total Family income(Last Month)</label>
                                <input type="number" class="form-control" name="family_income" value="<?php echo $pre_data['family_income'] ? $pre_data['family_income'] : '0'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Savings (BDT) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="personal_savings" value="<?php echo $pre_data['personal_savings'] ? $pre_data['personal_savings'] : '00'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Loan Amount (BDT) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="personal_debt" value="<?php echo $pre_data['personal_debt'] ? $pre_data['personal_debt'] : '00'; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Ownership of House <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $all_house_ownership = array(
                                            'own' => 'Own',
                                            'rental' => 'Rental',
                                            'without_paying' => 'Live Without Paying',
                                            'khas_land' => 'Living in Khas Land');
                                        ?>
                                        <?php foreach ($all_house_ownership as $key => $value) : $allHouseOwnership[] = $key; ?>
                                            <label><input class="px house_ownership" type="radio" name="current_residence_ownership" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['current_residence_ownership'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['current_residence_ownership']):
                                            ?>
                                            <label><input class="px" type="radio" name="current_residence_ownership" <?php
                                                if (!in_array($pre_data['current_residence_ownership'], $allHouseOwnership)): echo 'checked';
                                                endif;
                                                ?> id="newHouseOwnership"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php else : ?>
                                            <label><input class="px" type="radio" name="current_residence_ownership" value="" id="newHouseOwnership"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newHouseOwnershipType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_ownership" id="newHouseOwnershipTypeText" value="<?php echo $pre_data['current_residence_ownership'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newHouseOwnership').is(':checked');

                                    if (isChecked == true) {
                                        $('#newHouseOwnershipType').show();
                                    }

                                    $("#newHouseOwnership").on("click", function () {
                                        $('#newHouseOwnershipType').show();
                                    });

                                    $(".house_ownership").on("click", function () {
                                        $('#newHouseOwnershipType').hide();
                                        $('#newHouseOwnershipTypeText').val('');
                                    });
                                });
                            </script>
                            <?php
                            $all_house_type = array(
                                'raw_house' => 'Raw house (Wall made of mud/straw, roof made of tin jute stick/ pampas grass/ khar/ leaves)',
                                'pucca' => 'Pucca (Wall, floor and roof of the house made of concrete)',
                                'live' => 'Live Semi-pucca (Roof made of tin, wall or floor made of concrete)',
                                'tin' => 'Tin (Wall, and roof of the house made of tin)');
                            ?>
                            <div class="form-group">
                                <label>Type of house <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        foreach ($all_house_type as $key => $value) :
                                            $allHouseType[] = $key;
                                            ?>
                                            <label><input class="px house" type="radio" name="current_residence_type" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['current_residence_type'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                            <?php
                                        endforeach;
                                        if ($pre_data['current_residence_type']):
                                            ?>
                                            <label><input class="px" type="radio" name="current_residence_type" <?php
                                                if (!in_array($pre_data['current_residence_type'], $allHouseType)): echo 'checked';
                                                endif;
                                                ?> id="newHouse"><span class="lbl">Others. Please specify…</span></label>
                                                      <?php else : ?>
                                            <label><input class="px" type="radio" name="current_residence_type" value="" id="newHouse"><span class="lbl">Others. Please specify…</span>
                                            </label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="newHouseType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" id="newHouseTypeText" type="text" name="new_residence" value="<?php echo $pre_data['current_residence_type'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newHouse').is(':checked');

                                    if (isChecked == true) {
                                        $('#newHouseType').show();
                                    }

                                    $("#newHouse").on("click", function () {
                                        $('#newHouseType').show();
                                    });

                                    $(".house").on("click", function () {
                                        $('#newHouseType').hide();
                                        $('#newHouseTypeText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Do you have any skill training?</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" id="trainingYes" name="have_training" value="yes" <?php echo $pre_data && $pre_data['have_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px" type="radio" id="trainingNo" name="have_training" value="no" <?php echo $pre_data && $pre_data['have_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <script>
                                init.push(function () {
                                    $('.trainingList').hide();
                                    var isChecked = $('#trainingYes').is(':checked');

                                    if (isChecked == true) {
                                        $('.trainingList').show();
                                    }

                                    $("#trainingYes").on("click", function () {
                                        $('.trainingList').show();
                                    });

                                    $("#trainingNo").on("click", function () {
                                        $('.trainingList').hide();
                                    });
                                });
                            </script>
                            <fieldset class="scheduler-border trainingList">
                                <legend class="scheduler-border">List of Trainings</legend>
                                <div class="form-group ">
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <?php
                                            $have_trainings = explode(',', $pre_data['have_skill_trainings']);
                                            $have_trainings = $have_trainings ? $have_trainings : array($have_trainings);
                                            foreach ($this->all_trainings as $key => $value) :
                                                ?>
                                                <label class="col-sm-12"><input class="px" type="checkbox" name="have_skill_trainings[]" value="<?php echo $key ?>" <?php
                                                    if (in_array($key, $have_trainings)) :
                                                        echo 'checked';
                                                    endif
                                                    ?>><span class="lbl"><?php echo $value ?></span></label>

                                            <?php endforeach ?>    
                                            <label class="col-sm-12"><input class="px col-sm-12" <?php echo $pre_data && $pre_data['other_have_skill_training'] != NULL ? 'checked' : '' ?> type="checkbox" id="newTraining"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newTrainingType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newTrainingText" name="new_skill_training" value="<?php echo $pre_data['other_have_skill_training'] ?>">
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#newTraining').is(':checked');

                                        if (isChecked == true) {
                                            $('#newTrainingType').show();
                                        }

                                        $("#newTraining").on("click", function () {
                                            $('#newTrainingType').toggle();
                                            $('#newTrainingText').val('');
                                        });
                                    });
                                </script>
                            </fieldset>
                            <div class="form-group">
                                <label>Are you interested in receiving any skills training?</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" id="interestedYes" name="is_interested_training" value="yes" <?php echo $pre_data && $pre_data['is_interested_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px" type="radio" id="interestedNo" name="is_interested_training" value="no" <?php echo $pre_data && $pre_data['is_interested_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <script>
                                init.push(function () {
                                    $('.interestedTrainingList').hide();
                                    var isChecked = $('#interestedYes').is(':checked');

                                    if (isChecked == true) {
                                        $('.interestedTrainingList').show();
                                    }

                                    $("#interestedYes").on("click", function () {
                                        $('.interestedTrainingList').show();
                                    });

                                    $("#interestedNo").on("click", function () {
                                        $('.interestedTrainingList').hide();
                                    });
                                });
                            </script>
                            <fieldset class="scheduler-border interestedTrainingList">
                                <legend class="scheduler-border">List of Trainings</legend>
                                <div class="form-group ">
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <?php
                                            $interested_trainings = explode(',', $pre_data['interested_trainings']);
                                            $interested_trainings = $interested_trainings ? $interested_trainings : array($interested_trainings);
                                            foreach ($this->all_trainings as $key => $value) :
                                                ?>
                                                <label class="col-sm-12"><input class="px" type="checkbox" name="interested_trainings[]" value="<?php echo $key ?>" <?php
                                                    if (in_array($key, $interested_trainings)) :
                                                        echo 'checked';
                                                    endif
                                                    ?>><span class="lbl"><?php echo $value ?></span></label>

                                            <?php endforeach ?>    
                                            <label class="col-sm-12"><input class="px col-sm-12" <?php echo $pre_data && $pre_data['other_interested_training'] != NULL ? 'checked' : '' ?> type="checkbox" id="newInterestedTraining"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newInterestedTrainingType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newInterestedTrainingText" name="new_interested_training" value="<?php echo $pre_data['other_interested_training'] ?>">
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#newInterestedTraining').is(':checked');

                                        if (isChecked == true) {
                                            $('#newInterestedTrainingType').show();
                                        }

                                        $("#newInterestedTraining").on("click", function () {
                                            $('#newInterestedTrainingType').toggle();
                                            $('#newInterestedTrainingText').val('');
                                        });
                                    });
                                </script>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="tab">
                <fieldset>
                    <legend>Section 4: Information on Income Generating Activities(IGA) Skills</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>IGA Skills ?  <span style="color: red;">*</span></label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" id="iga_skillYes" name="have_earner_skill" value="yes" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px" type="radio" id="iga_skillNo" name="have_earner_skill" value="no" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <script>
                                init.push(function () {
                                    $('.iga_skill').hide();
                                    var isChecked = $('#iga_skillYes').is(':checked');

                                    if (isChecked == true) {
                                        $('.iga_skill').show();
                                    }

                                    $("#iga_skillYes").on("click", function () {
                                        $('.iga_skill').show();
                                    });

                                    $("#iga_skillNo").on("click", function () {
                                        $('.iga_skill').hide();
                                    });
                                });
                            </script>
                            <fieldset class="scheduler-border iga_skill">
                                <legend class="scheduler-border">IGA Skills</legend>
                                <div class="form-group ">
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label class="col-sm-12"><input class="px" id="vocationalSkill" type="checkbox" name="technical_have_skills[]" <?php echo $pre_data && $pre_data['vocational_skill'] != NULL ? 'checked' : '' ?> value="Vocational"><span class="lbl">Vocational</span></label>
                                            <div id="vocationalSkillAttr" class="form-group col-sm-9">
                                                <input type="text" class="form-control" placeholder="Specify....." name="new_vocational" value="<?php echo $pre_data['vocational_skill'] ? $pre_data['vocational_skill'] : ''; ?>" />
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $('#vocationalSkillAttr').hide();
                                                    var isChecked = $('#vocationalSkill').is(':checked');

                                                    if (isChecked == true) {
                                                        $('#vocationalSkillAttr').show();
                                                    }

                                                    $("#vocationalSkill").on("click", function () {
                                                        $('#vocationalSkillAttr').toggle();
                                                    });
                                                });
                                            </script>
                                            <label class="col-sm-12"><input class="px" id="handicraftSkill" type="checkbox" name="technical_have_skills[]" <?php echo $pre_data && $pre_data['handicraft_skill'] != NULL ? 'checked' : '' ?> value="Handicrafts"><span class="lbl">Handicrafts</span></label>
                                            <div id="handicraftSkillAttr" class="form-group col-sm-9">
                                                <input type="text" class="form-control" placeholder="Specify....." name="new_handicrafts" value="<?php echo $pre_data['handicraft_skill'] ? $pre_data['handicraft_skill'] : ''; ?>" />
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $('#handicraftSkillAttr').hide();
                                                    var isChecked = $('#handicraftSkill').is(':checked');

                                                    if (isChecked == true) {
                                                        $('#handicraftSkillAttr').show();
                                                    }

                                                    $("#handicraftSkill").on("click", function () {
                                                        $('#handicraftSkillAttr').toggle();
                                                    });
                                                });
                                            </script>
                                            <?php
                                            $all_have_skills = array(
                                                'Beauty Parlour' => 'Beauty Parlour',
                                                'Tailor Work' => 'Tailor Work',
                                                "Block Bati's" => "Block Batik's",
                                                'Cultivating bees/crab fattening' => 'Cultivating bees/crab fattening',
                                                'Livestock Rearing' => 'Livestock Rearing',
                                                'Poultry Rearing' => 'Poultry Rearing',
                                                'Cooking' => 'Cooking',
                                            );
                                            $have_skills = explode(',', $pre_data['have_skills']);
                                            $have_skills = $have_skills ? $have_skills : array($have_skills);
                                            foreach ($all_have_skills as $key => $value) :
                                                ?>
                                                <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="<?php echo $key ?>" <?php
                                                    if (in_array($key, $have_skills)) :
                                                        echo 'checked';
                                                    endif
                                                    ?>><span class="lbl"><?php echo $value ?></span></label>

                                            <?php endforeach ?>    
                                            <label class="col-sm-12"><input class="px col-sm-12" <?php echo $pre_data && $pre_data['other_have_skills'] != NULL ? 'checked' : '' ?> type="checkbox" id="newSkill"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newSkillType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newSkillTypeText" name="new_have_technical" value="<?php echo $pre_data['other_have_skills'] ?>">
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#newSkill').is(':checked');

                                        if (isChecked == true) {
                                            $('#newSkillType').show();
                                        }

                                        $("#newSkill").on("click", function () {
                                            $('#newSkillType').toggle();
                                            $('#newSkillTypeText').val('');
                                        });
                                    });
                                </script>
                            </fieldset >
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="tab">
                <fieldset>
                    <legend>Section 5: Vulnerability Assessment</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Survivor</legend>
                                <div class="form-group">
                                    <label>Do you have any disability?  <span style="color: red;">*</span></label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" id="yesdisability" name="is_physically_challenged" value="yes" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" id="nodisability" name="is_physically_challenged" value="no" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group disability" style="display:none">                           
                                    <div class="form-group">
                                        <label class="control-label input-label">Type of disability</label>
                                        <div class="select2-primary">
                                            <select class="form-control" name="disability_type" style="text-transform: capitalize">
                                                <option value="">Select One</option>
                                                <?php foreach ($this->all_disabilities as $key => $value) : ?>
                                                    <option id="<?php echo $key ?>" value="<?php echo $value ?>" <?php echo $pre_data && $pre_data['disability_type'] == $key ? 'selected' : '' ?>><?php echo $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#yesdisability').is(':checked');

                                        if (isChecked == true) {
                                            $('.disability').show();
                                        }

                                        $("#yesdisability").on("click", function () {
                                            $('.disability').show();
                                        });

                                        $("#nodisability").on("click", function () {
                                            $('.disability').hide();
                                        });
                                    });
                                </script>
                                <div class="form-group">
                                    <label>Do you have any Chronic Disease?  <span style="color: red;">*</span></label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" id="yeChronicDisease" name="having_chronic_disease" value="yes" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" id="noChronicDisease" name="having_chronic_disease" value="no" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#yeChronicDisease').is(':checked');

                                        if (isChecked == true) {
                                            $('.ChronicDisease').show();
                                        }

                                        $("#yeChronicDisease").on("click", function () {
                                            $('.ChronicDisease').show();
                                        });

                                        $("#noChronicDisease").on("click", function () {
                                            $('.ChronicDisease').hide();
                                        });
                                    });
                                </script>
                                <fieldset class="scheduler-border ChronicDisease" style="display: none; margin-bottom: 1em;">
                                    <legend class="scheduler-border">Type of Disease</legend>
                                    <div class="form-group ">
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $all_disease_types = array(
                                                    'Cancer' => 'Cancer',
                                                    'Diabetes' => 'Diabetes',
                                                    'Arthritis' => 'Arthritis',
                                                    'Asthmatic' => 'Asthmatic',
                                                    'Kidney Disease' => 'Kidney Disease',
                                                    'Heart Diseases' => 'Heart Diseases',
                                                    'Bronchitis' => 'Bronchitis',
                                                );
                                                $disease_types = explode(',', $pre_data['disease_type']);
                                                $disease_types = $disease_types ? $disease_types : array($disease_types);
                                                ?>
                                                <?php foreach ($all_disease_types as $key => $value) :
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="<?php echo $key ?>" <?php
                                                        if (in_array($key, $disease_types)) :
                                                            echo 'checked';
                                                        endif
                                                        ?>><span class="lbl"><?php echo $value ?></span></label>
                                                              <?php endforeach ?>
                                                <label><input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['other_disease_type'] != NULL ? 'checked' : '' ?> id="newDisease"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newDiseaseTypes" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disease_type" id="newDiseaseTypesText" value="<?php echo $pre_data['other_disease_type'] ?> ">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newDisease').is(':checked');

                                            if (isChecked == true) {
                                                $('#newDiseaseTypes').show();
                                            }

                                            $("#newDisease").on("click", function () {
                                                $('#newDiseaseTypes').toggle();
                                                $('#newDiseaseTypesText').val('');
                                            });
                                        });
                                    </script>
                                </fieldset>
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Survivor's Family</legend>
                                <div class="form-group">
                                    <label>Have any disabled individual in your family?</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="radio" id="yesFamilyDisable" name="is_family_challenged" value="yes" <?php echo $pre_data && $pre_data['is_family_challenged'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                            <label><input class="px" type="radio" id="noFamilyDisable" name="is_family_challenged" value="no" <?php echo $pre_data && $pre_data['is_family_challenged'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group familyDisability" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label input-label">Type of disability</label>
                                        <div class="select2-primary">
                                            <select class="form-control" name="family_disability_type" style="text-transform: capitalize">
                                                <option value="">Select One</option>
                                                <?php foreach ($this->all_disabilities as $key => $value) : ?>
                                                    <option id="<?php echo $key ?>" value="<?php echo $value ?>" <?php echo $pre_data && $pre_data['family_disability_type'] == $key ? 'selected' : '' ?>><?php echo $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#yesFamilyDisable').is(':checked');

                                        if (isChecked == true) {
                                            $('.familyDisability').show();
                                        }

                                        $("#yesFamilyDisable").on("click", function () {
                                            $('.familyDisability').show();
                                        });

                                        $("#noFamilyDisable").on("click", function () {
                                            $('.familyDisability').hide();
                                        });
                                    });
                                </script>
                                <div class="form-group">
                                    <label>Relationship with Survivor</label>
                                    <input class="form-control" type="text" name="survivor_relationship" value="<?php echo $pre_data['survivor_relationship'] ? $pre_data['survivor_relationship'] : ''; ?>">
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div style="overflow:auto;">
                <div style="float:right; margin-top: 5px;">
                    <button type="button" class="previous">Previous</button>
                    <button type="button" class="next">Next</button>
                    <button type="button" class="submit">Submit</button>
                </div>
            </div>
            <div style="text-align:center;margin-top:40px;">
                <span class="step">1</span>
                <span class="step">2</span>
                <span class="step">3</span>
                <span class="step">4</span>
                <span class="step">5</span>
            </div>
            <a href="<?php echo url('admin/dev_customer_management/manage_customers?action=clear_form') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Clear Form Data</a>
        </form>
    </div>
</div>
<script                                                                                                                                                                                        >
    init.push(function () {
        /* Auto Save Form Data */

        $("#myForm").on("change paste keyup", function () {
            $.ajax({
                type: 'POST',
                data: $('#myForm').serialize() + '&saveForm=1',
                beforeSend: function () {
                    $('#autoSave').html("<span class='text-danger'>(Saving...)</span>");
                },
                success: function (result) {
                    $('#autoSave').html(result);
                }}
            );
        });

        /* Multi Step Form Data */

        (function ($) {
            $.fn.multiStepForm = function (args) {
                if (args === null || typeof args !== 'object' || $.isArray(args))
                    throw  " : Called with Invalid argument";
                var form = this;
                var tabs = form.find('.tab');
                var steps = form.find('.step');
                steps.each(function (i, e) {
                    $(e).on('click', function (ev) {
                        form.navigateTo(i);
                    });
                });
                form.navigateTo = function (i) {/*index*/
                    /*Mark the current section with the class 'current'*/
                    tabs.removeClass('current').eq(i).addClass('current');
                    // Show only the navigation buttons that make sense for the current section:
                    form.find('.previous').toggle(i > 0);
                    atTheEnd = i >= tabs.length - 1;
                    form.find('.next').toggle(!atTheEnd);
                    // console.log('atTheEnd='+atTheEnd);
                    form.find('.submit').toggle(atTheEnd);
                    fixStepIndicator(curIndex());
                    return form;
                }
                function curIndex() {
                    /*Return the current index by looking at which section has the class 'current'*/
                    return tabs.index(tabs.filter('.current'));
                }
                function fixStepIndicator(n) {
                    steps.each(function (i, e) {
                        i == n ? $(e).addClass('active') : $(e).removeClass('active');
                    });
                }
                /* Previous button is easy, just go back */
                form.find('.previous').click(function () {
                    form.navigateTo(curIndex() - 1);
                });

                /* Next button goes forward iff current block validates */
                form.find('.next').click(function () {
                    if ('validations' in args && typeof args.validations === 'object' && !$.isArray(args.validations)) {
                        if (!('noValidate' in args) || (typeof args.noValidate === 'boolean' && !args.noValidate)) {
                            form.validate(args.validations);
                            if (form.valid() == true) {
                                form.navigateTo(curIndex() + 1);
                                return true;
                            }
                            return false;
                        }
                    }
                    form.navigateTo(curIndex() + 1);
                });
                form.find('.submit').on('click', function (e) {
                    if (typeof args.beforeSubmit !== 'undefined' && typeof args.beforeSubmit !== 'function')
                        args.beforeSubmit(form, this);
                    /*check if args.submit is set false if not then form.submit is not gonna run, if not set then will run by default*/
                    if (typeof args.submit === 'undefined' || (typeof args.submit === 'boolean' && args.submit)) {
                        form.submit();
                    }
                    return form;
                });
                /*By default navigate to the tab 0, if it is being set using defaultStep property*/
                typeof args.defaultStep === 'number' ? form.navigateTo(args.defaultStep) : null;

                form.noValidate = function () {

                }
                return form;
            };
        }(jQuery));

        $(document).ready(function () {
            $.validator.addMethod('date', function (value, element, param) {
                return (value != 0) && (value <= 31) && (value == parseInt(value, 10));
            }, 'Please enter a valid date!');
            $.validator.addMethod('month', function (value, element, param) {
                return (value != 0) && (value <= 12) && (value == parseInt(value, 10));
            }, 'Please enter a valid month!');
            $.validator.addMethod('year', function (value, element, param) {
                return (value != 0) && (value >= 1900) && (value == parseInt(value, 10));
            }, 'Please enter a valid year not less than 1900!');
            $.validator.addMethod('username', function (value, element, param) {
                var nameRegex = /^[a-zA-Z0-9]+$/;
                return value.match(nameRegex);
            }, 'Only a-z, A-Z, 0-9 characters are allowed');

            var val = {
                // Specify Validation Rules
                rules: {
                    full_name: {
                        required: true
                    },
                    father_name: {
                        required: true
                    },
                    customer_birthdate: {
                        required: true
                    },
                    educational_qualification: {
                        required: true
                    },
                    customer_gender: {
                        required: true
                    },
                    marital_status: {
                        required: true
                    },
                    permanent_division: {
                        required: true
                    },
                    permanent_district: {
                        required: true
                    },
                    permanent_sub_district: {
                        required: true
                    },
                    male_household_member: {
                        required: true
                    },
                    female_household_member: {
                        required: true
                    },
                    boy_household_member: {
                        required: true
                    },
                    girl_household_member: {
                        required: true
                    },
                    left_port: {
                        required: true
                    },
                    preferred_country: {
                        required: true
                    },
                    final_destination: {
                        required: true
                    },
                    migration_type: {
                        required: true
                    },
                    visa_type: {
                        required: true
                    },
                    return_date: {
                        required: true
                    },
                    migration_occupation: {
                        required: true
                    },
                    pre_occupation: {
                        required: true
                    },
                    present_occupation: {
                        required: true
                    },
                    present_income: {
                        required: true
                    },
                    personal_savings: {
                        required: true
                    },
                    personal_debt: {
                        required: true
                    },
                    current_residence_ownership: {
                        required: true
                    },
                    current_residence_type: {
                        required: true
                    },
                    have_earner_skill: {
                        required: true
                    },
                    is_physically_challenged: {
                        required: true
                    },
                    having_chronic_disease: {
                        required: true
                    }
                },
                // Specify Validation Error Messages
                messages: {
                    educational_qualification: {
                        required: "Educational Qualification is required"
                    },
                    customer_gender: {
                        required: "Gender is required"
                    },
                    marital_status: {
                        required: "Marital is required"
                    },
                    permanent_division: {
                        required: "Division is required"
                    },
                    permanent_district: {
                        required: "District is required"
                    },
                    permanent_sub_district: {
                        required: "Upazila is required"
                    },
                    male_household_member: {
                        required: "Male Family Member is required"
                    },
                    female_household_member: {
                        required: "Female Family Member is required"
                    },
                    boy_household_member: {
                        required: "Boy Family Member is required"
                    },
                    girl_household_member: {
                        required: "Girl Family Member is required"
                    },
                    left_port: {
                        required: "Transit/Route of Migration/ Trafficking Member is required"
                    },
                    preferred_country: {
                        required: "Desired Destination is required"
                    },
                    final_destination: {
                        required: "Final destination is required"
                    },
                    migration_type: {
                        required: "Type of Channels is required"
                    },
                    visa_type: {
                        required: "Type of visa is required"
                    },
                    departure_date: {
                        required: "Date of Departure from Bangladesh is required"
                    },
                    return_date: {
                        required: "Date of Return to Bangladesh is required"
                    },
                    migration_occupation: {
                        required: "Occupation in overseas country is required"
                    },
                    pre_occupation: {
                        required: "Main occupation (before trafficking) is required"
                    },
                    present_occupation: {
                        required: "Main occupation (after return) is required"
                    },
                    present_income: {
                        required: "Monthly income of returnee after return(in BDT) is required"
                    },
                    personal_savings: {
                        required: "Savings (BDT) is required"
                    },
                    personal_debt: {
                        required: "Loan Amount (BDT) is required"
                    },
                    current_residence_ownership: {
                        required: "Ownership of House is required"
                    },
                    current_residence_type: {
                        required: "Type of house is required"
                    },
                    have_earner_skill: {
                        required: "IGA Skills is required"
                    },
                    is_physically_challenged: {
                        required: "Do you have any disability is required"
                    },
                    having_chronic_disease: {
                        required: "Any Chronic Disease is required"
                    }
                }
            }
            $("#myForm").multiStepForm({
                beforeSubmit: function (form, submit) {
                    console.log("called before submiting the form");
                    console.log(form);
                    console.log(submit);
                },
                validations: val,
            }).navigateTo(0);
        });
    });
</script>