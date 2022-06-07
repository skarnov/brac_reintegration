<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_country = $_GET['country'] ? $_GET['country'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_city_corporation = $_GET['city_corporation'] ? $_GET['city_corporation'] : null;
$filter_project_id = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_service_received = $_GET['service_received'] ? $_GET['service_received'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'pk_support_id' => 'dev_airport_land_supports.pk_support_id',
        'brac_info_id' => 'dev_airport_land_supports.brac_info_id',
        'project_short_name' => 'dev_projects.project_short_name',
        'full_name' => 'dev_airport_land_supports.full_name',
        'mobile_number' => 'dev_airport_land_supports.mobile_number',
        'destination_country' => 'dev_airport_land_supports.destination_country',
        'division' => 'dev_airport_land_supports.division',
        'district' => 'dev_airport_land_supports.district',
        'upazilla' => 'dev_airport_land_supports.upazilla',
        'permanent_city_corporation' => 'dev_airport_land_supports.permanent_city_corporation',
    ),
    'brac_info_id' => $filter_id,
    'gender' => $filter_gender,
    'name' => $filter_name,
    'country' => $filter_country,
    'division' => $filter_division,
    'district' => $filter_district,
    'upazilla' => $filter_sub_district,
    'city_corporation' => $filter_city_corporation,
    'project_id' => $filter_project_id,
    'service_received' => $filter_service_received,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_support_id',
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

$results = $this->get_airport_land_supports($args);
$pagination = pagination($results['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project_id, 'single' => true));

$countries = get_countries();
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
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$filterString = array();
if ($filter_id)
    $filterString[] = 'Info ID: ' . $filter_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_gender)
    $filterString[] = 'Gender: ' . $filter_gender;
if ($filter_country)
    $filterString[] = 'Country: ' . $filter_country;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_city_corporation)
    $filterString[] = 'City Corporation: ' . $filter_city_corporation;
if ($filter_project_id)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_service_received)
    $filterString[] = 'Service Received: ' . $filter_service_received;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_csv']) {
    $args['select_fields'] = array(
        'pk_support_id' => 'dev_airport_land_supports.pk_support_id',
        'project_short_name' => 'dev_projects.project_short_name',
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
    );
    unset($args['limit']);
    $results = $this->get_airport_land_supports($args);

    foreach ($results['data'] as $i => $value) {
        $results['data'][$i]['service_received'] = explode(',', $value['service_received']);
    }

    $data = $results['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Airport_and_Land_Supports' . time() . '.csv';

    /* Cycle Through All Files in The Directory */
    foreach (glob($target_dir . "*.csv") as $file) {

        /* If File is 24 Hours (86400 Seconds) Old Then Delete It */
        if (time() - filectime($file) > 86400) {
            unlink($file);
        }
    }

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Airport/Land Support', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        '#',
        'Project',
        'BRAC Info ID',
        'Return Route',
        'Port Name',
        'Entry Date',
        'Data Submission Date',
        'Arrival Date',
        'Person Type',
        'Name',
        'Gender',
        'Disability',
        'Passport Number',
        'Travel Pass',
        'Mobile Number',
        'Emergency Number',
        'Division',
        'District',
        'Upazila',
        'City Corporation',
        'Union',
        'Village',
        'Destination Country',
        'Food Service Received',
        'Information Service Received',
        'Medical Treatment Service Received',
        'Transport Support Service Received',
        'Accommodation Service Received',
        'Mobile Communication Service Received',
        'Health Kits Service Received',
        'Psychosocial Counseling Service Received',
        'Other Service Received'
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $entry_date = $user['entry_date'] && $user['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($user['entry_date'])) : 'N/A';
            $create_date = $user['create_date'] && $user['create_date'] != '0000-00-00' ? date('d-m-Y', strtotime($user['create_date'])) : 'N/A';
            $arrival_date = $user['arrival_date'] && $user['arrival_date'] != '0000-00-00' ? date('d-m-Y', strtotime($user['arrival_date'])) : 'N/A';

            $dataToSheet = array(
                ++$count
                , $user['project_short_name']
                , $user['brac_info_id']
                , ucfirst($user['return_route'])
                , $user['port_name']
                , $entry_date
                , $create_date
                , $arrival_date
                , ucwords(str_replace('_', ' ', $value['person_type']))
                , $user['full_name']
                , ucfirst($user['gender'])
                , ucfirst($user['is_disable'])
                , $user['passport_number']
                , $user['travel_pass']
                , $user['mobile_number'] . "\r"
                , $user['emergency_mobile'] . "\r"
                , ucfirst($user['division'])
                , ucfirst($user['district'])
                , ucfirst($user['upazilla'])
                , ucfirst($user['city_corporation'])
                , ucfirst($user['user_union'])
                , $user['village']
                , $user['destination_country']
                , $user['food']
                , $user['information']
                , $user['medical_treatment']
                , $user['transport_support']
                , $user['accommodation']
                , $user['mobile_communication']
                , $user['health_kits']
                , $user['psychosocial_counseling']
                , $user['other_service_received']);
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

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
    <h1>All Airport and Land Supports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_airport_land_support',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Airport and Land Support',
                'title' => 'New Airport and Land Support',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&name=' . $filter_name . '&id=' . $filter_id . '&gender=' . $filter_gender . '&country=' . $filter_country . '&service_received=' . $filter_service_received . '&city_corporation=' . $filter_city_corporation . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&project_id=' . $filter_project_id . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
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
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Gender</label>
    <div class="select2-primary">
        <select class="form-control" name="gender">
            <option value="">Select One</option>
            <?php foreach ($this->all_gender as $key => $value) : ?>
                <option value="<?php echo $key ?>" <?php echo ($key == $filter_gender) ? 'selected' : '' ?>><?php echo $value ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('id', 'id', array(
    'width' => 3, 'type' => 'text', 'label' => 'Info ID',
        ), $filter_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
$all_countries = getWorldCountry();
$result = array_combine($all_countries, $all_countries);
?>
<div class="form-group col-sm-3">
    <label>Service Received</label>
    <div class="select2-primary">
        <select class="form-control country" name="service_received" style="text-transform: capitalize">
            <option value="">Select One</option>
            <?php foreach ($this->service_received as $key => $value) : ?>
                <option value="<?php echo $key ?>" <?php echo ($key == $filter_service_received) ? 'selected' : '' ?>><?php echo $value['q'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Destination Country</label>
    <div class="select2-primary">
        <select class="form-control country" name="country" style="text-transform: capitalize">
            <option value="">Select One</option>
            <?php foreach ($countries as $country) : ?>
                <option value="<?php echo $country['nicename'] ?>" <?php echo ($country['nicename'] == $filter_country) ? 'selected' : '' ?>><?php echo $country['nicename'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control division" name="division" style="text-transform: capitalize">
            <option value="">Select One</option>
            <?php foreach ($divisions as $division) : ?>
                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo (strtolower($division['name']) == $filter_division) ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
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
    <label>City Corporation</label>
    <div class="select2-primary">
        <select class="form-control city_corporation" name="city_corporation" id="cityCorporationList" style="text-transform: capitalize">
            <?php if ($filter_city_corporation) : ?>
                <option value="<?php echo $filter_city_corporation ?>"><?php echo $filter_city_corporation ?></option>
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Airport and Land Supports') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Info ID</th>
                <th>Project</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Destination Country</th>
                <th>Present Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['brac_info_id'] ?></td>
                    <td><?php echo $value['project_short_name'] ?></td>
                    <td><?php echo $value['full_name'] ?></td>
                    <td><?php echo $value['mobile_number'] ?></td>
                    <td><?php echo $value['destination_country'] ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $value['division'] . ',<br><b>District - </b>' . $value['district'] . ',<br><b>City Corporation - </b>' . $value['city_corporation'] . ',<br><b>Upazila - </b>' . $value['upazilla'] ?></td>
                    <td style="text-transform: capitalize">
                        <?php if (has_permission('edit_airport_land_support')): ?>
                            <div class="btn-group">
                                <a href="<?php echo url('admin/immediate_support/manage_airport_land_support?action=add_edit_airport_land_support&edit=' . $value['pk_support_id']) ?>" class="btn btn-flat btn-labeled btn-sm btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            </div>                                
                        <?php endif ?>
                        <?php if (has_permission('delete_airport_land_support')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $value['pk_support_id']),
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
                        text: 'Delete Immediate Assistance after Arrival',
                        value: 'deleteAirportLandSupport'
                    }],
                callback: function (result) {
                    if (result == 'deleteAirportLandSupport') {
                        window.location.href = '?action=deleteAirportLandSupport&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>