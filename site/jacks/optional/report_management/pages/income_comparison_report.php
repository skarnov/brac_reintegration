<?php
$customerManager = jack_obj('dev_customer_management');

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_birth = $_GET['birth'] ? $_GET['birth'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'income_comparison' => true,
    'select_fields' => array(
        'pk_customer_id' => 'dev_customers.pk_customer_id',
        'project_short_name' => 'dev_projects.project_short_name',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'present_income' => 'dev_economic_profile.present_income',
        'create_date' => 'dev_economic_profile.create_date',
        'entry_date' => 'dev_followups.entry_date',
        'monthly_average_income' => 'dev_followups.monthly_average_income',
    ),
    'project' => $filter_project,
    'customer_id' => $filter_customer_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'birth' => $filter_birth,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_followups.fk_customer_id',
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

$case_review = $customerManager->get_case_review($args);
$pagination = pagination($case_review['total'], $per_page_items, $start);

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
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_customer_id)
    $filterString[] = 'Beneficiary ID: ' . $filter_customer_id;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_birth)
    $filterString[] = 'Birth ID: ' . $filter_birth;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $args = array(
        'income_comparison' => true,
        'select_fields' => array(
            'project_short_name' => 'dev_projects.project_short_name',
            'pk_customer_id' => 'dev_customers.pk_customer_id',
            'customer_id' => 'dev_customers.customer_id',
            'full_name' => 'dev_customers.full_name',
            'present_income' => 'dev_economic_profile.present_income',
            'create_date' => 'dev_economic_profile.create_date',
            'entry_date' => 'dev_followups.entry_date',
            'followup_create_date' => 'dev_followups.create_date AS followup_create_date',
            'monthly_average_income' => 'dev_followups.monthly_average_income',
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

    $data = $customerManager->get_case_review($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Income_Comparison-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Income Comparison Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Project Name',
        'Entry Date',
        'Data Submission Date',
        'Beneficiary ID',
        'Full Name',
        'Initialization Date',
        'Previous Income (BDT)',
        'Current Income Entry Date',
        'Current Income (BDT)',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $create_date = $value['create_date'] == '0000-00-00' ? 'N/A' : $value['create_date'];
            $entry_date = $value['entry_date'] == '0000-00-00' ? 'N/A' : $value['entry_date'];

            $dataToSheet = array(
                ++$count
                , $value['project_short_name']
                , $entry_date
                , $value['followup_create_date'] == '0000-00-00' ? 'N/A' : $value['followup_create_date']
                , $value['customer_id']
                , $value['full_name']
                , $create_date
                , $value['present_income']
                , $entry_date
                , $value['monthly_average_income']
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
    <h1>Income Comparison Report</h1>  
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&customer_id=' . $filter_customer_id . '&project_id=' . $filter_project . '&name=' . $filter_name . '&nid=' . $filter_nid . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
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
<?php
echo formProcessor::form_elements('customer_id', 'customer_id', array(
    'width' => 2, 'type' => 'text', 'label' => 'Participant ID',
        ), $filter_customer_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Participant Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 3, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('birth', 'birth', array(
    'width' => 3, 'type' => 'text', 'label' => 'Birth ID',
        ), $filter_birth);
?>
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
        <?php echo searchResultText($case_review['total'], $start, $per_page_items, count($case_review['data']), 'income review') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project Name</th>-->
                <th>Beneficiary ID</th>
                <th>Full Name</th>
                <th>Initialization Date</th>
                <th>Previous Income</th>
                <th>Current Income Entry Date</th>
                <th>Current Income</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($case_review['data'] as $i => $case) {
                ?>
                <tr>
    <!--                    <td><?php echo $case['project_short_name'] ?></td>-->
                    <td><?php echo $case['customer_id'] ?></td>
                    <td><?php echo $case['full_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($case['create_date'])) ?></td>
                    <td><?php echo $case['present_income'] . ' BDT' ?></td>
                    <td><?php echo date('d-m-Y', strtotime($case['entry_date'])) ?></td>
                    <td><?php echo $case['monthly_average_income'] . ' BDT' ?></td>
                    <td class="tar action_column">
                        <div class="btn-group btn-group-sm">
                            <?php
                            echo linkButtonGenerator(array(
                                'href' => build_url(array('action' => 'pdf_download_income_comparison_report', 'id' => $case['pk_customer_id'])),
                                'action' => 'download',
                                'icon' => 'icon_download',
                                'text' => 'Income Comparison',
                                'title' => 'Download Income Comparison Report',
                            ));
                            ?>
                        </div>
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
    });
</script>