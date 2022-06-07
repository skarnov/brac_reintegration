<?php
class dev_staff_management {
    var $myBranch = null;
    var $myBranchType = null;

    var $thsClass = 'dev_staff_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Staff Management',
            'permissions' => array(
                'manage_staffs' => array(
                    'add_staff' => 'Add Staff',
                    'edit_staff' => 'Edit Staff',
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
            'label' => 'Staffs',
            'description' => 'Manage All Staffs',
            'menu_group' => 'Administration',
            'position' => 'default',
            'action' => 'manage_staffs',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_staffs')) admenu_register($params);
    }

    function manage_staffs() {
        if (!has_permission('manage_staffs')) return null;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_staffs');

        if ($_GET['action'] == 'add_edit_staff')
            include('pages/add_edit_staff.php');
        else
            include('pages/list_staffs.php');
    }

    function get_lookups($lookup_group) {
        $sql = "SELECT * FROM dev_lookups WHERE lookup_group = '$lookup_group'";
        $data = sql_data_collector($sql);
        return $data;
    }

    function isStaff(){
        global $_config;
        if($_config['user']){
            if(in_array(ROLE_staff_ID, $_config['user']['roles_list']) !== false) return true;
            else return false;
        }
        else return false;
    }

    function getMyBranch(){
        global $_config;
        if($this->myBranch) return $this->myBranch;
        else if($_config['user']['user_branch']){
            $branchManager = jack_obj('dev_branch_management');
            $this->myBranch = $branchManager->get_branches(array('id' => $_config['user']['user_branch'], 'single' => true));
            if($this->myBranch){
                $this->myBranchType = $this->myBranch['_branch_type_slug'];
            }
            else $this->myBranch = null;

            return $this->myBranch;
        }
        else return null;
    }

    function getMyBranchType(){
        global $_config;
        if($this->myBranchType) return $this->myBranchType;
        else if($_config['user']['user_branch']){
            $branchManager = jack_obj('dev_branch_management');
            $this->myBranch = $branchManager->get_branches(array('id' => $_config['user']['user_branch'], 'single' => true));
            if($this->myBranch){
                $this->myBranchType = $this->myBranch['_branch_type_slug'];
            }
            else $this->myBranchType = null;

            return $this->myBranchType;
        }
        else return null;
    }

    function get_staffs($param = null) {
        $profileManager = jack_obj('dev_profile_management');
        
        $param['single'] = $param['single'] ? $param['single'] : false;

        global $devdb;

        $defaultSelect = "dev_users.*
                           , dev_branches.branch_name
                           , dev_lookups.lookup_value as designation
                            ";

        $select = "SELECT ".($param['select_fields'] ? implode(", ",$param['select_fields'])." " : $defaultSelect);
        $from = "FROM dev_users 
                        LEFT JOIN dev_users_roles_relation On (dev_users.pk_user_id = dev_users_roles_relation.fk_user_id) 
                        LEFT JOIN dev_branches On (dev_users.user_branch = dev_branches.pk_branch_id) 
                        LEFT JOIN dev_lookups On (dev_users.user_designation = dev_lookups.pk_lookup_id) 
                        ";

        $where = "WHERE 1 ";
        $conditions = "";

        $count_sql = "SELECT COUNT(pk_user_id) AS TOTAL ".$from.$where;
        
        $loopCondition = array(
            'user_id' => 'dev_users.pk_user_id',
            'user_fb_id' => 'dev_users.user_fb_id',
            'project_id' => 'dev_users.fk_project_id',
            'user_email' => 'dev_users.user_email',
            'user_status' => 'dev_users.user_status',
            'user_type' => 'dev_users.user_type',
            'meta_type' => 'dev_users.user_meta_type',
            'name' => 'dev_users.user_fullname',
            'branch' => 'dev_users.user_branch',
            'designation' => 'dev_users.user_designation',
            'division' => 'dev_branches.branch_division',
            'district' => 'dev_branches.branch_district',
            'sub_district' => 'dev_branches.branch_sub_district',
            'user_role' => 'dev_users.user_roles',
            );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql = $select.$from.$where.$conditions.$orderBy.$limitBy;
        $count_sql .= $conditions;
        
        $users = sql_data_collector($sql, $count_sql, $param);
        return $users;
    }
}

new dev_staff_management();
