<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

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
        'year' => 'dev_meeting_targets.year',
        'month' => 'dev_meeting_targets.month',
        'meeting_name' => 'dev_meetings.meeting_name',
        'meeting_target' => 'dev_meeting_targets.meeting_target',
        'meeting_achievement' => 'dev_meeting_targets.meeting_achievement',
        'achievement_male' => 'dev_meeting_targets.achievement_male',
        'achievement_female' => 'dev_meeting_targets.achievement_female',
        'achievement_boy' => 'dev_meeting_targets.achievement_boy',
        'achievement_girl' => 'dev_meeting_targets.achievement_girl',
        'achievement_total' => 'dev_meeting_targets.achievement_total',
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
        'col' => 'pk_meeting_target_id',
        'order' => 'DESC'
    ),
);

$results = $this->get_meeting_targets($args);
$pagination = pagination($results['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project_id, 'single' => true));

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

if ($_GET['download_excel']) {
    $filter_project_name = null;
    $filter_branch_name = null;
    $filter_month_name = null;
    if ($filter_project_id)
        $find_pro = array_search($filter_project_id, array_column($all_projects['data'], 'pk_project_id'));
    $filter_project_name = $all_projects['data'][$find_pro]['project_short_name'];

    if ($filter_branch_id)
        $find_bran = array_search($filter_branch_id, array_column($all_branches['data'], 'pk_branch_id'));
    $filter_branch_name = $all_branches['data'][$find_bran]['branch_name'];

    if ($filter_month)
        $filter_month_name = $all_months[$filter_month];

    unset($args['limit']);

    $args['data_only'] = true;
    $data = $this->get_meeting_targets($args);
    $data = $data['data'];
    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'meeting-report-' . time() . '.xlsx';
    //$writer->openToFile('lemon1.xlsx'); // write data to a file or to a PHP stream
    $writer->openToBrowser($fileName); // stream data directly to the browser
    // Header text
    $style2 = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(15)
            //->setFontColor(Color::BLUE)
            ->setShouldWrapText()
            ->setCellAlignment(CellAlignment::LEFT)
            ->build();

    /** add a row at a time */
    $report_head = ['Meeting Reports'];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head, $style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: ' . Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
        "SL",
        'Project Name',
        'District',
        'Upazila',
        'Branch Name',
        'Month',
        'Meeting',
        'Target',
        'Achievement',
        'Variance',
        'Male',
        'Female',
        'Boy',
        'Girl',
        'Total',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $month_name = $all_months[$value['month']];

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($value['project_short_name']),
                WriterEntityFactory::createCell($value['branch_district']),
                WriterEntityFactory::createCell($value['branch_sub_district']),
                WriterEntityFactory::createCell($value['branch_name']),
                WriterEntityFactory::createCell($month_name),
                WriterEntityFactory::createCell($value['meeting_name']),
                WriterEntityFactory::createCell($value['meeting_target']),
                WriterEntityFactory::createCell($value['meeting_achievement']),
                WriterEntityFactory::createCell($value['meeting_target'] - $value['meeting_achievement']),
                WriterEntityFactory::createCell($value['achievement_male']),
                WriterEntityFactory::createCell($value['achievement_female']),
                WriterEntityFactory::createCell($value['achievement_boy']),
                WriterEntityFactory::createCell($value['achievement_girl']),
                WriterEntityFactory::createCell($value['achievement_total']),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }


    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:O1', 'A2:O2', 'A3:O3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Meeting Reports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_excel=1&project_id=' . $filter_project_id . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&branch_id=' . $filter_branch_id . '&month=' . $filter_month,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Meeting Reports',
                'title' => 'Download Meeting Reports',
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'meeting') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Meeting</th>
                <th>Target</th>
                <th>Achievement</th>
                <th>Variance</th>
                <th>Male</th>
                <th>Female</th>
                <th>Boy</th>
                <th>Girl</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($results['data'] as $value) {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['meeting_name']; ?></td>
                    <td><?php echo $value['meeting_target']; ?></td>
                    <td><?php echo $value['meeting_achievement']; ?></td>
                    <td><?php echo $value['meeting_target'] - $value['meeting_achievement']; ?></td>
                    <td><?php echo $value['achievement_male']; ?></td>
                    <td><?php echo $value['achievement_female']; ?></td>
                    <td><?php echo $value['achievement_boy']; ?></td>
                    <td><?php echo $value['achievement_girl']; ?></td>
                    <td><?php echo $value['achievement_total']; ?></td>
                </tr>
                <?php
                $i++;
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