<?php

$event_id = $_GET['id'];

$args = array(
    'select_fields' => array(
        'branch_name' => 'dev_branches.branch_name',
        'project_name' => 'dev_projects.project_name',
        'month' => 'dev_events.month',
        'activity_name' => 'dev_activities.activity_name',
        'event_start_date' => 'dev_events.event_start_date',
        'event_start_time' => 'dev_events.event_start_time',
        'event_end_date' => 'dev_events.event_end_date',
        'event_end_time' => 'dev_events.event_end_time',
        'event_division' => 'dev_events.event_division',
        'event_district' => 'dev_events.event_district',
        'event_upazila' => 'dev_events.event_upazila',
        'event_union' => 'dev_events.event_union',
        'event_village' => 'dev_events.event_village',
        'event_ward' => 'dev_events.event_ward',
        'event_location' => 'dev_events.event_location',
        'participant_boy' => 'dev_events.participant_boy',
        'participant_girl' => 'dev_events.participant_girl',
        'participant_male' => 'dev_events.participant_male',
        'participant_female' => 'dev_events.participant_female',
        'preparatory_work' => 'dev_events.preparatory_work',
        'time_management' => 'dev_events.time_management',
        'participants_attention' => 'dev_events.participants_attention',
        'logistical_arrangements' => 'dev_events.logistical_arrangements',
        'relevancy_delivery' => 'dev_events.relevancy_delivery',
        'participants_feedback' => 'dev_events.participants_feedback',
        'event_note' => 'dev_events.event_note',
    ),
    'id' => $event_id,
    'single' => true
);
$args['report'] = true;
$events = $this->get_events($args);
$event_validations = $this->get_event_validations(array('event_id' => $event_id));

if ($events['month'] == 1):
    $month_name = 'January';
elseif ($events['month'] == 2):
    $month_name = 'February';
elseif ($events['month'] == 3):
    $month_name = 'March';
elseif ($events['month'] == 4):
    $month_name = 'April';
elseif ($events['month'] == 5):
    $month_name = 'May';
elseif ($events['month'] == 6):
    $month_name = 'June';
elseif ($events['month'] == 7):
    $month_name = 'July';
elseif ($events['month'] == 8):
    $month_name = 'August';
elseif ($events['month'] == 9):
    $month_name = 'September';
elseif ($events['month'] == 10):
    $month_name = 'October';
elseif ($events['month'] == 11):
    $month_name = 'November';
elseif ($events['month'] == 12):
    $month_name = 'December';
endif;

$reportTitle = 'Event Information';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Event Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Project Name: ' . $events['project_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Branch Name: ' . $events['branch_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Month Name: ' . $month_name, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Activity Name: ' . $events['activity_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, $events['event_start_date'] ? 'Event Start Date: ' . date('d-m-Y', strtotime($events['event_start_date'])) : 'N/A', 0, 1, 'L');
$devPdf->Cell(0, 0, $events['event_start_time'] ? 'Event Start Time: ' . date('H:i:s', strtotime($events['event_start_time'])) : 'N/A', 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Basic Geographical Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Division: ' . ucfirst($events['event_division']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'District: ' . ucfirst($events['event_district']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Upazila: ' . ucfirst($events['event_upazila']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Union: ' . ucfirst($events['event_union']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Village: ' . $events['event_village'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Ward: ' . $events['event_ward'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Exact Location: ' . $events['event_location'], 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Number of participants in the Event', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Boy (<18): ' . $events['participant_boy'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Girl (<18): ' . $events['participant_girl'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Men (>=18): ' . $events['participant_male'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Women (>=18): ' . $events['participant_female'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Show Observation Checklist', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Preparatory work for the event was: ' . $events['preparatory_work'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Time management of the event was: ' . $events['time_management'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Participants attention during the event was: ' . $events['participants_attention'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Logistical arrangements: ' . $events['logistical_arrangements'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Relevancy of delivery of messages from the event was: ' . $events['relevancy_delivery'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Participants feedback on the overall event was: ' . $events['participants_feedback'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Event Note: ' . $events['event_note'], 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Event Validation', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($event_validations['data'] != NULL) {
    foreach ($event_validations['data'] as $i => $item) {
        $devPdf->Cell(0, 10, $item['interview_date'] ? 'Interview Date: ' . date('d-m-Y', strtotime($item['interview_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, $item['interview_time'] ? 'Interview Time: ' . date('H:i:s', strtotime($item['interview_time'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Reviewed By: ' . ucfirst($item['reviewed_by']), 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Beneficiary ID (If Any): ' . $item['beneficiary_id'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Participant Name: ' . $item['participant_name'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Gender: ' . ucfirst($item['gender']), 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Participant Age: ' . $item['age'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Participant Mobile: ' . $item['mobile'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Do you enjoy this Event? ' . ucfirst($item['enjoyment']), 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Trafficked Victim ' . ucfirst($item['victim']), 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Trafficked Victim Family Member ' . ucfirst($item['victim_family']), 0, 1, 'L');
        $devPdf->Cell(0, 0, 'What were the messages or issues delivered in the event? ', 0, 1, 'L');
        $devPdf->Cell(0, 10, '- ' . ucfirst($item['message'] . ' ' . $item['other_message']), 0, 1, 'L');
        $devPdf->Cell(0, 0, 'How do you intend to use these messages in your personal life? ', 0, 1, 'L');
        $devPdf->Cell(0, 10, '- ' . $item['use_message'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'What was mentioned in the event show that was not clear to you? ', 0, 1, 'L');
        $devPdf->Cell(0, 10, '- ' . $item['mentioned_event'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Additional comments (If Any) ', 0, 1, 'L');
        $devPdf->Cell(0, 10, '- ' . $item['additional_comments'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Quote ', 0, 1, 'L');
        $devPdf->Cell(0, 10, '- ' . $item['quote'], 0, 1, 'L');
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
