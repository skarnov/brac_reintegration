<?php

$complain_id = $_GET['id'];

$complains = $this->get_complain_fileds(array('id' => $complain_id, 'single' => true));

$reportTitle = 'Complain File';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Geographical Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Project Name: ', 1, 0);
$devPdf->Cell(135, 6, $complains['project_short_name'], 1, 1);

$devPdf->Cell(50, 6, 'Division: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['division']), 1, 1);

$devPdf->Cell(50, 6, 'District: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['district']), 1, 1);

$devPdf->Cell(50, 6, 'Upazila: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['upazila']), 1, 1);

$devPdf->Cell(50, 6, 'Police Station: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['police_station']), 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Complain File Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Case Number: ', 1, 0);
$devPdf->Cell(135, 6, $complains['case_id'], 1, 1);

$devPdf->Cell(50, 6, 'Date of Complain File: ', 1, 0);
$devPdf->Cell(135, 6, $complains['complain_register_date'] ? date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A', 1, 1);

$devPdf->Cell(50, 6, 'Survivor Name: ', 1, 0);
$devPdf->Cell(135, 6, $complains['full_name'], 1, 1);

$devPdf->Cell(50, 6, 'Age: ', 1, 0);
$devPdf->Cell(135, 6, $complains['age'], 1, 1);

$devPdf->Cell(50, 6, 'Gender: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['gender']), 1, 1);

$devPdf->Cell(50, 6, 'Month: ', 1, 0);
$devPdf->Cell(135, 6, $complains['month'], 1, 1);

$devPdf->Cell(50, 6, 'Type of Case: ', 1, 0);
$devPdf->Cell(135, 6, $complains['type_case'], 1, 1);

$devPdf->Cell(50, 6, 'Comments: ', 1, 0);
$devPdf->Cell(135, 6, $complains['comments'], 1, 1);

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
