<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_country = $_GET['country'] ? $_GET['country'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_municipality = $_GET['municipality'] ? $_GET['municipality'] : null;
$filter_city_corporation = $_GET['city_corporation'] ? $_GET['city_corporation'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_return_start_date = $_GET['return_start_date'] ? $_GET['return_start_date'] : null;
$filter_return_end_date = $_GET['return_end_date'] ? $_GET['return_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'select_fields' => array(
        'pk_returnee_id' => 'dev_returnees.pk_returnee_id',
        'returnee_id' => 'dev_returnees.returnee_id',
        'full_name' => 'dev_returnees.full_name',
        'returnee_gender' => 'dev_returnees.returnee_gender',
        'mobile_number' => 'dev_returnees.mobile_number',
        'passport_number' => 'dev_returnees.passport_number',
        'return_date' => 'dev_returnees.return_date',
        'destination_country' => 'dev_returnees.destination_country',
        'permanent_division' => 'dev_returnees.permanent_division',
        'permanent_district' => 'dev_returnees.permanent_district',
        'permanent_sub_district' => 'dev_returnees.permanent_sub_district',
        'municipality' => 'dev_returnees.permanent_municipality',
        'city_corporation' => 'dev_returnees.permanent_city_corporation',
        'permanent_union' => 'dev_returnees.permanent_union',
        'fk_branch_id' => 'dev_returnees.fk_branch_id',
    ),
    'returnee_id' => $filter_returnee_id,
    'name' => $filter_name,
    'gender' => $filter_gender,
    'project' => $filter_project,
    'country' => $filter_country,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'municipality' => $filter_municipality,
    'city_corporation' => $filter_city_corporation,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_returnee_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'collection_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

if ($filter_return_start_date && $filter_return_end_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'return_date' => array(
            'left' => date_to_db($filter_return_start_date),
            'right' => date_to_db($filter_return_end_date),
        ),
    );
}

$returnees = $this->get_returnees($args);
$pagination = pagination($returnees['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

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

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_returnee_id)
    $filterString[] = 'ID: ' . $filter_returnee_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_country)
    $filterString[] = 'Country: ' . $filter_country;
if ($filter_gender)
    $filterString[] = 'Gender: ' . $filter_gender;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_municipality)
    $filterString[] = 'Municipality: ' . $filter_municipality;
if ($filter_city_corporation)
    $filterString[] = 'City Corporation: ' . $filter_city_corporation;
if ($filter_union)
    $filterString[] = 'Union: ' . $filter_union;
if ($filter_return_start_date)
    $filterString[] = 'Return Date (Start): ' . $filter_return_start_date;
if ($filter_return_end_date)
    $filterString[] = 'Return Date: (End)' . $filter_return_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $args = array(
        'select_fields' => array(
            'collection_date' => 'dev_returnees.collection_date',
            'create_date' => 'dev_returnees.create_date',
            'returnee_id' => 'dev_returnees.returnee_id',
            'branch_name' => 'dev_branches.branch_name',
            'project_short_name' => 'dev_projects.project_short_name',
            'full_name' => 'dev_returnees.full_name',
            'father_name' => 'dev_returnees.father_name',
            'mother_name' => 'dev_returnees.mother_name',
            'marital_status' => 'dev_returnees.marital_status',
            'returnee_gender' => 'dev_returnees.returnee_gender',
            'educational_qualification' => 'dev_returnees.educational_qualification',
            'mobile_number' => 'dev_returnees.mobile_number',
            'emergency_mobile' => 'dev_returnees.emergency_mobile',
            'nid_number' => 'dev_returnees.nid_number',
            'birth_reg_number' => 'dev_returnees.birth_reg_number',
            'passport_number' => 'dev_returnees.passport_number',
            'permanent_division' => 'dev_returnees.permanent_division',
            'permanent_district' => 'dev_returnees.permanent_district',
            'permanent_sub_district' => 'dev_returnees.permanent_sub_district',
            'municipality' => 'dev_returnees.permanent_municipality',
            'city_corporation' => 'dev_returnees.permanent_city_corporation',
            'permanent_union' => 'dev_returnees.permanent_union',
            'brac_info_id' => 'dev_returnees.brac_info_id',
            'person_type' => 'dev_returnees.person_type',
            'departure_date' => 'dev_returnees.departure_date',
            'return_date' => 'dev_returnees.return_date',
            'destination_country' => 'dev_returnees.destination_country',
            'legal_document' => 'dev_returnees.legal_document',
            'other_legal_document' => 'dev_returnees.other_legal_document',
            'remigrate_intention' => 'dev_returnees.remigrate_intention',
            'destination_country_profession' => 'dev_returnees.destination_country_profession',
            'profile_selection' => 'dev_returnees.profile_selection',
            'remarks' => 'dev_returnees.remarks',
        ),
        'returnee_id' => $filter_returnee_id,
        'name' => $filter_name,
        'gender' => $filter_gender,
        'project' => $filter_project,
        'country' => $filter_country,
        'division' => $filter_division,
        'district' => $filter_district,
        'sub_district' => $filter_sub_district,
        'municipality' => $filter_municipality,
        'city_corporation' => $filter_city_corporation,
        'union' => $filter_union,
        'report' => true,
    );

    $data = $this->get_returnees($args);
    $data = $data['data'];

    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename=Returnee-' . time() . '.csv');
    $fp = fopen('php://output', 'w');

    $report_title = array('', 'Returnee Report', '');
    fputcsv($fp, $report_title);

    $blank_row = array('');
    fputcsv($fp, $blank_row);

    $headers = array(
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Returnee ID',
        'Branch Name',
        'Project Name',
        'Full Name',
        "Father's Name",
        "Mother's Name",
        'Marital Status',
        'Gender',
        'Educational Qualification',
        'Mobile Number',
        'Emergency Mobile Number',
        'NID Number',
        'Birth Registration Number',
        'Passport',
        'Division',
        'District',
        'Upazila',
        'Municipality',
        'City Corporation',
        'Union',
        'BRAC Info ID',
        'Type of person',
        'Departure Date',
        'Return Date',
        'Country of Destination',
        'Legal Document',
        'Intention to Remigration',
        'Occupation in overseas country',
        'Selected for profiling',
        'Remarks',
    );

    fputcsv($fp, $headers);

    foreach ($data as $value) {
        if ($value['educational_qualification'] == 'illiterate'):
            $educational_qualification = 'Illiterate';
        elseif ($value['educational_qualification'] == 'sign'):
            $educational_qualification = 'Can Sign only';
        elseif ($value['educational_qualification'] == 'psc'):
            $educational_qualification = 'Primary education (Passed Grade 5)';
        elseif ($value['educational_qualification'] == 'not_psc'):
            $educational_qualification = 'Did not complete primary education';
        elseif ($value['educational_qualification'] == 'jsc'):
            $educational_qualification = 'Completed JSC (Passed Grade 8) or equivalent';
        elseif ($value['educational_qualification'] == 'ssc'):
            $educational_qualification = 'Completed School Secondary Certificate or equivalent';
        elseif ($value['educational_qualification'] == 'hsc'):
            $educational_qualification = 'Higher Secondary Certificate/Diploma/ equivalent';
        elseif ($value['educational_qualification'] == 'bachelor'):
            $educational_qualification = 'Bachelor’s degree or equivalent';
        elseif ($value['educational_qualification'] == 'master'):
            $educational_qualification = 'Masters or Equivalent';
        elseif ($value['educational_qualification'] == 'professional_education'):
            $educational_qualification = 'Completed Professional education';
        elseif ($value['educational_qualification'] == 'general_education'):
            $educational_qualification = 'Completed general Education';
        else:
            $educational_qualification = $value['educational_qualification'];
        endif;

        $collection_date = $value['collection_date'] && $value['collection_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['collection_date'])) : 'N/A';
        $departure_date = $value['departure_date'] && $value['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['departure_date'])) : 'N/A';
        $return_date = $value['return_date'] && $value['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['return_date'])) : 'N/A';

        $dataToSheet = array(
            ++$count
            , $collection_date
            , $value['create_date']
            , $value['returnee_id']
            , $value['branch_name']
            , $value['project_short_name']
            , $value['full_name']
            , $value['father_name']
            , $value['mother_name']
            , ucfirst($value['marital_status'])
            , ucfirst($value['returnee_gender'])
            , $educational_qualification
            , $value['mobile_number'] . "\r"
            , $value['emergency_mobile'] . "\r"
            , $value['nid_number'] . "\r"
            , $value['birth_reg_number'] . "\r"
            , $value['passport_number'] . "\r"
            , ucfirst($value['permanent_division'])
            , ucfirst($value['permanent_district'])
            , ucfirst($value['permanent_sub_district'])
            , ucfirst($value['permanent_municipality'])
            , ucfirst($value['permanent_city_corporation'])
            , ucfirst($value['permanent_union'])
            , $value['brac_info_id']
            , ucwords(str_replace('_', ' ', $value['person_type']))
            , $departure_date . "\r"
            , $return_date . "\r"
            , $value['destination_country']
            , $value['legal_document'] . ' ' . $value['other_legal_document']
            , ucfirst($value['remigrate_intention'])
            , $value['destination_country_profession']
            , ucfirst($value['profile_selection'])
            , $value['remarks']
        );

        fputcsv($fp, $dataToSheet);
    }

    ob_flush();
    fclose($fp);
    die();
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Returnees</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_returnee',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Returnee',
                'title' => 'New Returnee',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&returnee_id=' . $filter_returnee_id . '&name=' . $filter_name . '&gender=' . $filter_gender . '&country=' . $filter_country . '&project=' . $filter_project . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&municipality=' . $filter_municipality . '&city_corporation=' . $filter_city_corporation . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date . '&return_start_date=' . $filter_return_start_date . '&return_end_date=' . $filter_return_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Returnees',
                'title' => 'Download Returnees',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Returnee Name',
        ), $filter_name);
?>
<div class="form-group col-sm-2">
    <label>Gender</label>
    <div class="select2-primary">
        <select class="form-control" name="gender">
            <option value="">Select One</option>
            <option value="male" <?php echo ('male' == $filter_gender) ? 'selected' : '' ?>>Men (>=18)</option>
            <option value="female" <?php echo ('female' == $filter_gender) ? 'selected' : '' ?>>Women (>=18)</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Entry Date (Start)</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-2">
    <label>Entry Date (End)</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="entry_end_date" value="<?php echo $filter_entry_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
</div>
<div class="form-group col-sm-2">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Country of Destination</label>
    <div class="select2-primary">
        <select class="form-control country" name="country" style="text-transform: capitalize">
            <?php if ($filter_country) : ?>
                <option value="<?php echo $filter_country ?>"><?php echo $filter_country ?></option>
            <?php else: ?>
                <option value="">Select One</option>
            <?php endif ?>
            <?php foreach ($countries as $country) : ?>
                <option id="<?php echo $country['nicename'] ?>" value="<?php echo $country['nicename'] ?>"><?php echo $country['nicename'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
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
<div class="form-group col-sm-2">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
            <?php if ($filter_district) : ?>
                <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Upazila</label>
    <div class="select2-primary">
        <select class="form-control subdistrict" name="sub_district" id="subdistrictList" style="text-transform: capitalize">
            <?php if ($filter_sub_district) : ?>
                <option value="<?php echo $filter_sub_district ?>"><?php echo $filter_sub_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Municipality</label>
    <div class="select2-primary">
        <select class="form-control municipality" name="municipality" id="municipalityList" style="text-transform: capitalize">
            <?php if ($filter_municipality) : ?>
                <option value="<?php echo $filter_municipality ?>"><?php echo $filter_municipality ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>City Corporation</label>
    <div class="select2-primary">
        <select class="form-control city_corporation" name="city_corporation" id="cityCorporationList" style="text-transform: capitalize">
            <?php if ($filter_city_corporation) : ?>
                <option value="<?php echo $filter_city_corporation ?>"><?php echo $filter_city_corporation ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Union</label>
    <div class="select2-primary">
        <select class="form-control union" name="union" id="unionList" style="text-transform: capitalize">
            <?php if ($filter_union) : ?>
                <option value="<?php echo $filter_union ?>"><?php echo $filter_union ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Return Date (Start)</label>
    <div class="input-group">
        <input id="startReturnDate" type="text" class="form-control" name="return_start_date" value="<?php echo $filter_return_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startReturnDate');
        });
    </script>
</div>
<div class="form-group col-sm-2">
    <label>Return Date (End)</label>
    <div class="input-group">
        <input id="endReturnDate" type="text" class="form-control" name="return_end_date" value="<?php echo $filter_return_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endReturnDate');
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
        <?php echo searchResultText($returnees['total'], $start, $per_page_items, count($returnees['data']), 'returnees') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Passport Number</th>
                <th>Return Date</th>
                <th>Present Address</th>
                <th>Destination Country</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($returnees['data'] as $i => $returnee) {
                ?>
                <tr>
                    <td><?php echo $returnee['returnee_id']; ?></td>
                    <td><?php echo $returnee['full_name']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $returnee['returnee_gender']; ?></td>
                    <td><?php echo $returnee['mobile_number']; ?></td>
                    <td><?php echo $returnee['passport_number']; ?></td>
                    <td><?php echo $returnee['return_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($returnee['return_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $returnee['permanent_division'] . ',<br><b>District - </b>' . $returnee['permanent_district'] . ',<br><b>Upazila - </b>' . $returnee['permanent_sub_district'] . ',<br><b>Municipality - </b>' . $returnee['permanent_municipality'] . ',<br><b>City Corporation - </b>' . $returnee['permanent_city_corporation'] . ',<br><b>Union - </b>' . $returnee['permanent_union'] ?></td>
                    <td><?php echo $returnee['destination_country'] ?></td>
                    <td>
                        <?php if (has_permission('edit_returnee')): ?>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo url('admin/dev_customer_management/manage_returnees?action=add_edit_returnee&edit=' . $returnee['pk_returnee_id']) ?>" class="btn btn-flat btn-labeled btn-sm btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            </div>                                
                        <?php endif ?>
                        <?php if (has_permission('delete_returnee')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $returnee['pk_returnee_id']),
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
                        text: 'Delete Returnee Profile Information',
                        value: 'deleteProfile'
                    }],
                callback: function (result) {
                    if (result == 'deleteProfile') {
                        window.location.href = '?action=deleteReturnee&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>