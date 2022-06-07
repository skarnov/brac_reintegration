<?php

$customer_id = $_GET['id'];

$args = array(
    'select_fields' => array(
        'full_name' => 'dev_customers.full_name',
        'customer_id' => 'dev_customers.customer_id',
        'customer_birthdate' => 'dev_customers.customer_birthdate',
        'nid_number' => 'dev_customers.nid_number',
        'birth_reg_number' => 'dev_customers.birth_reg_number',
        'customer_gender' => 'dev_customers.customer_gender',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'emergency_mobile' => 'dev_customers.emergency_mobile',
        'emergency_name' => 'dev_customers.emergency_name',
        'emergency_relation' => 'dev_customers.emergency_relation',
        'father_name' => 'dev_customers.father_name',
        'mother_name' => 'dev_customers.mother_name',
        'permanent_village' => 'dev_customers.permanent_village',
        'permanent_ward' => 'dev_customers.permanent_ward',
        'permanent_union' => 'dev_customers.permanent_union',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_house' => 'dev_customers.permanent_house',
        'pk_immediate_support_id' => 'dev_immediate_supports.pk_immediate_support_id',
        'fk_staff_id' => 'dev_immediate_supports.fk_staff_id',
        'fk_customer_id' => 'dev_immediate_supports.fk_customer_id',
        'immediate_support' => 'dev_immediate_supports.immediate_support',
        'support_date' => 'dev_immediate_supports.entry_date AS support_date',
        'arrival_place' => 'dev_immediate_supports.arrival_place',
        'selected_supports' => 'dev_initial_evaluation.selected_supports',
        'plan_date' => 'dev_reintegration_plan.plan_date',
        'reintegration_financial_service' => 'dev_reintegration_plan.reintegration_financial_service',
        'service_requested' => 'dev_reintegration_plan.service_requested',
        'other_service_requested' => 'dev_reintegration_plan.other_service_requested',
        'social_protection' => 'dev_reintegration_plan.social_protection',
        'security_measure' => 'dev_reintegration_plan.security_measure',
        'service_requested_note' => 'dev_reintegration_plan.service_requested_note',
        'first_meeting' => 'dev_psycho_supports.first_meeting',
        'problem_identified' => 'dev_psycho_supports.problem_identified',
        'other_problem_identified' => 'dev_psycho_supports.other_problem_identified',
        'problem_description' => 'dev_psycho_supports.problem_description',
        'initial_plan' => 'dev_psycho_supports.initial_plan',
        'family_counseling' => 'dev_psycho_supports.family_counseling',
        'session_place' => 'dev_psycho_supports.session_place',
        'session_number' => 'dev_psycho_supports.session_number',
        'session_duration' => 'dev_psycho_supports.session_duration',
        'other_requirements' => 'dev_psycho_supports.other_requirements',
        'reffer_to' => 'dev_psycho_supports.reffer_to',
        'referr_address' => 'dev_psycho_supports.referr_address',
        'contact_number' => 'dev_psycho_supports.contact_number',
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
        'is_certification_received' => 'dev_economic_training.is_certification_received',
        'economic_training_comment' => 'dev_economic_training.economic_training_comment',
        'referral_training_type' => 'dev_economic_training.referral_training_type',
        'other_referral_training_type' => 'dev_economic_training.other_referral_training_type',
        'referral_training_institution_name' => 'dev_economic_training.referral_training_institution_name',
        'referral_training_place' => 'dev_economic_training.referral_training_place',
        'referral_economic_training_start_date' => 'dev_economic_training.referral_economic_training_start_date',
        'referral_economic_training_end_date' => 'dev_economic_training.referral_economic_training_end_date',
        'referral_certification_received' => 'dev_economic_training.referral_certification_received',
        'referral_economic_training_comment' => 'dev_economic_training.referral_economic_training_comment',
        'financial_training_entry' => 'dev_financial_literacy.entry_date AS financial_training_entry',
        'financial_training_received' => 'dev_financial_literacy.financial_training_received',
        'financial_institution_name' => 'dev_financial_literacy.financial_institution_name',
        'financial_training_place' => 'dev_financial_literacy.financial_training_place',
        'financial_training_start_date' => 'dev_financial_literacy.financial_training_start_date',
        'financial_training_end_date' => 'dev_financial_literacy.financial_training_end_date',
        'financial_certification_received' => 'dev_financial_literacy.financial_certification_received',
        'financial_training_comment' => 'dev_financial_literacy.financial_training_comment',
        'social_support_entry_date' => 'dev_social_supports.social_support_entry_date',
        'support_referred' => 'dev_social_supports.support_referred',
        'other_support_referred' => 'dev_social_supports.other_support_referred',
        'other_social_protection' => 'dev_social_supports.other_social_protection',
        'medical_support_entry_date' => 'dev_social_supports.medical_support_entry_date',
        'medical_support_type' => 'dev_social_supports.medical_support_type',
        'medical_institution_name' => 'dev_social_supports.medical_institution_name',
        'treatment_allowance' => 'dev_social_supports.treatment_allowance',
        'treatment_allowance_date' => 'dev_social_supports.treatment_allowance_date',
        'treatment_allowance_comment' => 'dev_social_supports.treatment_allowance_comment',
        'present_income' => 'dev_economic_profile.present_income',
    ),
    'id' => $customer_id,
    'single' => true
);

$case_info = $this->get_cases($args);

$nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
$birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
$support_date = $case_info['support_date'] ? date('d-m-Y', strtotime($case_info['support_date'])) : 'N/A';
$plan_date = $case_info['plan_date'] ? date('d-m-Y', strtotime($case_info['plan_date'])) : 'N/A';
$first_meeting = $case_info['first_meeting'] ? date('d-m-Y', strtotime($case_info['first_meeting'])) : 'N/A';
$economic_inkind_date = $case_info['economic_inkind_date'] ? date('d-m-Y', strtotime($case_info['economic_inkind_date'])) : 'N/A';
$support_delivery_date = $case_info['support_delivery_date'] ? date('d-m-Y', strtotime($case_info['support_delivery_date'])) : 'N/A';
$economic_training_entry_date = $case_info['economic_training_entry_date'] ? date('d-m-Y', strtotime($case_info['economic_training_entry_date'])) : 'N/A';
$economic_training_start_date = $case_info['economic_training_start_date'] ? date('d-m-Y', strtotime($case_info['economic_training_start_date'])) : 'N/A';
$economic_training_end_date = $case_info['economic_training_end_date'] ? date('d-m-Y', strtotime($case_info['economic_training_end_date'])) : 'N/A';
$referral_economic_training_start_date = $case_info['referral_economic_training_start_date'] ? date('d-m-Y', strtotime($case_info['referral_economic_training_start_date'])) : 'N/A';
$referral_economic_training_end_date = $case_info['referral_economic_training_end_date'] ? date('d-m-Y', strtotime($case_info['referral_economic_training_end_date'])) : 'N/A';
$financial_training_entry_date = $case_info['financial_training_entry'] ? date('d-m-Y', strtotime($case_info['financial_training_entry'])) : 'N/A';
$financial_training_start_date = $case_info['financial_training_start_date'] ? date('d-m-Y', strtotime($case_info['financial_training_start_date'])) : 'N/A';
$financial_training_end_date = $case_info['financial_training_end_date'] ? date('d-m-Y', strtotime($case_info['financial_training_end_date'])) : 'N/A';
$social_support_entry_date = $case_info['social_support_entry_date'] ? date('d-m-Y', strtotime($case_info['social_support_entry_date'])) : 'N/A';
$medical_support_entry_date = $case_info['medical_support_entry_date'] ? date('d-m-Y', strtotime($case_info['medical_support_entry_date'])) : 'N/A';
$treatment_allowance_date = $case_info['treatment_allowance_date'] ? date('d-m-Y', strtotime($case_info['treatment_allowance_date'])) : 'N/A';

$psychosocial_sessions = $this->get_psychosocial_session(array('customer_id' => $customer_id));
$family_counselings = $this->get_family_counseling(array('customer_id' => $customer_id));
$psychosocial_completions = $this->get_psychosocial_completion(array('customer_id' => $customer_id));
$psychosocial_followups = $this->get_psychosocial_followup(array('customer_id' => $customer_id));
$economic_referrals = $this->get_economic_referrals(array('customer_id' => $customer_id));
$received_economic_referrals = $this->get_economic_referral_received(array('customer_id' => $customer_id));
$reviews = $this->get_case_review(array('customer_id' => $customer_id));

$reportTitle = 'Case Management Form';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 1: Personal Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Participant ID:', 1, 0);
$devPdf->Cell(135, 6, $case_info['customer_id'], 1, 1);

$devPdf->Cell(50, 6, 'Full Name:', 1, 0);
$devPdf->Cell(135, 6, $case_info['full_name'], 1, 1);

$devPdf->Cell(50, 6, 'NID Number:', 1, 0);
$devPdf->Cell(135, 6, $nid_number, 1, 1);

$devPdf->Cell(50, 6, 'Birth Registration Number:', 1, 0);
$devPdf->Cell(135, 6, $birth_reg_number, 1, 1);

$devPdf->Cell(50, 6, 'Date of Birth:', 1, 0);
$devPdf->Cell(135, 6, date('d-m-Y', strtotime($case_info['customer_birthdate'])), 1, 1);

$devPdf->Cell(50, 6, 'Gender:', 1, 0);
$devPdf->Cell(135, 6, ucfirst($case_info['customer_gender']), 1, 1);

$devPdf->Cell(50, 6, 'Mobile No:', 1, 0);
$devPdf->Cell(135, 6, $case_info['customer_mobile'], 1, 1);

$devPdf->Cell(50, 6, 'Emergency Mobile No:', 1, 0);
$devPdf->Cell(135, 6, $case_info['emergency_mobile'], 1, 1);

$devPdf->Cell(50, 6, 'Name of That Person:', 1, 0);
$devPdf->Cell(135, 6, $case_info['emergency_name'], 1, 1);

$devPdf->Cell(50, 6, 'Relation with Participant:', 1, 0);
$devPdf->Cell(135, 6, $case_info['emergency_relation'], 1, 1);

$devPdf->Cell(50, 6, 'Father\'s Name:', 1, 0);
$devPdf->Cell(135, 6, $case_info['father_name'], 1, 1);

$devPdf->Cell(50, 6, 'Mother\'s Name:', 1, 0);
$devPdf->Cell(135, 6, $case_info['mother_name'], 1, 1);

$devPdf->Cell(50, 6, 'Village:', 1, 0);
$devPdf->Cell(135, 6, $case_info['permanent_village'], 1, 1);

$devPdf->Cell(50, 6, 'Ward No:', 1, 0);
$devPdf->Cell(135, 6, $case_info['permanent_ward'], 1, 1);

$devPdf->Cell(50, 6, 'Union:', 1, 0);
$devPdf->Cell(135, 6, ucfirst($case_info['permanent_union']), 1, 1);

$devPdf->Cell(50, 6, 'Upazilla:', 1, 0);
$devPdf->Cell(135, 6, ucfirst($case_info['permanent_sub_district']), 1, 1);

$devPdf->Cell(50, 6, 'District:', 1, 0);
$devPdf->Cell(135, 6, ucfirst($case_info['permanent_district']), 1, 1);

$devPdf->Multicell(185, 6, 'Present Address of Beneficiary: ' . $case_info['permanent_house'], 1, 1);
$devPdf->Ln(5);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 2: Immediate Support Provided After Arrival', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Support Date: ', 1, 0);
$devPdf->Cell(135, 6, $support_date, 1, 1);

$devPdf->Cell(50, 6, 'Arrival Place: ', 1, 0);
$devPdf->Cell(135, 6, $case_info['arrival_place'], 1, 1);

$devPdf->Multicell(185, 6, 'Immediate Support Services Received: ' . $case_info['immediate_support'], 1, 1);
$devPdf->Ln(5);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3: Preferred Services and Reintegration Plan', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(50, 6, 'Plan Date: ', 1, 0);
$devPdf->Cell(135, 6, $plan_date, 1, 1);

$devPdf->Multicell(185, 6, 'Type of Services Requested: ' . $case_info['service_requested'] . ' ' . $case_info['other_service_requested'], 1, 1);
$devPdf->Multicell(185, 6, 'Social Protection Schemes: ' . $case_info['social_protection'], 1, 1);
$devPdf->Multicell(185, 6, 'Special Security Measures: ' . $case_info['security_measure'], 1, 1);
$devPdf->Multicell(185, 6, 'Note: ' . $case_info['service_requested_note'], 1, 1);
$devPdf->Ln(5);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4: Psychosocial Reintegration Support Services', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Date of First Meeting: ', 1, 0);
$devPdf->Cell(140, 6, $first_meeting, 1, 1);

$devPdf->Multicell(185, 6, 'Problems Identified: ' . $case_info['problem_identified'], 1, 1);
$devPdf->Multicell(185, 6, 'Problems Description: ' . $case_info['problem_description'], 1, 1);
$devPdf->Multicell(185, 6, 'Initial Plan: ' . $case_info['initial_plan'], 1, 1);

$devPdf->Cell(40, 6, 'Place of Session: ', 1, 0);
$devPdf->Cell(145, 6, ucfirst($case_info['session_place']), 1, 1);

$devPdf->Cell(60, 6, 'Number of Sessions (Estimate): ', 1, 0);
$devPdf->Cell(125, 6, $case_info['session_number'], 1, 1);

$devPdf->Cell(50, 6, 'Duration of Session: ', 1, 0);
$devPdf->Cell(135, 6, $case_info['session_duration'], 1, 1);

$devPdf->Multicell(185, 6, 'Other Requirements: ' . $case_info['other_requirements'], 1, 1);

$devPdf->Cell(30, 6, 'Referred To: ', 1, 0);
$devPdf->Cell(155, 6, $case_info['reffer_to'], 1, 1);

$devPdf->Multicell(185, 6, 'Referred Address of Organization/Individual: ' . $case_info['referr_address'], 1, 1);

$devPdf->Cell(50, 6, 'Referred Phone Number: ', 1, 0);
$devPdf->Cell(135, 6, $case_info['contact_number'], 1, 1);

$devPdf->Multicell(185, 6, 'Reason for Referral: ' . $case_info['reason_for_reffer'] . ' ' . $case_info['other_reason_for_reffer'], 1, 1);
$devPdf->Ln(5);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4.1: Psychosocial Reintegration Session Activities', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_sessions['data'] != NULL) {
    $psychosocial_session_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 8),
        array('text' => 'Time', 'align' => 'L', 'width' => 8),
        array('text' => 'Next Date', 'align' => 'L', 'width' => 8),
        array('text' => 'Activities', 'align' => 'L', 'width' => 16),
        array('text' => 'Comments', 'align' => 'L', 'width' => 60),
    );

    foreach ($psychosocial_sessions['data'] as $i => $item) {
        $psychosocial_session_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['next_date'] ? date('d-m-Y', strtotime($item['next_date'])) : 'N/A',
            $item['activities_description'],
            $item['session_comments'],
        );
    }

    $devPdf->resetOptions();
    $devPdf->setOption('headers', $psychosocial_session_header);
    $devPdf->setOption('data', $psychosocial_session_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4.2: Family Counseling Session', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($family_counselings['data'] != NULL) {
    $family_counseling_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 8),
        array('text' => 'Time', 'align' => 'L', 'width' => 8),
        array('text' => 'Place', 'align' => 'L', 'width' => 8),
        array('text' => 'Counseled Men', 'align' => 'L', 'width' => 12),
        array('text' => 'Counseled Women', 'align' => 'L', 'width' => 12),
        array('text' => 'Total Counseled', 'align' => 'L', 'width' => 12),
        array('text' => 'Comments', 'align' => 'L', 'width' => 40),
    );

    foreach ($family_counselings['data'] as $i => $item) {
        $family_counseling_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['session_place'],
            $item['male_counseled'],
            $item['female_counseled'],
            $item['members_counseled'],
            $item['session_comments'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $family_counseling_header);
    $devPdf->setOption('data', $family_counseling_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4.3: Session Completion Status', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_completions['data'] != NULL) {
    foreach ($psychosocial_completions['data'] as $i => $item) {
        $devPdf->Cell(50, 6, 'Date: ', 1, 0);
        $devPdf->Cell(135, 6, $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A', 1, 1);

        $devPdf->Cell(50, 6, 'Session Completion: ', 1, 0);
        $devPdf->Cell(135, 6, ucfirst($item['is_completed']), 1, 1);

        $devPdf->Multicell(185, 6, 'Review of Counseling Session: ' . $item['review_session'], 1, 1);
        $devPdf->Multicell(185, 6, 'Comments of The Client: ' . $item['client_comments'], 1, 1);
        $devPdf->Multicell(185, 6, 'Comments of The Counselor: ' . $item['counsellor_comments'], 1, 1);
        $devPdf->Multicell(185, 6, 'Final Evaluation: ' . $item['final_evaluation'], 1, 1);

        $devPdf->Cell(70, 6, 'Required Session After Completion: ', 1, 0);
        $devPdf->Cell(115, 6, ucfirst($item['required_session']), 1, 1);

        $devPdf->Ln(5);
    }
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4.4: Psychosocial Reintegration (Followup)', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_followups['data'] != NULL) {
    $psychosocial_followup_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 15),
        array('text' => 'Time', 'align' => 'L', 'width' => 15),
        array('text' => 'Comments', 'align' => 'L', 'width' => 70),
    );

    foreach ($psychosocial_followups['data'] as $i => $item) {
        $psychosocial_followup_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['followup_comments'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $psychosocial_followup_header);
    $devPdf->setOption('data', $psychosocial_followup_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5: Economic Reintegration', 0, 1, 'C');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5.1: In Kind Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Entry Date: ', 1, 0);
$devPdf->Cell(140, 6, $economic_inkind_date, 1, 1);

$devPdf->Cell(45, 6, 'Type of In Kind Support: ', 1, 0);
$devPdf->Cell(140, 6, ucfirst($case_info['in_kind_type']), 1, 1);

$devPdf->SetFont('Times', 'B', 12);
$devPdf->Cell(185, 6, 'Support Received From - ', 1, 1);

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Organization Name: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['organization_name'], 1, 1);

$devPdf->Cell(45, 6, 'Support Delivery Date: ', 1, 0);
$devPdf->Cell(140, 6, $support_delivery_date, 1, 1);

$devPdf->Multicell(185, 6, 'Support Type: ' . $case_info['inkind_project'] . ' ' . $case_info['other_inkind_project'], 1, 1);

$devPdf->Cell(45, 6, 'Support Amount: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['support_amount'] . ' BDT', 1, 1);

$devPdf->Multicell(185, 6, 'Description of Support Delivered: ' . $case_info['economic_support_delivered'], 1, 1);

$devPdf->Multicell(185, 6, 'Type of Business: ' . $case_info['business_type'] . '' . $case_info['other_business_type'], 1, 1);

$devPdf->Multicell(185, 6, 'Comments: ' . $case_info['economic_other_comments'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5.2: Training', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Entry Date: ', 1, 0);
$devPdf->Cell(140, 6, $economic_training_entry_date, 1, 1);

$devPdf->Cell(45, 6, 'Training Type: ', 1, 0);
$devPdf->Cell(140, 6, ucfirst($case_info['training_type']), 1, 1);

if ($case_info['direct_training_type']):
    $devPdf->SetFont('Times', 'B', 12);
    $devPdf->Cell(185, 6, 'Direct Training - ', 1, 1);

    $devPdf->SetFont('Times', '', 12);

    $devPdf->Multicell(185, 6, 'Direct Training Type: ' . $case_info['direct_training_type'] . ' ' . $case_info['other_direct_training_type'], 1, 1);

    $devPdf->Cell(45, 6, 'Institution Name: ', 1, 0);
    $devPdf->Cell(140, 6, $case_info['training_institution_name'], 1, 1);

    $devPdf->Cell(45, 6, 'Place of Training: ', 1, 0);
    $devPdf->Cell(140, 6, $case_info['training_place'], 1, 1);

    $devPdf->Cell(45, 6, 'Training Start Date: ', 1, 0);
    $devPdf->Cell(140, 6, $economic_training_start_date, 1, 1);

    $devPdf->Cell(45, 6, 'Training End Date: ', 1, 0);
    $devPdf->Cell(140, 6, $economic_training_end_date, 1, 1);

    $devPdf->Cell(45, 6, 'Certificate Received: ', 1, 0);
    $devPdf->Cell(140, 6, ucfirst($case_info['is_certification_received']), 1, 1);

    $devPdf->Multicell(185, 6, 'Comment: ' . $case_info['economic_training_comment'], 1, 1);
endif;

if ($case_info['referral_training_type']):
    $devPdf->SetFont('Times', 'B', 12);
    $devPdf->Cell(185, 6, 'Referral Training - ', 1, 1);

    $devPdf->SetFont('Times', '', 12);

    $devPdf->Multicell(185, 6, 'Referral Training Type: ' . $case_info['referral_training_type'] . ' ' . $case_info['other_referral_training_type'], 1, 1);

    $devPdf->Cell(45, 6, 'Institution Name: ', 1, 0);
    $devPdf->Cell(140, 6, $case_info['referral_training_institution_name'], 1, 1);

    $devPdf->Cell(45, 6, 'Place of Training: ', 1, 0);
    $devPdf->Cell(140, 6, $case_info['referral_training_place'], 1, 1);

    $devPdf->Cell(45, 6, 'Training Start Date: ', 1, 0);
    $devPdf->Cell(140, 6, $referral_economic_training_start_date, 1, 1);

    $devPdf->Cell(45, 6, 'Training End Date: ', 1, 0);
    $devPdf->Cell(140, 6, $referral_economic_training_end_date, 1, 1);

    $devPdf->Cell(45, 6, 'Certificate Received: ', 1, 0);
    $devPdf->Cell(140, 6, ucfirst($case_info['referral_certification_received']), 1, 1);

    $devPdf->Multicell(185, 6, 'Comment: ' . $case_info['referral_economic_training_comment'], 1, 1);
endif;

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5.3: Financial Literacy & Remittance Management Training', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Entry Date: ', 1, 0);
$devPdf->Cell(140, 6, $financial_training_entry_date, 1, 1);

$devPdf->Cell(65, 6, 'Who has received the training? ', 1, 0);
$devPdf->Cell(120, 6, $case_info['financial_training_received'], 1, 1);

$devPdf->Cell(45, 6, 'Institution Name: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['financial_institution_name'], 1, 1);

$devPdf->Cell(45, 6, 'Place of Training: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['financial_training_place'], 1, 1);

$devPdf->Cell(45, 6, 'Training Start Date: ', 1, 0);
$devPdf->Cell(140, 6, $financial_training_start_date, 1, 1);

$devPdf->Cell(45, 6, 'Training End Date: ', 1, 0);
$devPdf->Cell(140, 6, $financial_training_end_date, 1, 1);

$devPdf->Cell(45, 6, 'Certificate Received: ', 1, 0);
$devPdf->Cell(140, 6, ucfirst($case_info['financial_certification_received']), 1, 1);

$devPdf->Multicell(185, 6, 'Comment: ' . $case_info['financial_training_comment'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5.4: Referral and Linkage Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($economic_referrals['data'] != NULL) {
    $economic_referrals_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 15),
        array('text' => 'Referred For', 'align' => 'L', 'width' => 60),
        array('text' => 'Referred Organization', 'align' => 'L', 'width' => 25),
    );

    foreach ($economic_referrals['data'] as $i => $item) {
        $economic_referrals_reportData[] = array(
            $item['referral_date'] ? date('d-m-Y', strtotime($item['referral_date'])) : 'N/A',
            $item['referred_for'] . ' ' . $item['other_referred_for'],
            $item['referred_organization'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $economic_referrals_header);
    $devPdf->setOption('data', $economic_referrals_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5.5: Referral Received and Linkage Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($economic_referrals['data'] != NULL) {
    $economic_referrals_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 15),
        array('text' => 'Referred For', 'align' => 'L', 'width' => 60),
        array('text' => 'Service Provider', 'align' => 'L', 'width' => 25),
    );

    foreach ($economic_referrals['data'] as $i => $item) {
        $economic_referrals_reportData[] = array(
            $item['received_date'] ? date('d-m-Y', strtotime($item['received_date'])) : 'N/A',
            $item['referred_for'] . ' ' . $item['other_referred_for'],
            $item['referral_service_provider'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $economic_referrals_header);
    $devPdf->setOption('data', $economic_referrals_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 6: Social Reintegration Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Entry Date: ', 1, 0);
$devPdf->Cell(140, 6, $social_support_entry_date, 1, 1);

$devPdf->Multicell(185, 6, 'Types of Support Referred for: ' . $case_info['support_referred'] . ' ' . $case_info['other_support_referred'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 6.1: Medical Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Entry Date: ', 1, 0);
$devPdf->Cell(140, 6, $medical_support_entry_date, 1, 1);

$devPdf->Cell(45, 6, 'Medical Support Type: ', 1, 0);
$devPdf->Cell(140, 6, ucfirst($case_info['medical_support_type']), 1, 1);

$devPdf->SetFont('Times', 'B', 12);
$devPdf->Cell(185, 6, 'Type of Medical Issue - ', 1, 1);

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(45, 6, 'Institution Name: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['medical_institution_name'], 1, 1);

$devPdf->Cell(45, 6, 'Treatment Allowance: ', 1, 0);
$devPdf->Cell(140, 6, $case_info['treatment_allowance'], 1, 1);

$devPdf->Cell(45, 6, 'Date: ', 1, 0);
$devPdf->Cell(140, 6, $treatment_allowance_date, 1, 1);

$devPdf->Multicell(185, 6, 'Comment: ' . $case_info['treatment_allowance_comment'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 7: Review and Follow-Up', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($reviews['data'] != NULL) {
    $count = 1;
    foreach ($reviews['data'] as $i => $item) {

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(185, 6, 'SL:' . $count, 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Date: ', 1, 0);
        $devPdf->Cell(140, 6, $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : '', 1, 1);

        $devPdf->Cell(45, 6, 'Present Support Status: ', 1, 0);
        $devPdf->Cell(140, 6, ucfirst($item['support_status']), 1, 1);

        $devPdf->Cell(45, 6, 'Type of support received: ', 1, 0);
        $devPdf->Cell(140, 6, ucfirst($item['support_received']), 1, 1);

        $devPdf->Multicell(185, 6, 'Confirmed Services Received after 3 Months: ' . $item['confirm_services'], 1, 1);

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(185, 6, 'Status of Case after Receiving the Services: ', 1, 1);
        $devPdf->Cell(185, 6, 'Psychosocial Support - ', 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Visit Date: ', 1, 0);
        $devPdf->Cell(140, 6, $item['psychosocial_date'] ? date('d-m-Y', strtotime($item['psychosocial_date'])) : '', 1, 1);

        $devPdf->Multicell(185, 6, 'Problem: ' . $item['psychosocial_problem'], 1, 1);
        $devPdf->Multicell(185, 6, 'Action Taken: ' . $item['psychosocial_action'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of the participant: ' . $item['psychosocial_participant'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of the counselor: ' . $item['psychosocial_counselor'], 1, 1);

        $devPdf->SetFont('Times', 'B', 12);

        $devPdf->Cell(185, 6, 'Economic Support - ', 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Visit Date: ', 1, 0);
        $devPdf->Cell(140, 6, $item['economic_date'] ? date('d-m-Y', strtotime($item['economic_date'])) : '', 1, 1);

        $devPdf->Cell(65, 6, 'Monthly Average Income: ', 1, 0);
        $devPdf->Cell(120, 6, $item['monthly_average_income'] . ' BDT', 1, 1);

        $devPdf->Multicell(185, 6, 'Challenges: ' . $item['economic_challenges'], 1, 1);

        $devPdf->Multicell(185, 6, 'Action Taken: ' . $item['economic_action'], 1, 1);
        $devPdf->Multicell(185, 6, 'Significant changes: ' . $item['significant_changes'], 1, 1);

        $devPdf->Multicell(185, 6, 'Remark of the participant: ' . $item['economic_participant'], 1, 1);
        $devPdf->Multicell(185, 6, 'Comment of BRAC Officer Responsible For Participant: ' . $item['economic_officer'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of RSC Manager: ' . $item['economic_manager'], 1, 1);

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(185, 6, 'Social Support - ', 1, 1);

        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(45, 6, 'Visit Date: ', 1, 0);
        $devPdf->Cell(140, 6, $item['social_date'] ? date('d-m-Y', strtotime($item['social_date'])) : '', 1, 1);

        $devPdf->Multicell(185, 6, 'Challenges: ' . $item['social_challenges'], 1, 1);
        $devPdf->Multicell(185, 6, 'Action Taken: ' . $item['social_action'], 1, 1);
        $devPdf->Multicell(185, 6, 'Significant changes: ' . $item['social_changes'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of the participant: ' . $item['social_participant'], 1, 1);
        $devPdf->Multicell(185, 6, 'Comment of BRAC Officer Responsible For Participant: ' . $item['social_officer'], 1, 1);
        $devPdf->Multicell(185, 6, 'Remark of RSC Manager: ' . $item['social_manager'], 1, 1);

        $devPdf->Ln(5);
        $count++;
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
