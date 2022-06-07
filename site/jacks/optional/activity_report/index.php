<?php

class dev_activity_report {

    var $thsClass = 'dev_activity_report';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Activity Report',
            'permissions' => array(
                'manage_misactivities' => array(
                    'add_misactivity' => 'Add MIS Activity',
                    'edit_misactivity' => 'Edit MIS Activity',
                    'delete_misactivity' => 'Delete MIS Activity',
                ),
                'manage_targets' => array(
                    'add_target' => 'Add Target',
                    'edit_target' => 'Edit Target',
                    'delete_target' => 'Delete Target',
                ),
                'manage_achievements' => array(
                    'add_achievement' => 'Add Achievement',
                    'edit_achievement' => 'Edit Achievement',
                    'delete_achievement' => 'Delete Achievement',
                ),
                'manage_misreports' => array(
                    'view_misreport' => 'View MIS Report',
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
            'label' => 'Activity Report',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Activities Management',
            'description' => 'Manage All MIS Activities',
            'menu_group' => 'Activity Report',
            'position' => 'default',
            'action' => 'manage_misactivities',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misactivities'))
            admenu_register($params);

        $params = array(
            'label' => 'Target Management',
            'description' => 'Manage All MIS Target',
            'menu_group' => 'Activity Report',
            'position' => 'default',
            'action' => 'manage_targets',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_targets'))
            admenu_register($params);

        $params = array(
            'label' => 'Achievements',
            'description' => 'Manage All Achievements',
            'menu_group' => 'Activity Report',
            'position' => 'default',
            'action' => 'manage_achievements',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_achievements'))
            admenu_register($params);

        $params = array(
            'label' => 'MIS Report',
            'description' => 'View All MIS Reports',
            'menu_group' => 'Activity Report',
            'position' => 'default',
            'action' => 'manage_misreports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project',
            'description' => 'View Project Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project and Dates',
            'description' => 'View Project and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_date_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project and Division',
            'description' => 'View Project and Division Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project and Branch',
            'description' => 'View Project and Branch Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_branch_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Branch and Dates',
            'description' => 'View Project, Branch and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_branch_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Division and Dates',
            'description' => 'View Project, Division and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Division and District',
            'description' => 'View Project, Division and District Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_district_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Division, District and Dates',
            'description' => 'View Project, Division, District and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_district_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Division, District and Upazila',
            'description' => 'View Project, Division, District and Upazila Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_district_upazila_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project, Division, District, Upazila and Dates',
            'description' => 'View Project, Division, District, Upazila and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_project_division_district_upazila_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Branch',
            'description' => 'View Branch Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_branch_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Branch and Dates',
            'description' => 'View Branch and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_branch_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division',
            'description' => 'View Division Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division and Dates',
            'description' => 'View Division and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division and District',
            'description' => 'View Division and District Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_district_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division, District and Dates',
            'description' => 'View Division, District and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_district_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division, District and Upazila',
            'description' => 'View Division, District and Upazila Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_district_upazila_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);

        $params = array(
            'label' => 'Division, District, Upazila and Dates',
            'description' => 'View Division, District, Upazila and Dates Reports',
            'menu_group' => 'MIS Report',
            'position' => 'top',
            'action' => 'manage_division_district_upazila_date_mis_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);
    }

    function manage_misactivities() {
        if (!has_permission('manage_misactivities'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_misactivities');

        if ($_GET['action'] == 'add_edit_misactivity')
            include('pages/add_edit_misactivity.php');
        elseif ($_GET['action'] == 'deleteMisactivity')
            include('pages/deleteMisactivity.php');
        else
            include('pages/list_misactivities.php');
    }

    function manage_targets() {
        if (!has_permission('manage_targets'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_targets');

        if ($_GET['action'] == 'add_edit_target')
            include('pages/add_edit_target.php');
        elseif ($_GET['action'] == 'add_edit_mis_target')
            include('pages/add_edit_mis_target.php');
        elseif ($_GET['action'] == 'deleteTarget')
            include('pages/deleteTarget.php');
        else
            include('pages/list_targets.php');
    }

    function manage_achievements() {
        if (!has_permission('manage_achievements'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_achievements');

        include('pages/list_achievements.php');
    }

    function manage_project_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_mis_reports');

        include('pages/list_project_mis_reports.php');
    }

    function manage_project_division_district_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_district_date_mis_reports');

        include('pages/list_project_division_district_date_reports.php');
    }

    function manage_project_division_district_upazila_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_district_upazila_mis_reports');

        include('pages/list_project_division_district_upazila_reports.php');
    }

    function manage_project_date_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_date_reports');

        include('pages/list_project_date_reports.php');
    }

    function manage_project_division_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_mis_reports');

        include('pages/list_project_division_reports.php');
    }

    function manage_division_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_mis_reports');

        include('pages/list_division_mis_reports.php');
    }

    function manage_branch_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_branch_mis_reports');

        include('pages/list_branch_mis_reports.php');
    }

    function manage_branch_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_branch_date_mis_reports');

        include('pages/list_branch_date_reports.php');
    }

    function manage_division_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_date_mis_reports');

        include('pages/list_division_date_reports.php');
    }

    function manage_division_district_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_district_mis_reports');

        include('pages/list_division_district_reports.php');
    }

    function manage_project_branch_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_and_branch_reports');

        include('pages/list_project_branch_reports.php');
    }

    function manage_project_branch_date_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_branch_date_reports');

        include('pages/list_project_branch_date_reports.php');
    }

    function manage_project_branch_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_branch_date_mis_reports');

        include('pages/list_project_branch_date_reports.php');
    }

    function manage_project_division_district_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_district_mis_reports');

        include('pages/list_project_division_district_reports.php');
    }

    function manage_project_division_district_upazila_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_district_upazila_date_mis_reports');

        include('pages/list_project_division_district_upazila_date_reports.php');
    }

    function manage_division_district_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_district_date_mis_reports');

        include('pages/list_division_district_date_reports.php');
    }

    function manage_project_division_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_division_date_mis_reports');

        include('pages/list_project_division_date_reports.php');
    }

    function manage_division_district_upazila_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_district_upazila_mis_reports');

        include('pages/list_division_district_upazila_reports.php');
    }

    function manage_division_district_upazila_date_mis_reports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_division_district_upazila_date_mis_reports');

        include('pages/list_division_district_upazila_date_reports.php');
    }

//    function manage_project_division_district_mis_reports() {
//        if (!has_permission('manage_misreports'))
//            return true;
//        global $devdb, $_config;
//        $myUrl = jack_url($this->thsClass, 'manage_project_division_district_mis_reports');
//
//        include('pages/list_project_division_district_reports.php');
//    }

    function manage_misreports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_misreports');

        include('pages/list_misreports.php');
    }

    function get_misactivities($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_activities 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_activities.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_activities.pk_activity_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_activities.pk_activity_id',
            'project_id' => 'dev_activities.fk_project_id',
            'activity_name' => 'dev_activities.activity_name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $misactivities = sql_data_collector($sql, $count_sql, $param);
        return $misactivities;
    }

    function add_edit_misactivity($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_misactivities(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid activity id, no data found']);
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
            $misactivity_data = array();
            $misactivity_data['fk_project_id'] = $params['form_data']['project_id'];
            $misactivity_data['activity_name'] = $params['form_data']['activity_name'];

            if ($is_update) {
                $misactivity_data['update_date'] = date('Y-m-d');
                $misactivity_data['update_time'] = date('H:i:s');
                $misactivity_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['misactivity_update'] = $devdb->insert_update('dev_activities', $misactivity_data, " pk_activity_id = '" . $is_update . "'");
            } else {
                $misactivity_data['create_date'] = date('Y-m-d');
                $misactivity_data['create_time'] = date('H:i:s');
                $misactivity_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['misactivity_insert'] = $devdb->insert_update('dev_activities', $misactivity_data);
            }
        }
        return $ret;
    }

    function get_targets($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_targets.pk_target_id',
            'year' => 'dev_targets.year',
            'month' => 'dev_targets.month',
            'branch' => 'dev_targets.fk_branch_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
            'project' => 'dev_targets.fk_project_id',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_branch_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'branch' => 'dev_targets.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_branch_project_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'branch' => 'dev_targets.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_branch_project_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'branch' => 'dev_targets.fk_branch_id',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_project_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_district_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_district_upazila_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_district_upazila_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_division_district_upazila_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_division_district_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_division_district_upazila_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_division_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_branch_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'branch' => 'dev_targets.fk_branch_id',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_date_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_division_district_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_project_division_district_reports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";

        $groupBy = ' ' . $param['group_by'];
        $sql = $select . $from . $where;

        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'project' => 'dev_targets.fk_project_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $groupBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_target($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_targets(array('id' => $edit, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid target id, no data found']);
            }

            $data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');

            $data['activity_target'] = $params['form_data']['target'];

            $data['update_date'] = $dateNow;
            $data['update_time'] = $timeNow;
            $data['updated_by'] = $_config['user']['pk_user_id'];
            $ret = $devdb->insert_update('dev_targets', $data, " pk_target_id  = '" . $is_update . "'");
            return $ret;
            exit();
        }

        if (!$ret['error']) {
            $data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');

            foreach ($params['form_data']['activity_id'] as $i => $value) {
                $year = $params['form_data']['year'];
                $month = $params['form_data']['month'];
                $branch_id = $params['form_data']['branch_id'];

                $pre_check = "SELECT month FROM dev_targets WHERE fk_branch_id = '$branch_id' AND year = '$year' AND month = '$month' AND fk_activity_id = '$value'";
                $result = $devdb->query($pre_check);

                if ($result) {
                    $ret['error'] = 'Data already inserted, please edit';
                } else {
                    $data['fk_branch_id'] = $branch_id;
                    $data['fk_project_id'] = $params['form_data']['project_id'];
                    $data['parent_branch'] = $params['branch_info']['fk_branch_id'];
                    $data['branch_division'] = $params['branch_info']['branch_division'];
                    $data['branch_district'] = $params['branch_info']['branch_district'];
                    $data['branch_sub_district'] = $params['branch_info']['branch_sub_district'];
                    $data['year'] = $year;
                    $data['month'] = $month;
                    $data['fk_activity_id'] = $value;
                    $data['activity_target'] = $params['form_data']['target'][$i];

                    if ($is_update) {
                        $data['update_date'] = $dateNow;
                        $data['update_time'] = $timeNow;
                        $data['updated_by'] = $_config['user']['pk_user_id'];
                        $ret = $devdb->insert_update('dev_targets', $data, " pk_target_id  = '" . $is_update . "'");
                    } else {
                        $data['create_date'] = $dateNow;
                        $data['create_time'] = $timeNow;
                        $data['created_by'] = $_config['user']['pk_user_id'];
                        $ret = $devdb->insert_update('dev_targets', $data);
                    }
                }
            }
        }
        return $ret;
    }

    function get_achievements($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets ";

        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_targets.pk_target_id',
            'year' => 'dev_targets.year',
            'month' => 'dev_targets.month',
            'branch' => 'dev_targets.fk_branch_id',
            'division' => 'dev_targets.branch_division',
            'district' => 'dev_targets.branch_district',
            'sub_district' => 'dev_targets.branch_sub_district',
            'project' => 'dev_targets.fk_project_id',
            'create_date' => 'dev_targets.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

}

new dev_activity_report();
