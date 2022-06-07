<?php
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
    'project' => $filter_project,
    'customer_id' => $filter_customer_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'birth' => $filter_birth,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'branch_id' => $branch_id,
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$customers = jack_obj('dev_customer_management');

$immediate_supports = $customers->count_immediate_supports($args);
$reintegration_plan = $customers->count_reintegration_plan($args);
$psycho_supports = $customers->count_psycho_supports($args);
$psycho_sessions = $customers->count_psycho_sessions($args);
$family_counseling = $customers->count_family_counseling($args);
$psycho_completions = $customers->count_psycho_completions($args);
$psycho_followups = $customers->count_psycho_followups($args);
$economic_inkinds = $customers->count_economic_inkind($args);
$economic_trainings = $customers->count_economic_training($args);
$financial_literacy = $customers->count_financial_literacy($args);
$economic_referrals = $customers->count_economic_referrals($args);
$economic_referrals_received = $customers->count_economic_referral_received($args);
$social_supports = $customers->count_social_supports($args);
$medical_supports = $customers->count_medical_supports($args);
$followups = $customers->count_followups($args);

$pagination = pagination($cases['total'], $per_page_items, $start);

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
if ($filter_id)
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

doAction('render_start');
?>
<div class="page-header">
    <h1>Case Report - Statistical</h1>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('customer_id', 'customer_id', array(
    'width' => 3, 'type' => 'text', 'label' => 'Participant ID',
        ), $filter_customer_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Participant Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 3, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('birth', 'birth', array(
    'width' => 3, 'type' => 'text', 'label' => 'Birth ID',
        ), $filter_birth);
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
    <table class="table table-bordered table-condensed" style="font-size: 1.5rem;">
        <thead>            
            <tr>
                <th>Immediate Support Services Received</th>
                <th><?php echo $immediate_supports['immediate_supports'] ?></th>
            </tr>
            <tr>
                <th>Preferred Services and Reintegration Plan</th>
                <th><?php echo $reintegration_plan['reintegration_plan'] ?></th>
            </tr>
            <tr>
                <th>Psychosocial Reintegration Support Services</th>
                <th><?php echo $psycho_supports['psycho_supports'] ?></th>
            </tr>
            <tr>
                <th>Psychosocial Reintegration Session Activities</th>
                <th><?php echo $psycho_sessions['psycho_sessions'] ?></th>
            </tr>
            <tr>
                <th>Family Counseling Session</th>
                <th><?php echo $family_counseling['family_counseling'] ?></th>
            </tr>

            <tr>
                <th>Session Completion Status</th>
                <th><?php echo $psycho_completions['psycho_completions'] ?></th>
            </tr>
            <tr>
                <th>Psychosocial Reintegration (Follow-up)</th>
                <th><?php echo $psycho_followups['psycho_followups'] ?></th>
            </tr>
            <tr>
                <th>In Kind Support</th>
                <th><?php echo $economic_inkinds['economic_inkind'] ?></th>
            </tr>
            <tr>
                <th>Training</th>
                <th><?php echo $economic_trainings['economic_training'] ?></th>
            </tr>
            <tr>
                <th>Financial Literacy & Remittance Management Training</th>
                <th><?php echo $financial_literacy['financial_literacy'] ?></th>
            </tr>
            <tr>
                <th>Referral and Linkage Support</th>
                <th><?php echo $economic_referrals['economic_referrals'] ?></th>
            </tr>
            <tr>
                <th>Referral Received and Linkage Support</th>
                <th><?php echo $economic_referrals_received['economic_referral_received'] ?></th>
            </tr>
            <tr>
                <th>Social Reintegration Support</th>
                <th><?php echo $social_supports['social_supports'] ?></th>
            </tr>
            <tr>
                <th> Medical Support</th>
                <th><?php echo $medical_supports['medical_supports'] ?></th>
            </tr>
            <tr>
                <th>Review and Follow-Up</th>
                <th><?php echo $followups['followups'] ?></th>
            </tr>
        </thead>
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