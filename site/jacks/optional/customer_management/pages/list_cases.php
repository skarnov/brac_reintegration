<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_birth = $_GET['birth'] ? $_GET['birth'] : null;
$filter_country = $_GET['country'] ? $_GET['country'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'id' => 'dev_immediate_supports.fk_customer_id',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'project_short_name' => 'dev_projects.project_short_name',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'final_destination' => 'dev_migrations.final_destination',
        'permanent_division' => 'dev_customers.permanent_division',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'customer_status' => 'dev_customers.customer_status',
    ),
    'customer_id' => $filter_customer_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'birth' => $filter_birth,
    'country' => $filter_country,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'project' => $filter_project,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_immediate_supports.fk_customer_id',
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

$cases = $this->get_cases($args);
$pagination = pagination($cases['total'], $per_page_items, $start);

$countries = get_countries();
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
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_birth)
    $filterString[] = 'Birth ID: ' . $filter_birth;
if ($filter_country)
    $filterString[] = 'Country: ' . $filter_country;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_excel']) {
    $args['report'] = true;
    $args['select_fields'] = array(
        'project_short_name' => 'dev_projects.project_short_name',
        'final_destination' => 'dev_migrations.final_destination',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'customer_birthdate' => 'dev_customers.customer_birthdate',
        'customer_gender' => 'dev_customers.customer_gender',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'permanent_village' => 'dev_customers.permanent_village',
        'permanent_ward' => 'dev_customers.permanent_ward',
        'permanent_union' => 'dev_customers.permanent_union',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'permanent_district' => 'dev_customers.permanent_district',
        'customer_create_date' => 'dev_customers.create_date AS customer_create_date',
        'customer_entry_date' => 'dev_customers.entry_date AS customer_entry_date',
        'entry_date' => 'dev_immediate_supports.entry_date AS entry_date',
        'arrival_place' => 'dev_immediate_supports.arrival_place',
        'immediate_support' => 'dev_immediate_supports.immediate_support',
        'plan_date' => 'dev_reintegration_plan.plan_date',
        'service_requested' => 'dev_reintegration_plan.service_requested',
        'other_service_requested' => 'dev_reintegration_plan.other_service_requested',
        'first_meeting' => 'dev_psycho_supports.first_meeting',
        'problem_identified' => 'dev_psycho_supports.problem_identified',
        'initial_plan' => 'dev_psycho_supports.initial_plan',
        'session_place' => 'dev_psycho_supports.session_place',
        'session_number' => 'dev_psycho_supports.session_number',
        'other_requirements' => 'dev_psycho_supports.other_requirements',
        'reffer_to' => 'dev_psycho_supports.reffer_to',
        'referr_address' => 'dev_psycho_supports.referr_address',
        'reason_for_reffer' => 'dev_psycho_supports.reason_for_reffer',
        'other_reason_for_reffer' => 'dev_psycho_supports.other_reason_for_reffer',
        'economic_inkind_date' => 'dev_economic_inkind.entry_date AS economic_inkind_date',
        'in_kind_type' => 'dev_economic_inkind.in_kind_type',
        'organization_name' => 'dev_economic_inkind.organization_name',
        'support_delivery_date' => 'dev_economic_inkind.support_delivery_date',
        'inkind_project' => 'dev_economic_inkind.inkind_project',
        'other_inkind_project' => 'dev_economic_inkind.other_inkind_project',
        'support_amount' => 'dev_economic_inkind.support_amount',
        'economic_support_delivered' => 'dev_economic_inkind.economic_support_delivered',
        'business_type' => 'dev_economic_inkind.business_type',
        'other_business_type' => 'dev_economic_inkind.other_business_type',
        'economic_other_comments' => 'dev_economic_inkind.economic_other_comments',
        'economic_training_entry_date' => 'dev_economic_training.entry_date AS economic_training_entry_date',
        'training_type' => 'dev_economic_training.training_type',
        'direct_training_type' => 'dev_economic_training.direct_training_type',
        'other_direct_training_type' => 'dev_economic_training.other_direct_training_type',
        'training_institution_name' => 'dev_economic_training.training_institution_name',
        'training_place' => 'dev_economic_training.training_place',
        'economic_training_start_date' => 'dev_economic_training.economic_training_start_date',
        'economic_training_end_date' => 'dev_economic_training.economic_training_end_date',
        'economic_training_comment' => 'dev_economic_training.economic_training_comment',
        'referral_training_type' => 'dev_economic_training.referral_training_type',
        'other_referral_training_type' => 'dev_economic_training.other_referral_training_type',
        'referral_training_institution_name' => 'dev_economic_training.referral_training_institution_name',
        'referral_training_place' => 'dev_economic_training.referral_training_place',
        'referral_economic_training_start_date' => 'dev_economic_training.referral_economic_training_start_date',
        'referral_economic_training_end_date' => 'dev_economic_training.referral_economic_training_end_date',
        'referral_economic_training_comment' => 'dev_economic_training.referral_economic_training_comment',
        'financial_training_entry' => 'dev_financial_literacy.entry_date AS financial_training_entry',
        'financial_training_received' => 'dev_financial_literacy.financial_training_received',
        'financial_institution_name' => 'dev_financial_literacy.financial_institution_name',
        'financial_training_place' => 'dev_financial_literacy.financial_training_place',
        'financial_training_start_date' => 'dev_financial_literacy.financial_training_start_date',
        'financial_training_end_date' => 'dev_financial_literacy.financial_training_end_date',
        'financial_training_comment' => 'dev_financial_literacy.financial_training_comment',
        'social_support_entry_date' => 'dev_social_supports.social_support_entry_date',
        'support_referred' => 'dev_social_supports.support_referred',
        'other_support_referred' => 'dev_social_supports.other_support_referred',
        'medical_support_entry_date' => 'dev_social_supports.medical_support_entry_date',
        'medical_support_type' => 'dev_social_supports.medical_support_type',
        'medical_institution_name' => 'dev_social_supports.medical_institution_name',
        'treatment_allowance' => 'dev_social_supports.treatment_allowance',
        'treatment_allowance_date' => 'dev_social_supports.treatment_allowance_date',
        'treatment_allowance_comment' => 'dev_social_supports.treatment_allowance_comment',
    );
    unset($args['listing']);
    unset($args['limit']);

    $data = $this->get_cases($args);
    $data = $data['data'];

    // This will be here in our project
    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $style10 = (new StyleBuilder())
            ->setBackgroundColor(Color::ALICEBLUE)
            ->build();

    $fileName = 'Case_Management-' . time() . '.xlsx';
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
    $report_head = ['Case Management Report '];
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
        'Project Name',
        'Entry Date',
        'Data Submission Date',
        'Country of Return',
        'Participant ID',
        'Full Name',
        'Date of Birth',
        'Gender',
        'Mobile No',
        'Village',
        'Ward No',
        'Union',
        'Upazilla',
        'District',
        'Support Date',
        'Arrival Place',
        'Immediate Support Services Received',
        'Plan Date',
        'Type of Services Requested',
        'Date of First Meeting',
        'Problems Identified',
        'Initial Plan',
        'Place of Session',
        'Number of Sessions (Estimate)',
        'Other Requirements',
        'Referred To',
        'Referred Address of Organization/Individual',
        'Reason for Referral',
        'In Kind Entry Date',
        'In Kind Type',
        'Support Received From',
        'Support Received Date',
        'Support Received Type',
        'Support Amount',
        'Support Description',
        'Business Type',
        'In Kind Comments',
        'Training Entry Date',
        'Training Type',
        'Direct Training Type',
        'Institution Name',
        'Place of Training',
        'Training Start Date',
        'Training End Date',
        'Comment',
        'Referral Training Type',
        'Referral Institution Name',
        '(Referral) Place of Training',
        '(Referral) Training Start Date',
        '(Referral) Training End Date',
        '(Referral) Training Comments',
        'Financial Literacy Entry Date',
        'Training Received',
        'Institution Name',
        'Place of Training',
        'Training Start Date',
        'Training End Date',
        'Comment',
        'Social Reintegration Entry Date',
        'Support Referred For',
        'Medical Support Entry Date',
        'Medical Support Type',
        'Institution Name (Hospital/Clinic)',
        'Treatment Allowance',
        'Support Date',
        'Comment',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();
    $merg_col = "";
    $mergeRanges = ['A1:CE1', 'A2:CE2', 'A3:CE3'];

    if ($data) {
        $count = 0;
        foreach ($data as $case_info) {
            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($case_info['project_short_name']),
                WriterEntityFactory::createCell($case_info['customer_entry_date'] && $case_info['customer_entry_date'] != '0000-00-00'  ? date('d-m-Y', strtotime($case_info['customer_entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['customer_create_date'] && $case_info['customer_create_date'] != '0000-00-00'  ? date('d-m-Y', strtotime($case_info['customer_create_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['final_destination']),
                WriterEntityFactory::createCell($case_info['customer_id']),
                WriterEntityFactory::createCell($case_info['full_name']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($case_info['customer_birthdate']))),
                WriterEntityFactory::createCell(ucfirst($case_info['customer_gender'])),
                WriterEntityFactory::createCell($case_info['customer_mobile']),
                WriterEntityFactory::createCell($case_info['permanent_village']),
                WriterEntityFactory::createCell($case_info['permanent_ward']),
                WriterEntityFactory::createCell($case_info['permanent_union']),
                WriterEntityFactory::createCell($case_info['permanent_sub_district']),
                WriterEntityFactory::createCell($case_info['permanent_district']),
                WriterEntityFactory::createCell($case_info['entry_date'] && $case_info['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['arrival_place']),
                WriterEntityFactory::createCell($case_info['immediate_support']),
                WriterEntityFactory::createCell($case_info['plan_date'] && $case_info['plan_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['plan_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['service_requested'] . ' ' . $case_info['other_service_requested']),
                WriterEntityFactory::createCell($case_info['first_meeting'] && $case_info['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['first_meeting'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['problem_identified']),
                WriterEntityFactory::createCell($case_info['initial_plan']),
                WriterEntityFactory::createCell(ucfirst($case_info['session_place'])),
                WriterEntityFactory::createCell($case_info['session_number']),
                WriterEntityFactory::createCell($case_info['other_requirements']),
                WriterEntityFactory::createCell($case_info['reffer_to']),
                WriterEntityFactory::createCell($case_info['referr_address']),
                WriterEntityFactory::createCell($case_info['reason_for_reffer'] . ' ' . $case_info['other_reason_for_reffer']),
                WriterEntityFactory::createCell($case_info['economic_inkind_date'] && $case_info['economic_inkind_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['economic_inkind_date'])) : 'N/A'),
                WriterEntityFactory::createCell(ucfirst($case_info['in_kind_type'])),
                WriterEntityFactory::createCell($case_info['organization_name']),
                WriterEntityFactory::createCell($case_info['support_delivery_date'] && $case_info['support_delivery_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['support_delivery_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['inkind_project'] . ' ' . $case_info['other_inkind_project']),
                WriterEntityFactory::createCell($case_info['support_amount']),
                WriterEntityFactory::createCell($case_info['economic_support_delivered']),
                WriterEntityFactory::createCell($case_info['business_type'] . ' ' . $case_info['other_business_type']),
                WriterEntityFactory::createCell($case_info['economic_other_comments']),
                WriterEntityFactory::createCell($case_info['economic_training_entry_date'] && $case_info['economic_training_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['economic_training_entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell(ucfirst($case_info['training_type'])),
                WriterEntityFactory::createCell($case_info['direct_training_type'] . ' ' . $case_info['other_direct_training_type']),
                WriterEntityFactory::createCell($case_info['training_institution_name']),
                WriterEntityFactory::createCell($case_info['training_place']),
                WriterEntityFactory::createCell($case_info['economic_training_start_date'] && $case_info['economic_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['economic_training_start_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['economic_training_end_date'] && $case_info['economic_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['economic_training_end_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['economic_training_comment']),
                WriterEntityFactory::createCell($case_info['referral_training_type'] . ' ' . $case_info['other_referral_training_type']),
                WriterEntityFactory::createCell($case_info['referral_training_institution_name']),
                WriterEntityFactory::createCell($case_info['referral_training_place']),
                WriterEntityFactory::createCell($case_info['referral_economic_training_start_date'] && $case_info['referral_economic_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['referral_economic_training_start_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['referral_economic_training_end_date'] && $case_info['referral_economic_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['referral_economic_training_end_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['referral_economic_training_comment']),
                WriterEntityFactory::createCell($case_info['financial_training_entry'] && $case_info['financial_training_entry'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['financial_training_entry'])) : 'N/A'),
                WriterEntityFactory::createCell(ucfirst($case_info['financial_training_received'])),
                WriterEntityFactory::createCell($case_info['financial_institution_name']),
                WriterEntityFactory::createCell($case_info['financial_training_place']),
                WriterEntityFactory::createCell($case_info['financial_training_start_date'] && $case_info['financial_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['financial_training_start_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['financial_training_end_date'] && $case_info['financial_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['financial_training_end_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['financial_training_comment']),
                WriterEntityFactory::createCell($case_info['social_support_entry_date'] && $case_info['social_support_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['social_support_entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['support_referred'] . ' ' . $case_info['other_support_referred'] . ' ' . $case_info['other_social_protection']),
                WriterEntityFactory::createCell($case_info['medical_support_entry_date'] && $case_info['medical_support_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['medical_support_entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell(ucfirst($case_info['medical_support_type'])),
                WriterEntityFactory::createCell($case_info['medical_institution_name']),
                WriterEntityFactory::createCell($case_info['treatment_allowance']),
                WriterEntityFactory::createCell($case_info['treatment_allowance_date'] && $case_info['treatment_allowance_date'] != '0000-00-00' ? date('d-m-Y', strtotime($case_info['treatment_allowance_date'])) : 'N/A'),
                WriterEntityFactory::createCell($case_info['treatment_allowance_comment']),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }
    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Cases</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_excel=1&customer_id=' . $filter_customer_id . '&name=' . $filter_name . '&nid=' . $filter_nid . '&birth=' . $filter_birth . '&country=' . $filter_country . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Case',
                'title' => 'Download Case',
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
    'width' => 2, 'type' => 'text', 'label' => 'Participant Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('birth', 'birth', array(
    'width' => 2, 'type' => 'text', 'label' => 'Birth ID',
        ), $filter_birth);
?>
<div class="form-group col-sm-2">
    <label>Country</label>
    <div class="select2-primary">
        <select class="form-control country" name="country" style="text-transform: capitalize">
            <?php if ($filter_country) : ?>
                <option value="<?php echo $filter_country ?>"><?php echo $filter_country ?></option>
            <?php else: ?>
                <option value="">Select One</option>
            <?php endif ?>
            <?php foreach ($countries as $country) : ?>
                <option id="<?php echo $country['nicename'] ?>" value="<?php echo $country['nicename'] ?>"><?php echo $country['nicename'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
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
                <th>Project Name</th>
                <th>Country</th>
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
                    <td><?php echo $case['project_short_name']; ?></td>
                    <td><?php echo $case['final_destination']; ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $case['permanent_division'] . ',<br><b>District - </b>' . $case['permanent_district'] . ',<br><b>Upazila - </b>' . $case['permanent_sub_district'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $case['customer_status']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_case')): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu" style="text-align: left;">
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $case['fk_customer_id']) ?>">Edit</a></li>
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_cases?action=download_pdf&id=' . $case['fk_customer_id']) ?>">Download PDF</a></li>
                                </ul>
                            </div>                         
                        <?php endif ?>
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