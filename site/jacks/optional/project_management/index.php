<?php

class dev_project_management {

    var $thsClass = 'dev_project_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Manage Projects',
            'permissions' => array(
                'manage_projects' => array(
                    'add_project' => 'Add Project',
                    'edit_project' => 'Edit Project',
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
            'label' => 'Projects',
            'description' => 'Manage All Projects',
            'menu_group' => 'Administration',
            'position' => 'default',
            'action' => 'manage_projects',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_projects'))
            admenu_register($params);
    }

    function manage_projects() {
        if (!has_permission('manage_projects'))
            return null;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_projects');

        include('pages/list_projects.php');
    }

    function get_projects($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '*');
        $from = " FROM dev_projects ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_project_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_project_id',
            'name' => 'project_short_name',
            'status' => 'project_status',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function add_edit_project($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        if ($is_update && !has_permission('edit_project')) {
            add_notification('You don\'t have enough permission to update project.', 'error');
            header('Location:' . build_url(NULL, array('edit', 'action')));
            exit();
        } elseif (!has_permission('add_project')) {
            add_notification('You don\'t have enough permission to create new project.', 'error');
            header('Location:' . build_url(NULL, array('action')));
            exit();
        }

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_projects(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                $ret['error'][] = 'Invalid project id, no data found';
            }
        }

        if (!strlen($params['project_name']))
            $ret['error'][] = 'Please provide the name for the project';
        if (!strlen($params['project_start']))
            $ret['error'][] = 'Please provide the start date of project';
        if (!strlen($params['project_end']))
            $ret['error'][] = 'Please provide the end date of project';

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['project_name'] = $params['project_name'];
            $insert_data['project_short_name'] = $params['project_short_name'];
            $insert_data['project_code'] = $params['project_code'];
            $insert_data['project_funded_by'] = $params['project_funded_by'];
            $insert_data['project_start'] = date_to_db($params['project_start']);
            $insert_data['project_end'] = date_to_db($params['project_end']);
            if ($params['project_end']) {
                $today = strtotime(date('Y-m-d'));
                $end_date = strtotime($params['project_end']);
                if ($today > $end_date) {
                    $insert_data['project_status'] = 'inactive';
                } else {
                    $insert_data['project_status'] = 'active';
                }
            }
            $insert_data['project_target'] = $params['project_target'];
        }
        if ($is_update) {
            $insert_data['update_time'] = date('H:i:s');
            $insert_data['update_date'] = date('Y-m-d');
            $insert_data['updated_by'] = $_config['user']['pk_user_id'];
            $ret = $devdb->insert_update('dev_projects', $insert_data, " pk_project_id = '" . $is_update . "'");
        } else {
            $insert_data['create_date'] = date('Y-m-d');
            $insert_data['create_time'] = date('H:i:s');
            $insert_data['created_by'] = $_config['user']['pk_user_id'];
            $ret = $devdb->insert_update('dev_projects', $insert_data);
        }

        if ($ret['success']) {
            if ($is_update) {
                $msg = "Project \"" . $insert_data['project_name'] . "\" has been updated";
                user_activity::add_activity($msg, 'success', 'update');
                add_notification($msg);
            } else {
                $msg = "New Project \"" . $insert_data['project_name'] . "\" has been created";
                user_activity::add_activity($msg, 'success', 'create');
                add_notification($msg);
            }
        }
        return $ret;
    }

    function get_project_form($edit = null) {
        $data = array();
        if ($edit) {
            $data = $this->get_projects(array('id' => $edit, 'single' => true));
            if (!$data)
                return array('error' => ['Invalid project, no data found']);
        }
        ob_start();
        ?>
        <form onsubmit="return false;">
            <div class="form-group">
                <label>Project Name</label>
                <input required type="text" name="project_name" class="form-control char_limit" data-max-char="500" value="<?php echo $data['project_name'] ?>" />
            </div>
            <div class="form-group">
                <label>Project Short Name</label>
                <input required type="text" name="project_short_name" class="form-control char_limit" data-max-char="50" value="<?php echo $data['project_short_name'] ?>" />
            </div>
            <div class="form-group">
                <label>Project Code</label>
                <input required type="text" name="project_code" class="form-control char_limit" data-max-char="10" value="<?php echo $data['project_code'] ?>" />
            </div>
            <div class="form-group">
                <label>Start Date &amp; End Date</label>
                <div class="input-daterange input-group" id="bs-datepicker-range">
                    <span class="input-group-addon">From</span>
                    <input required type="text" class="input-sm form-control" value="<?php echo $data ? date_to_user($data['project_start']) : '' ?>" name="project_start" placeholder="Start date">
                    <span class="input-group-addon">to</span>
                    <input required type="text" class="input-sm form-control" value="<?php echo $data ? date_to_user($data['project_end']) : '' ?>" name="project_end" placeholder="End date">
                </div>
                <script type="text/javascript">
                    $('#bs-datepicker-range').datepicker({
                        todayBtn: "linked",
                        autoclose: true,
                        format: "dd-mm-yyyy",
                        todayHighlight: true
                    });
                </script>
            </div>
            <div class="form-group">
                <label>Project Funded By</label>
                <textarea name="project_funded_by" class="form-control autoHeightTextarea autoAutoHeightTextarea"><?php echo $data['project_funded_by'] ?></textarea>
            </div>
            <div class="form-group">
                <label>Project Target</label>
                <textarea name="project_target" class="form-control autoHeightTextarea autoAutoHeightTextarea"><?php echo $data['project_target'] ?></textarea>
            </div>
        </form>
        <script type="text/javascript">
            autoHeight('.autoAutoHeightTextarea');
            initCharLimit();
        </script>
        <?php
        $out = ob_get_clean();

        return array('success' => $out);
    }

}

new dev_project_management();
