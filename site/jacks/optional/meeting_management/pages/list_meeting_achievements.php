<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_year = $_GET['year'] ? $_GET['year'] : null;
$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_project_id = $_GET['project_id'] ? $_GET['project_id'] : null;

$args = array(
    'select_fields' => array(
        'id' => 'dev_meeting_targets.pk_meeting_target_id',
        'branch_id' => 'dev_meeting_targets.fk_branch_id',
        'project_id' => 'dev_meeting_targets.fk_project_id',
        'project_name' => 'dev_projects.project_short_name',
        'branch_name' => 'dev_branches.branch_name',
        'district' => 'dev_meeting_targets.branch_district',
        'sub_district' => 'dev_meeting_targets.branch_sub_district',
        'month' => 'dev_meeting_targets.year',
        'month' => 'dev_meeting_targets.month',
        'meeting_name' => 'dev_meetings.meeting_name',
        'meeting_target' => 'dev_meeting_targets.meeting_target',
        'meeting_achievement' => 'dev_meeting_targets.meeting_achievement',
    ),
    'year' => $filter_year,
    'month' => $filter_month,
    'branch_id' => $filter_branch_id,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'project_id' => $filter_project_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_meeting_target_id ',
        'order' => 'DESC'
    ),
);

$results = $this->get_meeting_targets($args);
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

$projectName = $projects->get_projects(array('id' => $filter_project_id, 'single' => true));

$filterString = array();
if ($filter_project_id)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_year)
    $filterString[] = 'Year: ' . $filter_year;
if ($filter_month)
    $filterString[] = 'Month: ' . $filter_month;
if ($filter_branch_id)
    $filterString[] = 'Branch: ' . $filter_branch_id;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$all_months = $this->get_months();

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
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id">
            <option value="">Select One</option>
            <?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $filter_branch_id) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
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
            <option value="2018" <?php echo ($filter_year == '2018') ? 'selected' : '' ?>>2018</option>
            <option value="2019" <?php echo ($filter_year == '2019') ? 'selected' : '' ?>>2019</option>
            <option value="2020" <?php echo ($filter_year == '2020') ? 'selected' : '' ?>>2020</option>
            <option value="2021" <?php echo ($filter_year == '2021') ? 'selected' : '' ?>>2021</option>
            <option value="2022" <?php echo ($filter_year == '2022') ? 'selected' : '' ?>>2022</option>
            <option value="2023" <?php echo ($filter_year == '2023') ? 'selected' : '' ?>>2023</option>
            <option value="2024" <?php echo ($filter_year == '2024') ? 'selected' : '' ?>>2024</option>
            <option value="2025" <?php echo ($filter_year == '2025') ? 'selected' : '' ?>>2025</option>
            <option value="2026" <?php echo ($filter_year == '2026') ? 'selected' : '' ?>>2026</option>
            <option value="2027" <?php echo ($filter_year == '2027') ? 'selected' : '' ?>>2027</option>
            <option value="2028" <?php echo ($filter_year == '2028') ? 'selected' : '' ?>>2028</option>
            <option value="2029" <?php echo ($filter_year == '2029') ? 'selected' : '' ?>>2029</option>
            <option value="2030" <?php echo ($filter_year == '2030') ? 'selected' : '' ?>>2030</option>
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Achievements') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project</th>
                <th>Branch</th>
                <th>District</th>
                <th>Sub-District</th>
                <th>Month</th>
                <th>Meeting Name</th>
                <th>Target</th>
                <th>Achievement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['project_short_name']; ?></td>
                    <td><?php echo $value['branch_name']; ?></td>
                    <td><?php echo $value['branch_district']; ?></td>
                    <td><?php echo $value['branch_sub_district']; ?></td>
                    <td><?php echo $value['year']; ?></td>
                    <td>
                        <?php
                        if ($value['month'] == '1') {
                            echo 'January';
                        } elseif ($value['month'] == '2') {
                            echo 'February';
                        } elseif ($value['month'] == '3') {
                            echo 'March';
                        } elseif ($value['month'] == '4') {
                            echo 'April';
                        } elseif ($value['month'] == '5') {
                            echo 'May';
                        } elseif ($value['month'] == '6') {
                            echo 'June';
                        } elseif ($value['month'] == '7') {
                            echo 'July';
                        } elseif ($value['month'] == '8') {
                            echo 'August';
                        } elseif ($value['month'] == '9') {
                            echo 'September';
                        } elseif ($value['month'] == '10') {
                            echo 'October';
                        } elseif ($value['month'] == '11') {
                            echo 'November';
                        } elseif ($value['month'] == '12') {
                            echo 'December';
                        }
                        ?>
                    </td>
                    <td><?php echo $value['meeting_name']; ?></td>
                    <td><?php echo $value['meeting_target']; ?></td>
                    <td><?php echo $value['meeting_achievement']; ?></td>
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