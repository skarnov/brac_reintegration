<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'project_short_name' => 'dev_projects.project_short_name',
        'entry_date' => 'dev_reintegration_satisfaction_scale.entry_date',
        'customer_id' => 'dev_customers.customer_id',
        'satisfied_assistance' => 'dev_reintegration_satisfaction_scale.satisfied_assistance',
        'satisfied_counseling' => 'dev_reintegration_satisfaction_scale.satisfied_counseling',
        'satisfied_economic' => 'dev_reintegration_satisfaction_scale.satisfied_economic',
        'satisfied_social' => 'dev_reintegration_satisfaction_scale.satisfied_social',
        'satisfied_community' => 'dev_reintegration_satisfaction_scale.satisfied_community',
        'satisfied_reintegration' => 'dev_reintegration_satisfaction_scale.satisfied_reintegration',
        'total_score' => 'dev_reintegration_satisfaction_scale.total_score',
    ),
    'project' => $filter_project,
    'customerId' => $filter_id,
    'branch_id' => $branch,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_satisfaction_scale',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'entry_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$customerManagement = jack_obj('dev_customer_management');
$satisfactions = $customerManagement->get_satisfaction_scale($args);

$pagination = pagination($satisfactions['total'], $per_page_items, $start);

$branchManagement = jack_obj('dev_branch_management');
$all_branches = $branchManagement->get_branches();

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
$branchName = $branchManagement->get_branches(array('id' => $filter_branch, 'single' => true));

$filterString = array();
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_branch)
    $filterString[] = 'Branch: ' . $branchName['branch_name'];
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
    unset($args['limit']);
    $args['select_fields'] = array(
        'customer_id' => 'dev_customers.customer_id',
        'project_short_name' => 'dev_projects.project_short_name',
        '*' => 'dev_reintegration_satisfaction_scale.*',
    );

    $data = $customerManagement->get_satisfaction_scale($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Reintegration_Assistance_Satisfaction_Scale_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Reintegration Assistance Satisfaction Scale Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $scores = array('Score Definition');
    fputcsv($fh, $scores);

    $definition = array(
        '5 = Very Satisfied',
        '4 = Satisfied',
        '3 = Ok',
        '2 = Dissatisfied',
        '1 = Very Dissatisfied',
    );

    fputcsv($fh, $definition);

    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Project Name',
        'Entry Date',
        'Data Submission Date',
        'Beneficiary ID',
        'Repatriation',
        'Counseling Assistance',
        'Economic Assistance',
        'Social Assistance',
        'The Community Level',
        'Reintegration Support',
        'Total Score',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['project_short_name']
                , $value['entry_date'] && $value['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['entry_date'])) : ''
                , $value['create_date'] && $value['create_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['create_date'])) : ''
                , $value['customer_id']
                , $value['satisfied_assistance']
                , $value['satisfied_counseling']
                , $value['satisfied_economic']
                , $value['satisfied_social']
                , $value['satisfied_community']
                , $value['satisfied_reintegration']
                , $value['total_score']
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
            <h1>Reintegration Assistance Satisfaction Scale Report</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_csv=1&id=' . $filter_id . '&project_id=' . $filter_project . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Reintegration Assistance Satisfaction Scale Report',
                        'title' => 'Reintegration Assistance Satisfaction Scale Report',
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('id', 'id', array(
    'width' => 3, 'type' => 'text', 'label' => 'Participant ID',
        ), $filter_id);
?>
<?php if (!$branch_id): ?>
    <div class="form-group col-sm-3">
        <label>Branch</label>
        <div class="select2-primary">
            <select class="form-control" name="branch_id">
                <option value="">Select One</option>
                <?php foreach ($all_branches['data'] as $branch) : ?>
                    <option value="<?php echo $branch['pk_branch_id'] ?>" <?php if ($branch['pk_branch_id'] == $filter_branch) echo 'selected' ?> ><?php echo $branch['branch_name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <?php
endif;
?>
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
<div class="form-group col-sm-3">
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
        <?php echo searchResultText($satisfactions['total'], $start, $per_page_items, count($satisfactions['data']), 'Reintegration Assistance Satisfaction Scale Report') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project Name</th>-->
                <th>Entry Date</th>
                <th>Beneficiary ID</th>
                <th>Repatriation</th>
                <th>Counseling Assistance</th>
                <th>Economic Assistance</th>
                <th>Social Assistance</th>
                <th>The Community Level</th>
                <th>Reintegration Support</th>
                <th>Expectation</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($satisfactions['data'] as $i => $event) {
                ?>
                <tr>
<!--                    <td><?php echo $event['project_short_name'] ?></td>-->
                    <td><?php echo $event['entry_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($event['entry_date'])) ?></td>
                    <td><?php echo $event['customer_id'] ?></td>
                    <td><?php echo $event['satisfied_assistance'] ?></td>
                    <td><?php echo $event['satisfied_counseling'] ?></td>
                    <td><?php echo $event['satisfied_economic'] ?></td>
                    <td><?php echo $event['satisfied_social'] ?></td>
                    <td><?php echo $event['satisfied_community'] ?></td>
                    <td><?php echo $event['satisfied_reintegration'] ?></td>
                    <td><?php echo $event['satisfied_expectation'] ?></td>
                    <td><?php echo $event['total_score'] ?></td>
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