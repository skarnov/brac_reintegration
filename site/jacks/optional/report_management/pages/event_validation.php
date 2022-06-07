<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'project_short_name' => 'dev_projects.project_short_name',
        'interview_date' => 'dev_event_validations.interview_date',
        'reviewed_by' => 'dev_event_validations.reviewed_by',
        'participant_name' => 'dev_event_validations.participant_name',
        'quote' => 'dev_event_validations.quote',
    ),
    'project' => $filter_project,
    'name' => $filter_name,
    'branch_id' => $branch_id ? $branch_id : $filter_branch_id,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_validation_id',
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

$eventManagement = jack_obj('dev_activity_management');
$events = $eventManagement->get_event_validations($args);
$pagination = pagination($events['total'], $per_page_items, $start);

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
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_branch_id)
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

if ($_GET['download_excel']) {
    $args['select_fields'] = array(
        'branch_name' => 'dev_branches.branch_name',
        'project_short_name' => 'dev_projects.project_short_name',
        'activity_name' => 'dev_activities.activity_name',
        'year' => 'dev_events.year',
        'month' => 'dev_events.month',
        'event_start_date' => 'dev_events.event_start_date',
        'event_start_time' => 'dev_events.event_start_time',
        'event_end_date' => 'dev_events.event_end_date',
        'event_end_time' => 'dev_events.event_end_time',
        'event_division' => 'dev_events.event_division',
        'event_district' => 'dev_events.event_district',
        'event_upazila' => 'dev_events.event_upazila',
        'event_union' => 'dev_events.event_union',
        'event_location' => 'dev_events.event_location',
        'event_village' => 'dev_events.event_village',
        'event_ward' => 'dev_events.event_ward',
        'interview_date' => 'dev_event_validations.interview_date',
        'interview_time' => 'dev_event_validations.interview_time',
        'reviewed_by' => 'dev_event_validations.reviewed_by',
        'beneficiary_id' => 'dev_event_validations.beneficiary_id',
        'participant_name' => 'dev_event_validations.participant_name',
        'gender' => 'dev_event_validations.gender',
        'age' => 'dev_event_validations.age',
        'mobile' => 'dev_event_validations.mobile',
        'enjoyment' => 'dev_event_validations.enjoyment',
        'victim' => 'dev_event_validations.victim',
        'victim_family' => 'dev_event_validations.victim_family',
        'message' => 'dev_event_validations.message',
        'other_message' => 'dev_event_validations.other_message',
        'use_message' => 'dev_event_validations.use_message',
        'mentioned_event' => 'dev_event_validations.mentioned_event',
        'additional_comments' => 'dev_event_validations.additional_comments',
        'quote' => 'dev_event_validations.quote',
        'create_date' => 'dev_event_validations.create_date',
    );
    unset($args['limit']);
    $data = $eventManagement->get_event_validations($args);

    foreach ($data['data'] as $key => $value) :
        if ($value['month'] == 1):
            $data['data'][$key]['month'] = 'January';

        elseif ($value['month'] == 2):
            $data['data'][$key]['month'] = 'February';

        elseif ($value['month'] == 3):
            $data['data'][$key]['month'] = 'March';

        elseif ($value['month'] == 4):
            $data['data'][$key]['month'] = 'April';

        elseif ($value['month'] == 5):
            $data['data'][$key]['month'] = 'May';

        elseif ($value['month'] == 6):
            $data['data'][$key]['month'] = 'June';

        elseif ($value['month'] == 7):
            $data['data'][$key]['month'] = 'July';

        elseif ($value['month'] == 8):
            $data['data'][$key]['month'] = 'August';

        elseif ($value['month'] == 9):
            $data['data'][$key]['month'] = 'September';

        elseif ($value['month'] == 10):
            $data['data'][$key]['month'] = 'October';

        elseif ($value['month'] == 11):
            $data['data'][$key]['month'] = 'November';

        elseif ($value['month'] == 12):
            $data['data'][$key]['month'] = 'December';

        endif;
    endforeach;

    $data = $data['data'];

    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'Event_Validation_' . time() . '.xlsx';
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
    $report_head = ['Event Validation Report'];
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
        'Branch Name',
        'Project Name',
//        'Entry Date',
        'Data Submission Date',
        'Activity Name',
        'Year',
        'Month',
        'Start Date',
        'Start Time',
        "End Date",
        "End Time",
        "Division",
        "District",
        'Upazila',
        'Union',
        'Event Location',
        'Event Village',
        'Event Ward',
        'Interview Date',
        'Interview Time',
        'Reviewed By',
        'Beneficiary Id',
        'Participant Name',
        'Gender',
        'Age',
        'Mobile',
        'Enjoyment',
        'Victim',
        'Victim Family',
        'Message',
        'Other Message',
        'User Message',
        'Mentioned Event',
        'Additional Comments',
        'Quote',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $i => $event) {
            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($event['branch_name']),
                WriterEntityFactory::createCell($event['project_short_name']),
//                WriterEntityFactory::createCell( $event['entry_date'] && $event['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($event['entry_date'])) : ''),
                WriterEntityFactory::createCell($event['create_date'] && $event['create_date'] != '0000-00-00' ? date('d-m-Y', strtotime($event['create_date'])) : ''),
                WriterEntityFactory::createCell($event['activity_name']),
                WriterEntityFactory::createCell($event['year']),
                WriterEntityFactory::createCell($event['month']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['event_start_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($event['event_start_time']))),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['event_end_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($event['event_end_time']))),
                WriterEntityFactory::createCell(ucfirst($event['event_division'])),
                WriterEntityFactory::createCell(ucfirst($event['event_district'])),
                WriterEntityFactory::createCell(ucfirst($event['event_upazila'])),
                WriterEntityFactory::createCell(ucfirst($event['event_union'])),
                WriterEntityFactory::createCell(ucfirst($event['event_location'])),
                WriterEntityFactory::createCell(ucfirst($event['event_village'])),
                WriterEntityFactory::createCell(ucfirst($event['event_ward'])),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['interview_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($event['interview_time']))),
                WriterEntityFactory::createCell($event['reviewed_by']),
                WriterEntityFactory::createCell($event['beneficiary_id']),
                WriterEntityFactory::createCell($event['participant_name']),
                WriterEntityFactory::createCell(ucfirst($event['gender'])),
                WriterEntityFactory::createCell($event['age']),
                WriterEntityFactory::createCell($event['mobile']),
                WriterEntityFactory::createCell(ucfirst($event['enjoyment'])),
                WriterEntityFactory::createCell(ucfirst($event['victim'])),
                WriterEntityFactory::createCell(ucfirst($event['victim_family'])),
                WriterEntityFactory::createCell($event['message']),
                WriterEntityFactory::createCell($event['other_message']),
                WriterEntityFactory::createCell($event['use_message']),
                WriterEntityFactory::createCell($event['mentioned_event']),
                WriterEntityFactory::createCell($event['additional_comments']),
                WriterEntityFactory::createCell($event['quote']),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }
    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:AG1', 'A2:AG2', 'A3:AG3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
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
            <h1>All Event Validation Report</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_excel=1&name=' . $filter_name . '&project_id=' . $filter_project . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Event Validation Report',
                        'title' => 'Download Event Validation Report',
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Activity Name',
        ), $filter_name);
?>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id">
            <option value="">Select One</option>
            <?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php if ($branch['pk_branch_id'] == $filter_branch_id) echo 'selected' ?> ><?php echo $branch['branch_name'] ?></option>
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
        <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'event validation') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Interview Date</th>
                <th>Reviewed By</th>
                <th>Participant Name</th>
                <th>Quote</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events['data'] as $i => $event) {
                ?>
                <tr>
                    <td><?php echo $event['project_short_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['interview_date'])) ?></td>
                    <td><?php echo $event['reviewed_by']; ?></td>
                    <td><?php echo $event['participant_name']; ?></td>
                    <td><?php echo $event['quote']; ?></td>
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
            var event = ths.attr('data-event');
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
                        window.location.href = '?action=deleteEventValidation&id=' + logId + '&event_id=' + event;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>