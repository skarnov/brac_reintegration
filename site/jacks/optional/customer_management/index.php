<?php

class dev_customer_management {

    var $thsClass = 'dev_customer_management';
    var $all_religions = array(
        'Islam' => 'Islam',
        'Hinduism' => 'Hinduism',
        'Buddhism' => 'Buddhism',
        'Christianity' => 'Christianity'
    );
    var $all_occupations = array(
        'unemployed' => 'Unemployed',
        'job' => 'Job',
        'business' => 'Business',
        'agriculture' => 'Agriculture',
        'day-labor' => 'Day-Labor',
        'housewife' => 'Housewife',
        'student' => 'Student',
    );
    var $all_trainings = array(
        'Plumbing' => 'Plumbing',
        'Machine Operator' => 'Machine Operator',
        'Carpenter' => 'Carpenter',
        'Rod Binding' => 'Rod Binding',
        'Electrical Maintenance' => 'Electrical Maintenance',
        'Electronics' => 'Electronics',
        'Driving' => 'Driving',
        'Tiles fitting' => 'Tiles fitting',
        'Tailor and dress making' => 'Tailor and dress making',
        'Food and beverages' => 'Food and beverages',
        'Painter' => 'Painter',
        'Handicraft' => 'Handicraft',
        'Fishing' => 'Fishing',
        'Nursery' => 'Nursery',
        'Poultry' => 'Poultry',
        'Livestock' => 'Livestock',
        'Cook' => 'Cook',
    );
    var $all_disabilities = array(
        'Autism' => 'Autism',
        'Physical' => 'Physical',
        'Psychosocial' => 'Psychosocial',
        'Visual Impaired' => 'Visual Impaired',
        'Speech Disability' => 'Speech Disability',
        'Intellectual Disability' => 'Intellectual Disability',
        'Hearing Disability' => 'Hearing Disability',
        'Hearing Visual Disability' => 'Hearing Visual Disability',
        'Cerebral Palsy' => 'Cerebral Palsy',
        'Down Syndrome' => 'Down Syndrome',
        'Multiple Disability' => 'Multiple Disability'
    );
    var $all_disaster_types = array(
        'River Erosion' => 'River Erosion',
        'Flood' => 'Flood',
        'Cyclone' => 'Cyclone',
        'Tsunami' => 'Tsunami',
        'Pricey' => 'Pricey',
        'Earthquake' => 'Earthquake',
    );
    var $all_economic_impact_types = array(
        'Poverty' => 'Poverty',
        'Imbalance Income-Expenditure' => 'Imbalance Income-Expenditure',
        'Unemployment' => 'Unemployment',
        'Shelter less' => 'Shelter less',
        'Landless' => 'Landless',
    );
    var $all_social_impact_types = array(
        'Violence' => 'Violence',
        'Social Discrimination' => 'Social Discrimination',
        'Gender Discrimination' => 'Gender Discrimination',
        'Religious Subjection' => 'Religious Subjection',
        'Political Pressure' => 'Political Pressure',
        'Security' => 'Security',
    );

    function __construct() {
        jack_register($this);
        ini_set('memory_limit', '1024M');
    }

    function init() {
        $permissions = array(
            'group_name' => 'Beneficiaries',
            'permissions' => array(
                'manage_customers' => array(
                    'add_customer' => 'Add Customer',
                    'edit_customer' => 'Edit Customer',
                    'delete_customer' => 'Delete Customer',
                ),
                'manage_cases' => array(
                    'add_case' => 'Add Case',
                    'edit_case' => 'Edit Case',
                    'delete_case' => 'Delete Case',
                ),
                'manage_returnees' => array(
                    'add_returnee' => 'Add Returnee',
                    'edit_returnee' => 'Edit Returnee',
                    'delete_returnee' => 'Delete Returnee',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Beneficiaries',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Participant Profiles',
            'description' => 'Manage All Participant Profiles',
            'menu_group' => 'Beneficiaries',
            'position' => 'default',
            'action' => 'manage_customers',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_customers'))
            admenu_register($params);

        $params = array(
            'label' => 'Cases Management',
            'description' => 'Manage All Beneficiary Case Management',
            'menu_group' => 'Beneficiaries',
            'position' => 'default',
            'action' => 'manage_cases',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_cases'))
            admenu_register($params);

        $params = array(
            'label' => 'Returnee Database',
            'description' => 'Manage All Returnee Management',
            'menu_group' => 'Beneficiaries',
            'position' => 'default',
            'action' => 'manage_returnees',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_returnees'))
            admenu_register($params);
    }

    function manage_customers() {
        if (!has_permission('manage_customers'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_customers');

        if ($_GET['action'] == 'add_edit_customer')
            include('pages/add_edit_customer.php');
        elseif ($_GET['action'] == 'clear_form') {
            unset($_SESSION['form_data']);
            include('pages/add_edit_customer.php');
        } elseif ($_GET['action'] == 'add_edit_evaluate')
            include('pages/add_edit_evaluate.php');
        elseif ($_GET['action'] == 'add_edit_satisfaction_scale')
            include('pages/add_edit_satisfaction_scale.php');
        elseif ($_GET['action'] == 'list_satisfaction_scale')
            include('pages/list_satisfaction_scale.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/download_pdf.php');
        elseif ($_GET['action'] == 'deleteProfile')
            include('pages/deleteProfile.php');
        elseif ($_GET['action'] == 'deleteMigration')
            include('pages/deleteMigration.php');
        elseif ($_GET['action'] == 'deleteScale')
            include('pages/deleteScale.php');
        else
            include('pages/list_customers.php');
    }

    function manage_cases() {
        if (!has_permission('manage_cases'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_cases');

        if ($_GET['action'] == 'add_edit_case')
            include('pages/add_edit_case.php');
        elseif ($_GET['action'] == 'add_edit_family_counseling')
            include('pages/add_edit_family_counseling.php');
        elseif ($_GET['action'] == 'add_edit_psychosocial_session')
            include('pages/add_edit_psychosocial_session.php');
        elseif ($_GET['action'] == 'add_edit_session_completion')
            include('pages/add_edit_session_completion.php');
        elseif ($_GET['action'] == 'add_edit_psychosocial_followup')
            include('pages/add_edit_psychosocial_followup.php');
        elseif ($_GET['action'] == 'add_edit_economic_referral')
            include('pages/add_edit_economic_referral.php');
        elseif ($_GET['action'] == 'add_edit_received_economic_referral')
            include('pages/add_edit_received_economic_referral.php');
        elseif ($_GET['action'] == 'add_edit_review')
            include('pages/add_edit_review.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/case_download_pdf.php');
        elseif ($_GET['action'] == 'deleteCase')
            include('pages/deleteCase.php');
        else
            include('pages/list_cases.php');
    }

    function manage_returnees() {
        if (!has_permission('manage_returnees'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_returnees');

        if ($_GET['action'] == 'add_edit_returnee')
            include('pages/add_edit_returnee.php');
        elseif ($_GET['action'] == 'deleteReturnee')
            include('pages/deleteReturnee.php');
        else
            include('pages/list_returnees.php');
    }

    function get_customers($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_customers  
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id) 
            ";
        } else {
            $from = "FROM dev_customers 
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_economic_profile ON (dev_economic_profile.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_customer_skills ON (dev_customer_skills.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_customer_health ON (dev_customer_health.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id) 
            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_customers.pk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'customer_id' => 'dev_customers.pk_customer_id',
            'id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'passport' => 'dev_customers.passport_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'union' => 'dev_customers.permanent_union',
            'project' => 'dev_customers.fk_project_id',
            'entry_date' => 'dev_customers.create_date',
            'customer_status' => 'dev_customers.customer_status',
            'branch_id' => 'dev_customers.fk_branch_id',
            'gender' => 'dev_customers.customer_gender',
            'customer_age' => 'dev_customers.customer_age',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function get_migration_documents($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_migration_documents ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_migration_documents.pk_document_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'document_id' => 'dev_migration_documents.pk_document_id',
            'customer_id' => 'dev_migration_documents.fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $migrations = sql_data_collector($sql, $count_sql, $param);
        return $migrations;
    }

    function add_edit_customer($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_customers(array('customer_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid participant id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $customer_data = array();
            $customer_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $customer_data['fk_project_id'] = $params['form_data']['project_id'];
            $customer_data['customer_id'] = $params['form_data']['customer_id'];
            $customer_data['full_name'] = $params['form_data']['full_name'];
            $customer_data['father_name'] = $params['form_data']['father_name'];
            $customer_data['mother_name'] = $params['form_data']['mother_name'];
            $customer_data['customer_birthdate'] = date('Y-m-d', strtotime($params['form_data']['customer_birthdate']));
            $customer_data['customer_mobile'] = $params['form_data']['customer_mobile'];
            $customer_data['emergency_mobile'] = $params['form_data']['emergency_mobile'];
            $customer_data['emergency_name'] = $params['form_data']['emergency_name'];
            $customer_data['emergency_relation'] = $params['form_data']['emergency_relation'];
            if ($params['form_data']['new_qualification']) {
                $customer_data['educational_qualification'] = $params['form_data']['new_qualification'];
            } else {
                $customer_data['educational_qualification'] = $params['form_data']['educational_qualification'];
            }
            if ($params['form_data']['new_religion']) {
                $customer_data['customer_religion'] = $params['form_data']['new_religion'];
            } else {
                $customer_data['customer_religion'] = $params['form_data']['customer_religion'];
            }
            $customer_data['nid_number'] = $params['form_data']['nid_number'];
            $customer_data['birth_reg_number'] = $params['form_data']['birth_reg_number'];
            $customer_data['passport_number'] = $params['form_data']['passport_number'];
            $customer_data['travel_pass'] = $params['form_data']['travel_pass'];
            if ($params['form_data']['new_gender']) {
                $customer_data['customer_gender'] = $params['form_data']['new_gender'];
            } else {
                $customer_data['customer_gender'] = $params['form_data']['customer_gender'];
            }
            $customer_data['marital_status'] = $params['form_data']['marital_status'];
            $customer_data['customer_spouse'] = $params['form_data']['customer_spouse'];
            $customer_data['permanent_village'] = $params['form_data']['permanent_village'];
            $customer_data['permanent_ward'] = $params['form_data']['permanent_ward'];
            $customer_data['permanent_division'] = $params['form_data']['permanent_division'];
            $customer_data['permanent_union'] = $params['form_data']['permanent_union'];
            $customer_data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $customer_data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $customer_data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $customer_data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $customer_data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $customer_data['permanent_district'] = $params['form_data']['permanent_district'];
            $customer_data['permanent_house'] = $params['form_data']['permanent_house'];
            $customer_data['customer_status'] = 'active';
            if ($is_update) {
                $customer_data['update_date'] = date('Y-m-d');
                $customer_data['update_time'] = date('H:i:s');
                $customer_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_customers', $customer_data, " pk_customer_id = '" . $is_update . "'");
            } else {
                $customer_data['create_date'] = date('Y-m-d');
                $customer_data['create_time'] = date('H:i:s');
                $customer_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_customers', $customer_data);

                $devdb->query(" UPDATE dev_customers SET customer_age = ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(customer_birthdate, '%Y-%m-%d'))/365)");

                $fk_customer_id = $ret['success'];

                /* Customer ID Creation */
                if ($customer_data['customer_id'] == NULL):
                    $district = $customer_data['permanent_district'];
                    $sub_district = $customer_data['permanent_sub_district'];

                    $sql = "SELECT COUNT(pk_customer_id) as TOTAL FROM dev_customers WHERE permanent_district = '$district' AND permanent_sub_district = '$sub_district'";
                    $totalCustomer = $devdb->get_row($sql)['TOTAL'];

                    $customerID = strtoupper(substr($district, 0, 3)) . '-' . strtoupper(substr($sub_district, 0, 4)) . '-' . date('m') . '-' . date('Y') . '-' . str_pad($totalCustomer, 6, '0', STR_PAD_LEFT);
                    $devdb->query("UPDATE dev_customers SET customer_id = '" . $customerID . "' WHERE pk_customer_id = '$fk_customer_id'");
                endif;
                /* End Customer ID Creation */
                $migration_data = array();
                $migration_data['fk_customer_id'] = $fk_customer_id;
                $ret['migration_insert'] = $devdb->insert_update('dev_migrations', $migration_data);

                $economic_profile_data = array();
                $economic_profile_data['fk_customer_id'] = $fk_customer_id;
                $ret['economic_insert'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data);

                $skill_data = array();
                $skill_data['fk_customer_id'] = $fk_customer_id;
                $ret['skill_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data);

                $customer_health_data = array();
                $customer_health_data['fk_customer_id'] = $fk_customer_id;
                $ret['health_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data);

                $case_data = array();
                $case_data['fk_customer_id'] = $fk_customer_id;
                $case_data['create_time'] = date('H:i:s');
                $case_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['case_insert'] = $devdb->insert_update('dev_immediate_supports', $case_data);

                $evaluate_data = array();
                $evaluate_data['fk_customer_id'] = $fk_customer_id;
                $ret['evaluate_insert'] = $devdb->insert_update('dev_initial_evaluation', $evaluate_data);
            }

            $migration_data = array();

            $migration_data['left_port'] = $params['form_data']['left_port'];
            $migration_data['preferred_country'] = $params['form_data']['preferred_country'];
            $migration_data['preferred_city'] = $params['form_data']['preferred_city'];
            $migration_data['final_destination'] = $params['form_data']['final_destination'];
            $migration_data['final_city'] = $params['form_data']['final_city'];
            $migration_data['migration_type'] = $params['form_data']['migration_type'];

            if ($params['form_data']['new_visa']) {
                $migration_data['visa_type'] = $params['form_data']['new_visa'];
            } else {
                $migration_data['visa_type'] = $params['form_data']['visa_type'];
            }

            if ($params['form_data']['new_departure_media']) {
                $migration_data['departure_media'] = $params['form_data']['new_departure_media'];
            } else {
                $migration_data['departure_media'] = $params['form_data']['departure_media'];
            }

            $migration_data['climate_effect'] = $params['form_data']['climate_effect'];

            if ($params['form_data']['new_natural_disaster'] == NULL) {
                $data_type = $params['form_data']['natural_disasters'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['natural_disasters'] = $data_types;
                $migration_data['other_natural_disaster'] = '';
            } elseif ($params['form_data']['natural_disasters'] == NULL) {
                $migration_data['other_natural_disaster'] = $params['form_data']['new_natural_disaster'];
            } elseif ($params['form_data']['natural_disasters'] != NULL && $params['form_data']['new_natural_disaster'] != NULL) {
                $data_type = $params['form_data']['natural_disasters'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['natural_disasters'] = $data_types;
                $migration_data['other_natural_disaster'] = $params['form_data']['new_natural_disaster'];
            }

            $migration_data['is_disaster_migration'] = $params['form_data']['is_disaster_migration'];

            if ($params['form_data']['new_economic_impact'] == NULL) {
                $data_type = $params['form_data']['economic_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['economic_impacts'] = $data_types;
                $migration_data['other_economic_impact'] = '';
            } elseif ($params['form_data']['economic_impacts'] == NULL) {
                $migration_data['other_economic_impact'] = $params['form_data']['new_economic_impact'];
            } elseif ($params['form_data']['economic_impacts'] != NULL && $params['form_data']['new_economic_impact'] != NULL) {
                $data_type = $params['form_data']['economic_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['economic_impacts'] = $data_types;
                $migration_data['other_economic_impact'] = $params['form_data']['new_economic_impact'];
            }

            $migration_data['financial_losses'] = $params['form_data']['financial_losses'];

            if ($params['form_data']['new_social_impact'] == NULL) {
                $data_type = $params['form_data']['social_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['social_impacts'] = $data_types;
                $migration_data['other_social_impact'] = '';
            } elseif ($params['form_data']['social_impacts'] == NULL) {
                $migration_data['other_social_impact'] = $params['form_data']['new_social_impact'];
            } elseif ($params['form_data']['social_impacts'] != NULL && $params['form_data']['new_social_impact'] != NULL) {
                $data_type = $params['form_data']['social_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['social_impacts'] = $data_types;
                $migration_data['other_social_impact'] = $params['form_data']['new_social_impact'];
            }

            $migration_data['is_climate_migration'] = $params['form_data']['is_climate_migration'];

            $migration_data['agency_name'] = $params['form_data']['agency_name'];
            $migration_data['rl_no'] = $params['form_data']['rl_no'];
            $migration_data['agency_know'] = $params['form_data']['agency_know'];
            $migration_data['agency_address'] = $params['form_data']['agency_address'];

            $migration_data['departure_date'] = date('Y-m-d', strtotime($params['form_data']['departure_date']));
            $migration_data['return_date'] = date('Y-m-d', strtotime($params['form_data']['return_date']));

            $diff = abs(strtotime($migration_data['return_date']) - strtotime($migration_data['departure_date']));

            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

            $migration_data['migration_duration'] = "Year: $years, Month: $months, Days: $days";

            $calc_age = $diff = abs(strtotime($migration_data['return_date']) - strtotime(date('Y-m-d', strtotime($params['form_data']['customer_birthdate']))));

            $calc_years = floor($calc_age / (365 * 60 * 60 * 24));
            $calc_months = floor(($calc_age - $calc_years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $calc_days = floor(($calc_age - $calc_years * 365 * 60 * 60 * 24 - $calc_months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

            $migration_data['returned_age'] = "Age: $calc_years, Month: $calc_months, Days: $calc_days";

            $migration_data['migration_occupation'] = $params['form_data']['migration_occupation'];
            $migration_data['earned_money'] = $params['form_data']['earned_money'];

            if ($params['form_data']['new_migration_reason'] == NULL) {
                $data_type = $params['form_data']['migration_reasons'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_reasons'] = $data_types;
                $migration_data['other_migration_reason'] = '';
            } elseif ($params['form_data']['migration_reasons'] == NULL) {
                $migration_data['other_migration_reason'] = $params['form_data']['new_migration_reason'];
            } elseif ($params['form_data']['migration_reasons'] != NULL && $params['form_data']['new_migration_reason'] != NULL) {
                $data_type = $params['form_data']['migration_reasons'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_reasons'] = $data_types;
                $migration_data['other_migration_reason'] = $params['form_data']['new_migration_reason'];
            }

            if ($params['form_data']['new_return_reason'] == NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
                $migration_data['other_destination_country_leave_reason'] = '';
            } elseif ($params['form_data']['destination_country_leave_reason'] == NULL) {
                $migration_data['other_destination_country_leave_reason'] = $params['form_data']['new_return_reason'];
            } elseif ($params['form_data']['destination_country_leave_reason'] != NULL && $params['form_data']['new_return_reason'] != NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
                $migration_data['other_destination_country_leave_reason'] = $params['form_data']['new_return_reason'];
            }

            $migration_data['is_cheated'] = $params['form_data']['is_cheated'];
            $migration_data['forced_work'] = $params['form_data']['forced_work'];
            $migration_data['excessive_work'] = $params['form_data']['excessive_work'];
            $migration_data['is_money_deducted'] = $params['form_data']['is_money_deducted'];
            $migration_data['is_movement_limitation'] = $params['form_data']['is_movement_limitation'];
            $migration_data['employer_threatened'] = $params['form_data']['employer_threatened'];
            $migration_data['is_kept_document'] = $params['form_data']['is_kept_document'];

            if ($is_update) {
                $migration_data['update_date'] = date('Y-m-d');
                $migration_data['update_time'] = date('H:i:s');
                $migration_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['migration_update'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $is_update . "'");

                $devdb->query("DELETE FROM dev_migration_documents WHERE fk_customer_id = '$is_update'");

                $document_name = $params['form_data']['document_name'];
                if ($document_name) {
                    $migration_documents = array();

                    $key = 0;
                    foreach ($document_name as $key => $value) {
                        if ($_FILES['document_file']['name'][$key]) {
                            $supported_ext = array('jpg', 'png');
                            $max_filesize = 512000;
                            $target_dir = _path('uploads', 'absolute') . "/";
                            if (!file_exists($target_dir))
                                mkdir($target_dir);
                            $target_file = $target_dir . basename($_FILES['document_file']['name'][$key]);
                            $fileinfo = pathinfo($target_file);
                            $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                            if (in_array(strtolower($imageFileType), $supported_ext)) {
                                if ($max_filesize && $_FILES['document_file']['size'][$key] <= $max_filesize) {
                                    if (!move_uploaded_file($_FILES['document_file']['tmp_name'][$key], $target_file)) {
                                        $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                                        $params['form_data']['document_file'] = '';
                                    } else {
                                        $fileinfo = pathinfo($target_file);
                                        $params['form_data']['document_file'] = $fileinfo['basename'];
                                        @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file'][$key]);
                                    }
                                } else
                                    $ret['error'][] = 'Customer Picture : <strong>' . $_FILES['document_file']['size'][$key] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                            } else
                                $ret['error'][] = 'Customer Picture : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
                        } else {
                            $params['form_data']['document_file'] = $params['form_data']['document_old_file'][$key];
                        }

                        $migration_documents['fk_customer_id'] = $is_update;
                        $migration_documents['document_name'] = $value;
                        $migration_documents['document_file'] = $params['form_data']['document_file'];
                        $migration_documents['update_time'] = date('H:i:s');
                        $migration_documents['update_date'] = date('Y-m-d');
                        $migration_documents['updated_by'] = $_config['user']['pk_user_id'];
                        $ret['migration_document'] = $devdb->insert_update('dev_migration_documents', $migration_documents);

                        $key++;
                    }
                }
            } else {
                $migration_data['update_date'] = date('Y-m-d');
                $migration_data['update_time'] = date('H:i:s');
                $migration_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['migration_insert'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $fk_customer_id . "'");

                $document_name = $params['form_data']['document_name'];

                if ($document_name) {
                    $migration_documents = array();
                    $key = 0;
                    foreach ($document_name as $key => $value) {
                        if ($_FILES['document_file']['name'][$key]) {
                            $supported_ext = array('jpg', 'png');
                            $max_filesize = 512000;
                            $target_dir = _path('uploads', 'absolute') . "/";
                            if (!file_exists($target_dir))
                                mkdir($target_dir);
                            $target_file = $target_dir . basename($_FILES['document_file']['name'][$key]);
                            $fileinfo = pathinfo($target_file);
                            $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                            if (in_array(strtolower($imageFileType), $supported_ext)) {
                                if ($max_filesize && $_FILES['document_file']['size'][$key] <= $max_filesize) {
                                    if (!move_uploaded_file($_FILES['document_file']['tmp_name'][$key], $target_file)) {
                                        $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                                        $params['form_data']['document_file'] = '';
                                    } else {
                                        $fileinfo = pathinfo($target_file);
                                        $params['form_data']['document_file'] = $fileinfo['basename'];
                                        @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file'][$key]);
                                    }
                                } else
                                    $ret['error'][] = 'Customer Picture : <strong>' . $_FILES['document_file']['size'][$key] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                            } else
                                $ret['error'][] = 'Customer Picture : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
                        } else {
                            $params['form_data']['document_file'] = $params['form_data']['document_old_file'][$key];
                        }

                        $migration_documents['fk_customer_id'] = $fk_customer_id;
                        $migration_documents['document_name'] = $value;
                        $migration_documents['document_file'] = $params['form_data']['document_file'];
                        $migration_documents['create_time'] = date('H:i:s');
                        $migration_documents['create_date'] = date('Y-m-d');
                        $migration_documents['created_by'] = $_config['user']['pk_user_id'];
                        $ret['migration_document'] = $devdb->insert_update('dev_migration_documents', $migration_documents);

                        $key++;
                    }
                }
            }

            $economic_profile_data = array();
            $economic_profile_data['migration_cost'] = $params['form_data']['migration_cost'];

            if ($params['form_data']['new_migration_cost_source'] == NULL) {
                $data_type = $params['form_data']['migration_cost_sources'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['migration_cost_sources'] = $data_types;
                $economic_profile_data['other_migration_cost_source'] = '';
            } elseif ($params['form_data']['migration_cost_sources'] == NULL) {
                $economic_profile_data['other_migration_cost_source'] = $params['form_data']['new_migration_cost_source'];
            } elseif ($params['form_data']['migration_cost_sources'] != NULL && $params['form_data']['new_migration_cost_source'] != NULL) {
                $data_type = $params['form_data']['migration_cost_sources'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['migration_cost_sources'] = $data_types;
                $economic_profile_data['other_migration_cost_source'] = $params['form_data']['new_migration_cost_source'];
            }

            $economic_profile_data['is_interested_training'] = $params['form_data']['is_interested_training'];

            if ($params['form_data']['new_interested_training'] == NULL) {
                $data_type = $params['form_data']['interested_trainings'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['interested_trainings'] = $data_types;
                $economic_profile_data['other_interested_training'] = '';
            } elseif ($params['form_data']['interested_trainings'] == NULL) {
                $economic_profile_data['other_interested_training'] = $params['form_data']['new_interested_training'];
            } elseif ($params['form_data']['interested_trainings'] != NULL && $params['form_data']['new_interested_training'] != NULL) {
                $data_type = $params['form_data']['interested_trainings'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['interested_trainings'] = $data_types;
                $economic_profile_data['other_interested_training'] = $params['form_data']['new_interested_training'];
            }

            $economic_profile_data['property_name'] = $params['form_data']['property_name'];
            $economic_profile_data['property_value'] = $params['form_data']['property_value'];
            $economic_profile_data['returnee_income_source'] = $params['form_data']['returnee_income_source'];
            $economic_profile_data['income_source'] = $params['form_data']['income_source'];
            $economic_profile_data['family_income'] = $params['form_data']['family_income'];

            if ($params['form_data']['new_pre_occupation']) {
                $economic_profile_data['pre_occupation'] = $params['form_data']['new_pre_occupation'];
            } else {
                $economic_profile_data['pre_occupation'] = $params['form_data']['pre_occupation'];
            }

            if ($params['form_data']['new_occupation']) {
                $economic_profile_data['present_occupation'] = $params['form_data']['new_occupation'];
            } else {
                $economic_profile_data['present_occupation'] = $params['form_data']['present_occupation'];
            }

            $economic_profile_data['present_income'] = $params['form_data']['present_income'];
            $economic_profile_data['personal_savings'] = $params['form_data']['personal_savings'];
            $economic_profile_data['personal_debt'] = $params['form_data']['personal_debt'];

            if ($params['form_data']['new_ownership']) {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['new_ownership'];
            } else {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['current_residence_ownership'];
            }

            if ($params['form_data']['new_residence']) {
                $economic_profile_data['current_residence_type'] = $params['form_data']['new_residence'];
            } else {
                $economic_profile_data['current_residence_type'] = $params['form_data']['current_residence_type'];
            }

            $economic_profile_data['male_household_member'] = $params['form_data']['male_household_member'];
            $economic_profile_data['female_household_member'] = $params['form_data']['female_household_member'];
            $economic_profile_data['boy_household_member'] = $params['form_data']['boy_household_member'];
            $economic_profile_data['girl_household_member'] = $params['form_data']['girl_household_member'];

            $economic_profile_data['total_member'] = $params['form_data']['male_household_member'] + $params['form_data']['female_household_member'] + $params['form_data']['boy_household_member'] + $params['form_data']['girl_household_member'];

            if ($params['form_data']['new_ownership']) {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['new_ownership'];
            } else {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['current_residence_ownership'];
            }

            if ($params['form_data']['new_residence']) {
                $economic_profile_data['current_residence_type'] = $params['form_data']['new_residence'];
            } else {
                $economic_profile_data['current_residence_type'] = $params['form_data']['current_residence_type'];
            }

            $economic_profile_data['have_training'] = $params['form_data']['have_training'];

            if ($params['form_data']['new_skill_training'] == NULL) {
                $data_type = $params['form_data']['have_skill_trainings'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['have_skill_trainings'] = $data_types;
                $economic_profile_data['other_have_skill_training'] = '';
            } elseif ($params['form_data']['have_skill_trainings'] == NULL) {
                $economic_profile_data['other_have_skill_training'] = $params['form_data']['new_skill_training'];
            } elseif ($params['form_data']['have_skill_trainings'] != NULL && $params['form_data']['new_skill_training'] != NULL) {
                $data_type = $params['form_data']['have_skill_trainings'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_profile_data['have_skill_trainings'] = $data_types;
                $economic_profile_data['other_have_skill_training'] = $params['form_data']['new_skill_training'];
            }

            if ($is_update) {
                $economic_profile_data['update_date'] = date('Y-m-d');
                $economic_profile_data['update_time'] = date('H:i:s');
                $economic_profile_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['economic_update'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $economic_profile_data['create_date'] = date('Y-m-d');
                $economic_profile_data['create_time'] = date('H:i:s');
                $economic_profile_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['economic_insert'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data, " fk_customer_id = '" . $fk_customer_id . "'");
            }

            $skill_data = array();
            $skill_data['have_earner_skill'] = $params['form_data']['have_earner_skill'];

            if ($params['form_data']['new_have_technical'] == NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $skill_data['have_skills'] = $data_types;
                $skill_data['other_have_skills'] = '';
            } elseif ($params['form_data']['technical_have_skills'] == NULL) {
                $skill_data['other_have_skills'] = $params['form_data']['new_have_technical'];
            } elseif ($params['form_data']['technical_have_skills'] != NULL && $params['form_data']['new_have_technical'] != NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $skill_data['have_skills'] = $data_types;
                $skill_data['other_have_skills'] = $params['form_data']['new_have_technical'];
            }

            if ($params['form_data']['new_vocational']) {
                $skill_data['vocational_skill'] = $params['form_data']['new_vocational'];
            }

            if ($params['form_data']['new_handicrafts']) {
                $skill_data['handicraft_skill'] = $params['form_data']['new_handicrafts'];
            }

            if ($is_update) {
                $skill_data['update_date'] = date('Y-m-d');
                $skill_data['update_time'] = date('H:i:s');
                $skill_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['skill_update'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $skill_data['create_date'] = date('Y-m-d');
                $skill_data['create_time'] = date('H:i:s');
                $skill_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['skill_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $fk_customer_id . "'");
            }

            $customer_health_data = array();
            $customer_health_data['is_physically_challenged'] = $params['form_data']['is_physically_challenged'];
            $customer_health_data['disability_type'] = $params['form_data']['disability_type'];
            $customer_health_data['having_chronic_disease'] = $params['form_data']['having_chronic_disease'];

            if ($params['form_data']['new_disease_type'] == NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $data_types;
                $customer_health_data['other_disease_type'] = '';
            } elseif ($params['form_data']['disease_type'] == NULL) {
                $customer_health_data['other_disease_type'] = $params['form_data']['new_disease_type'];
            } elseif ($params['form_data']['disease_type'] != NULL && $params['form_data']['new_disease_type'] != NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $data_types;
                $customer_health_data['other_disease_type'] = $params['form_data']['new_disease_type'];
            }

            $customer_health_data['is_family_challenged'] = $params['form_data']['is_family_challenged'];
            $customer_health_data['family_disability_type'] = $params['form_data']['family_disability_type'];
            $customer_health_data['survivor_relationship'] = $params['form_data']['survivor_relationship'];

            if ($is_update) {
                $customer_health_data['update_date'] = date('Y-m-d');
                $customer_health_data['update_time'] = date('H:i:s');
                $customer_health_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['health_update'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $customer_health_data['create_date'] = date('Y-m-d');
                $customer_health_data['create_time'] = date('H:i:s');
                $customer_health_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['health_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $fk_customer_id . "'");
            }
        }
        return $ret;
    }

    function get_initial_evaluation($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_initial_evaluation ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_evaluation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_evaluation_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_initial_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_initial_evaluation(array('customer_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid evaluation id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $data = array();
            $data['is_participant'] = $params['form_data']['is_participant'];
            $data['justification_project'] = $params['form_data']['justification_project'];

            $data_type = $params['form_data']['selected_supports'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $data['selected_supports'] = $data_types;

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_initial_evaluation', $data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_initial_evaluation', $data);
            }
        }
        return $ret;
    }

    function get_satisfaction_scale($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_reintegration_satisfaction_scale "
                . " LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_reintegration_satisfaction_scale.fk_customer_id)"
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_satisfaction_scale) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_reintegration_satisfaction_scale.pk_satisfaction_scale',
            'customer_id' => 'dev_reintegration_satisfaction_scale.fk_customer_id',
            'customerId' => 'dev_customers.customer_id',
            'branch_id' => 'dev_customers.fk_branch_id',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'union' => 'dev_customers.permanent_union',
            'project' => 'dev_customers.fk_project_id',
            'entry_date' => 'dev_reintegration_satisfaction_scale.entry_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_satisfaction_scale($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_satisfaction_scale(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid satisfaction scale id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $satisfaction_scale_data = array();

            $satisfaction_scale_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $satisfaction_scale_data['satisfied_assistance'] = $params['form_data']['satisfied_assistance'];
            $satisfaction_scale_data['satisfied_assistance_date'] = $params['form_data']['satisfied_assistance_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_assistance_date'])) : '';
            $satisfaction_scale_data['satisfied_counseling'] = $params['form_data']['satisfied_counseling'];
            $satisfaction_scale_data['satisfied_counseling_date'] = $params['form_data']['satisfied_counseling_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_counseling_date'])) : '';
            $satisfaction_scale_data['satisfied_economic'] = $params['form_data']['satisfied_economic'];
            $satisfaction_scale_data['satisfied_economic_date'] = $params['form_data']['satisfied_economic_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_economic_date'])) : '';
            $satisfaction_scale_data['satisfied_social'] = $params['form_data']['satisfied_social'];
            $satisfaction_scale_data['satisfied_social_date'] = $params['form_data']['satisfied_social_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_social_date'])) : '';
            $satisfaction_scale_data['satisfied_community'] = $params['form_data']['satisfied_community'];
            $satisfaction_scale_data['satisfied_community_date'] = $params['form_data']['satisfied_community_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_community_date'])) : '';
            $satisfaction_scale_data['satisfied_reintegration'] = $params['form_data']['satisfied_reintegration'];
            $satisfaction_scale_data['satisfied_reintegration_date'] = $params['form_data']['satisfied_reintegration_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_reintegration_date'])) : '';
            $satisfaction_scale_data['satisfied_expectation'] = $params['form_data']['satisfied_expectation'];
            $satisfaction_scale_data['satisfied_expectation_date'] = $params['form_data']['satisfied_expectation_date'] ? date('Y-m-d', strtotime($params['form_data']['satisfied_expectation_date'])) : '';

            $satisfaction_scale_data['total_score'] = ((int) $satisfaction_scale_data['satisfied_assistance'] + (int) $satisfaction_scale_data['satisfied_counseling'] + (int) $satisfaction_scale_data['satisfied_economic'] + (int) $satisfaction_scale_data['satisfied_social'] + (int) $satisfaction_scale_data['satisfied_community'] + (int) $satisfaction_scale_data['satisfied_reintegration'] + (int) $satisfaction_scale_data['satisfied_expectation']);

            if ($is_update) {
                $satisfaction_scale_data['update_date'] = date('Y-m-d');
                $satisfaction_scale_data['update_time'] = date('H:i:s');
                $satisfaction_scale_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_scale_data, " pk_satisfaction_scale = '" . $is_update . "'");
            } else {
                $satisfaction_scale_data['fk_customer_id'] = $params['customer_id'];
                $satisfaction_scale_data['create_date'] = date('Y-m-d');
                $satisfaction_scale_data['create_time'] = date('H:i:s');
                $satisfaction_scale_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_scale_data);
            }
        }
        return $ret;
    }

    function get_cases($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM
                        dev_immediate_supports 
                        LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['report']) {
            $from = "FROM 
                        dev_immediate_supports
                        LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_supports ON (dev_psycho_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_economic_inkind ON (dev_economic_inkind.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_economic_training ON (dev_economic_training.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_financial_literacy ON (dev_financial_literacy.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_social_supports ON (dev_social_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
                        LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_immediate_supports.fk_customer_id)
            ";
        } else if ($param['immediate_support_only']) {
            $from = "FROM dev_immediate_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['reintegration_plan_only']) {
            $from = "FROM dev_reintegration_plan
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_reintegration_plan.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_reintegration_plan.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['psycho_supports_only']) {
            $from = "FROM dev_psycho_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_psycho_supports.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_psycho_supports.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['psycho_session_only']) {
            $from = "FROM dev_psycho_sessions
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_psycho_sessions.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_psycho_sessions.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['family_counseling_only']) {
            $from = "FROM dev_psycho_family_counselling
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_psycho_family_counselling.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_psycho_family_counselling.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['session_completion_only']) {
            $from = "FROM dev_psycho_completions
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_psycho_completions.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_psycho_completions.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['psycho_followups_only']) {
            $from = "FROM dev_psycho_followups
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_psycho_followups.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_psycho_followups.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['inkind_only']) {
            $from = "FROM dev_economic_inkind
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_economic_inkind.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_economic_inkind.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['economic_training_only']) {
            $from = "FROM dev_economic_training
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_economic_training.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_economic_training.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['financial_literacy_only']) {
            $from = "FROM dev_financial_literacy
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_financial_literacy.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_financial_literacy.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['economic_referrals_only']) {
            $from = "FROM dev_economic_referrals
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_economic_referrals.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_economic_referrals.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['referral_received_only']) {
            $from = "FROM dev_economic_referral_received
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_economic_referral_received.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_economic_referral_received.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['social_support_only']) {
            $from = "FROM dev_social_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_social_supports.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_social_supports.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else if ($param['followups_only']) {
            $from = "FROM dev_followups
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_followups.fk_customer_id)
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_followups.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";
        } else {
            $from = "FROM dev_immediate_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_initial_evaluation ON (dev_initial_evaluation.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_psycho_supports ON (dev_psycho_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_psycho_family_counselling ON (dev_psycho_family_counselling.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_psycho_sessions ON (dev_psycho_sessions.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_psycho_completions ON (dev_psycho_completions.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_psycho_followups ON (dev_psycho_followups.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_economic_inkind ON (dev_economic_inkind.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_economic_training ON (dev_economic_training.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_financial_literacy ON (dev_financial_literacy.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_social_supports ON (dev_social_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_followups ON (dev_followups.fk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_economic_profile ON (dev_economic_profile.fk_customer_id = dev_immediate_supports.fk_customer_id)
            ";
        }

        $where = " WHERE 1";

        if ($param['immediate_support_only']) {
            $conditions = " AND dev_immediate_supports.entry_date IS NOT NULL ";
        } else {
            $conditions = " ";
        }
        $sql = $select . $from . $where;

        if ($param['reintegration_plan_only']) {
            $count_sql = "SELECT COUNT(dev_reintegration_plan.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['psycho_supports_only']) {
            $count_sql = "SELECT COUNT(dev_psycho_supports.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['psycho_session_only']) {
            $count_sql = "SELECT COUNT(dev_psycho_sessions.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['family_counseling_only']) {
            $count_sql = "SELECT COUNT(dev_psycho_family_counselling.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['session_completion_only']) {
            $count_sql = "SELECT COUNT(dev_psycho_completions.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['psycho_followups_only']) {
            $count_sql = "SELECT COUNT(dev_psycho_followups.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['inkind_only']) {
            $count_sql = "SELECT COUNT(dev_economic_inkind.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['economic_training_only']) {
            $count_sql = "SELECT COUNT(dev_economic_training.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['financial_literacy_only']) {
            $count_sql = "SELECT COUNT(dev_financial_literacy.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['economic_referrals_only']) {
            $count_sql = "SELECT COUNT(dev_economic_referrals.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['referral_received_only']) {
            $count_sql = "SELECT COUNT(dev_economic_referral_received.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['social_support_only']) {
            $count_sql = "SELECT COUNT(dev_social_supports.fk_customer_id) AS TOTAL " . $from . $where;
        } else if ($param['followups_only']) {
            $count_sql = "SELECT COUNT(dev_followups.fk_customer_id) AS TOTAL " . $from . $where;
        } else {
            $count_sql = "SELECT COUNT(dev_immediate_supports.fk_customer_id) AS TOTAL " . $from . $where;
        }

        if ($param['immediate_support_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'support_date' => 'dev_immediate_supports.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['reintegration_plan_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_reintegration_plan.plan_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['psycho_supports_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_psycho_supports.first_meeting',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['psycho_session_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_psycho_sessions.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['family_counseling_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_psycho_family_counselling.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['session_completion_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_psycho_completions.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['psycho_followups_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_psycho_followups.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['inkind_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_economic_inkind.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['economic_training_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_economic_training.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['financial_literacy_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_economic_training.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['economic_referrals_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_economic_referrals.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['referral_received_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'entry_date' => 'dev_economic_referral_received.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['social_support_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'social_support_entry_date' => 'dev_social_supports.social_support_entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else if ($param['followups_only']) {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'social_support_entry_date' => 'dev_followups.entry_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        } else {
            $loopCondition = array(
                'id' => 'dev_immediate_supports.fk_customer_id',
                'customer_id' => 'dev_customers.customer_id',
                'project' => 'dev_customers.fk_project_id',
                'name' => 'dev_customers.full_name',
                'nid' => 'dev_customers.nid_number',
                'birth' => 'dev_customers.birth_reg_number',
                'country' => 'dev_migrations.final_destination',
                'division' => 'dev_customers.permanent_division',
                'district' => 'dev_customers.permanent_district',
                'sub_district' => 'dev_customers.permanent_sub_district',
                'project' => 'dev_customers.fk_project_id',
                'entry_date' => 'dev_customers.create_date',
                'branch_id' => 'dev_customers.fk_branch_id',
            );
        }

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function add_edit_case($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_cases(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid case id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            /*
              -------------------------------------------------------------------
              | Table Name : dev_immediate_supports
              |------------------------------------------------------------------
             */
            $immediate_support = array();
            $immediate_support['fk_branch_id'] = $_config['user']['user_branch'];
            $immediate_support['fk_staff_id'] = $params['form_data']['fk_staff_id'];
            $immediate_support['entry_date'] = $params['form_data']['support_date'] ? date('Y-m-d', strtotime($params['form_data']['support_date'])) : '';
            $immediate_support['arrival_place'] = $params['form_data']['arrival_place'];
            $immediate_support['comment'] = $params['form_data']['immediate_comment'];

            $data_type = $params['form_data']['immediate_support'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $immediate_support['immediate_support'] = $data_types;

            if ($is_update) {
                $immediate_support['update_date'] = date('Y-m-d');
                $immediate_support['update_time'] = date('H:i:s');
                $immediate_support['updated_by'] = $_config['user']['pk_user_id'];
                $ret['support_update'] = $devdb->insert_update('dev_immediate_supports', $immediate_support, " fk_customer_id = '" . $is_update . "'");
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_reintegration_plan
              |------------------------------------------------------------------
             */

            $reintegration_plan = array();
            $reintegration_plan['plan_date'] = $params['form_data']['plan_date'] ? date('Y-m-d', strtotime($params['form_data']['plan_date'])) : '';

            if ($params['form_data']['new_service_requested'] == NULL) {
                $data_type = $params['form_data']['service_requested'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $reintegration_plan['service_requested'] = $data_types;
            } elseif ($params['form_data']['service_requested'] == NULL) {
                $reintegration_plan['other_service_requested'] = $params['form_data']['new_service_requested'];
            } elseif ($params['form_data']['service_requested'] != NULL && $params['form_data']['new_service_requested'] != NULL) {
                $data_type = $params['form_data']['service_requested'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $reintegration_plan['service_requested'] = $data_types;
                $reintegration_plan['other_service_requested'] = $params['form_data']['new_service_requested'];
            }

            $reintegration_plan['reintegration_financial_service'] = $params['form_data']['reintegration_financial_service'];

            if ($params['form_data']['new_social_protection']) {
                $reintegration_plan['social_protection'] = $params['form_data']['new_social_protection'];
            }
            if ($params['form_data']['new_security_measures']) {
                $reintegration_plan['security_measure'] = $params['form_data']['new_security_measures'];
            }

            $reintegration_plan['service_requested_note'] = $params['form_data']['service_requested_note'];
            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_reintegration_plan WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $reintegration_plan['update_date'] = date('Y-m-d');
                    $reintegration_plan['update_time'] = date('H:i:s');
                    $reintegration_plan['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['reintegration_update'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_plan, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $reintegration_plan['fk_customer_id'] = $is_update;
                    $reintegration_plan['create_date'] = date('Y-m-d');
                    $reintegration_plan['create_time'] = date('H:i:s');
                    $reintegration_plan['created_by'] = $_config['user']['pk_user_id'];
                    $ret['reintegration_insert'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_plan);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_psycho_supports
              |------------------------------------------------------------------
             */

            $psycho_supports = array();
            $psycho_supports['first_meeting'] = $params['form_data']['first_meeting'] ? date('Y-m-d', strtotime($params['form_data']['first_meeting'])) : '';

            if ($params['form_data']['new_problem_identified'] == NULL) {
                $data_type = $params['form_data']['problem_identified'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['problem_identified'] = $data_types;
                $psycho_supports['other_problem_identified'] = '';
            } elseif ($params['form_data']['problem_identified'] == NULL) {
                $psycho_supports['other_problem_identified'] = $params['form_data']['new_problem_identified'];
            } elseif ($params['form_data']['problem_identified'] != NULL && $params['form_data']['new_problem_identified'] != NULL) {
                $data_type = $params['form_data']['problem_identified'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['problem_identified'] = $data_types;
                $psycho_supports['other_problem_identified'] = $params['form_data']['new_problem_identified'];
            }

            $psycho_supports['problem_description'] = $params['form_data']['problem_description'];
            $psycho_supports['initial_plan'] = $params['form_data']['initial_plan'];
            $psycho_supports['family_counseling'] = $params['form_data']['family_counseling'];

            if ($params['form_data']['new_session_place']) {
                $psycho_supports['session_place'] = $params['form_data']['new_session_place'];
            } else {
                $psycho_supports['session_place'] = $params['form_data']['session_place'];
            }

            $psycho_supports['session_number'] = $params['form_data']['session_number'];
            $psycho_supports['session_duration'] = $params['form_data']['session_duration'];
            $psycho_supports['other_requirements'] = $params['form_data']['other_requirements'];
            $psycho_supports['reffer_to'] = $params['form_data']['reffer_to'];
            $psycho_supports['referr_address'] = $params['form_data']['referr_address'];
            $psycho_supports['contact_number'] = $params['form_data']['contact_number'];

            if ($params['form_data']['new_reason_for_reffer'] == NULL) {
                $data_type = $params['form_data']['reason_for_reffer'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['reason_for_reffer'] = $data_types;
                $psycho_supports['other_reason_for_reffer'] = '';
            } elseif ($params['form_data']['reason_for_reffer'] == NULL) {
                $psycho_supports['other_reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'];
            } elseif ($params['form_data']['reason_for_reffer'] != NULL && $params['form_data']['new_reason_for_reffer'] != NULL) {
                $data_type = $params['form_data']['reason_for_reffer'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['reason_for_reffer'] = $data_types;
                $psycho_supports['other_reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'];
            }

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_psycho_supports WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $psycho_supports['update_date'] = date('Y-m-d');
                    $psycho_supports['update_time'] = date('H:i:s');
                    $psycho_supports['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['psycho_support_update'] = $devdb->insert_update('dev_psycho_supports', $psycho_supports, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $psycho_supports['fk_customer_id'] = $is_update;
                    $psycho_supports['create_date'] = date('Y-m-d');
                    $psycho_supports['create_time'] = date('H:i:s');
                    $psycho_supports['created_by'] = $_config['user']['pk_user_id'];

                    $ret['psycho_support_insert'] = $devdb->insert_update('dev_psycho_supports', $psycho_supports);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_economic_inkind
              |------------------------------------------------------------------
             */

            $economic_inkind_data = array();
            $economic_inkind_data['entry_date'] = $params['form_data']['economic_inkind_date'] ? date('Y-m-d', strtotime($params['form_data']['economic_inkind_date'])) : '';

            $economic_inkind_data['in_kind_type'] = $params['form_data']['in_kind_type'];
            $economic_inkind_data['organization_name'] = $params['form_data']['organization_name'];

            $economic_inkind_data['support_delivery_date'] = $params['form_data']['support_delivery_date'] ? date('Y-m-d', strtotime($params['form_data']['support_delivery_date'])) : '';

            if ($params['form_data']['new_inkind_project'] == NULL) {
                $data_type = $params['form_data']['inkind_project'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_inkind_data['inkind_project'] = $data_types;
                $economic_inkind_data['other_inkind_project'] = '';
            } elseif ($params['form_data']['inkind_project'] == NULL) {
                $economic_inkind_data['other_inkind_project'] = $params['form_data']['new_inkind_project'];
            } elseif ($params['form_data']['inkind_project'] != NULL && $params['form_data']['new_inkind_project'] != NULL) {
                $data_type = $params['form_data']['inkind_project'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_inkind_data['inkind_project'] = $data_types;
                $economic_inkind_data['other_inkind_project'] = $params['form_data']['new_inkind_project'];
            }

            $economic_inkind_data['support_amount'] = $params['form_data']['support_amount'];
            $economic_inkind_data['economic_support_delivered'] = $params['form_data']['economic_support_delivered'];

            if ($params['form_data']['new_business_type'] == NULL) {
                $data_type = $params['form_data']['business_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_inkind_data['business_type'] = $data_types;
                $economic_inkind_data['other_business_type'] = '';
            } elseif ($params['form_data']['business_type'] == NULL) {
                $economic_inkind_data['other_business_type'] = $params['form_data']['new_business_type'];
            } elseif ($params['form_data']['business_type'] != NULL && $params['form_data']['new_business_type'] != NULL) {
                $data_type = $params['form_data']['business_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_inkind_data['business_type'] = $data_types;
                $economic_inkind_data['other_business_type'] = $params['form_data']['new_business_type'];
            }

            $economic_inkind_data['economic_other_comments'] = $params['form_data']['economic_other_comments'];

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_economic_inkind WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $economic_inkind_data['update_date'] = date('Y-m-d');
                    $economic_inkind_data['update_time'] = date('H:i:s');
                    $economic_inkind_data['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['economic_inkind_update'] = $devdb->insert_update('dev_economic_inkind', $economic_inkind_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $economic_inkind_data['fk_customer_id'] = $is_update;
                    $economic_inkind_data['create_date'] = date('Y-m-d');
                    $economic_inkind_data['create_time'] = date('H:i:s');
                    $economic_inkind_data['created_by'] = $_config['user']['pk_user_id'];
                    $ret['economic_inkind_insert'] = $devdb->insert_update('dev_economic_inkind', $economic_inkind_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_economic_training
              |------------------------------------------------------------------
             */

            $economic_training_data = array();
            $economic_training_data['entry_date'] = $params['form_data']['economic_training_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['economic_training_entry_date'])) : '';

            $economic_training_data['training_type'] = $params['form_data']['training_type'];

            if ($params['form_data']['new_direct_training_type'] == NULL) {
                $data_type = $params['form_data']['direct_training_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_training_data['direct_training_type'] = $data_types;
                $economic_training_data['other_direct_training_type'] = '';
            } elseif ($params['form_data']['direct_training_type'] == NULL) {
                $economic_training_data['other_direct_training_type'] = $params['form_data']['new_direct_training_type'];
            } elseif ($params['form_data']['direct_training_type'] != NULL && $params['form_data']['new_direct_training_type'] != NULL) {
                $data_type = $params['form_data']['direct_training_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_training_data['direct_training_type'] = $data_types;
                $economic_training_data['other_direct_training_type'] = $params['form_data']['new_direct_training_type'];
            }

            $economic_training_data['training_institution_name'] = $params['form_data']['training_institution_name'];
            $economic_training_data['training_place'] = $params['form_data']['training_place'];

            $economic_training_data['economic_training_start_date'] = $params['form_data']['economic_training_start_date'] ? date('Y-m-d', strtotime($params['form_data']['economic_training_start_date'])) : '';
            $economic_training_data['economic_training_end_date'] = $params['form_data']['economic_training_end_date'] ? date('Y-m-d', strtotime($params['form_data']['economic_training_end_date'])) : '';

            $economic_training_data['is_certification_received'] = $params['form_data']['is_certification_received'];
            $economic_training_data['economic_training_comment'] = $params['form_data']['economic_training_comment'];

            if ($params['form_data']['new_referral_training_type'] == NULL) {
                $data_type = $params['form_data']['referral_training_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_training_data['referral_training_type'] = $data_types;
                $economic_training_data['other_referral_training_type'] = '';
            } elseif ($params['form_data']['referral_training_type'] == NULL) {
                $economic_training_data['other_referral_training_type'] = $params['form_data']['new_referral_training_type'];
            } elseif ($params['form_data']['referral_training_type'] != NULL && $params['form_data']['new_referral_training_type'] != NULL) {
                $data_type = $params['form_data']['referral_training_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_training_data['referral_training_type'] = $data_types;
                $economic_training_data['other_referral_training_type'] = $params['form_data']['new_referral_training_type'];
            }

            $economic_training_data['referral_training_institution_name'] = $params['form_data']['referral_training_institution_name'];
            $economic_training_data['referral_training_place'] = $params['form_data']['referral_training_place'];

            $economic_training_data['referral_economic_training_start_date'] = $params['form_data']['referral_economic_training_start_date'] ? date('Y-m-d', strtotime($params['form_data']['referral_economic_training_start_date'])) : '';
            $economic_training_data['referral_economic_training_end_date'] = $params['form_data']['referral_economic_training_end_date'] ? date('Y-m-d', strtotime($params['form_data']['referral_economic_training_end_date'])) : '';

            $economic_training_data['referral_certification_received'] = $params['form_data']['referral_certification_received'];
            $economic_training_data['referral_economic_training_comment'] = $params['form_data']['referral_economic_training_comment'];

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_economic_training WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $economic_training_data['update_date'] = date('Y-m-d');
                    $economic_training_data['update_time'] = date('H:i:s');
                    $economic_training_data['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['economic_training_update'] = $devdb->insert_update('dev_economic_training', $economic_training_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $economic_training_data['fk_customer_id'] = $is_update;
                    $economic_training_data['create_date'] = date('Y-m-d');
                    $economic_training_data['create_time'] = date('H:i:s');
                    $economic_training_data['created_by'] = $_config['user']['pk_user_id'];
                    $ret['economic_training_insert'] = $devdb->insert_update('dev_economic_training', $economic_training_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_financial_literacy
              |------------------------------------------------------------------
             */

            $dev_financial_literacy_data = array();
            $dev_financial_literacy_data['entry_date'] = $params['form_data']['financial_training_entry'] ? date('Y-m-d', strtotime($params['form_data']['financial_training_entry'])) : '';

            $data_type = $params['form_data']['financial_training_received'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $dev_financial_literacy_data['financial_training_received'] = $data_types;

            $dev_financial_literacy_data['financial_institution_name'] = $params['form_data']['financial_institution_name'];
            $dev_financial_literacy_data['financial_training_place'] = $params['form_data']['financial_training_place'];

            $dev_financial_literacy_data['financial_training_start_date'] = $params['form_data']['financial_training_start_date'] ? date('Y-m-d', strtotime($params['form_data']['financial_training_start_date'])) : '';
            $dev_financial_literacy_data['financial_training_end_date'] = $params['form_data']['financial_training_end_date'] ? date('Y-m-d', strtotime($params['form_data']['financial_training_end_date'])) : '';

            $dev_financial_literacy_data['financial_certification_received'] = $params['form_data']['financial_certification_received'];
            $dev_financial_literacy_data['financial_training_comment'] = $params['form_data']['financial_training_comment'];

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_financial_literacy WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $dev_financial_literacy_data['update_date'] = date('Y-m-d');
                    $dev_financial_literacy_data['update_time'] = date('H:i:s');
                    $dev_financial_literacy_data['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['financial_literacy_update'] = $devdb->insert_update('dev_financial_literacy', $dev_financial_literacy_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $dev_financial_literacy_data['fk_customer_id'] = $is_update;
                    $dev_financial_literacy_data['create_date'] = date('Y-m-d');
                    $dev_financial_literacy_data['create_time'] = date('H:i:s');
                    $dev_financial_literacy_data['created_by'] = $_config['user']['pk_user_id'];
                    $ret['financial_literacy_insert'] = $devdb->insert_update('dev_financial_literacy', $dev_financial_literacy_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_social_supports
              |------------------------------------------------------------------
             */

            $dev_social_supports_data = array();
            $dev_social_supports_data['social_support_entry_date'] = $params['form_data']['social_support_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['social_support_entry_date'])) : '';
            $dev_social_supports_data['medical_support_entry_date'] = $params['form_data']['medical_support_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['medical_support_entry_date'])) : '';

            if ($params['form_data']['new_support_referred'] == NULL) {
                $data_type = $params['form_data']['support_referred'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['support_referred'] = $data_types;
                $dev_social_supports_data['other_support_referred'] = '';
            } elseif ($params['form_data']['support_referred'] == NULL) {
                $dev_social_supports_data['other_support_referred'] = $params['form_data']['new_support_referred'];
            } elseif ($params['form_data']['support_referred'] != NULL && $params['form_data']['new_support_referred'] != NULL) {
                $data_type = $params['form_data']['support_referred'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['support_referred'] = $data_types;
                $dev_social_supports_data['other_support_referred'] = $params['form_data']['new_support_referred'];
            }

            $dev_social_supports_data['social_referred_entry_date'] = $params['form_data']['social_referred_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['social_referred_entry_date'])) : '';
            $dev_social_supports_data['social_referred_organization'] = $params['form_data']['social_referred_organization'];
            $dev_social_supports_data['social_organization_type'] = $params['form_data']['social_organization_type'];
            $dev_social_supports_data['social_organization_address'] = $params['form_data']['social_organization_address'];
            $dev_social_supports_data['social_organization_comment'] = $params['form_data']['social_organization_comment'];

            $dev_social_supports_data['other_social_protection'] = $params['form_data']['other_social_protection'];
            $dev_social_supports_data['medical_support_type'] = $params['form_data']['medical_support_type'];
            $dev_social_supports_data['medical_institution_name'] = $params['form_data']['medical_institution_name'];
            $dev_social_supports_data['treatment_allowance'] = $params['form_data']['treatment_allowance'];

            $dev_social_supports_data['treatment_allowance_date'] = $params['form_data']['treatment_allowance_date'] ? date('Y-m-d', strtotime($params['form_data']['treatment_allowance_date'])) : '';
            $dev_social_supports_data['treatment_allowance_comment'] = $params['form_data']['treatment_allowance_comment'];

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_social_supports WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $dev_social_supports_data['update_date'] = date('Y-m-d');
                    $dev_social_supports_data['update_time'] = date('H:i:s');
                    $dev_social_supports_data['updated_by'] = $_config['user']['pk_user_id'];
                    $ret['social_support_update'] = $devdb->insert_update('dev_social_supports', $dev_social_supports_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $dev_social_supports_data['fk_customer_id'] = $is_update;
                    $dev_social_supports_data['create_date'] = date('Y-m-d');
                    $dev_social_supports_data['create_time'] = date('H:i:s');
                    $dev_social_supports_data['created_by'] = $_config['user']['pk_user_id'];
                    $ret['social_support_insert'] = $devdb->insert_update('dev_social_supports', $dev_social_supports_data);
                }
            }
        }
        return $ret;
    }

    function get_family_counseling($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_family_counselling ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_family_counselling_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_family_counselling_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_family_counseling($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_family_counseling(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid family counseling id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $psycho_family_counselling_data = array();
            $psycho_family_counselling_data['entry_date'] = $params['form_data']['family_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['family_entry_date'])) : '';
            $psycho_family_counselling_data['entry_time'] = $params['form_data']['family_entry_time'];
            $psycho_family_counselling_data['session_end_time'] = $params['form_data']['session_end_time'];
            $psycho_family_counselling_data['session_place'] = $params['form_data']['session_place'];
            $psycho_family_counselling_data['activities_description'] = $params['form_data']['activities_description'];
            $psycho_family_counselling_data['session_comments'] = $params['form_data']['session_comments'];
            $psycho_family_counselling_data['male_counseled'] = $params['form_data']['male_counseled'];
            $psycho_family_counselling_data['female_counseled'] = $params['form_data']['female_counseled'];
            $psycho_family_counselling_data['members_counseled'] = $psycho_family_counselling_data['male_counseled'] + $psycho_family_counselling_data['female_counseled'];

            if ($is_update) {
                $psycho_family_counselling_data['update_date'] = date('Y-m-d');
                $psycho_family_counselling_data['update_time'] = date('H:i:s');
                $psycho_family_counselling_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_family_counselling', $psycho_family_counselling_data, " pk_psycho_family_counselling_id = '" . $is_update . "'");
            } else {
                $psycho_family_counselling_data['fk_customer_id'] = $params['customer_id'];
                $psycho_family_counselling_data['create_date'] = date('Y-m-d');
                $psycho_family_counselling_data['create_time'] = date('H:i:s');
                $psycho_family_counselling_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_family_counselling', $psycho_family_counselling_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_session($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_sessions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_session_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_session_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial_session($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_session(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial session id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $psycho_sessions_data = array();
            $psycho_sessions_data['entry_date'] = $params['form_data']['session_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['session_entry_date'])) : '';
            $psycho_sessions_data['entry_time'] = $params['form_data']['session_entry_time'];
            $psycho_sessions_data['session_end_time'] = $params['form_data']['session_end_time'];
            $psycho_sessions_data['activities_description'] = $params['form_data']['activities_description'];
            $psycho_sessions_data['session_comments'] = $params['form_data']['session_comments'];
            $psycho_sessions_data['next_date'] = $params['form_data']['next_date'] ? date('Y-m-d', strtotime($params['form_data']['next_date'])) : '';

            if ($is_update) {
                $psycho_sessions_data['update_date'] = date('Y-m-d');
                $psycho_sessions_data['update_time'] = date('H:i:s');
                $psycho_sessions_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_sessions', $psycho_sessions_data, " pk_psycho_session_id = '" . $is_update . "'");
            } else {
                $psycho_sessions_data['fk_customer_id'] = $params['customer_id'];
                $psycho_sessions_data['create_date'] = date('Y-m-d');
                $psycho_sessions_data['create_time'] = date('H:i:s');
                $psycho_sessions_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_sessions', $psycho_sessions_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_completion($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_completions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_completion_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_completion_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial_completion($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_completion(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial completion id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $psycho_completions_data = array();
            $psycho_completions_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $psycho_completions_data['is_completed'] = $params['form_data']['is_completed'];
            $psycho_completions_data['dropout_reason'] = $params['form_data']['dropout_reason'];
            $psycho_completions_data['review_session'] = $params['form_data']['review_session'];
            $psycho_completions_data['client_comments'] = $params['form_data']['client_comments'];
            $psycho_completions_data['counsellor_comments'] = $params['form_data']['counsellor_comments'];
            $psycho_completions_data['final_evaluation'] = $params['form_data']['final_evaluation'];
            $psycho_completions_data['required_session'] = $params['form_data']['required_session'];

            if ($is_update) {
                $psycho_completions_data['update_date'] = date('Y-m-d');
                $psycho_completions_data['update_time'] = date('H:i:s');
                $psycho_completions_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_completions', $psycho_completions_data, " pk_psycho_completion_id = '" . $is_update . "'");
            } else {
                $psycho_completions_data['fk_customer_id'] = $params['customer_id'];
                $psycho_completions_data['create_date'] = date('Y-m-d');
                $psycho_completions_data['create_time'] = date('H:i:s');
                $psycho_completions_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_completions', $psycho_completions_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_followup($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_followups ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_followup_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_followup_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_economic_referrals($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_economic_referrals ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_economic_referral_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_economic_referral_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_economic_referral($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_economic_referrals(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid economic referral id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $data = array();
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $data['referral_id'] = $params['form_data']['referral_id'];
            if ($params['form_data']['new_referred_for'] == NULL) {
                $data_type = $params['form_data']['referred_for'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['referred_for'] = $data_types;
                $data['other_referred_for'] = '';
            } elseif ($params['form_data']['referred_for'] == NULL) {
                $data['other_referred_for'] = $params['form_data']['new_referred_for'];
            } elseif ($params['form_data']['referred_for'] != NULL && $params['form_data']['new_referred_for'] != NULL) {
                $data_type = $params['form_data']['referred_for'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['referred_for'] = $data_types;
                $data['other_referred_for'] = $params['form_data']['new_referred_for'];
            }
            $data['referred_organization'] = $params['form_data']['referred_organization'];
            $data['referred_organization_address'] = $params['form_data']['referred_organization_address'];
            $data['referral_date'] = $params['form_data']['referral_date'] ? date('Y-m-d', strtotime($params['form_data']['referral_date'])) : '';
            $data['referral_comment'] = $params['form_data']['referral_comment'];
            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_economic_referrals', $data, " pk_economic_referral_id = '" . $is_update . "'");
            } else {
                $data['fk_customer_id'] = $params['customer_id'];
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_economic_referrals', $data);
            }
        }
        return $ret;
    }

    function get_economic_referral_received($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_economic_referral_received ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_economic_referral_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_economic_referral_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_received_economic_referral($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_economic_referral_received(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid economic referral id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $data = array();
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $data['referral_id'] = $params['form_data']['referral_id'];
            if ($params['form_data']['new_referred_for'] == NULL) {
                $data_type = $params['form_data']['referred_for'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['referred_for'] = $data_types;
                $data['other_referred_for'] = '';
            } elseif ($params['form_data']['referred_for'] == NULL) {
                $data['other_referred_for'] = $params['form_data']['new_referred_for'];
            } elseif ($params['form_data']['referred_for'] != NULL && $params['form_data']['new_referred_for'] != NULL) {
                $data_type = $params['form_data']['referred_for'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['referred_for'] = $data_types;
                $data['other_referred_for'] = $params['form_data']['new_referred_for'];
            }
            $data['referral_service_provider'] = $params['form_data']['referral_service_provider'];
            $data['referral_service_provider_address'] = $params['form_data']['referral_service_provider_address'];
            $data['received_date'] = $params['form_data']['received_date'] ? date('Y-m-d', strtotime($params['form_data']['received_date'])) : '';
            $data['referral_comment'] = $params['form_data']['referral_comment'];
            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_economic_referral_received', $data, " pk_economic_referral_id = '" . $is_update . "'");
            } else {
                $data['fk_customer_id'] = $params['customer_id'];
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_economic_referral_received', $data);
            }
        }
        return $ret;
    }

    function add_edit_psychosocial_followup($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_followup(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial followup id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $psycho_followups_data = array();
            $psycho_followups_data['entry_time'] = $params['form_data']['followup_entry_time'];
            $psycho_followups_data['session_end_time'] = $params['form_data']['session_end_time'];
            $psycho_followups_data['entry_date'] = $params['form_data']['followup_entry_date'] ? date('Y-m-d', strtotime($params['form_data']['followup_entry_date'])) : '';
            $psycho_followups_data['followup_comments'] = $params['form_data']['followup_comments'];

            if ($is_update) {
                $psycho_followups_data['update_date'] = date('Y-m-d');
                $psycho_followups_data['update_time'] = date('H:i:s');
                $psycho_followups_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_followups', $psycho_followups_data, " pk_psycho_followup_id = '" . $is_update . "'");
            } else {
                $psycho_followups_data['fk_customer_id'] = $params['customer_id'];
                $psycho_followups_data['create_date'] = date('Y-m-d');
                $psycho_followups_data['create_time'] = date('H:i:s');
                $psycho_followups_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_psycho_followups', $psycho_followups_data);
            }
        }
        return $ret;
    }

    function get_case_review($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['income_comparison']) {
            $from = "FROM dev_followups
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_followups.fk_customer_id) 
                LEFT JOIN dev_economic_profile ON (dev_economic_profile.fk_customer_id = dev_followups.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id) 
                ";
        } else if ($param['individual_income_info']) {
            $from = "FROM dev_economic_profile
                ";
        } else if ($param['individual_income_comparison']) {
            $from = "FROM dev_followups
                ";
        } else {
            $from = "FROM dev_followups ";
        }

        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_followup_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_followups.pk_followup_id',
            'fk_customer_id' => 'dev_followups.fk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'economic_profile_id' => 'dev_economic_profile.fk_customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'entry_date' => 'dev_followups.entry_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);
        return $result;
    }

    function add_edit_review($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_case_review(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid review followup id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $followups_data = array();
            $followups_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $followups_data['support_status'] = $params['form_data']['support_status'];

            $data_type = $params['form_data']['support_received'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $followups_data['support_received'] = $data_types;

            $data_type = $params['form_data']['confirm_services'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $followups_data['confirm_services'] = $data_types;

            if ($params['form_data']['followup_financial_service']):
                $followups_data['followup_financial_service'] = $params['form_data']['followup_financial_service'];
            endif;

            $followups_data['psychosocial_date'] = $params['form_data']['psychosocial_date'] ? date('Y-m-d', strtotime($params['form_data']['psychosocial_date'])) : '';

            $followups_data['psychosocial_problem'] = $params['form_data']['psychosocial_problem'];
            $followups_data['psychosocial_action'] = $params['form_data']['psychosocial_action'];
            $followups_data['psychosocial_participant'] = $params['form_data']['psychosocial_participant'];
            $followups_data['psychosocial_counselor'] = $params['form_data']['psychosocial_counselor'];

            $followups_data['economic_date'] = $params['form_data']['economic_date'] ? date('Y-m-d', strtotime($params['form_data']['economic_date'])) : '';

            $followups_data['monthly_average_income'] = $params['form_data']['monthly_average_income'];
            $followups_data['economic_challenges'] = $params['form_data']['economic_challenges'];
            $followups_data['economic_action'] = $params['form_data']['economic_action'];

            if ($params['form_data']['new_significant_changes'] == NULL) {
                $data_type = $params['form_data']['significant_changes'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $followups_data['significant_changes'] = $data_types;
                $followups_data['other_significant_changes'] = '';
            } elseif ($params['form_data']['significant_changes'] == NULL) {
                $followups_data['other_significant_changes'] = $params['form_data']['new_significant_changes'];
            } elseif ($params['form_data']['significant_changes'] != NULL && $params['form_data']['new_significant_changes'] != NULL) {
                $data_type = $params['form_data']['significant_changes'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $followups_data['significant_changes'] = $data_types;
                $followups_data['other_significant_changes'] = $params['form_data']['new_significant_changes'];
            }

            $followups_data['economic_participant'] = $params['form_data']['economic_participant'];
            $followups_data['economic_officer'] = $params['form_data']['economic_officer'];
            $followups_data['economic_manager'] = $params['form_data']['economic_manager'];

            $followups_data['social_date'] = $params['form_data']['social_date'] ? date('Y-m-d', strtotime($params['form_data']['social_date'])) : '';

            $followups_data['social_challenges'] = $params['form_data']['social_challenges'];
            $followups_data['social_action'] = $params['form_data']['social_action'];

            $data_type = $params['form_data']['social_changes'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $followups_data['social_changes'] = $data_types;

            $followups_data['social_participant'] = $params['form_data']['social_participant'];
            $followups_data['social_officer'] = $params['form_data']['social_officer'];
            $followups_data['social_manager'] = $params['form_data']['social_manager'];

            if ($is_update) {
                $followups_data['update_date'] = date('Y-m-d');
                $followups_data['update_time'] = date('H:i:s');
                $followups_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_followups', $followups_data, " pk_followup_id = '" . $is_update . "'");
            } else {
                $followups_data['fk_customer_id'] = $params['customer_id'];
                $followups_data['create_date'] = date('Y-m-d');
                $followups_data['create_time'] = date('H:i:s');
                $followups_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_followups', $followups_data);
            }
        }
        return $ret;
    }

    function get_returnees($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['report']) :
            $from = "FROM dev_returnees 
                LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_returnees.fk_branch_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_returnees.fk_project_id)
            ";
        else :
            $from = "FROM dev_returnees ";
        endif;

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_returnees.pk_returnee_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_returnee_id',
            'returnee_id' => 'returnee_id',
            'name' => 'full_name',
            'gender' => 'returnee_gender',
            'project' => 'fk_project_id',
            'country' => 'destination_country',
            'division' => 'permanent_division',
            'district' => 'permanent_district',
            'sub_district' => 'permanent_sub_district',
            'municipality' => 'permanent_municipality',
            'city_corporation' => 'permanent_city_corporation',
            'union' => 'permanent_union',
            'collection_date' => 'collection_date',
            'return_date' => 'return_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $returnees = sql_data_collector($sql, $count_sql, $param);
        return $returnees;
    }

    function add_edit_returnee($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_returnees(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid returnee id, no data found']);
            }
        }

        $nid_number = $params['form_data']['nid_number'];
        $birth_reg_number = $params['form_data']['birth_reg_number'];
        $passport_number = $params['form_data']['passport_number'];

        if (!($nid_number || $birth_reg_number || $passport_number)) :
            $ret['error'][] = 'Please enter at least one of <strong>NID, Birth Registration or Passport number</strong>';
        endif;

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $returnee_data = array();
            $returnee_data['fk_branch_id'] = $params['form_data']['fk_branch_id'];
            $returnee_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $returnee_data['returnee_id'] = $params['form_data']['returnee_id'];
            $returnee_data['brac_info_id'] = $params['form_data']['brac_info_id'];
            $returnee_data['collection_date'] = $params['form_data']['collection_date'] ? date('Y-m-d', strtotime($params['form_data']['collection_date'])) : '';
            $returnee_data['person_type'] = $params['form_data']['person_type'];
            $returnee_data['full_name'] = $params['form_data']['full_name'];
            if ($params['form_data']['new_gender']) {
                $returnee_data['returnee_gender'] = $params['form_data']['new_gender'];
            } else {
                $returnee_data['returnee_gender'] = $params['form_data']['returnee_gender'];
            }
            $returnee_data['mobile_number'] = $params['form_data']['mobile_number'];
            $returnee_data['emergency_mobile'] = $params['form_data']['emergency_mobile'];
            $returnee_data['nid_number'] = $params['form_data']['nid_number'];
            $returnee_data['birth_reg_number'] = $params['form_data']['birth_reg_number'];
            $returnee_data['passport_number'] = $params['form_data']['passport_number'];
            $returnee_data['father_name'] = $params['form_data']['father_name'];
            $returnee_data['mother_name'] = $params['form_data']['mother_name'];
            $returnee_data['marital_status'] = $params['form_data']['marital_status'];
            $returnee_data['returnee_spouse'] = $params['form_data']['returnee_spouse'];
            $returnee_data['permanent_division'] = $params['form_data']['permanent_division'];
            $returnee_data['permanent_district'] = $params['form_data']['permanent_district'];
            $returnee_data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $returnee_data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $returnee_data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $returnee_data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $returnee_data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $returnee_data['permanent_union'] = $params['form_data']['permanent_union'];
            $returnee_data['permanent_village'] = $params['form_data']['permanent_village'];
            $returnee_data['departure_date'] = $params['form_data']['departure_date'] ? date('Y-m-d', strtotime($params['form_data']['departure_date'])) : '';
            $returnee_data['return_date'] = $params['form_data']['return_date'] ? date('Y-m-d', strtotime($params['form_data']['return_date'])) : '';
            $returnee_data['destination_country'] = $params['form_data']['destination_country'];
            $returnee_data['destination_city'] = $params['form_data']['destination_city'];

            if ($params['form_data']['new_legal_document'] == NULL) {
                $data_type = $params['form_data']['legal_document'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $returnee_data['legal_document'] = $data_types;
                $returnee_data['other_legal_document'] = '';
            } elseif ($params['form_data']['legal_document'] == NULL) {
                $returnee_data['other_legal_document'] = $params['form_data']['new_legal_document'];
            } elseif ($params['form_data']['legal_document'] != NULL && $params['form_data']['new_legal_document'] != NULL) {
                $data_type = $params['form_data']['legal_document'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $returnee_data['legal_document'] = $data_types;
                $returnee_data['other_legal_document'] = $params['form_data']['new_legal_document'];
            }

            $returnee_data['remigrate_intention'] = $params['form_data']['remigrate_intention'];

            if ($params['form_data']['new_qualification']) {
                $returnee_data['educational_qualification'] = $params['form_data']['new_qualification'];
            } else {
                $returnee_data['educational_qualification'] = $params['form_data']['educational_qualification'];
            }

            $returnee_data['destination_country_profession'] = $params['form_data']['destination_country_profession'];
            $returnee_data['profile_selection'] = $params['form_data']['profile_selection'];
            $returnee_data['remarks'] = $params['form_data']['remarks'];

            if ($is_update) {
                $returnee_data['update_date'] = date('Y-m-d');
                $returnee_data['update_time'] = date('H:i:s');
                $returnee_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_returnees', $returnee_data, " pk_returnee_id = '" . $is_update . "'");
            } else {
                $returnee_data['create_date'] = date('Y-m-d');
                $returnee_data['create_time'] = date('H:i:s');
                $returnee_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_returnees', $returnee_data);
            }
        }
        return $ret;
    }

    function count_immediate_supports($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_immediate_supports.fk_customer_id) AS immediate_supports ";

        $from = "FROM dev_immediate_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " AND dev_immediate_supports.entry_date IS NOT NULL";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_immediate_supports.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_reintegration_plan($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_reintegration_plan.fk_customer_id) AS reintegration_plan ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " AND dev_reintegration_plan.update_date IS NOT NULL";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_reintegration_plan.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_psycho_supports($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_psycho_supports.fk_customer_id) AS psycho_supports ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_psycho_supports ON (dev_psycho_supports.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " AND dev_psycho_supports.update_date IS NOT NULL";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_supports.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_family_counseling($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_psycho_family_counselling.fk_customer_id) AS family_counseling ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_psycho_family_counselling ON (dev_psycho_family_counselling.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_family_counselling.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_psycho_sessions($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_psycho_sessions.fk_customer_id) AS psycho_sessions ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_psycho_sessions ON (dev_psycho_sessions.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = "";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_sessions.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_psycho_completions($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_psycho_completions.fk_customer_id) AS psycho_completions ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_psycho_completions ON (dev_psycho_completions.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_completions.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_psycho_followups($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_psycho_followups.fk_customer_id) AS psycho_followups ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_psycho_followups ON (dev_psycho_followups.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_followups.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_economic_inkind($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_economic_inkind.fk_customer_id) AS economic_inkind ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_economic_inkind ON (dev_economic_inkind.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_economic_inkind.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_economic_training($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_economic_training.fk_customer_id) AS economic_training ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_economic_training ON (dev_economic_training.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_economic_training.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_financial_literacy($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_financial_literacy.fk_customer_id) AS financial_literacy ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_financial_literacy ON (dev_financial_literacy.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_financial_literacy.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_economic_referrals($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_economic_referrals.fk_customer_id) AS economic_referrals ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_economic_referrals ON (dev_economic_referrals.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_economic_referrals.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_economic_referral_received($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_economic_referral_received.fk_customer_id) AS economic_referral_received ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_economic_referral_received ON (dev_economic_referral_received.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_economic_referral_received.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_social_supports($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_social_supports.social_support_entry_date) AS social_supports ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_social_supports ON (dev_social_supports.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_social_supports.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_medical_supports($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_social_supports.medical_support_entry_date) AS medical_supports ";

        $from = "FROM dev_customers
                   LEFT JOIN dev_social_supports ON (dev_social_supports.fk_customer_id = dev_customers.pk_customer_id)
                   LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_social_supports.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function count_followups($param = null) {
        $param['single'] = true;

        $select = "SELECT COUNT(dev_followups.fk_customer_id) AS followups ";

        $from = "FROM dev_followups
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_followups.fk_customer_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_customers.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_followups.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'project' => 'dev_customers.fk_project_id',
            'customer_id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'create_date' => 'dev_customers.create_date',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

}

new dev_customer_management();
