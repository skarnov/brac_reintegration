<?php

$customer_id = $_GET['id'];
$profile = $this->get_customers(array('customer_id' => $customer_id, 'single' => true));

$migration_medias = json_decode($profile['migration_medias']);
$nid_number = $profile['nid_number'] ? $profile['nid_number'] : 'N/A';
$birth_reg_number = $profile['birth_reg_number'] ? $profile['birth_reg_number'] : 'N/A';
$customer_spouse = $profile['customer_spouse'] ? $profile['customer_spouse'] : 'N/A';

if ($profile['educational_qualification'] == 'illiterate'):
    $educational_qualification = 'Illiterate';
elseif ($profile['educational_qualification'] == 'sign'):
    $educational_qualification = 'Can Sign only';
elseif ($profile['educational_qualification'] == 'psc'):
    $educational_qualification = 'Primary education (Passed Grade 5)';
elseif ($profile['educational_qualification'] == 'not_psc'):
    $educational_qualification = 'Did not complete primary education';
elseif ($profile['educational_qualification'] == 'jsc'):
    $educational_qualification = 'Completed JSC (Passed Grade 8) or equivalent';
elseif ($profile['educational_qualification'] == 'ssc'):
    $educational_qualification = 'Completed School Secondary Certificate or equivalent';
elseif ($profile['educational_qualification'] == 'hsc'):
    $educational_qualification = 'Higher Secondary Certificate/Diploma/ equivalent';
elseif ($profile['educational_qualification'] == 'bachelor'):
    $educational_qualification = 'Bachelor’s degree or equivalent';
elseif ($profile['educational_qualification'] == 'master'):
    $educational_qualification = 'Masters or Equivalent';
elseif ($profile['educational_qualification'] == 'professional_education'):
    $educational_qualification = 'Completed Professional education';
elseif ($profile['educational_qualification'] == 'general_education'):
    $educational_qualification = 'Completed general Education';
else:
    $educational_qualification = $profile['educational_qualification'];
endif;

if ($profile['current_residence_ownership'] == 'own'):
    $current_residence_ownership = 'Own';
elseif ($profile['educational_qualification'] == 'rental'):
    $current_residence_ownership = 'Rental';
elseif ($profile['educational_qualification'] == 'without_paying'):
    $current_residence_ownership = 'Live without paying';
elseif ($profile['educational_qualification'] == 'khas_land'):
    $current_residence_ownership = 'Khas land';
else:
    $current_residence_ownership = $profile['current_residence_ownership'];
endif;

if ($profile['current_residence_type'] == 'raw_house'):
    $current_residence_type = 'Raw house (wall made of mud/straw, roof made of tin jute stick/ pampas grass/ khar/ leaves)';
elseif ($profile['current_residence_type'] == 'pucca'):
    $current_residence_type = 'Pucca (wall, floor and roof of the house made of concrete)';
elseif ($profile['current_residence_type'] == 'live'):
    $current_residence_type = 'Live Semi-pucca (roof made of tin, wall or floor made of concrete)';
elseif ($profile['current_residence_type'] == 'tin'):
    $current_residence_type = 'Tin (wall, and roof of the house made of tin)';
else:
    $current_residence_type = $profile['current_residence_type'];
endif;

$family_members = $profile['male_household_member'] + $profile['female_household_member'] + $profile['boy_household_member'] + $profile['girl_household_member'];
$disability_type = $profile['disability_type'] ? $profile['disability_type'] : 'N/A';

$reportTitle = 'Beneficiary Profile';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 7, 'Section 1: Personal information', 0, 1, 'C');
$devPdf->Ln(5);

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(70, 6, 'Beneficiary ID/Reference number: ', 1, 0);
$devPdf->Cell(115, 6, $profile['customer_id'], 1, 1);

$devPdf->Cell(50, 6, 'Project Name: ', 1, 0);
$devPdf->Cell(135, 6, $profile['project_short_name'], 1, 1);

$devPdf->Cell(50, 6, 'Full Name: ', 1, 0);
$devPdf->Cell(135, 6, $profile['full_name'], 1, 1);

$devPdf->Cell(50, 6, 'NID Number: ', 1, 0);
$devPdf->Cell(135, 6, $nid_number, 1, 1);

$devPdf->Cell(50, 6, 'Birth Registration Number: ', 1, 0);
$devPdf->Cell(135, 6, $birth_reg_number, 1, 1);

$devPdf->Cell(50, 6, 'Father\'s Name: ', 1, 0);
$devPdf->Cell(135, 6, $profile['father_name'], 1, 1);

$devPdf->Cell(50, 6, 'Mother\'s Name: ', 1, 0);
$devPdf->Cell(135, 6, $profile['mother_name'], 1, 1);

$devPdf->Cell(50, 6, 'Date of Birth: ', 1, 0);
$devPdf->Cell(135, 6, date('d-m-Y', strtotime($profile['customer_birthdate'])), 1, 1);

$devPdf->Cell(50, 6, 'Gender: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['customer_gender']), 1, 1);

$devPdf->Cell(50, 6, 'Marital Status: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['marital_status']), 1, 1);

$devPdf->Cell(50, 6, 'Spouse Name: ', 1, 0);
$devPdf->Cell(135, 6, $customer_spouse, 1, 1);

$devPdf->Cell(50, 6, 'Mobile No: ', 1, 0);
$devPdf->Cell(135, 6, $profile['customer_mobile'], 1, 1);

$devPdf->Cell(50, 6, 'Emergency Mobile No: ', 1, 0);
$devPdf->Cell(135, 6, $profile['emergency_mobile'], 1, 1);

$devPdf->Cell(50, 6, 'Name of That Person: ', 1, 0);
$devPdf->Cell(135, 6, $profile['emergency_name'], 1, 1);

$devPdf->Cell(50, 6, 'Relation with Participant: ', 1, 0);
$devPdf->Cell(135, 6, $profile['emergency_relation'], 1, 1);

$devPdf->Cell(50, 6, 'Village: ', 1, 0);
$devPdf->Cell(135, 6, $profile['permanent_village'], 1, 1);

$devPdf->Cell(50, 6, 'Ward No: ', 1, 0);
$devPdf->Cell(135, 6, $profile['permanent_ward'], 1, 1);

$devPdf->Cell(50, 6, 'Union: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['permanent_union']), 1, 1);

$devPdf->Cell(50, 6, 'Upazilla: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['permanent_sub_district']), 1, 1);

$devPdf->Cell(50, 6, 'District: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['permanent_district']), 1, 1);

$devPdf->Multicell(185, 6, 'Present Address of Beneficiary: ' . $profile['permanent_house'], 1, 1);

$devPdf->Cell(50, 6, 'Educational Qualification: ', 1, 0);
$devPdf->Cell(135, 6, $educational_qualification, 1, 1);

$devPdf->Cell(100, 6, 'Number of Accompany/ Number of Family Member: ', 1, 0);
$devPdf->Cell(85, 6, $family_members, 1, 1);

$devPdf->Cell(50, 6, 'Boy (<18): ', 1, 0);
$devPdf->Cell(135, 6, $profile['boy_household_member'], 1, 1);

$devPdf->Cell(50, 6, 'Girl (<18): ', 1, 0);
$devPdf->Cell(135, 6, $profile['girl_household_member'], 1, 1);

$devPdf->Cell(50, 6, 'Men (>=18): ', 1, 0);
$devPdf->Cell(135, 6, $profile['male_household_member'], 1, 1);

$devPdf->Cell(50, 6, 'Women (>=18): ', 1, 0);
$devPdf->Cell(135, 6, $profile['female_household_member'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 2: Trafficking/ Migration history', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(80, 6, 'Transit/ Route of Migration/ Trafficking: ', 1, 0);
$devPdf->Cell(105, 6, $profile['left_port'], 1, 1);

$devPdf->Cell(50, 6, 'Desired Destination: ', 1, 0);
$devPdf->Cell(135, 6, $profile['preferred_country'], 1, 1);

$devPdf->Cell(50, 6, 'Final Destination: ', 1, 0);
$devPdf->Cell(135, 6, $profile['final_destination'], 1, 1);

$devPdf->Cell(50, 6, 'Type of Channels: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['migration_type']), 1, 1);

$devPdf->Cell(50, 6, 'Type of Visa: ', 1, 0);
$devPdf->Cell(135, 6, ucfirst($profile['visa_type']), 1, 1);

$devPdf->Cell(0, 10, 'Media of Departure Information: ', 0, 1, 'L');

$devPdf->Cell(50, 6, 'Name: ', 1, 0);
$devPdf->Cell(135, 6, $migration_medias->departure_media ? $migration_medias->departure_media : $profile['departure_media'], 1, 1);

$devPdf->Cell(50, 6, 'Relation: ', 1, 0);
$devPdf->Cell(135, 6, $migration_medias->media_relation ? $migration_medias->media_relation : $profile['media_relation'], 1, 1);

$devPdf->Cell(50, 6, 'Address: ', 1, 0);
$devPdf->Cell(135, 6, $migration_medias->media_address ? $migration_medias->media_address : $profile['media_address'], 1, 1);

$devPdf->Cell(50, 6, 'Passport No: ', 1, 0);
$devPdf->Cell(135, 6, $profile['passport_number'], 1, 1);

$devPdf->Cell(70, 6, 'Date of Departure From Bangladesh: ', 1, 0);
$devPdf->Cell(115, 6, date('d-m-Y', strtotime($profile['departure_date'])), 1, 1);

$devPdf->Cell(70, 6, 'Date of Return to Bangladesh: ', 1, 0);
$devPdf->Cell(115, 6, date('d-m-Y', strtotime($profile['return_date'])), 1, 1);

$devPdf->Cell(80, 6, 'Age (When Came Back in Bangladesh): ', 1, 0);
$devPdf->Cell(105, 6, $profile['returned_age'], 1, 1);

$devPdf->Cell(80, 6, 'Duration of Stay Abroad (Months): ', 1, 0);
$devPdf->Cell(105, 6, $profile['migration_duration'], 1, 1);

$devPdf->Cell(70, 6, 'Occupation in overseas country: ', 1, 0);
$devPdf->Cell(115, 6, $profile['migration_occupation'], 1, 1);

$devPdf->Cell(70, 6, 'Income: (If applicable): ', 1, 0);
$devPdf->Cell(115, 6, $profile['earned_money'], 1, 1);

$devPdf->Multicell(185, 6, 'Reasons for Migration: ' . ucfirst($profile['migration_reasons']) . ' ' . ucfirst($profile['other_migration_reason']), 1, 1);
$devPdf->Multicell(185, 6, 'Reasons for returning to Bangladesh: ' . ucfirst($profile['destination_country_leave_reason']) . ' ' . ucfirst($profile['other_destination_country_leave_reason']), 1, 1);

$devPdf->Cell(170, 6, 'False promises about a job prior to arrival at workplace abroad: ', 1, 0);
$devPdf->Cell(15, 6, ucfirst($profile['is_cheated']), 1, 1);

$devPdf->Cell(170, 6, 'Forced to perform work or other activities against your will, after the departure from Bangladesh? ', 1, 0);
$devPdf->Cell(15, 6, ucfirst($profile['forced_work']), 1, 1);

$devPdf->Cell(170, 6, 'Experienced excessive working hours (more than 40 hours a week): ', 1, 0);
$devPdf->Cell(15, 6, ucfirst($profile['excessive_work']), 1, 1);

$devPdf->Cell(170, 6, 'Deductions from salary for recruitment fees at workplace: ', 1, 0);
$devPdf->Cell(15, 6, ucfirst($profile['is_money_deducted']), 1, 1);

$devPdf->Cell(175, 6, 'Denied freedom of movement during or between work shifts after your departure from Bangladesh? ', 1, 0);
$devPdf->Cell(10, 6, ucfirst($profile['is_movement_limitation']), 1, 1);

$devPdf->Multicell(185, 6, 'Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation? ' . ucfirst($profile['employer_threatened']), 1, 1);
$devPdf->Multicell(185, 6, 'Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh? ' . ucfirst($profile['is_kept_document']), 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3: Socio Economic Profile', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(70, 6, 'Any Property/Wealth: ', 1, 0);
$devPdf->Cell(115, 6, $profile['property_name'], 1, 1);

$devPdf->Cell(70, 6, 'Any Property/Wealth Value: ', 1, 0);
$devPdf->Cell(115, 6, $profile['property_value'], 1, 1);

$devPdf->Cell(70, 6, 'Main Occupation (Before Trafficking): ', 1, 0);
$devPdf->Cell(115, 6, $profile['pre_occupation'], 1, 1);

$devPdf->Cell(70, 6, 'Main Occupation (After Return): ', 1, 0);
$devPdf->Cell(115, 6, $profile['present_occupation'], 1, 1);

$devPdf->Cell(100, 6, 'Monthly Income of Survivor After Return (In BDT): ', 1, 0);
$devPdf->Cell(85, 6, $profile['present_income'], 1, 1);

$devPdf->Cell(70, 6, 'Last Month Income in BDT: ', 1, 0);
$devPdf->Cell(115, 6, $profile['returnee_income_source'], 1, 1);

$devPdf->Cell(70, 6, 'Source of Income: ', 1, 0);
$devPdf->Cell(115, 6, $profile['income_source'], 1, 1);

$devPdf->Cell(70, 6, 'Total Family Income (Last Month): ', 1, 0);
$devPdf->Cell(115, 6, $profile['family_income'], 1, 1);

$devPdf->Cell(70, 6, 'Savings (BDT): ', 1, 0);
$devPdf->Cell(115, 6, $profile['personal_savings'], 1, 1);

$devPdf->Cell(70, 6, 'Loan Amount (BDT): ', 1, 0);
$devPdf->Cell(115, 6, $profile['personal_debt'], 1, 1);

$devPdf->Cell(70, 6, 'Ownership of House: ', 1, 0);
$devPdf->Cell(115, 6, ucfirst($current_residence_ownership), 1, 1);

$devPdf->Multicell(185, 6, 'Type of house: ' . $current_residence_type, 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4: Information on Income Generating Activities(IGA) Skills', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(70, 6, 'Any IGA Skills?  ', 1, 0);
$devPdf->Cell(115, 6, ucfirst($profile['have_earner_skill']), 1, 1);

$devPdf->Multicell(185, 6, 'IGA Skills: ' . $profile['have_skills'] . ' ' . $profile['other_have_skills'] . ' ' . $profile['vocational_skill'] . ' ' . $profile['handicraft_skill'], 1, 1);

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5: Vulnerability Assessment', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

$devPdf->Cell(70, 6, 'Do You Have Any Disability?  ', 1, 0);
$devPdf->Cell(115, 6, ucfirst($profile['is_physically_challenged']), 1, 1);

$devPdf->Multicell(185, 6, 'Type of Disability: ' . $disability_type, 1, 1);

$devPdf->Cell(70, 6, 'Any Chronic Disease?  ', 1, 0);
$devPdf->Cell(115, 6, ucfirst($profile['having_chronic_disease']), 1, 1);

$devPdf->Multicell(185, 6, 'Type of Disease: ' . $profile['disease_type'] . ' ' . $profile['other_disease_type'], 1, 1);

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');