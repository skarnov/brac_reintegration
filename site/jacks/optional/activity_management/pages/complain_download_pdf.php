<?php

$complain_id = $_GET['id'];

$complains = $this->get_complains(array('id' => $complain_id, 'single' => true));

$reportTitle = 'Community Service';

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

$devPdf->Cell(50, 6, 'Branch Name: ', 1, 0);
$devPdf->Cell(135, 6, $complains['branch_name'], 1, 1);

$devPdf->Cell(50, 6, 'Division: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['division']), 1, 1);

$devPdf->Cell(50, 6, 'District: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['branch_district']), 1, 1);

$devPdf->Cell(50, 6, 'Upazila: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['upazila']), 1, 1);

$devPdf->Cell(50, 6, 'Union: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['branch_union']), 1, 1);

$devPdf->Cell(50, 6, 'Village: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['village']), 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Community Service Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Service Date: ', 1, 0);
$devPdf->Cell(135, 6, $complains['complain_register_date'] ? date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A', 1, 1);

$devPdf->Cell(50, 6, 'Name of Service Recipient: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['name']), 1, 1);

$devPdf->Cell(50, 6, 'Age: ', 1, 0);
$devPdf->Cell(135, 6, $complains['age'], 1, 1);

$devPdf->Multicell(185, 6, 'Type of Service Seeking: ' . $complains['type_service'] . ' ' . $complains['other_type_service'], 1, 1);

$devPdf->Cell(50, 6, 'Service recipient: ', 1, 0);
$devPdf->Cell(135, 6, ucwords(str_replace('_', ' ', $complains['type_recipient'])), 1, 1);

$devPdf->Cell(50, 6, 'Gender: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($complains['gender']), 1, 1);

$devPdf->Multicell(185, 6, 'How To Know About This Service of The Project? ' . $complains['know_service'] . ' ' . $complains['other_know_service'], 1, 1);
$devPdf->Multicell(185, 6, 'Remark: ' . $complains['remark'], 1, 1);
$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');