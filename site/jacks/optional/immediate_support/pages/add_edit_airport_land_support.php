<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_airport_land_support', 'edit_airport_land_support')) {
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
            'fk_project_id' => 'dev_airport_land_supports.fk_project_id',
            'brac_info_id' => 'dev_airport_land_supports.brac_info_id',
            'return_route' => 'dev_airport_land_supports.return_route',
            'port_name' => 'dev_airport_land_supports.port_name',
            'arrival_date' => 'dev_airport_land_supports.arrival_date',
            'person_type' => 'dev_airport_land_supports.person_type',
            'full_name' => 'dev_airport_land_supports.full_name',
            'gender' => 'dev_airport_land_supports.gender',
            'is_disable' => 'dev_airport_land_supports.is_disable',
            'travel_pass' => 'dev_airport_land_supports.travel_pass',
            'passport_number' => 'dev_airport_land_supports.passport_number',
            'mobile_number' => 'dev_airport_land_supports.mobile_number',
            'emergency_mobile' => 'dev_airport_land_supports.emergency_mobile',
            'division' => 'dev_airport_land_supports.division',
            'district' => 'dev_airport_land_supports.district',
            'upazilla' => 'dev_airport_land_supports.upazilla',
            'permanent_police_station' => 'dev_airport_land_supports.permanent_police_station',
            'permanent_post_office' => 'dev_airport_land_supports.permanent_post_office',
            'permanent_municipality' => 'dev_airport_land_supports.permanent_municipality',
            'permanent_city_corporation' => 'dev_airport_land_supports.permanent_city_corporation',
            'user_union' => 'dev_airport_land_supports.user_union',
            'village' => 'dev_airport_land_supports.village',
            'destination_country' => 'dev_airport_land_supports.destination_country',
            'food' => 'dev_airport_land_supports.food',
            'information' => 'dev_airport_land_supports.information',
            'medical_treatment' => 'dev_airport_land_supports.medical_treatment',
            'transport_support' => 'dev_airport_land_supports.transport_support',
            'accommodation' => 'dev_airport_land_supports.accommodation',
            'mobile_communication' => 'dev_airport_land_supports.mobile_communication',
            'health_kitss' => 'dev_airport_land_supports.health_kits',
            'psychosocial_counseling' => 'dev_airport_land_supports.psychosocial_counseling',
            'other_service_received' => 'dev_airport_land_supports.other_service_received',
            'entry_date' => 'dev_airport_land_supports.entry_date',
            'create_date' => 'dev_airport_land_supports.create_date',
        ),
        'id' => $edit,
        'single' => true
    );
    $pre_data = $this->get_airport_land_supports($args);

    if (!$pre_data) {
        add_notification('Invalid ID, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'brac_info_id' => 'BRAC Info ID',
            'return_route' => 'Return Route',
            'arrival_date' => 'Arrival Date',
            'person_type' => 'Person Type',
            'full_name' => 'Full Name',
            'destination_country' => 'Destination Country',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    $ret = $this->add_edit_airport_land_support($data);

    if ($ret['success']) {
        $msg = "Information has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/immediate_support/manage_airport_land_support?action=add_edit_airport_land_support&edit=' . $edit));
        } else {
            header('location: ' . url('admin/immediate_support/manage_airport_land_support'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Airport and Land Support</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Airport and Land Supports',
                'title' => 'Manage Airport and Land Supports',
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
                                    <select class="form-control" name="project_id">
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
                                <label>Return Route (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="return_route" value="land" <?php echo $pre_data && $pre_data['return_route'] == 'land' ? 'checked' : '' ?>><span class="lbl">Land</span></label>
                                        <label><input class="px" type="radio" name="return_route" value="air" <?php echo $pre_data && $pre_data['return_route'] == 'air' ? 'checked' : '' ?>><span class="lbl">Air</span></label>
                                        <label><input class="px" type="radio" name="return_route" value="sea" <?php echo $pre_data && $pre_data['return_route'] == 'sea' ? 'checked' : '' ?>><span class="lbl">Sea</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Port Name</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="port_name">
                                        <option value="">Select One</option>
                                        <?php foreach ($this->all_ports as $value) : ?>
                                            <option value="<?php echo $value ?>" <?php echo ($value == $pre_data['port_name']) ? 'selected' : '' ?>><?php echo $value ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Arrival Date (*)</label>
                                <div class="input-group">
                                    <input id="arrival_date" required type="text" class="form-control" name="arrival_date" value="<?php echo $pre_data['arrival_date'] && $pre_data['arrival_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['arrival_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('arrival_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Person Type (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="person_type" value="trafficked_survivor" <?php echo $pre_data && $pre_data['person_type'] == 'trafficked_survivor' ? 'checked' : '' ?>><span class="lbl">Trafficked Survivor</span></label>
                                        <label><input class="px" type="radio" name="person_type" value="returnee_migrant_worker" <?php echo $pre_data && $pre_data['person_type'] == 'returnee_migrant_worker' ? 'checked' : '' ?>><span class="lbl">Returnee Migrant Worker</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="boy" <?php echo $pre_data && $pre_data['gender'] == 'boy' ? 'checked' : '' ?>><span class="lbl">Men (Boy <18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="girl" <?php echo $pre_data && $pre_data['gender'] == 'girl' ? 'checked' : '' ?>><span class="lbl">Women (Girl <18)</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Disability</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="is_disable" value="yes" <?php echo $pre_data && $pre_data['is_disable'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="is_disable" value="no" <?php echo $pre_data && $pre_data['is_disable'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
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
                                <label>Passport Number</label>
                                <input class="form-control" type="text" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Travel Pass</label>
                                <input class="form-control" type="text" name="travel_pass" value="<?php echo $pre_data['travel_pass'] ? $pre_data['travel_pass'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name (*)</label>
                                <input class="form-control" type="text" required name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="number" name="mobile_number" value="<?php echo $pre_data['mobile_number'] ? $pre_data['mobile_number'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Emergency Number</label>
                                <input class="form-control" type="number" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>">
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
                        <div class="col-sm-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Service Received</legend>
                                <?php foreach ($this->service_received as $db_column => $each_qa) : ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $each_qa['q'] ?></label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    foreach ($each_qa['a'] as $a_value) :
                                                        $selected = $pre_data && $pre_data[$db_column] == $a_value ? 'checked' : '';
                                                        ?>
                                                        <label><input class="px" type="radio" name="<?php echo $db_column ?>" value="<?php echo $a_value ?>" <?php echo $selected ?>><span class="lbl"><?php echo $a_value ?></span></label>
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Other Service Received? (If Any)</label>
                                        <input class="form-control" type="text" name="other_service_received" value="<?php echo $pre_data['other_service_received'] ? $pre_data['other_service_received'] : ''; ?>">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/immediate_support/manage_airport_land_support') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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