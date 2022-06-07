<?php

$customerManager = jack_obj('dev_customer_management');

$customer_id = $_GET['id'];

$args = array(
    'income_comparison' => true,
    'select_fields' => array(
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'present_income' => 'dev_economic_profile.present_income',
        'create_date' => 'dev_economic_profile.create_date',
    ),
    'fk_customer_id' => $customer_id,
    'single' => true
);

$initial_information = $customerManager->get_case_review($args);

$create_date = $initial_information['create_date'] == '0000-00-00' ? 'N/A' : $initial_information['create_date'];

$args = array(
    'fk_customer_id' => $customer_id,
    'individual_income_comparison' => true,
);

$all_followups = $customerManager->get_case_review($args);

$args = array(
    'fk_customer_id' => $customer_id,
    'order_by' => array(
        'col' => 'dev_followups.pk_followup_id',
        'order' => 'DESC'
    ),
    'single' => true
);

$latest_income_info = $customerManager->get_case_review($args);

$latest_income = $latest_income_info['monthly_average_income'];
$latest_income_date = $latest_income_info['entry_date'] == '0000-00-00' ? 'N/A' : $latest_income_info['entry_date'];

$income_difference = $latest_income - $initial_information['present_income'];
$income_percentage = ($income_difference / $latest_income) * 100;

$reportTitle = 'Income Comparison Report';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Income Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Beneficiary ID:', 1, 0);
$devPdf->Cell(135, 6, $initial_information['customer_id'], 1, 1);

$devPdf->Cell(50, 6, 'Full Name:', 1, 0);
$devPdf->Cell(135, 6, $initial_information['full_name'], 1, 1);

$devPdf->Cell(50, 6, 'Initialization Date:', 1, 0);
$devPdf->Cell(135, 6, $create_date, 1, 1);

$devPdf->Cell(50, 6, 'Previous Income:', 1, 0);
$devPdf->Cell(135, 6, $initial_information['present_income'] . ' BDT', 1, 1);

$devPdf->Cell(50, 6, 'Latest Income Date:', 1, 0);
$devPdf->Cell(135, 6, $latest_income_date, 1, 1);

$devPdf->Cell(50, 6, 'Latest Income:', 1, 0);
$devPdf->Cell(135, 6, $latest_income . ' BDT', 1, 1);

$devPdf->Cell(50, 6, 'Income Difference:', 1, 0);
$devPdf->Cell(135, 6, $income_difference . ' BDT', 1, 1);

$devPdf->Cell(50, 6, 'Income Percentage:', 1, 0);
$devPdf->Cell(135, 6, $income_percentage . '%', 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Review and Follow-Up', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($all_followups['data'] != NULL) {
    $count = 1;
    foreach ($all_followups['data'] as $i => $item) {

        $economic_date = $item['economic_date'] == '0000-00-00' ? 'N/A' : $item['economic_date'];

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(185, 6, 'SL:' . $count, 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Date: ', 1, 0);
        $devPdf->Cell(140, 6, $item['entry_date'] && $item['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($item['entry_date'])) : '', 1, 1);

        $devPdf->Cell(45, 6, 'Present Support Status: ', 1, 0);
        $devPdf->Cell(140, 6, ucfirst($item['support_status']), 1, 1);

        $devPdf->Cell(45, 6, 'Type of support received: ', 1, 0);
        $devPdf->Cell(140, 6, ucfirst($item['support_received']), 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Visit Date: ', 1, 0);
        $devPdf->Cell(140, 6, $economic_date, 1, 1);

        $devPdf->Cell(65, 6, 'Monthly Average Income: ', 1, 0);
        $devPdf->Cell(120, 6, $item['monthly_average_income'] . ' BDT', 1, 1);

        $devPdf->Multicell(185, 6, 'Challenges: ' . $item['economic_challenges'], 1, 1);

        $devPdf->Multicell(185, 6, 'Action Taken: ' . $item['economic_action'], 1, 1);
        $devPdf->Multicell(185, 6, 'Significant changes: ' . $item['significant_changes'], 1, 1);

        $devPdf->Multicell(185, 6, 'Remark of the participant: ' . $item['economic_participant'], 1, 1);
        $devPdf->Multicell(185, 6, 'Comment of BRAC Officer Responsible For Participant: ' . $item['economic_officer'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of RSC Manager: ' . $item['economic_manager'], 1, 1);

        $devPdf->Ln(5);
        $count++;
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
