<?php

$workshop_id = $_GET['id'];

$workshop = $this->get_workshops(array('id' => $workshop_id, 'single' => true));
$participants = $this->get_workshop_participants(array('fk_workshop_id' => $workshop_id));
$workshop_validation = $this->get_workshop_validations(array('workshop_id' => $workshop_id));

$reportTitle = 'Workshop Report - ' . $workshop['workshop_name'];

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');
$devPdf->Ln(5);

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Project Name: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['project_short_name'], 1, 1);

$devPdf->Cell(50, 6, 'Workshop Held at: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($workshop['workshop_held']), 1, 1);

$devPdf->Cell(50, 6, 'Workshop Venue: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['workshop_venue'], 1, 1);

$devPdf->Cell(50, 6, 'Workshop Start Date: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['workshop_start_date'] && $workshop['workshop_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($workshop['workshop_start_date'])) : 'N/A', 1, 1);

$devPdf->Cell(50, 6, 'Workshop End Date: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['workshop_end_date'] && $workshop['workshop_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($workshop['workshop_end_date'])) : 'N/A', 1, 1);

$devPdf->Cell(50, 6, 'Workshop Duration: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['workshop_duration'], 1, 1);

$devPdf->Cell(50, 6, 'Division: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['event_division'], 1, 1);

$devPdf->Cell(50, 6, 'District: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['event_district'], 1, 1);

$devPdf->Cell(50, 6, 'Upazila: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['event_upazila'], 1, 1);

$devPdf->Cell(50, 6, 'Union: ', 1, 0);
$devPdf->Cell(135, 6, $workshop['event_union'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Participants', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($participants['data'] != NULL) :
    foreach ($participants['data'] as $i => $participants) :
        $devPdf->Cell(50, 6, 'Name: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['participant_name'], 1, 1);

        $devPdf->Cell(50, 6, 'Organizational Name: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['organizational_name'], 1, 1);

        $devPdf->Cell(50, 6, 'Beneficiary ID: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['beneficiary_id'], 1, 1);

        $devPdf->Cell(50, 6, 'Age: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['participant_age'], 1, 1);

        $devPdf->Cell(50, 6, 'Profession: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['participant_profession'], 1, 1);

        $devPdf->Cell(50, 6, 'Type of Participant: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['participant_type']), 1, 1);

        $devPdf->Cell(50, 6, 'Gender: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['participant_gender'], 1, 1);

        $devPdf->Cell(50, 6, 'Mobile: ', 1, 0);
        $devPdf->Cell(135, 6, $participants['participant_mobile'], 1, 1);

        $devPdf->Cell(50, 6, 'Division: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_division']), 1, 1);

        $devPdf->Cell(50, 6, 'District: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_district']), 1, 1);

        $devPdf->Cell(50, 6, 'Upazila: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_sub_district']), 1, 1);

        $devPdf->Cell(50, 6, 'Police Station: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_police_station']), 1, 1);

        $devPdf->Cell(50, 6, 'Post Office: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_post_office']), 1, 1);

        $devPdf->Cell(50, 6, 'Municipality: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_municipality']), 1, 1);

        $devPdf->Cell(50, 6, 'City Corporation: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_city_corporation']), 1, 1);

        $devPdf->Cell(50, 6, 'Union: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_union']), 1, 1);

        $devPdf->Cell(50, 6, 'Ward: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_ward']), 1, 1);

        $devPdf->Cell(50, 6, 'Village: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($participants['permanent_village']), 1, 1);
        $devPdf->Ln(5);
    endforeach;
endif;

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Validation', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($workshop_validation['data'] != NULL) {
    foreach ($workshop_validation['data'] as $i => $item) {
        $devPdf->Cell(50, 6, 'Entry Date: ', 1, 0);
        $devPdf->Cell(135, 6, $item['entry_date'] && $item['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A', 1, 1);

        $devPdf->Cell(50, 6, 'Evaluator Profession: ', 1, 0);
        $devPdf->Cell(135, 6, $item['evaluator_profession'], 1, 1);

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(0, 12, 'Organization of The Workshop', 1, 1, 'L');
        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(170, 6, 'How satisfied are you with the contents of workshop/workshop: ', 1, 0);
        $devPdf->Cell(15, 6, $item['satisfied_workshop'], 1, 1);

        $devPdf->Cell(170, 6, 'How satisfied are you with the workshop venue and other logistic supports: ', 1, 0);
        $devPdf->Cell(15, 6, $item['satisfied_supports'], 1, 1);

        $devPdf->Cell(170, 6, 'How satisfied are you with the workshop/workshop facilitation: ', 1, 0);
        $devPdf->Cell(15, 6, $item['satisfied_facilitation'], 1, 1);

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(0, 13, 'Outcome of The Workshop', 1, 1, 'L');
        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(170, 6, 'What extent your knowledge increased on NPA: ', 1, 0);
        $devPdf->Cell(15, 6, $item['outcome_workshop'], 1, 1);

        $devPdf->Cell(170, 6, 'What extent your knowledge increased on trafficking law: ', 1, 0);
        $devPdf->Cell(15, 6, $item['trafficking_law'], 1, 1);

        $devPdf->Cell(170, 6, 'What extent your knowledge increased on policy process: ', 1, 0);
        $devPdf->Cell(15, 6, $item['policy_process'], 1, 1);

        $devPdf->Cell(170, 6, 'What extent your knowledge increased on over all contents: ', 1, 0);
        $devPdf->Cell(15, 6, $item['all_contents'], 1, 1);

        $devPdf->Multicell(185, 6, 'Recommendation (If Any): ' . $item['recommendation'], 1, 1);
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();