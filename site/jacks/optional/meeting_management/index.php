<?php

class dev_meeting_management {

    var $thsClass = 'dev_meeting_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Meeting Management',
            'permissions' => array(
                'manage_meeting_entries' => array(
                    'add_meeting_entry' => 'Add Meeting Entry',
                    'edit_meeting_entry' => 'Edit Meeting Entry',
                    'delete_meeting_entry' => 'Delete Meeting Entry',
                ),
                'manage_meetings' => array(
                    'add_meeting' => 'Add Staff Meeting',
                    'edit_meeting' => 'Edit Staff Meeting',
                    'delete_meeting' => 'Delete Staff Meeting',
                ),
                'manage_meeting_targets' => array(
                    'add_meeting_target' => 'Add Meeting Target',
                    'edit_meeting_target' => 'Edit Meeting Target',
                    'delete_meeting_target' => 'Delete Meeting Target',
                ),
                'manage_meeting_achievements' => array(
                    'add_meeting_achievement' => 'Add Meeting Achievement',
                    'edit_meeting_achievement' => 'Edit Meeting Achievement',
                    'delete_meeting_achievement' => 'Delete Meeting Achievement',
                ),
                'manage_meeting_reports' => array(
                    'view_meeting_report' => 'View Meeting Report',
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
            'label' => 'Meeting Management',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Meetings',
            'description' => 'Manage Meeting Entries',
            'menu_group' => 'Meeting Management',
            'position' => 'default',
            'action' => 'manage_meeting_entries',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meeting_entries'))
            admenu_register($params);

        $params = array(
            'label' => 'Staff Meeting Management',
            'description' => 'Manage Staff Meeting',
            'menu_group' => 'Meeting Management',
            'position' => 'default',
            'action' => 'manage_meetings',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meetings'))
            admenu_register($params);

        $params = array(
            'label' => 'Target Management',
            'description' => 'Manage All Meeting Target',
            'menu_group' => 'Meeting Management',
            'position' => 'default',
            'action' => 'manage_meeting_targets',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meeting_targets'))
            admenu_register($params);

        $params = array(
            'label' => 'Meeting Achievements',
            'description' => 'Manage All Achievements',
            'menu_group' => 'Meeting Management',
            'position' => 'default',
            'action' => 'manage_meeting_achievements',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meeting_achievements'))
            admenu_register($params);

        $params = array(
            'label' => 'Meeting Report',
            'description' => 'View All Meeting Reports',
            'menu_group' => 'Meeting Management',
            'position' => 'default',
            'action' => 'manage_meeting_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meeting_reports'))
            admenu_register($params);
    }

    function manage_meeting_entries() {
        if (!has_permission('manage_meeting_entries'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meeting_entries');

        if ($_GET['action'] == 'add_edit_meeting_entry')
            include('pages/add_edit_meeting_entry.php');
        elseif ($_GET['action'] == 'deleteMeetingEntry')
            include('pages/deleteMeetingEntry.php');
        else
            include('pages/list_meeting_entries.php');
    }

    function manage_meetings() {
        if (!has_permission('manage_meetings'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meetings');

        if ($_GET['action'] == 'add_edit_meeting')
            include('pages/add_edit_meeting.php');
        elseif ($_GET['action'] == 'deleteMeeting')
            include('pages/deleteMeeting.php');
        else
            include('pages/list_meetings.php');
    }

    function manage_meeting_targets() {
        if (!has_permission('manage_meeting_targets'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meeting_targets');

        if ($_GET['action'] == 'add_edit_target')
            include('pages/add_edit_target.php');
        elseif ($_GET['action'] == 'add_edit_meeting_target')
            include('pages/add_edit_meeting_target.php');
        elseif ($_GET['action'] == 'deleteMeetingTarget')
            include('pages/deleteMeetingTarget.php');
        else
            include('pages/list_meeting_targets.php');
    }

    function manage_meeting_achievements() {
        if (!has_permission('manage_meeting_achievements'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meeting_achievements');

        include('pages/list_meeting_achievements.php');
    }

    function manage_meeting_reports() {
        if (!has_permission('manage_meeting_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meeting_reports');

        include('pages/list_meeting_reports.php');
    }

    function get_meetings($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_meetings 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_meetings.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_meetings.pk_meeting_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_meetings.pk_meeting_id',
            'project_id' => 'dev_meetings.fk_project_id',
            'meeting_name' => 'dev_meetings.meeting_name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_meeting($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_meetings(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid meeting id, no data found']);
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
            $meeting_data = array();
            $meeting_data['fk_project_id'] = $params['form_data']['project_id'];
            $meeting_data['meeting_name'] = $params['form_data']['meeting_name'];

            if ($is_update) {
                $meeting_data['update_date'] = date('Y-m-d');
                $meeting_data['update_time'] = date('H:i:s');
                $meeting_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['meeting_update'] = $devdb->insert_update('dev_meetings', $meeting_data, " pk_meeting_id = '" . $is_update . "'");
            } else {
                $meeting_data['create_date'] = date('Y-m-d');
                $meeting_data['create_time'] = date('H:i:s');
                $meeting_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['meeting_insert'] = $devdb->insert_update('dev_meetings', $meeting_data);
            }
        }
        return $ret;
    }

    function get_months() {
        $data = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );

        return $data;
    }

    function get_meeting_targets($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_meeting_targets 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_meeting_targets.fk_project_id)
                LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_meeting_targets.fk_branch_id)
                LEFT JOIN dev_meetings ON (dev_meetings.pk_meeting_id  = dev_meeting_targets.fk_meeting_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_meeting_targets.pk_meeting_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_meeting_targets.pk_meeting_target_id',
            'year' => 'dev_meeting_targets.year',
            'month' => 'dev_meeting_targets.month',
            'branch_id' => 'dev_meeting_targets.fk_branch_id',
            'district' => 'dev_meeting_targets.branch_district',
            'sub_district' => 'dev_meeting_targets.branch_sub_district',
            'project_id' => 'dev_meeting_targets.fk_project_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_meeting_target($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_meeting_targets(array('id' => $edit, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid target id, no data found']);
            }

            $data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');

            $data['meeting_target'] = $params['form_data']['target'];

            $data['update_date'] = $dateNow;
            $data['update_time'] = $timeNow;
            $data['updated_by'] = $_config['user']['pk_user_id'];
            $ret = $devdb->insert_update('dev_meeting_targets', $data, " pk_meeting_target_id  = '" . $is_update . "'");
            return $ret;
            exit();
        }

        if (!$ret['error']) {
            $data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');

            foreach ($params['form_data']['meeting_id'] as $i => $value) {
                $month = $params['form_data']['month'];
                $branch_id = $params['form_data']['branch_id'];

                $pre_check = "SELECT month FROM dev_meeting_targets WHERE fk_branch_id = '$branch_id' AND month = '$month' AND fk_meeting_id = '$value'";
                $result = $devdb->query($pre_check);

                if ($result) {
                    $ret['error'] = 'Data already inserted, please edit';
                } else {
                    $data['fk_project_id'] = $params['form_data']['project_id'];
                    $data['fk_branch_id'] = $branch_id;
                    $data['branch_district'] = $params['branch_district'];
                    $data['branch_sub_district'] = $params['branch_sub_district'];
                    $data['month'] = $month;
                    $data['fk_meeting_id'] = $value;
                    $data['meeting_target'] = $params['form_data']['target'][$i];

                    if ($is_update) {
                        $data['update_date'] = $dateNow;
                        $data['update_time'] = $timeNow;
                        $data['updated_by'] = $_config['user']['pk_user_id'];
                        $ret = $devdb->insert_update('dev_meeting_targets', $data, " pk_meeting_target_id  = '" . $is_update . "'");
                    } else {
                        $data['create_date'] = $dateNow;
                        $data['create_time'] = $timeNow;
                        $data['created_by'] = $_config['user']['pk_user_id'];
                        $ret = $devdb->insert_update('dev_meeting_targets', $data);
                    }
                }
            }
        }
        return $ret;
    }

    function get_meeting_entries($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_meeting_entries 
                LEFT JOIN dev_meetings ON (dev_meetings.pk_meeting_id = dev_meeting_entries.fk_meeting_id)
                LEFT JOIN dev_users ON (dev_users.pk_user_id = dev_meeting_entries.created_by)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_meeting_entries.pk_meeting_entry_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_meeting_entries.pk_meeting_entry_id',
            'name' => 'dev_meetings.meeting_name',
            'branch_id' => 'dev_meeting_entries.fk_branch_id',
            'division' => 'dev_meeting_entries.meeting_entry_division',
            'district' => 'dev_meeting_entries.meeting_entry_district',
            'sub_district' => 'dev_meeting_entries.meeting_entry_upazila',
            'union' => 'dev_meeting_entries.meeting_entry_union',
            'create_date' => 'dev_meeting_entries.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_meeting_entry($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_meeting_entries(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid meeting id, no data found']);
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
            $events_data = array();
            $events_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $events_data['fk_project_id'] = $params['form_data']['project_id'];
            $events_data['month'] = $params['form_data']['month'];
            $events_data['fk_meeting_id'] = $params['form_data']['meeting_id'];
            $events_data['meeting_entry_start_date'] = date('Y-m-d', strtotime($params['form_data']['meeting_entry_start_date']));
            $events_data['meeting_entry_start_time'] = $params['form_data']['meeting_entry_start_time'];
            $events_data['meeting_entry_end_date'] = date('Y-m-d', strtotime($params['form_data']['meeting_entry_end_date']));
            $events_data['meeting_entry_end_time'] = $params['form_data']['meeting_entry_end_time'];
            $events_data['meeting_entry_division'] = $params['form_data']['meeting_entry_division'];
            $events_data['meeting_entry_district'] = $params['form_data']['meeting_entry_district'];
            $events_data['meeting_entry_upazila'] = $params['form_data']['meeting_entry_upazila'];
            $events_data['meeting_entry_union'] = $params['form_data']['meeting_entry_union'];
            $events_data['meeting_entry_village'] = $params['form_data']['meeting_entry_village'];
            $events_data['meeting_entry_ward'] = $params['form_data']['meeting_entry_ward'];
            $events_data['meeting_entry_location'] = $params['form_data']['meeting_entry_location'];
            $events_data['participant_boy'] = $params['form_data']['participant_boy'];
            $events_data['participant_girl'] = $params['form_data']['participant_girl'];
            $events_data['participant_male'] = $params['form_data']['participant_male'];
            $events_data['participant_female'] = $params['form_data']['participant_female'];
            $events_data['preparatory_work'] = $params['form_data']['preparatory_work'];
            $events_data['time_management'] = $params['form_data']['time_management'];
            $events_data['participants_attention'] = $params['form_data']['participants_attention'];
            $events_data['logistical_arrangements'] = $params['form_data']['logistical_arrangements'];
            $events_data['relevancy_delivery'] = $params['form_data']['relevancy_delivery'];
            $events_data['participants_feedback'] = $params['form_data']['participants_feedback'];
            $events_data['observation_score'] = $events_data['preparatory_work'] + $events_data['time_management'] + $events_data['participants_attention'] + $events_data['logistical_arrangements'] + $events_data['relevancy_delivery'] + $events_data['participants_feedback'];
            $events_data['meeting_entry_note'] = $params['form_data']['meeting_entry_note'];
            if ($is_update) {
                $events_data['update_date'] = date('Y-m-d');
                $events_data['update_time'] = date('H:i:s');
                $events_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['events_update'] = $devdb->insert_update('dev_meeting_entries', $events_data, " pk_meeting_entry_id  = '" . $is_update . "'");

                $achievement_male = $params['form_data']['participant_male'];
                $achievement_female = $params['form_data']['participant_female'];
                $achievement_boy = $params['form_data']['participant_boy'];
                $achievement_girl = $params['form_data']['participant_girl'];
                $achievement_total = $achievement_male + $achievement_female + $achievement_boy + $achievement_girl;
                $update_date = date('Y-m-d');
                $update_time = date('H:i:s');
                $updated_by = $_config['user']['pk_user_id'];

                $sql = "UPDATE dev_meeting_targets SET achievement_male = '$achievement_male', achievement_female = '$achievement_female', achievement_boy = '$achievement_boy', achievement_girl = '$achievement_girl', achievement_total = '$achievement_total', update_date = '$update_date', update_time = '$update_time', updated_by = '$updated_by' WHERE fk_meeting_id = '" . $events_data['fk_meeting_id'] . "' AND fk_branch_id = '" . $events_data['fk_branch_id'] . "' AND fk_project_id = '" . $events_data['fk_project_id'] . "' AND month = '" . $events_data['month'] . "'";
                $ret['meeting_update'] = $devdb->query($sql);
            } else {
                $events_data['create_date'] = date('Y-m-d');
                $events_data['create_time'] = date('H:i:s');
                $events_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['events_insert'] = $devdb->insert_update('dev_meeting_entries', $events_data);

                $achievement_male = $params['form_data']['participant_male'];
                $achievement_female = $params['form_data']['participant_female'];
                $achievement_boy = $params['form_data']['participant_boy'];
                $achievement_girl = $params['form_data']['participant_girl'];
                $achievement_total = $achievement_male + $achievement_female + $achievement_boy + $achievement_girl;
                $update_date = date('Y-m-d');
                $update_time = date('H:i:s');
                $updated_by = $_config['user']['pk_user_id'];

                $sql = "UPDATE dev_meeting_targets SET achievement_male = '$achievement_male', achievement_female = '$achievement_female', achievement_boy = '$achievement_boy', achievement_girl = '$achievement_girl', achievement_total = '$achievement_total', meeting_achievement = meeting_achievement + 1, update_date = '$update_date', update_time = '$update_time', updated_by = '$updated_by' WHERE fk_meeting_id = '" . $events_data['fk_meeting_id'] . "' AND fk_branch_id = '" . $events_data['fk_branch_id'] . "' AND fk_project_id = '" . $events_data['fk_project_id'] . "' AND month = '" . $events_data['month'] . "'";
                $ret['meeting_update'] = $devdb->query($sql);
            }
        }
        return $ret;
    }

}

new dev_meeting_management();
