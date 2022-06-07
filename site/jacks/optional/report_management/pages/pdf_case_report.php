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
$filter_report = $_GET['report'] ? $_GET['report'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'id' => 'dev_immediate_supports.fk_customer_id',
        'project_short_name' => 'dev_projects.project_short_name',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'birth_reg_number' => 'dev_customers.birth_reg_number',
        'permanent_division' => 'dev_customers.permanent_division',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'customer_status' => 'dev_customers.customer_status',
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
        'col' => 'dev_immediate_supports.fk_customer_id',
        'order' => 'DESC'
    ),
);

$customers = jack_obj('dev_customer_management');
$cases = $customers->get_cases($args);
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
    <h1>Case Report - Customize PDF</h1>
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
<div class="form-group col-sm-6">
    <?php
    $filter_report = $filter_report ? $filter_report : array($filter_report);
    ?> 
    <label>Generate Report By Checking</label>
    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
        <div class="options_holder radio">
            <label><input class="px" type="checkbox" name="report[]" value="immediate_support" <?php
                if (in_array('immediate_support', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Immediate Support Services Received</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="reintegration_plan" <?php
                if (in_array('reintegration_plan', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Preferred Services and Reintegration Plan</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="psychosocial_support" <?php
                if (in_array('psychosocial_support', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Psychosocial Reintegration Support Services</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="reintegration_session" <?php
                if (in_array('reintegration_session', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Psychosocial Reintegration Session Activities</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="family_counseling" <?php
                if (in_array('family_counseling', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Family Counseling Session</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="session_completion" <?php
                if (in_array('session_completion', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Session Completion Status</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="follow_up" <?php
                if (in_array('follow_up', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Psychosocial Reintegration (Follow-up)</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="in_kind" <?php
                if (in_array('in_kind', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Economic In-kind</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="training" <?php
                if (in_array('training', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Training</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="financial_literacy" <?php
                if (in_array('financial_literacy', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Financial Literacy & Remittance Management Training</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="economic_referrals" <?php
                if (in_array('economic_referrals', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Referral and Linkage Support</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="economic_referrals_received" <?php
                if (in_array('economic_referrals_received', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Referral Received and Linkage Support</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="social_reintegration" <?php
                if (in_array('social_reintegration', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Social Reintegration Support</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="medical_support" <?php
                if (in_array('medical_support', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Medical Support</span></label>
            <label><input class="px" type="checkbox" name="report[]" value="review" <?php
                if (in_array('review', $filter_report)) {
                    echo 'checked';
                }
                ?>><span class="lbl">Review and Follow-Up</span></label>
        </div>
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
        <?php echo searchResultText($cases['total'], $start, $per_page_items, count($cases['data']), 'cases') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Participant ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Birth ID</th>
                <th>Present Address</th>
                <th>Status</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cases['data'] as $i => $case) {
                ?>
                <tr>
                    <td><?php echo $case['customer_id']; ?></td>
                    <td><?php echo $case['full_name']; ?></td>
                    <td><?php echo $case['customer_mobile']; ?></td>
                    <td><?php echo $case['birth_reg_number']; ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $case['permanent_division'] . ',<br><b>District - </b>' . $case['permanent_district'] . ',<br><b>Upazila - </b>' . $case['permanent_sub_district'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $case['customer_status']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_case')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'download_pdf', 'id' => $case['fk_customer_id'])),
                                    'action' => 'download',
                                    'icon' => 'icon_download',
                                    'text' => 'Download',
                                    'title' => 'Download Case',
                                ));
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
        });
    });
</script>