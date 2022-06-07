<?php

class immediate_support {

    var $thsClass = 'immediate_support';
    var $all_gender = array(
        'male' => 'Men (>=18)',
        'female' => 'Women (>=18)');
    var $service_received = array(
        'food' => array(
            'q' => 'Food',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'information' => array(
            'q' => 'Information',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'medical_treatment' => array(
            'q' => 'Medical Treatment',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'transport_support' => array(
            'q' => 'Transport Support',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'accommodation' => array(
            'q' => 'Accommodation',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'mobile_communication' => array(
            'q' => 'Mobile Communication',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'health_kits' => array(
            'q' => 'Health Kits',
            'a' => array(
                'Yes', 'No'
            ),
        ),
        'psychosocial_counseling' => array(
            'q' => 'Psychosocial Counseling',
            'a' => array(
                'Yes', 'No'
            ),
        ),
    );
    var $all_ports = array(
        'Benapole Land Port',
        'Burimari Land Port',
        'Akhaura Land Port',
        'Bhomra Land Port',
        'Nakugaon Land Port',
        'Tamabil Land Port',
        'Darshana Land Port',
        'Belonia Land Port',
        'Gobrakura-Karaitali Land Port',
        'Ramgarh Land Port',
        'Sonahat Land Port',
        'Tegamukh Land Port',
        'Chilahati Land Port',
        'Daulatganj Land Port',
        'Dhanua Kamalpur Land Port',
        'Sheola Land Port',
        'Balla Land Port',
        'Sonamasjid Land Port',
        'Hili Land Port',
        'Banglabandha Land Port',
        'Teknaf Land Port',
        'Bibirbazar Land Port',
        'Birol Land Port',
        'Chittagong Seaport',
        'Mongla Seaport',
        'Payra Seaport',
        'Teknaf Seaarea',
        'Barisal Seaport',
        'Coxbazar Seaarea',
        'Patenga Seaarea',
        'Hazrat Shahjalal International Airport',
        'Shah Amanat International Airport',
        'Osmani International Airport',
    );
    var $all_service_type = array(
        'Death body' => 'Death body',
        'Rescue' => 'Rescue',
        'Claim Compensation' => 'Claim Compensation',
        'Treatment Support' => 'Treatment Support',
        'Student Stipend' => 'Student Stipend',
        'Job Placement' => 'Job Placement',
        'Employer' => 'Employer',
        'Agent' => 'Agent',
        'Middle Man' => 'Middle Man',
    );
    var $all_rescue_reasons = array(
        'physical_torture' => 'Physical Torture',
        'sexual_abuse' => 'Sexual Abuse',
        'deportation' => 'Deportation',
        'undocumented' => 'Undocumented',
        'accident' => 'Accident',
        'unemployment' => 'Unemployment',
    );
    var $all_complains = array(
        'BMET' => 'BMET',
        'WEWB' => 'WEWB',
        'MOHA' => 'MOHA',
        'MOFA' => 'MOFA',
        'MoEWOE' => 'MoEWOE',
        'DEMO' => 'DEMO',
        'Law Enforcement Agency' => 'Law Enforcement Agency',
    );

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Immediate Support',
            'permissions' => array(
                'manage_airport_land_support' => array(
                    'add_airport_land_support' => 'Add Airport Land Support',
                    'edit_airport_land_support' => 'Edit Airport Land Support',
                    'delete_airport_land_support' => 'Delete Airport Land Support',
                ),
                'manage_access_to_pp' => array(
                    'add_access_to_pp' => 'Add Access To Service',
                    'edit_access_to_pp' => 'Edit Access To Service',
                    'delete_access_to_pp' => 'Delete Access To Service',
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
            'label' => 'Immediate Support',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Airport/Land Support',
            'description' => 'Manage All Airport/Land Support',
            'menu_group' => 'Immediate Support',
            'position' => 'default',
            'action' => 'manage_airport_land_support',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_airport_land_support'))
            admenu_register($params);

        $params = array(
            'label' => 'Access To Service',
            'description' => 'Manage All Access To Service',
            'menu_group' => 'Immediate Support',
            'position' => 'default',
            'action' => 'manage_access_to_pp',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_access_to_pp'))
            admenu_register($params);
    }

    function manage_airport_land_support() {
        if (!has_permission('manage_airport_land_support'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_airport_land_support');

        if ($_GET['action'] == 'add_edit_airport_land_support')
            include('pages/add_edit_airport_land_support.php');
        elseif ($_GET['action'] == 'deleteAirportLandSupport')
            include('pages/deleteAirportLandSupport.php');
        else
            include('pages/list_airport_land_support.php');
    }

    function manage_access_to_pp() {
        if (!has_permission('manage_access_to_pp'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_access_to_pp');

        if ($_GET['action'] == 'add_edit_access_to_pp')
            include('pages/add_edit_access_to_pp.php');
        elseif ($_GET['action'] == 'deleteAccessToPP')
            include('pages/deleteAccessToPP.php');
        else
            include('pages/list_access_to_pp.php');
    }

    function get_airport_land_supports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_airport_land_supports
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_airport_land_supports.fk_project_id)";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_airport_land_supports.pk_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_support_id',
            'brac_info_id' => 'brac_info_id',
            'name' => 'full_name',
            'country' => 'dev_airport_land_supports.destination_country',
            'division' => 'division',
            'district' => 'district',
            'upazilla' => 'upazilla',
            'city_corporation' => 'dev_airport_land_supports.city_corporation',
            'service_received' => 'dev_airport_land_supports.service_received',
            'project_id' => 'fk_project_id',
            'gender' => 'dev_airport_land_supports.gender',
            'create_date' => 'dev_airport_land_supports.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function add_edit_airport_land_support($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_airport_land_supports(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid airport/land support id, no data found']);
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
            $data['fk_project_id'] = $params['form_data']['project_id'];
            $data['brac_info_id'] = $params['form_data']['brac_info_id'];
            $data['return_route'] = $params['form_data']['return_route'];
            $data['port_name'] = $params['form_data']['port_name'];
            $data['arrival_date'] = $params['form_data']['arrival_date'] ? date('Y-m-d', strtotime($params['form_data']['arrival_date'])) : '';
            $data['person_type'] = $params['form_data']['person_type'];
            $data['full_name'] = $params['form_data']['full_name'];
            $data['gender'] = $params['form_data']['gender'];
            $data['is_disable'] = $params['form_data']['is_disable'];
            $data['passport_number'] = $params['form_data']['passport_number'];
            $data['travel_pass'] = $params['form_data']['travel_pass'];
            $data['mobile_number'] = $params['form_data']['mobile_number'];
            $data['emergency_mobile'] = $params['form_data']['emergency_mobile'];
            $data['division'] = $params['form_data']['division'];
            $data['district'] = $params['form_data']['district'];
            $data['upazilla'] = $params['form_data']['upazilla'];
            $data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $data['user_union'] = $params['form_data']['user_union'];
            $data['village'] = $params['form_data']['village'];
            $data['destination_country'] = $params['form_data']['destination_country'];
            $data['food'] = $params['form_data']['food'];
            $data['information'] = $params['form_data']['information'];
            $data['medical_treatment'] = $params['form_data']['medical_treatment'];
            $data['transport_support'] = $params['form_data']['transport_support'];
            $data['accommodation'] = $params['form_data']['accommodation'];
            $data['mobile_communication'] = $params['form_data']['mobile_communication'];
            $data['health_kits'] = $params['form_data']['health_kits'];
            $data['psychosocial_counseling'] = $params['form_data']['psychosocial_counseling'];
            $data['other_service_received'] = $params['form_data']['other_service_received'];

            if ($is_update) {
                $data['modify_date'] = date('Y-m-d');
                $data['modify_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_airport_land_supports', $data, " pk_support_id = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_airport_land_supports', $data);
            }
        }
        return $ret;
    }

    function get_access_to_pp($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_access_to_pp
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_access_to_pp.fk_project_id)";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_access_to_pp.pk_access_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_access_id',
            'brac_info_id' => 'brac_info_id',
            'name' => 'full_name',
            'division' => 'division',
            'district' => 'district',
            'upazilla' => 'upazilla',
            'gender' => 'dev_access_to_pp.gender',
            'country' => 'dev_access_to_pp.destination_country',
            'city_corporation' => 'dev_access_to_pp.city_corporation',
            'problem' => 'dev_access_to_pp.rescue_reason',
            'project_id' => 'fk_project_id',
            'create_date' => 'dev_access_to_pp.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $migrations = sql_data_collector($sql, $count_sql, $param);
        return $migrations;
    }

    function add_edit_access_to_pp($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_access_to_pp(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid id, no data found']);
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
            $data['fk_project_id'] = $params['form_data']['project_id'];
            $data['brac_info_id'] = $params['form_data']['brac_info_id'];
            $data['full_name'] = $params['form_data']['full_name'];
            $data['gender'] = $params['form_data']['gender'];
            $data['disability'] = $params['form_data']['disability'];
            $data['mobile'] = $params['form_data']['mobile_number'];
            $data['division'] = $params['form_data']['division'];
            $data['district'] = $params['form_data']['district'];
            $data['upazilla'] = $params['form_data']['upazilla'];
            $data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $data['user_union'] = $params['form_data']['user_union'];
            $data['village'] = $params['form_data']['village'];

            if ($params['form_data']['new_service_type'] == NULL) {
                $data_type = $params['form_data']['service_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['service_type'] = $data_types;
                $data['other_service_type'] = '';
            } elseif ($params['form_data']['service_type'] == NULL) {
                $data['other_service_type'] = $params['form_data']['new_service_type'];
            } elseif ($params['form_data']['service_type'] != NULL && $params['form_data']['new_service_type'] != NULL) {
                $data_type = $params['form_data']['service_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['service_type'] = $data_types;
                $data['other_service_type'] = $params['form_data']['new_service_type'];
            }

            if ($params['form_data']['new_rescue']) {
                $data['rescue_reason'] = $params['form_data']['new_rescue'];
            } else {
                $data['rescue_reason'] = $params['form_data']['rescue_reason'];
            }

            $data['destination_country'] = $params['form_data']['destination_country'];
            $data['support_date'] = $params['form_data']['support_date'] ? date('Y-m-d', strtotime($params['form_data']['support_date'])) : '';

            if ($params['form_data']['new_complain_to'] == NULL) {
                $data_type = $params['form_data']['complain_to'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['complain_to'] = $data_types;
                $data['other_complain_to'] = '';
            } elseif ($params['form_data']['complain_to'] == NULL) {
                $data['other_complain_to'] = $params['form_data']['new_complain_to'];
            } elseif ($params['form_data']['complain_to'] != NULL && $params['form_data']['new_complain_to'] != NULL) {
                $data_type = $params['form_data']['complain_to'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['complain_to'] = $data_types;
                $data['other_complain_to'] = $params['form_data']['new_complain_to'];
            }

            $data['service_result'] = $params['form_data']['service_result'];
            $data['return_date'] = $params['form_data']['return_date'] ? date('Y-m-d', strtotime($params['form_data']['return_date'])) : '';
            $data['comment'] = $params['form_data']['comment'];

            if ($is_update) {
                $data['modify_date'] = date('Y-m-d');
                $data['modify_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_access_to_pp', $data, " pk_access_id = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_access_to_pp', $data);
            }
        }
        return $ret;
    }

}

new immediate_support();
