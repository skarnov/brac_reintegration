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
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_service_recipient = $_GET['service_recipient'] ? $_GET['service_recipient'] : null;
$filter_service_seeking = $_GET['service_seeking'] ? $_GET['service_seeking'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'project_short_code' => 'dev_projects.project_short_name',
        'branch_name' => 'dev_branches.branch_name',
        '*' => 'dev_complains.*',
    ),
    'project' => $filter_project,
    'gender' => $filter_gender,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'type_recipient' => $filter_service_recipient,
    'type_service' => $filter_service_seeking,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_complain_id',
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

$complains = $this->get_complains($args);
$pagination = pagination($complains['total'], $per_page_items, $start);

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
if ($filter_gender)
    $filterString[] = 'Gender: ' . $filter_gender;
if ($filter_service_recipient)
    $filterString[] = 'Service Recipient: ' . $filter_service_recipient;
if ($filter_service_seeking)
    $filterString[] = 'Service Seeking: ' . $filter_service_seeking;
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

if ($_GET['download_excel']) {
    unset($args['limit']);
    $args['data_only'] = true;
    $data = $this->get_complains($args);
    $data = $data['data'];

    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'Community_Services-' . time() . '.xlsx';
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
    $report_head = ['Community Services Report'];
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
        'Project Name',
        'Branch Name',
        'Division',
        'District',
        'Upazila',
        'Union',
        'Village',
        'Service Date',
        'Name of service recipient',
        'Age',
        'Type of service seeking',
        'Service recipient',
        'Gender',
        'How to know about this service of the project',
        'Remark',
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
                WriterEntityFactory::createCell($complains['project_short_name']),
                WriterEntityFactory::createCell($complains['branch_name']),
                WriterEntityFactory::createCell(ucfirst($complains['division'])),
                WriterEntityFactory::createCell(ucfirst($complains['branch_district'])),
                WriterEntityFactory::createCell(ucfirst($complains['upazila'])),
                WriterEntityFactory::createCell(ucfirst($complains['branch_union'])),
                WriterEntityFactory::createCell($complains['village']),
                WriterEntityFactory::createCell($complains['complain_register_date'] && $complains['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A'),
                WriterEntityFactory::createCell($complains['name']),
                WriterEntityFactory::createCell($complains['age']),
                WriterEntityFactory::createCell($complains['type_service'] . ' ' . $complains['other_type_service']),
                WriterEntityFactory::createCell(ucwords(str_replace('_', ' ', $complain['type_recipient']))),
                WriterEntityFactory::createCell(ucfirst($complains['gender'])),
                WriterEntityFactory::createCell($complains['know_service'] . ' ' . $complains['other_know_service']),
                WriterEntityFactory::createCell($complains['remark']),
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
    <div class="row">
        <div class="col-md-8">
            <h1>All Community Services</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_complain',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'New Community Service',
                        'title' => 'New Community Service',
                    ));
                    ?>
                </div>
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_excel=1&project_id=' . $filter_project . '&gender=' . $filter_gender . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&service_recipient=' . $filter_service_recipient . '&service_seeking=' . $filter_service_seeking . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Community Services',
                        'title' => 'Download Community Services',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $complains['total'] ?></span><br>
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
    <label>Service Recipient</label>
    <div class="select2-primary">
        <select class="form-control" name="service_recipient">
            <option value="">Select One</option>
            <?php foreach ($this->type_recipient as $key => $value) : ?>    
                <option value="<?php echo $key ?>" <?php echo ($key == $filter_service_recipient) ? 'selected' : '' ?>><?php echo $value ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Service Seeking</label>
    <div class="select2-primary">
        <select class="form-control" name="service_seeking">
            <option value="">Select One</option>
            <option value="Case filing support" <?php echo ('Case filing support' == $filter_service_seeking) ? 'selected' : '' ?>>Case filing support</option>
            <option value="Trafficking information" <?php echo ('Trafficking information' == $filter_service_seeking) ? 'selected' : '' ?>>Trafficking information</option>
            <option value="Safe migration information" <?php echo ('Safe migration information' == $filter_service_seeking) ? 'selected' : '' ?>>Safe migration information</option>
            <option value="Missing information" <?php echo ('Missing information' == $filter_service_seeking) ? 'selected' : '' ?>>Missing information</option>
            <option value="Rescue support" <?php echo ('Rescue support' == $filter_service_seeking) ? 'selected' : '' ?>>Rescue support</option>
            <option value="Dead body recover support" <?php echo ('Dead body recover support' == $filter_service_seeking) ? 'selected' : '' ?>>Dead body recover support</option>
            <option value="Claim compensation" <?php echo ('Claim compensation' == $filter_service_seeking) ? 'selected' : '' ?>>Claim compensation</option>
            <option value="Legal support" <?php echo ('Legal support' == $filter_service_seeking) ? 'selected' : '' ?>>Legal support</option>
            <option value="Project information" <?php echo ('Project information' == $filter_service_seeking) ? 'selected' : '' ?>>Project information</option>
            <option value="Training support" <?php echo ('Training support' == $filter_service_seeking) ? 'selected' : '' ?>>Training support</option>
            <option value="Loan support" <?php echo ('Loan support' == $filter_service_seeking) ? 'selected' : '' ?>>Loan support</option>
            <option value="Job placement" <?php echo ('Job placement' == $filter_service_seeking) ? 'selected' : '' ?>>Job placement</option>
            <option value="Others" <?php echo ('Others' == $filter_service_seeking) ? 'selected' : '' ?>>Others</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Service Start Date</label>
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
    <label>Service End Date</label>
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
    <div class="table-header">
        <?php echo searchResultText($complains['total'], $start, $per_page_items, count($complains['data']), 'Community Service') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project</th>-->
                <th>Entry Date</th>
                <th>Service Date</th>
                <th>Service Recipient</th>
                <th>Recipient Age</th>
                <th>Recipient Gender</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($complains['data'] as $i => $complain) {
                ?>
                <tr>
<!--                    <td><?php echo $complain['project_short_name']; ?></td>-->
                    <td><?php echo $complain['entry_date'] ? date('d-m-Y', strtotime($complain['entry_date'])) : '' ?></td>
                    <td><?php echo $complain['complain_register_date'] ? date('d-m-Y', strtotime($complain['complain_register_date'])) : '' ?></td>
                    <td style="text-transform: capitalize"><?php echo ucwords(str_replace('_', ' ', $complain['type_recipient'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $complain['age']; ?></td>
                    <td>
                        <?php
                        if ($complain['gender'] == 'male' && $complain['age'] <= 17) {
                            echo 'Boy (<18)';
                        } else if ($complain['gender'] == 'male' && $complain['age'] > 17) {
                            echo 'Men (>=18)';
                        } else if ($complain['gender'] == 'female' && $complain['age'] <= 17) {
                            echo 'Girl (<18)';
                        } else if ($complain['gender'] == 'female' && $complain['age'] > 17) {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
                    <td class="tar action_column">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                            <ul class="dropdown-menu">
                                <?php if (has_permission('edit_complain')): ?>
                                    <li><a href="<?php echo url('admin/dev_activity_management/manage_complains?action=add_edit_complain&edit=' . $complain['pk_complain_id']) ?>">Edit</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo url('admin/dev_activity_management/manage_complains?action=download_pdf&id=' . $complain['pk_complain_id']) ?>">Download PDF</a></li>
                            </ul>
                        </div>
                        <?php if (has_permission('delete_complain')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain['pk_complain_id']),
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
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteComplain&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>