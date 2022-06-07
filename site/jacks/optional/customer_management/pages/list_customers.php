<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_passport = $_GET['passport'] ? $_GET['passport'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'pk_customer_id' => 'dev_customers.pk_customer_id',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'project_short_name' => 'dev_projects.project_short_name',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'passport_number' => 'dev_customers.passport_number',
        'permanent_division' => 'dev_customers.permanent_division',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'permanent_union' => 'dev_customers.permanent_union',
        'customer_status' => 'dev_customers.customer_status'
    ),
    'id' => $filter_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'passport' => $filter_passport,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'project' => $filter_project,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_customer_id',
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

$customers = $this->get_customers($args);
$pagination = pagination($customers['total'], $per_page_items, $start);

$manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18";
$boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18";
$womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18";
$girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18";

if ($filter_division):
    $manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18"
            . " AND permanent_division ='$filter_division'";
    $boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18"
            . " AND permanent_division ='$filter_division'";
    $womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18"
            . " AND permanent_division ='$filter_division'";
    $girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18"
            . " AND permanent_division ='$filter_division'";
endif;

if ($filter_district):
    $manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district'";
    $boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district'";
    $womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district'";
    $girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district'";
endif;

if ($filter_sub_district):
    $manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district'";
    $boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district'";
    $womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district'";
    $girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district'";
endif;

if ($filter_union):
    $manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district' AND permanent_union = '$filter_union'";
    $boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district' AND permanent_union = '$filter_union'";
    $womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district' AND permanent_union = '$filter_union'";
    $girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18"
            . " AND permanent_division ='$filter_division' AND permanent_district = '$filter_district' AND permanent_sub_district = '$filter_sub_district' AND permanent_union = '$filter_union'";
endif;

$manCustomer = $devdb->get_row($manCustomer)['manCustomer'];
$boyCustomer = $devdb->get_row($boyCustomer)['boyCustomer'];
$womanCustomer = $devdb->get_row($womanCustomer)['womanCustomer'];
$girlCustomer = $devdb->get_row($girlCustomer)['girlCustomer'];

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
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_passport)
    $filterString[] = 'Passport: ' . $filter_passport;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_union)
    $filterString[] = 'Union: ' . $filter_union;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_excel']) {
    unset($args['select_fields']);
    unset($args['limit']);
    unset($args['listing']);

    $args['data_only'] = true;
    $data = $this->get_customers($args);
    $data = $data['data'];

    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'Participants-' . time() . '.xlsx';
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
    $report_head = ['Participants Report'];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head, $style2);
    $writer->addRow($singleRow);

    $report_date = ['Report Generate Date: ' . Date('d-m-Y')];
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
        'Beneficiary ID/Reference number',
        'Full Name',
        'NID Number',
        'Birth Registration Number',
        "Father's Name",
        "Mother's Name",
        'Date of Birth',
        'Gender',
        'Marital Status',
        "Spouse Name",
        'Mobile No',
        'Emergency Mobile No',
        'Name of that Person',
        "Relation with Participant",
        "Village",
        'Ward No',
        'Union',
        'Upazilla',
        "District",
        'Present Address of Beneficiary',
        'Educational Qualification',
        'Boy (<18)',
        "Girl (<18)",
        "Men (>=18)",
        'Women (>=18)',
        'Number of Accompany/ Number of Family Member',
        'Transit/ Route of Migration/ Trafficking',
        "Desired destination",
        'Final destination',
        'Type of Channels',
        'Type of Visa',
        "Name of Media Departure",
        "Relation of Media Departure",
        'Address of Media Departure',
        'Passport No',
        'Date of Departure from Bangladesh',
        "Date of Return to Bangladesh",
        'Age (When come back in Bangladesh)',
        'Duration of Stay Abroad (Months)',
        'Occupation in overseas country',
        "Income: (If applicable)",
        "Reasons for Migration",
        'Reasons for returning to Bangladesh',
        'False promises about a job prior to arrival at workplace abroad',
        'Forced to perform work or other activities against your will, after the departure from Bangladesh?',
        "Experienced excessive working hours (more than 40 hours a week)",
        'Deductions from salary for recruitment fees at workplace',
        'Denied freedom of movement during or between work shifts after your departure from Bangladesh?',
        'Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation?',
        "Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh?",
        'Any Property/wealth',
        'Any Property/wealth Value',
        'Main occupation (Before trafficking)',
        'Main occupation (after return)',
        'Monthly income of Survivor after return(in BDT)',
        'Source of income (Last month in BDT)',
        'Source of income',
        'Total Family income(last month)',
        'Savings (BDT)',
        'Loan Amount (BDT)',
        'Ownership of House',
        'Type of house',
        'Any IGA Skills?',
        'IGA Skills',
        'Do you have any disability?',
        'Type of disability',
        "Any Chronic Disease?",
        "Type of Disease",
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $profile) {
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
                $current_residence_ownership = $profile['educational_qualification'];
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
            $have_skills = $profile['have_skills'] ? $profile['have_skills'] : 'N/A';
            $disease_type = $profile['disease_type'] ? $profile['disease_type'] : 'N/A';

            $migration_medias = json_decode($profile['migration_medias']);

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($profile['project_short_name']),
                WriterEntityFactory::createCell($profile['entry_date'] ? date('d-m-Y', strtotime($profile['entry_date'])) : 'N/A'),
                WriterEntityFactory::createCell($profile['create_date'] ? date('d-m-Y', strtotime($profile['create_date'])) : 'N/A'),
                WriterEntityFactory::createCell($profile['customer_id']),
                WriterEntityFactory::createCell($profile['full_name']),
                WriterEntityFactory::createCell($nid_number),
                WriterEntityFactory::createCell($birth_reg_number),
                WriterEntityFactory::createCell($profile['father_name']),
                WriterEntityFactory::createCell($profile['mother_name']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($profile['customer_birthdate']))),
                WriterEntityFactory::createCell(ucfirst($profile['customer_gender'])),
                WriterEntityFactory::createCell(ucfirst($profile['marital_status'])),
                WriterEntityFactory::createCell($customer_spouse),
                WriterEntityFactory::createCell($profile['customer_mobile']),
                WriterEntityFactory::createCell($profile['emergency_mobile']),
                WriterEntityFactory::createCell($profile['emergency_name']),
                WriterEntityFactory::createCell($profile['emergency_relation']),
                WriterEntityFactory::createCell($profile['permanent_village']),
                WriterEntityFactory::createCell($profile['permanent_ward']),
                WriterEntityFactory::createCell(ucfirst($profile['permanent_union'])),
                WriterEntityFactory::createCell(ucfirst($profile['permanent_sub_district'])),
                WriterEntityFactory::createCell(ucfirst($profile['permanent_district'])),
                WriterEntityFactory::createCell($profile['permanent_house']),
                WriterEntityFactory::createCell($educational_qualification),
                WriterEntityFactory::createCell($profile['boy_household_member']),
                WriterEntityFactory::createCell($profile['girl_household_member']),
                WriterEntityFactory::createCell($profile['male_household_member']),
                WriterEntityFactory::createCell($profile['female_household_member']),
                WriterEntityFactory::createCell($family_members),
                WriterEntityFactory::createCell($profile['left_port']),
                WriterEntityFactory::createCell($profile['preferred_country']),
                WriterEntityFactory::createCell($profile['final_destination']),
                WriterEntityFactory::createCell(ucfirst($profile['migration_type'])),
                WriterEntityFactory::createCell(ucfirst($profile['visa_type'])),
                WriterEntityFactory::createCell($migration_medias->departure_media ? $migration_medias->departure_media : $profile['departure_media']),
                WriterEntityFactory::createCell($migration_medias->media_relation ? $migration_medias->media_relation : $profile['media_relation']),
                WriterEntityFactory::createCell($migration_medias->media_address ? $migration_medias->media_address : $profile['media_address']),
                WriterEntityFactory::createCell($profile['passport_number']),
                WriterEntityFactory::createCell($profile['departure_date'] && $profile['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($profile['departure_date'])) : 'N/A'),
                WriterEntityFactory::createCell($profile['return_date'] && $profile['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($profile['return_date'])) : 'N/A'),
                WriterEntityFactory::createCell($profile['returned_age']),
                WriterEntityFactory::createCell($profile['migration_duration']),
                WriterEntityFactory::createCell($profile['migration_occupation']),
                WriterEntityFactory::createCell($profile['earned_money']),
                WriterEntityFactory::createCell($profile['migration_reasons'] . ' ' . $profile['other_migration_reason']),
                WriterEntityFactory::createCell($profile['destination_country_leave_reason'] . ' ' . $profile['other_destination_country_leave_reason']),
                WriterEntityFactory::createCell(ucfirst($profile['is_cheated'])),
                WriterEntityFactory::createCell(ucfirst($profile['forced_work'])),
                WriterEntityFactory::createCell(ucfirst($profile['excessive_work'])),
                WriterEntityFactory::createCell(ucfirst($profile['is_money_deducted'])),
                WriterEntityFactory::createCell(ucfirst($profile['is_movement_limitation'])),
                WriterEntityFactory::createCell(ucfirst($profile['employer_threatened'])),
                WriterEntityFactory::createCell(ucfirst($profile['is_kept_document'])),
                WriterEntityFactory::createCell($profile['property_name']),
                WriterEntityFactory::createCell($profile['property_value']),
                WriterEntityFactory::createCell($profile['pre_occupation']),
                WriterEntityFactory::createCell($profile['present_occupation']),
                WriterEntityFactory::createCell($profile['present_income']),
                WriterEntityFactory::createCell($profile['returnee_income_source']),
                WriterEntityFactory::createCell($profile['income_source']),
                WriterEntityFactory::createCell($profile['family_income']),
                WriterEntityFactory::createCell($profile['personal_savings']),
                WriterEntityFactory::createCell($profile['personal_debt']),
                WriterEntityFactory::createCell($current_residence_ownership),
                WriterEntityFactory::createCell($current_residence_type),
                WriterEntityFactory::createCell($profile['have_earner_skill']),
                WriterEntityFactory::createCell($have_skills . ' ' . $profile['other_have_skills'] . ' ' . $profile['vocational_skill'] . ' ' . $profile['handicraft_skill']),
                WriterEntityFactory::createCell(ucfirst($profile['is_physically_challenged'])),
                WriterEntityFactory::createCell($disability_type),
                WriterEntityFactory::createCell(ucfirst($profile['having_chronic_disease'])),
                WriterEntityFactory::createCell($disease_type . ' ' . $profile['other_disease_type']),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }

    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:BQ1', 'A2:BQ2', 'A3:BQ3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Participants</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_customer',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Participant Profile',
                'title' => 'New Participant Profile',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_excel=1&id=' . $filter_id . '&name=' . $filter_name . '&nid=' . $filter_nid . '&passport=' . $filter_passport . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&project_id=' . $filter_project . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Profiles',
                'title' => 'Download Profiles',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="stat-panel">
            <div class="stat-row">
                <div class="stat-cell bg-color-dark-blue darker">
                    <i class="fa fa-lightbulb-o bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
                    <span class="text-xlg"><?php echo ($manCustomer + $womanCustomer + $boyCustomer + $girlCustomer) ?></span><br>
                    <span class="text-bg">Total Participants</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-counters bg-primary no-border-b no-padding text-center">
                    <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>" class="stat-cell col-xs-4 padding-sm no-padding-hr">
                        <span class="text-bg"><strong><?php echo $manCustomer ?></strong></span><br>
                        <span class="text-xs">Man</span>
                    </a>
                    <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                        <span class="text-bg"><strong><?php echo $womanCustomer ?></strong></span><br>
                        <span class="text-xs">Woman</span>
                    </a>
                    <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                        <span class="text-bg"><strong><?php echo $boyCustomer ?></strong></span><br>
                        <span class="text-xs">Boy</span>
                    </a>
                    <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                        <span class="text-bg"><strong><?php echo $girlCustomer ?></strong></span><br>
                        <span class="text-xs">Girl</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <style>
            #bar-chart{
                min-height: 200px;
            }
        </style>
        <div id="bar-chart" ></div>
        <script>
            var data = [
                {y: 'Man', a: <?php echo $manCustomer ?>},
                {y: 'Boy', a: <?php echo $boyCustomer ?>},
                {y: 'Woman', a: <?php echo $womanCustomer ?>},
                {y: 'Girl', a: <?php echo $girlCustomer ?>},
            ],
                    config = {
                        data: data,
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Total'],
                        fillOpacity: 0.6,
                        hideHover: 'auto',
                        behaveLikeLine: true,
                        resize: true,
                        pointFillColors: ['#ffffff'],
                        pointStrokeColors: ['black'],
                        lineColors: ['gray']
                    };

            config.element = 'bar-chart';
            Morris.Bar(config);
        </script>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('id', 'id', array(
    'width' => 2, 'type' => 'text', 'label' => 'Participant ID',
        ), $filter_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Participant Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('passport', 'passport', array(
    'width' => 2, 'type' => 'text', 'label' => 'Passport',
        ), $filter_passport);
?>
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
<div class="form-group col-sm-2">
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
        <?php echo searchResultText($customers['total'], $start, $per_page_items, count($customers['data']), 'participants') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Project Name</th>
                <th>Present Address</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customers['data'] as $i => $customer) {
                ?>
                <tr>
                    <td><?php echo $customer['customer_id']; ?></td>
                    <td><?php echo $customer['full_name']; ?></td>
                    <td><?php echo $customer['customer_mobile']; ?></td>
                    <td><?php echo $customer['project_short_name']; ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $customer['permanent_division'] . ',<br><b>District - </b>' . $customer['permanent_district'] . ',<br><b>Upazila - </b>' . $customer['permanent_sub_district'] . ',<br><b>Union - </b>' . $customer['permanent_union'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $customer['customer_status']; ?></td>
                    <td>
                        <?php if (has_permission('edit_customer')): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark-gray dropdown-toggle" data-toggle="dropdown"><i class="btn-label fa fa-cogs"></i> Options&nbsp;<i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $customer['pk_customer_id']) ?>">Edit</a></li>
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_customers?action=add_edit_evaluate&edit=' . $customer['pk_customer_id']) ?>">Evaluate</a></li>
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_customers?action=download_pdf&id=' . $customer['pk_customer_id']) ?>">Download PDF</a></li>
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $customer['pk_customer_id']) ?>">Case Management</a></li>
                                    <li><a href="<?php echo url('admin/dev_customer_management/manage_customers?action=list_satisfaction_scale&id=' . $customer['pk_customer_id']) ?>">Reintegration Assistance<br/> Satisfaction Scale</a></li>
                                </ul>
                            </div>                         
                        <?php endif ?>
                        <?php if (has_permission('delete_customer')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $customer['pk_customer_id']),
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
                        text: 'Delete Participant Profile Information',
                        value: 'deleteProfile'
                    }],
                callback: function (result) {
                    if (result == 'deleteProfile') {
                        window.location.href = '?action=deleteProfile&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>