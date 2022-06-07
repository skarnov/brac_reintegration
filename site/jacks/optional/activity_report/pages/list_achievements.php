<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_year = $_GET['year'] ? $_GET['year'] : null;
$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_project = $_GET['project'] ? $_GET['project'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'id' => 'dev_targets.pk_target_id',
        'branch' => 'dev_targets.fk_branch_id',
        'project' => 'dev_targets.fk_project_id',
        'project_name' => 'dev_projects.project_short_name',
        'branch_name' => 'dev_branches.branch_name',
        'district' => 'dev_targets.branch_district',
        'sub_district' => 'dev_targets.branch_sub_district',
        'year' => 'dev_targets.year',
        'month' => 'dev_targets.month',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'dev_targets.activity_target',
        'activity_achievement' => 'dev_targets.activity_achievement',
    ),
    'year' => $filter_year,
    'month' => $filter_month,
    'branch' => $filter_branch,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'project' => $filter_project,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_target_id',
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

$results = $this->get_targets($args);
if ($filter_year || $filter_month || $filter_branch || $filter_division || $filter_district || $filter_sub_district || $filter_project || $filter_entry_start_date || $filter_entry_end_date):
    $args = array(
        'select_fields' => array(
            'total_target' => 'SUM(dev_targets.activity_target) AS total_target',
            'total_achievement' => 'SUM(dev_targets.activity_achievement) AS total_achievement',
        ),
        'year' => $filter_year,
        'month' => $filter_month,
        'branch' => $filter_branch,
        'division' => $filter_division,
        'district' => $filter_district,
        'sub_district' => $filter_sub_district,
        'project' => $filter_project,
        'single' => TRUE,
    );

    if ($filter_entry_start_date && $filter_entry_start_date) {
        $args['BETWEEN_INCLUSIVE'] = array(
            'create_date' => array(
                'left' => date_to_db($filter_entry_start_date),
                'right' => date_to_db($filter_entry_end_date),
            ),
        );
    }
    $total_stat = $this->get_achievements($args);
endif;

$pagination = pagination($results['total'], $per_page_items, $start);

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

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projectName = $projects->get_projects(array('id' => $filter_project_id, 'single' => true));
$branchName = $branches->get_branches(array('id' => $filter_branch, 'single' => true));

$filterString = array();
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_year)
    $filterString[] = 'Year: ' . $filter_year;
if ($filter_month)
    $filterString[] = 'Month: ' . $filter_month;
if ($filter_branch)
    $filterString[] = 'Branch: ' . $branchName['branch_name'];
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

$all_months = get_months();

doAction('render_start');
?>
<div class="page-header">
    <h1>All Achievements</h1>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch">
            <option value="">Select One</option>
            <?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $filter_branch) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
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
    <label>Year</label>
    <div class="select2-primary">
        <select class="form-control" name="year">
            <option value="">Select One</option>
            <?php foreach (get_years() as $year) : ?>
                <option value="<?php echo $year ?>" <?php echo ($year == $filter_year) ? 'selected' : '' ?>><?php echo $year ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Month</label>
    <div class="select2-primary">
        <select class="form-control" name="month">
            <option value="">Select One</option>
            <?php foreach ($all_months as $i => $month) :
                ?>
                <option value="<?php echo $i ?>" <?php echo ($i == $filter_month) ? 'selected' : '' ?>><?php echo $month ?></option>
            <?php endforeach ?>
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Achievements') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project</th>
                <th>Branch</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Year</th>
                <th>Month</th>
                <th>Activity Name</th>
                <th>Target</th>
                <th>Achievement</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_stat): ?>
                <tr style="font-size: 2em;font-weight: bolder;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $total_stat['total_target']; ?></td>
                    <td><?php echo $total_stat['total_achievement']; ?></td>
                </tr>
                <tr style="border: 2px solid black;">
                    <td></td>
                </tr>
            <?php endif ?>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['project_short_name']; ?></td>
                    <td><?php echo $value['branch_name']; ?></td>
                    <td><?php echo $value['branch_district']; ?></td>
                    <td><?php echo $value['branch_sub_district']; ?></td>
                    <td><?php echo $value['year']; ?></td>
                    <td><?php echo $all_months[$value['month']] ?></td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td><?php echo $value['activity_target']; ?></td>
                    <td><?php echo $value['activity_achievement']; ?></td>
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