<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_case_type = $_GET['type_case'] ? $_GET['type_case'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'project_short_code' => 'dev_projects.project_short_name',
        '*' => 'dev_complain_fileds.*',
    ),
    'project' => $filter_project,
    'gender' => $filter_gender,
    'type_case' => $filter_case_type,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_complain_fileds.pk_complain_filed_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'complain_register_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$complain_fileds = $this->get_complain_fileds($args);
$pagination = pagination($complain_fileds['total'], $per_page_items, $start);

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

$filterString = array();
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_name)
    $filterString[] = 'Gender: ' . $filter_name;
if ($filter_case_type)
    $filterString[] = 'Case Type: ' . $filter_case_type;
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

if ($_GET['download_excel']) {
    unset($args['limit']);

    $args['data_only'] = true;
    $data = $this->get_complain_fileds($args);
    $data = $data['data'];
    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'Complain-Files-' . time() . '.xlsx';
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
    $report_head = ['Complain Files Report'];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head, $style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: ' . Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Case Number',
        'Project Name',
        'Survivor Name',
        'Date of Complain File',
        'Month',
        'Division',
        'District',
        'Upazila',
        'Police Station',
        'Age',
        'Gender',
        'Type of Case',
        'Comments',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $complains) {

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($complains['entry_date'] && $complains['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($complains['entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell($complains['create_date'] ? date('d-m-Y', strtotime($complains['create_date'])) : 'N/A'),
                WriterEntityFactory::createCell($complains['case_id']),
                WriterEntityFactory::createCell($complains['project_short_name']),
                WriterEntityFactory::createCell($complains['full_name']),
                WriterEntityFactory::createCell($complains['complain_register_date'] && $complains['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A'),
                WriterEntityFactory::createCell($complains['month']),
                WriterEntityFactory::createCell(ucfirst($complains['division'])),
                WriterEntityFactory::createCell(ucfirst($complains['district'])),
                WriterEntityFactory::createCell(ucfirst($complains['upazila'])),
                WriterEntityFactory::createCell(ucfirst($complains['police_station'])),
                WriterEntityFactory::createCell($complains['age']),
                WriterEntityFactory::createCell(ucfirst($complains['gender'])),
                WriterEntityFactory::createCell(ucfirst($complains['type_case'])),
                WriterEntityFactory::createCell($complains['comments']),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }

    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:M1', 'A2:M2', 'A3:M3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Complain Files</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_complain_filed',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'New Complain File',
                        'title' => 'New Complain File',
                    ));
                    ?>
                </div>
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_excel=1&project_id=' . $filter_project . '&gender=' . $filter_gender . '&type_case=' . $filter_type_case . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Complain Files',
                        'title' => 'Download Complain Files',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $complain_fileds['total'] ?></span><br>
                    <span class="text-sm">Stored in Database</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-cell bg-warning padding-sm no-padding-t text-center">
                    <div id="stats-sparklines-2" class="stats-sparklines" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<!--<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>-->
<div class="form-group col-sm-3">
    <label>Gender</label>
    <div class="select2-primary">
        <select class="form-control" name="gender">
            <option value="">Select One</option>
            <option value="male" <?php echo ('male' == $filter_gender) ? 'selected' : '' ?>>Men (>=18)</option>
            <option value="female" <?php echo ('female' == $filter_gender) ? 'selected' : '' ?>>Women (>=18)</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Case Type</label>
    <div class="select2-primary">
        <select class="form-control" name="type_case">
            <option value="">Select One</option>
            <option value="Missing" <?php echo ('Missing' == $filter_case_type) ? 'selected' : '' ?>>Missing</option>
            <option value="Flee away with" <?php echo ('Flee away with' == $filter_case_type) ? 'selected' : '' ?>>Flee away with</option>
            <option value="Abduction" <?php echo ('Abduction' == $filter_case_type) ? 'selected' : '' ?>>Abduction</option>
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
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($complain_fileds['total'], $start, $per_page_items, count($complain_fileds['data']), 'complain fileds') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project</th>-->
                <th>Case Number</th>
                <th>Date</th>
                <th>Month</th>
                <th>Police Station</th>
                <th>Case Type</th>
                <th>Gender</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($complain_fileds['data'] as $i => $complain_filed) {
                ?>
                <tr>
<!--                    <td><?php echo $complain_filed['project_short_name']; ?></td>-->
                    <td><?php echo $complain_filed['case_id']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($complain_filed['complain_register_date'])) ?></td>
                    <td><?php echo $complain_filed['month']; ?></td>
                    <td><?php echo $complain_filed['police_station']; ?></td>
                    <td><?php echo $complain_filed['type_case']; ?></td>
                    <td>
                        <?php
                        if ($complain_filed['gender'] == 'male' && $complain_filed['age'] <= 17) {
                            echo 'Boy (<18)';
                        } else if ($complain_filed['gender'] == 'male' && $complain_filed['age'] > 17) {
                            echo 'Men (>=18)';
                        } else if ($complain_filed['gender'] == 'female' && $complain_filed['age'] <= 17) {
                            echo 'Girl (<18)';
                        } else if ($complain_filed['gender'] == 'female' && $complain_filed['age'] > 17) {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
                    <td class="tar action_column">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                            <ul class="dropdown-menu">
                                <?php if (has_permission('edit_complain_filed')): ?>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_complain_fileds?action=add_edit_complain_filed&edit=' . $complain_filed['pk_complain_filed_id']) ?>">Edit</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo url('admin/dev_activity_management/manage_complain_investigations?action=add_edit_complain_investigation&complain_id=' . $complain_filed['pk_complain_filed_id']) ?>">New Investigation</a></li>
                                <li><a href="<?php echo url('admin/dev_activity_management/manage_complain_investigations?complain_id=' . $complain_filed['pk_complain_filed_id']) ?>">Investigation List</a></li>
                                <li><a href="<?php echo url('admin/dev_activity_management/manage_complain_fileds?action=download_pdf&id=' . $complain_filed['pk_complain_filed_id']) ?>">Download PDF</a></li>
                            </ul>
                        </div>
                        <?php if (has_permission('delete_complain_filed')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain_filed['pk_complain_filed_id']),
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
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteComplainFiled&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>