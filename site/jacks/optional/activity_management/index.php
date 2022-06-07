<?php

class dev_activity_management {

    var $thsClass = 'dev_activity_management';
    var $evaluationQA = array(
        'preparatory_work' => array(
            'q' => 'Preparatory work for the event was',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
        'time_management' => array(
            'q' => 'Time management of the event was',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
        'participants_attention' => array(
            'q' => 'Participants attention during the event was',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
        'logistical_arrangements' => array(
            'q' => 'Logistical arrangements (e.g. stationery, sitting arrangements, sound quality others) were',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
        'relevancy_delivery' => array(
            'q' => 'Relevancy of delivery of messages from the event was',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
        'participants_feedback' => array(
            'q' => 'Participants feedback on the overall event was',
            'a' => array(
                '5' => 'Excellent',
                '4' => 'Good',
                '3' => 'Neutral',
                '2' => 'Need To Improved',
                '1' => 'Not Observed',
            ),
        ),
    );
    var $type_recipient = array(
        'survivor' => 'Survivor',
        'returnee' => 'Returnee',
        'potential_migrant' => 'Potential Migrant',
        'returnee_family' => 'Returnee\'s Family',
        'survivor_family' => 'Survivor\'s Family',
        'potential_migrant_family' => 'Potential Migrant Family',
        'family' => 'Family',
        'community_member' => 'Community Member',
        'journalist' => 'Journalist',
    );
    var $all_workshop_held = array(
        'international' => 'International',
        'national' => 'National',
        'district' => 'District',
        'upazila' => 'Upazila',
        'Union' => 'Union',
    );

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Activities',
            'permissions' => array(
                'manage_events' => array(
                    'add_event' => 'Add Event',
                    'edit_event' => 'Edit Event',
                    'delete_event' => 'Delete Event',
                ),
                'manage_event_validations' => array(
                    'add_event_validation' => 'Add Event Validation',
                    'edit_event_validation' => 'Edit Event Validation',
                    'delete_event_validation' => 'Delete Event Validation',
                ),
                'manage_trainings' => array(
                    'add_training' => 'Add Training',
                    'edit_training' => 'Edit Training',
                    'delete_training' => 'Delete Training',
                ),
                'manage_training_validations' => array(
                    'add_training_validation' => 'Add Training Validation',
                    'edit_training_validation' => 'Edit Training Validation',
                    'delete_training_validation' => 'Delete Training Validation',
                ),
                'manage_workshops' => array(
                    'add_workshop' => 'Add Workshop',
                    'edit_workshop' => 'Edit Workshop',
                    'delete_workshop' => 'Delete Workshop',
                ),
                'manage_workshop_validations' => array(
                    'add_workshop_validation' => 'Add Workshop Validation',
                    'edit_workshop_validation' => 'Edit Workshop Validation',
                    'delete_workshop_validation' => 'Delete Workshop Validation',
                ),
                'manage_complains' => array(
                    'add_complain' => 'Add Complain',
                    'edit_complain' => 'Edit Complain',
                    'delete_complain' => 'Delete Complain',
                ),
                'manage_complain_fileds' => array(
                    'add_complain_filed' => 'Add Complain Filed',
                    'edit_complain_filed' => 'Edit Complain Filed',
                    'delete_complain_filed' => 'Delete Complain Filed',
                ),
                'manage_complain_investigations' => array(
                    'add_complain_investigation' => 'Add Complain Investigation',
                    'edit_complain_investigation' => 'Edit Complain Investigation',
                    'delete_complain_investigation' => 'Delete Complain Investigation',
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
            'label' => 'Activities',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Events',
            'description' => 'Manage All Event',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_events',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_events'))
            admenu_register($params);
        $params = array(
            'label' => 'Trainings',
            'description' => 'Manage All Training',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_trainings',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_trainings'))
            admenu_register($params);
        $params = array(
            'label' => 'Workshops',
            'description' => 'Manage All Workshop',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_workshops',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_workshops'))
            admenu_register($params);
        $params = array(
            'label' => 'Community Service',
            'description' => 'Manage All Community Services',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_complains',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complains'))
            admenu_register($params);
        $params = array(
            'label' => 'Complain Files',
            'description' => 'Manage All Complain Fileds',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_complain_fileds',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complain_fileds'))
            admenu_register($params);
        $params = array(
            'label' => 'Complain Investigations',
            'description' => 'Manage All Complain Investigations',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_complain_investigations',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complain_investigations'))
            admenu_register($params);
    }

    function manage_events() {
        if (!has_permission('manage_events'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_events');

        if ($_GET['action'] == 'add_edit_event')
            include('pages/add_edit_event.php');
        elseif ($_GET['action'] == 'add_edit_event_validation')
            include('pages/add_edit_event_validation.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/event_download_pdf.php');
        elseif ($_GET['action'] == 'deleteEvent')
            include('pages/deleteEvent.php');
        else
            include('pages/list_events.php');
    }

    function manage_event_validations() {
        if (!has_permission('manage_event_validations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_event_validations');

        if ($_GET['action'] == 'add_edit_event_validation')
            include('pages/add_edit_event_validation.php');
        elseif ($_GET['action'] == 'deleteEventValidation')
            include('pages/deleteEventValidation.php');
        else
            include('pages/list_event_validations.php');
    }

    function manage_trainings() {
        if (!has_permission('manage_trainings'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_trainings');

        if ($_GET['action'] == 'add_edit_training')
            include('pages/add_edit_training.php');
        elseif ($_GET['action'] == 'participants_list')
            include('pages/list_training_participants.php');
        elseif ($_GET['action'] == 'add_edit_participant')
            include('pages/add_edit_training_participant.php');
        elseif ($_GET['action'] == 'training_validation')
            include('pages/list_training_validation.php');
        elseif ($_GET['action'] == 'add_edit_training_validation')
            include('pages/add_edit_training_validation.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/training_download_pdf.php');
        elseif ($_GET['action'] == 'deleteTraining')
            include('pages/deleteTraining.php');
        elseif ($_GET['action'] == 'deleteTrainingParticipant')
            include('pages/deleteTrainingParticipant.php');
        elseif ($_GET['action'] == 'deleteTrainingValidation')
            include('pages/deleteTrainingValidation.php');
        else
            include('pages/list_trainings.php');
    }

    function manage_workshops() {
        if (!has_permission('manage_workshops'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_workshops');

        if ($_GET['action'] == 'add_edit_workshop')
            include('pages/add_edit_workshop.php');
        elseif ($_GET['action'] == 'participants_list')
            include('pages/list_workshop_participants.php');
        elseif ($_GET['action'] == 'add_edit_participant')
            include('pages/add_edit_workshop_participant.php');
        elseif ($_GET['action'] == 'workshop_validation')
            include('pages/list_workshop_validation.php');
        elseif ($_GET['action'] == 'add_edit_workshop_validation')
            include('pages/add_edit_workshop_validation.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/workshop_download_pdf.php');
        elseif ($_GET['action'] == 'deleteWorkshop')
            include('pages/deleteWorkshop.php');
        elseif ($_GET['action'] == 'deleteWorkshopParticipant')
            include('pages/deleteWorkshopParticipant.php');
        elseif ($_GET['action'] == 'deleteWorkshopValidation')
            include('pages/deleteWorkshopValidation.php');
        else
            include('pages/list_workshops.php');
    }

    function manage_complains() {
        if (!has_permission('manage_complains'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complains');

        if ($_GET['action'] == 'add_edit_complain')
            include('pages/add_edit_complain.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/complain_download_pdf.php');
        elseif ($_GET['action'] == 'deleteComplain')
            include('pages/deleteComplain.php');
        else
            include('pages/list_complains.php');
    }

    function manage_complain_fileds() {
        if (!has_permission('manage_complain_fileds'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complain_fileds');

        if ($_GET['action'] == 'add_edit_complain_filed')
            include('pages/add_edit_complain_filed.php');
        elseif ($_GET['action'] == 'download_pdf')
            include('pages/complain_filed_download_pdf.php');
        elseif ($_GET['action'] == 'deleteComplainFiled')
            include('pages/deleteComplainFiled.php');
        else
            include('pages/list_complain_fileds.php');
    }

    function manage_complain_investigations() {
        if (!has_permission('manage_complain_investigations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complain_investigations');

        if ($_GET['action'] == 'add_edit_complain_investigation')
            include('pages/add_edit_complain_investigation.php');
        elseif ($_GET['action'] == 'deleteComplainInvestigation')
            include('pages/deleteComplainInvestigation.php');
        else
            include('pages/list_complain_investigations.php');
    }

    function get_events($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['report']) :
            $from = "FROM dev_events 
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id = dev_events.fk_activity_id)
                LEFT JOIN dev_users ON (dev_users.pk_user_id = dev_events.created_by)
                LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_events.fk_branch_id)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_events.fk_project_id)                
            ";
        else :
            $from = "FROM dev_events 
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id = dev_events.fk_activity_id)
                LEFT JOIN dev_users ON (dev_users.pk_user_id = dev_events.created_by)
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_events.fk_project_id)
            ";
        endif;

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_events.pk_event_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_events.pk_event_id',
            'name' => 'dev_activities.activity_name',
            'project' => 'dev_events.fk_project_id',
            'branch' => 'dev_events.fk_branch_id',
            'division' => 'dev_events.event_division',
            'district' => 'dev_events.event_district',
            'sub_district' => 'dev_events.event_upazila',
            'union' => 'dev_events.event_union',
            'create_date' => 'dev_events.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function check_targets($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_targets ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'year' => 'year',
            'month' => 'month',
            'fk_project_id' => 'fk_project_id',
            'fk_branch_id' => 'fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        if (!$result) {
            return array('error' => ['Please Select The PROJECT, YEAR, MONTH Properly, Inform Your Branch Supervisor To Set Your Target']);
        }

        return $result;
    }

    function add_edit_event($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_events(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid event id, no data found']);
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
            $events_data['fk_branch_id'] = $params['form_data']['fk_branch_id'];
            $events_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $events_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $events_data['year'] = $params['form_data']['year'];
            $events_data['month'] = $params['form_data']['month'];
            $events_data['fk_activity_id'] = $params['form_data']['fk_activity_id'];
            $events_data['event_start_date'] = $params['form_data']['event_start_date'] ? date('Y-m-d', strtotime($params['form_data']['event_start_date'])) : '';
            $events_data['event_start_time'] = $params['form_data']['event_start_time'];
            $events_data['event_end_date'] = $params['form_data']['event_end_date'] ? date('Y-m-d', strtotime($params['form_data']['event_end_date'])) : '';
            $events_data['event_end_time'] = $params['form_data']['event_end_time'];
            $events_data['event_division'] = $params['form_data']['event_division'];
            $events_data['event_district'] = $params['form_data']['event_district'];
            $events_data['event_upazila'] = $params['form_data']['event_upazila'];
            $events_data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $events_data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $events_data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $events_data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $events_data['event_union'] = $params['form_data']['event_union'];
            $events_data['event_village'] = $params['form_data']['event_village'];
            $events_data['event_ward'] = $params['form_data']['event_ward'];
            $events_data['event_location'] = $params['form_data']['event_location'];
            $events_data['participant_boy'] = $params['form_data']['participant_boy'] ? $params['form_data']['participant_boy'] : 0;
            $events_data['participant_girl'] = $params['form_data']['participant_girl'] ? $params['form_data']['participant_girl'] : 0;
            $events_data['participant_male'] = $params['form_data']['participant_male'] ? $params['form_data']['participant_male'] : 0;
            $events_data['participant_female'] = $params['form_data']['participant_female'] ? $params['form_data']['participant_female'] : 0;
            $events_data['preparatory_work'] = $params['form_data']['preparatory_work'] ? $params['form_data']['preparatory_work'] : 0;
            $events_data['time_management'] = $params['form_data']['time_management'] ? $params['form_data']['time_management'] : 0;
            $events_data['participants_attention'] = $params['form_data']['participants_attention'] ? $params['form_data']['participants_attention'] : 0;
            $events_data['logistical_arrangements'] = $params['form_data']['logistical_arrangements'] ? $params['form_data']['logistical_arrangements'] : 0;
            $events_data['relevancy_delivery'] = $params['form_data']['relevancy_delivery'] ? $params['form_data']['relevancy_delivery'] : 0;
            $events_data['participants_feedback'] = $params['form_data']['participants_feedback'] ? $params['form_data']['participants_feedback'] : 0;
            $events_data['observation_score'] = $events_data['preparatory_work'] + $events_data['time_management'] + $events_data['participants_attention'] + $events_data['logistical_arrangements'] + $events_data['relevancy_delivery'] + $events_data['participants_feedback'];
            $events_data['event_note'] = $params['form_data']['event_note'];
            if ($is_update) {
                $events_data['update_date'] = date('Y-m-d');
                $events_data['update_time'] = date('H:i:s');
                $events_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_events', $events_data, " pk_event_id  = '" . $is_update . "'");
            } else {
                $events_data['validation_count'] = 0;
                $events_data['create_date'] = date('Y-m-d');
                $events_data['create_time'] = date('H:i:s');
                $events_data['created_by'] = $_config['user']['pk_user_id'];
                $new_event = $devdb->insert_update('dev_events', $events_data);
                if ($new_event['success']):
                    $achievement = array();
                    $achievement['activity_achievement'] = $params['target_info']['activity_achievement'] + 1;
                    $achievement['achievement_male'] = $params['target_info']['achievement_male'] + $events_data['participant_male'];
                    $achievement['achievement_female'] = $params['target_info']['achievement_female'] + $events_data['participant_female'];
                    $achievement['achievement_boy'] = $params['target_info']['achievement_boy'] + $events_data['participant_boy'];
                    $achievement['achievement_girl'] = $params['target_info']['achievement_girl'] + $events_data['participant_girl'];
                    $achievement['achievement_total'] = $achievement['achievement_male'] + $achievement['achievement_female'] + $achievement['achievement_boy'] + $achievement['achievement_girl'];
                    $achievement['update_date'] = $events_data['update_date'];
                    $achievement['update_time'] = $events_data['update_time'];
                    $achievement['updated_by'] = $events_data['updated_by'];
                    $ret = $devdb->insert_update('dev_targets', $achievement, " pk_target_id  = '" . $params['target_info']['pk_target_id'] . "'");
                else:
                    return array('error' => ['Achievement not updated!']);
                endif;
            }
        }
        return $ret;
    }

    function get_event_validations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_event_validations
                    LEFT JOIN dev_events ON (dev_events.pk_event_id = dev_event_validations.fk_event_id)
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_events.fk_branch_id)
                    LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_events.fk_project_id)
                    LEFT JOIN dev_activities ON (dev_activities.pk_activity_id = dev_events.fk_activity_id)
                  ";
        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_event_validations.pk_validation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_event_validations.pk_validation_id',
            'event_id' => 'dev_event_validations.fk_event_id',
            'project' => 'dev_events.fk_project_id',
            'name' => 'dev_activities.activity_name',
            'branch_id' => 'dev_events.fk_branch_id',
            'division' => 'dev_events.event_division',
            'district' => 'dev_events.event_district',
            'sub_district' => 'dev_events.event_upazila',
            'union' => 'dev_events.event_union',
            'create_date' => 'dev_event_validations.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_event_validation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_event_validations(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid event validation id, no data found']);
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
            $data['fk_event_id'] = $params['event_id'];
            $data['interview_date'] = $params['form_data']['interview_date'] ? date('Y-m-d', strtotime($params['form_data']['interview_date'])) : '';
            $data['interview_time'] = $params['form_data']['interview_time'];
            $data['reviewed_by'] = $params['form_data']['reviewed_by'];
            $data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $data['participant_name'] = $params['form_data']['participant_name'];
            if ($params['form_data']['new_gender']) {
                $data['gender'] = $params['form_data']['new_gender'];
            } else {
                $data['gender'] = $params['form_data']['gender'];
            }
            $data['age'] = $params['form_data']['age'];
            $data['mobile'] = $params['form_data']['mobile'];
            $data['enjoyment'] = $params['form_data']['enjoyment'];
            $data['victim'] = $params['form_data']['victim'];
            $data['victim_family'] = $params['form_data']['victim_family'];

            if ($params['form_data']['new_message'] == NULL) {
                $data_type = $params['form_data']['message'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['message'] = $data_types;
            } elseif ($params['form_data']['message'] == NULL) {
                $data['other_message'] = $params['form_data']['new_message'];
            } elseif ($params['form_data']['message'] != NULL && $params['form_data']['new_message'] != NULL) {
                $data_type = $params['form_data']['message'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['message'] = $data_types;
                $data['other_message'] = $params['form_data']['new_message'];
            }

            $data['use_message'] = $params['form_data']['use_message'];
            $data['mentioned_event'] = $params['form_data']['mentioned_event'];
            $data['additional_comments'] = $params['form_data']['additional_comments'];
            $data['quote'] = $params['form_data']['quote'];

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_update'] = $devdb->insert_update('dev_event_validations', $data, " pk_validation_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_insert'] = $devdb->insert_update('dev_event_validations', $data);
                $sql = "UPDATE dev_events SET validation_count = validation_count + 1 WHERE pk_event_id = '" . $params['event_id'] . "'";
                $devdb->query($sql);
            }
        }
        return $ret;
    }

    function get_trainings($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_trainings 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_trainings.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_trainings.pk_training_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_trainings.pk_training_id',
            'project' => 'dev_trainings.fk_project_id',
            'training_name' => 'dev_trainings.training_name',
            'training_start_date ' => 'dev_trainings.training_start_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_training($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_trainings(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid training id, no data found']);
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
            $training_data = array();
            $training_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $training_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $training_data['training_start_date'] = $params['form_data']['training_start_date'] ? date('Y-m-d', strtotime($params['form_data']['training_start_date'])) : '';
            $training_data['training_end_date'] = $params['form_data']['training_end_date'] ? date('Y-m-d', strtotime($params['form_data']['training_end_date'])) : '';
            $training_data['training_name'] = $params['form_data']['training_name'];
            $training_data['training_duration'] = $params['form_data']['training_duration'];
            $training_data['event_division'] = $params['form_data']['event_division'];
            $training_data['event_district'] = $params['form_data']['event_district'];
            $training_data['event_upazila'] = $params['form_data']['event_upazila'];
            $training_data['event_union'] = $params['form_data']['event_union'];
            $training_data['training_venue'] = $params['form_data']['training_venue'];
            $training_data['training_held'] = $params['form_data']['training_held'];
            if ($is_update) {
                $training_data['update_date'] = date('Y-m-d');
                $training_data['update_time'] = date('H:i:s');
                $training_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_trainings', $training_data, " pk_training_id   = '" . $is_update . "'");
            } else {
                $training_data['create_date'] = date('Y-m-d');
                $training_data['create_time'] = date('H:i:s');
                $training_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_trainings', $training_data);
            }
        }
        return $ret;
    }

    function get_training_participants($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['report']) {
            $from = " FROM dev_training_participants "
                    . " LEFT JOIN dev_trainings ON (dev_trainings.pk_training_id  = dev_training_participants.fk_training_id)"
                    . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_trainings.fk_project_id)";
        } else {
            $from = " FROM dev_training_participants ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_training_participants.pk_training_participant_id) AS TOTAL " . $from . $where;

        if ($param['report']) {
            $loopCondition = array(
                'training_name' => 'dev_trainings.training_name',
                'project' => 'dev_trainings.fk_project_id',
            );
        } else {
            $loopCondition = array(
                'id' => 'dev_training_participants.pk_training_participant_id',
                'fk_training_id' => 'dev_training_participants.fk_training_id',
                'participant_name' => 'dev_training_participants.participant_name',
                'project' => 'dev_trainings.fk_project_id',
            );
        }

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_training_participants($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_training_participants(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid training id, no data found']);
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
            $data['fk_training_id'] = $params['fk_training_id'];
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $data['organizational_name'] = $params['form_data']['organizational_name'];
            $data['participant_name'] = $params['form_data']['participant_name'];
            $data['participant_age'] = $params['form_data']['participant_age'];
            $data['participant_profession'] = $params['form_data']['participant_profession'];

            if ($params['form_data']['new_gender']) {
                $data['participant_gender'] = $params['form_data']['new_gender'];
            } else {
                $data['participant_gender'] = $params['form_data']['participant_gender'];
            }

            if ($params['form_data']['new_participant']) {
                $data['participant_type'] = $params['form_data']['new_participant'];
            } else {
                $data['participant_type'] = $params['form_data']['participant_type'];
            }

            $data['participant_mobile'] = $params['form_data']['participant_mobile'];
            $data['permanent_division'] = $params['form_data']['permanent_division'];
            $data['permanent_district'] = $params['form_data']['permanent_district'];
            $data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $data['permanent_union'] = $params['form_data']['permanent_union'];
            $data['permanent_village'] = $params['form_data']['permanent_village'];
            $data['permanent_ward'] = $params['form_data']['permanent_ward'];

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_training_participants', $data, " pk_training_participant_id   = '" . $is_update . "'");
            } else {
                $training_info = $this->get_trainings(array('id' => $data['fk_training_id'], 'single' => true));
                $training = array();
                if ($params['form_data']['participant_gender'] == 'male') {
                    $training['male_participant'] = $training_info['male_participant'] + 1;
                    $ret = $devdb->insert_update('dev_trainings', $training, "  pk_training_id   = '" . $training_info['pk_training_id'] . "'");
                } else if ($params['form_data']['participant_gender'] == 'female') {
                    $training['female_participant'] = $training_info['female_participant'] + 1;
                    $ret = $devdb->insert_update('dev_trainings', $training, "  pk_training_id   = '" . $training_info['pk_training_id'] . "'");
                }
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_training_participants', $data);
            }
        }
        return $ret;
    }

    function get_training_validations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_training_validations ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_training_validations.pk_training_validation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_training_validations.pk_training_validation_id',
            'training_id' => 'dev_training_validations.fk_training_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_training_validation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_training_validations(array('id' => $is_update, 'single' => true));
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
            $data['fk_training_id'] = $params['training_id'];
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';

            if ($params['form_data']['new_evaluator_profession']) {
                $data['evaluator_profession'] = $params['form_data']['new_evaluator_profession'];
            } else {
                $data['evaluator_profession'] = $params['form_data']['evaluator_profession'];
            }

            $data['satisfied_training'] = $params['form_data']['satisfied_training'];
            $data['satisfied_supports'] = $params['form_data']['satisfied_supports'];
            $data['satisfied_facilitation'] = $params['form_data']['satisfied_facilitation'];
            $data['outcome_training'] = $params['form_data']['outcome_training'];
            $data['trafficking_law'] = $params['form_data']['trafficking_law'];
            $data['policy_process'] = $params['form_data']['policy_process'];
            $data['all_contents'] = $params['form_data']['all_contents'];
            $data['recommendation'] = $params['form_data']['recommendation'];
            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_training_validations', $data, " pk_training_validation_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_training_validations', $data);
            }
        }
        return $ret;
    }

    function get_workshops($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_workshops "
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_workshops.fk_project_id)";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_workshops.pk_workshop_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_workshops.pk_workshop_id',
            'project' => 'dev_workshops.fk_project_id',
            'workshop_name' => 'dev_workshops.workshop_name',
            'workshop_start_date' => 'dev_workshops.workshop_start_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_workshop($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_workshops(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid workshop id, no data found']);
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
            $workshop_data = array();
            $workshop_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $workshop_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $workshop_data['workshop_start_date'] = $params['form_data']['workshop_start_date'] ? date('Y-m-d', strtotime($params['form_data']['workshop_start_date'])) : '';
            $workshop_data['workshop_end_date'] = $params['form_data']['workshop_end_date'] ? date('Y-m-d', strtotime($params['form_data']['workshop_end_date'])) : '';
            $workshop_data['workshop_name'] = $params['form_data']['workshop_name'];
            $workshop_data['workshop_duration'] = $params['form_data']['workshop_duration'];
            $workshop_data['event_division'] = $params['form_data']['event_division'];
            $workshop_data['event_district'] = $params['form_data']['event_district'];
            $workshop_data['event_upazila'] = $params['form_data']['event_upazila'];
            $workshop_data['event_union'] = $params['form_data']['event_union'];
            $workshop_data['workshop_venue'] = $params['form_data']['workshop_venue'];
            $workshop_data['workshop_held'] = $params['form_data']['workshop_held'];

            if ($is_update) {
                $workshop_data['update_date'] = date('Y-m-d');
                $workshop_data['update_time'] = date('H:i:s');
                $workshop_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshops', $workshop_data, " pk_workshop_id   = '" . $is_update . "'");
            } else {
                $workshop_data['create_date'] = date('Y-m-d');
                $workshop_data['create_time'] = date('H:i:s');
                $workshop_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshops', $workshop_data);
            }
        }
        return $ret;
    }

    function get_workshop_participants($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['report']) {
            $from = "FROM dev_workshop_participants "
                    . " LEFT JOIN dev_workshops ON (dev_workshops.pk_workshop_id  = dev_workshop_participants.fk_workshop_id)"
                    . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_workshops.fk_project_id)";
        } else {
            $from = "FROM dev_workshop_participants ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_workshop_participants.pk_workshop_participant_id) AS TOTAL " . $from . $where;

        if ($param['report']) {
            $loopCondition = array(
                'workshop_name' => 'dev_workshops.workshop_name',
                'project' => 'dev_workshops.fk_project_id',
            );
        } else {
            $loopCondition = array(
                'id' => 'dev_workshop_participants.pk_workshop_participant_id',
                'fk_workshop_id' => 'dev_workshop_participants.fk_workshop_id',
                'participant_name' => 'dev_workshop_participants.participant_name',
                'project' => 'dev_workshops.fk_project_id',
            );
        }

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_workshop_participants($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_workshop_participants(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid workshop id, no data found']);
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
            $data['fk_workshop_id'] = $params['fk_workshop_id'];
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $data['participant_name'] = $params['form_data']['participant_name'];
            $data['organizational_name'] = $params['form_data']['organizational_name'];
            $data['participant_age'] = $params['form_data']['participant_age'];
            $data['participant_profession'] = $params['form_data']['participant_profession'];

            if ($params['form_data']['new_gender']) {
                $data['participant_gender'] = $params['form_data']['new_gender'];
            } else {
                $data['participant_gender'] = $params['form_data']['participant_gender'];
            }

            if ($params['form_data']['new_participant']) {
                $data['participant_type'] = $params['form_data']['new_participant'];
            } else {
                $data['participant_type'] = $params['form_data']['participant_type'];
            }

            $data['participant_mobile'] = $params['form_data']['participant_mobile'];
            $data['permanent_division'] = $params['form_data']['permanent_division'];
            $data['permanent_district'] = $params['form_data']['permanent_district'];
            $data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $data['permanent_municipality'] = $params['form_data']['permanent_municipality'];
            $data['permanent_city_corporation'] = $params['form_data']['permanent_city_corporation'];
            $data['permanent_union'] = $params['form_data']['permanent_union'];
            $data['permanent_village'] = $params['form_data']['permanent_village'];
            $data['permanent_ward'] = $params['form_data']['permanent_ward'];

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshop_participants', $data, " pk_workshop_participant_id   = '" . $is_update . "'");
            } else {
                $workshop_info = $this->get_workshops(array('id' => $data['fk_workshop_id'], 'single' => true));
                $workshop = array();
                if ($params['form_data']['participant_gender'] == 'male') {
                    $workshop['male_participant'] = $workshop_info['male_participant'] + 1;
                    $ret = $devdb->insert_update('dev_workshops', $workshop, "  pk_workshop_id   = '" . $workshop_info['pk_workshop_id'] . "'");
                } else if ($params['form_data']['participant_gender'] == 'female') {
                    $workshop['female_participant'] = $workshop_info['female_participant'] + 1;
                    $ret = $devdb->insert_update('dev_workshops', $workshop, "  pk_workshop_id   = '" . $workshop_info['pk_workshop_id'] . "'");
                }
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshop_participants', $data);
            }
        }
        return $ret;
    }

    function get_workshop_validations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_workshop_validations ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_workshop_validations.pk_workshop_validation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_workshop_validations.pk_workshop_validation_id',
            'workshop_id' => 'dev_workshop_validations.fk_workshop_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_workshop_validation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_workshop_validations(array('id' => $is_update, 'single' => true));
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
            $data['fk_workshop_id'] = $params['workshop_id'];
            $data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';

            if ($params['form_data']['new_evaluator_profession']) {
                $data['evaluator_profession'] = $params['form_data']['new_evaluator_profession'];
            } else {
                $data['evaluator_profession'] = $params['form_data']['evaluator_profession'];
            }

            $data['satisfied_workshop'] = $params['form_data']['satisfied_workshop'];
            $data['satisfied_supports'] = $params['form_data']['satisfied_supports'];
            $data['satisfied_facilitation'] = $params['form_data']['satisfied_facilitation'];
            $data['outcome_workshop'] = $params['form_data']['outcome_workshop'];
            $data['trafficking_law'] = $params['form_data']['trafficking_law'];
            $data['policy_process'] = $params['form_data']['policy_process'];
            $data['all_contents'] = $params['form_data']['all_contents'];
            $data['recommendation'] = $params['form_data']['recommendation'];
            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshop_validations', $data, " pk_workshop_validation_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_workshop_validations', $data);
            }
        }
        return $ret;
    }

    function get_complains($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_complains "
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_complains.fk_project_id)"
                . " LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_complains.fk_branch_id)";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complains.pk_complain_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complains.pk_complain_id',
            'project' => 'dev_complains.fk_project_id',
            'gender' => 'dev_complains.gender',
            'division' => 'dev_complains.division',
            'district' => 'dev_complains.branch_district',
            'sub_district' => 'dev_complains.upazila',
            'union' => 'dev_complains.branch_union',
            'type_recipient' => 'dev_complains.type_recipient',
            'type_service' => 'dev_complains.type_service',
            'complain_register_date' => 'dev_complains.complain_register_date'
        );

        if ($param['type_service']) {
            $conditions .= " AND type_service LIKE '%" . $param['type_service'] . "%'";
        }

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_complain($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complains(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain id, no data found']);
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
            $complains_data = array();
            $complains_data['complain_register_date'] = $params['form_data']['complain_register_date'] ? date('Y-m-d', strtotime($params['form_data']['complain_register_date'])) : '';
            $complains_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $complains_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $complains_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $complains_data['division'] = $params['form_data']['division'];
            $complains_data['branch_district'] = $params['form_data']['branch_district'];
            $complains_data['upazila'] = $params['form_data']['upazila'];
            $complains_data['branch_union'] = $params['form_data']['branch_union'];
            $complains_data['village'] = $params['form_data']['village'];
            $complains_data['name'] = $params['form_data']['name'];
            $complains_data['age'] = $params['form_data']['age'];

            if ($params['form_data']['new_gender']) {
                $complains_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complains_data['gender'] = $params['form_data']['gender'];
            }

            if ($params['form_data']['new_recipient']) {
                $complains_data['type_recipient'] = $params['form_data']['new_recipient'];
            } else {
                $complains_data['type_recipient'] = $params['form_data']['type_recipient'];
            }

            if ($params['form_data']['new_type_service'] == NULL) {
                $data_type = $params['form_data']['type_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['type_service'] = $data_types;
            } elseif ($params['form_data']['type_service'] == NULL) {
                $complains_data['other_type_service'] = $params['form_data']['new_type_service'];
            } elseif ($params['form_data']['type_service'] != NULL && $params['form_data']['new_type_service'] != NULL) {
                $data_type = $params['form_data']['type_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['type_service'] = $data_types;
                $complains_data['other_type_service'] = $params['form_data']['new_type_service'];
            }

            if ($params['form_data']['new_know_service'] == NULL) {
                $data_type = $params['form_data']['know_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['know_service'] = $data_types;
            } elseif ($params['form_data']['know_service'] == NULL) {
                $complains_data['other_know_service'] = $params['form_data']['new_know_service'];
            } elseif ($params['form_data']['know_service'] != NULL && $params['form_data']['new_know_service'] != NULL) {
                $data_type = $params['form_data']['know_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['know_service'] = $data_types;
                $complains_data['other_know_service'] = $params['form_data']['new_know_service'];
            }

            $complains_data['remark'] = $params['form_data']['remark'];

            if ($is_update) {
                $complains_data['update_date'] = date('Y-m-d');
                $complains_data['update_time'] = date('H:i:s');
                $complains_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complains', $complains_data, " pk_complain_id  = '" . $is_update . "'");
            } else {
                $complains_data['create_date'] = date('Y-m-d');
                $complains_data['create_time'] = date('H:i:s');
                $complains_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complains', $complains_data);
            }
        }
        return $ret;
    }

    function get_complain_fileds($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_complain_fileds"
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_complain_fileds.fk_project_id) ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_fileds.pk_complain_filed_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_fileds.pk_complain_filed_id',
            'project' => 'dev_complain_fileds.fk_project_id',
            'gender' => 'dev_complain_fileds.gender',
            'type_case' => 'dev_complain_fileds.type_case',
            'division' => 'dev_complain_fileds.division',
            'district' => 'dev_complain_fileds.district',
            'sub_district' => 'dev_complain_fileds.upazila',
            'complain_register_date' => 'dev_complain_fileds.complain_register_date'
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_complain_filed($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complain_fileds(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain file id, no data found']);
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
            $complain_filed_data = array();
            $complain_filed_data['complain_register_date'] = $params['form_data']['complain_register_date'] ? date('Y-m-d', strtotime($params['form_data']['complain_register_date'])) : '';
            $complain_filed_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $complain_filed_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $complain_filed_data['full_name'] = $params['form_data']['full_name'];
            $complain_filed_data['month'] = $params['form_data']['month'];
            $complain_filed_data['division'] = $params['form_data']['division'];
            $complain_filed_data['district'] = $params['form_data']['district'];
            $complain_filed_data['upazila'] = $params['form_data']['upazila'];
            $complain_filed_data['police_station'] = $params['form_data']['police_station'];
            $complain_filed_data['case_id'] = $params['form_data']['case_id'];
            $complain_filed_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complain_filed_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complain_filed_data['gender'] = $params['form_data']['gender'];
            }

            if ($params['form_data']['new_type_case']) {
                $complain_filed_data['type_case'] = $params['form_data']['new_type_case'];
            } else {
                $complain_filed_data['type_case'] = $params['form_data']['type_case'];
            }
            $complain_filed_data['comments'] = $params['form_data']['comments'];

            if ($is_update) {
                $complain_filed_data['update_date'] = date('Y-m-d');
                $complain_filed_data['update_time'] = date('H:i:s');
                $complain_filed_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_fileds', $complain_filed_data, " pk_complain_filed_id  = '" . $is_update . "'");
            } else {
                $complain_filed_data['create_date'] = date('Y-m-d');
                $complain_filed_data['create_time'] = date('H:i:s');
                $complain_filed_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_fileds', $complain_filed_data);
            }
        }
        return $ret;
    }

    function get_complain_investigations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_complain_investigations"
                . " LEFT JOIN dev_complain_fileds ON (dev_complain_fileds.pk_complain_filed_id = dev_complain_investigations.fk_complain_id) "
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_complain_fileds.fk_project_id) ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_investigations.pk_complain_investigation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_investigations.pk_complain_investigation_id',
            'project' => 'dev_complain_fileds.fk_project_id',
            'complain_file_id' => 'dev_complain_investigations.fk_complain_id',
            'gender' => 'dev_complain_investigations.gender',
            'type_case' => 'dev_complain_investigations.type_case',
            'division' => 'dev_complain_investigations.division',
            'district' => 'dev_complain_investigations.district',
            'upazila' => 'dev_complain_investigations.upazila',
            'entry_date' => 'dev_complain_investigations.complain_register_date'
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_complain_investigation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complain_investigations(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain investigation id, no data found']);
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
            $complain_investigation_data = array();
            $complain_investigation_data['complain_register_date'] = $params['form_data']['complain_register_date'] ? date('Y-m-d', strtotime($params['form_data']['complain_register_date'])) : '';
            $complain_investigation_data['entry_date'] = $params['form_data']['entry_date'] ? date('Y-m-d', strtotime($params['form_data']['entry_date'])) : '';
            $complain_investigation_data['fk_complain_id'] = $params['complain_id'];
            $complain_investigation_data['running_investigation'] = $params['form_data']['running_investigation'];
            $complain_investigation_data['full_name'] = $params['form_data']['full_name'];
            $complain_investigation_data['month'] = $params['form_data']['month'];
            $complain_investigation_data['division'] = $params['form_data']['division'];
            $complain_investigation_data['district'] = $params['form_data']['district'];
            $complain_investigation_data['upazila'] = $params['form_data']['upazila'];
            $complain_investigation_data['police_station'] = $params['form_data']['police_station'];
            $complain_investigation_data['case_id'] = $params['form_data']['case_id'];
            $complain_investigation_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complain_investigation_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complain_investigation_data['gender'] = $params['form_data']['gender'];
            }

            if ($params['form_data']['new_type_case']) {
                $complain_investigation_data['type_case'] = $params['form_data']['new_type_case'];
            } else {
                $complain_investigation_data['type_case'] = $params['form_data']['type_case'];
            }
            $complain_investigation_data['comments'] = $params['form_data']['comments'];

            if ($is_update) {
                $complain_investigation_data['update_date'] = date('Y-m-d');
                $complain_investigation_data['update_time'] = date('H:i:s');
                $complain_investigation_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_investigations', $complain_investigation_data, " pk_complain_investigation_id  = '" . $is_update . "'");
            } else {
                $complain_investigation_data['create_date'] = date('Y-m-d');
                $complain_investigation_data['create_time'] = date('H:i:s');
                $complain_investigation_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_investigations', $complain_investigation_data);
            }
        }
        return $ret;
    }

}

new dev_activity_management();
