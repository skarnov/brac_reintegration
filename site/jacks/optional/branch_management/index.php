<?php

class dev_branch_management {

    var $thsClass = 'dev_branch_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Branch Management',
            'permissions' => array(
                'manage_branches' => array(
                    'add_branch' => 'Add Branch',
                    'edit_branch' => 'Edit Branch',
                    'configure_branch_types' => 'Configure Branch Types',
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
            'label' => 'Branches',
            'description' => 'Manage All Branches',
            'menu_group' => 'Administration',
            'position' => 'default',
            'action' => 'manage_branches',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_branches'))
            admenu_register($params);
    }

    function manage_branches() {
        if (!has_permission('manage_branches'))
            return null;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_branches');

        if ($_GET['action'] == 'configure_branch_types'):
            include('pages/branch_types.php');
        elseif ($_GET['action'] == 'add_edit_branch'):
            include('pages/add_edit_branch.php');
        elseif ($_GET['action'] == 'get_districts'):
            include('pages/get_districts.php');
        elseif ($_GET['action'] == 'get_upazilas'):
            include('pages/get_upazilas.php');
        else:
            include('pages/list_branches.php');
        endif;
    }

    function get_all_branches_hierarchical($parent = null) {
        $items = array();

        $sql = "SELECT * FROM dev_branches WHERE 1 ";
        if ($parent)
            $sql .= "";

        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : 'branches.*,  branch_type.item_title as branch_type_name');
        $from = " FROM dev_branches AS branches LEFT JOIN dev_branch_types AS branch_type ON (branches.fk_branch_type=branch_type.pk_item_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(branches.pk_branch_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'branches.pk_branch_id',
            'type' => 'branches.fk_branch_type',
            'division' => 'branches.branch_division',
            'district' => 'branches.branch_district',
            'sub_district' => 'branches.branch_sub_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        if ($param['name']) {
            $conditions .= " AND branches.branch_name LIKE '%" . $param['name'] . "%'";
        }

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function get_branches($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : 'branches.*,  branch_type.item_title as branch_type_name, branch_type._branch_type_slug , parent_branches.branch_name as parent_branch_name, projects.project_name');
        $from = " FROM dev_branches AS branches 
                            LEFT JOIN dev_branch_types AS branch_type ON (branches.fk_branch_type=branch_type.pk_item_id)
                            LEFT JOIN dev_branches AS parent_branches ON (branches.fk_branch_id=parent_branches.pk_branch_id)
                            LEFT JOIN dev_projects AS projects ON (branches.fk_project_id=projects.pk_project_id)
                            ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(branches.pk_branch_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'branches.pk_branch_id',
            'type' => 'branches.fk_branch_type',
            'branch_type_slug' => 'branch_type._branch_type_slug',
            'division' => 'branches.branch_division',
            'district' => 'branches.branch_district',
            'sub_district' => 'branches.branch_sub_district',
            'parent_branch' => 'branches.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        if ($param['name']) {
            $conditions .= " AND branches.branch_name LIKE '%" . $param['name'] . "%'";
        }

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function add_edit_branch($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        if ($is_update && !has_permission('edit_branch')) {
            add_notification('You don\'t have enough permission to update branch.', 'error');
            header('Location:' . build_url(NULL, array('edit', 'action')));
            exit();
        } elseif (!has_permission('add_branch')) {
            add_notification('You don\'t have enough permission to create new branch.', 'error');
            header('Location:' . build_url(NULL, array('action')));
            exit();
        }

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_branches(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                $ret['error'][] = 'Invalid branch id, no data found';
            }
        }

        if (!strlen($params['branch_name']))
            $ret['error'][] = 'Please provide the name for the branch';
        if (!strlen($params['fk_branch_type']))
            $ret['error'][] = 'Please select branch type';

        if (!$ret['error']) {
            $insert_data = array(
                'fk_branch_id' => $params['fk_branch_id'],
                'fk_branch_type' => $params['fk_branch_type'],
                'fk_project_id' => $params['fk_project_id'],
                'branch_name' => $params['branch_name'],
                'branch_address' => $params['branch_address'],
                'branch_division' => $params['branch_division'],
                'branch_district' => $params['branch_district'],
                'branch_sub_district' => $params['branch_sub_district'],
                'update_time' => date('H:i:s'),
                'update_date' => date('Y-m-d'),
                'updated_by' => $_config['user']['pk_user_id'],
            );
            if ($is_update) {
                $ret = $devdb->insert_update('dev_branches', $insert_data, " pk_branch_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_branches', $insert_data);
            }

            if ($ret['success']) {
                if ($is_update) {
                    $msg = "Branch " . $insert_data['branch_name'] . " has been updated";
                    user_activity::add_activity($msg, 'success', 'update');
                    add_notification($msg);
                } else {
                    $msg = "New " . $insert_data['branch_type'] . " branch " . $insert_data['branch_name'] . " has been created";
                    user_activity::add_activity($msg, 'success', 'create');
                    add_notification($msg);
                }
            }
        }
        return $ret;
    }

    function get_branch_form($edit = null) {
        $data = array();
        if ($edit) {
            $data = $this->get_branches(array('id' => $edit, 'single' => true));
            if (!$data)
                return array('error' => ['Invalid branch, no data found']);
        }
        $branch_types = $this->get_menuItems(true);

        $projectManager = jack_obj('dev_project_management');
        $projects = $projectManager->get_projects(array('data_only' => true));
        $projects = $projects['data'];

        $divisions = get_division();

        ob_start();
        ?>
        <form onsubmit="return false;">
            <div class="form-group">
                <label>Branch Type</label>
                <select required class="form-control" id="fk_branch_type" name="fk_branch_type">
                    <option value="">Select One</option>
                    <?php
                    foreach ($branch_types as $i => $v) {
                        $selected = $data && $data['fk_branch_type'] == $v['pk_item_id'] ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $v['pk_item_id'] . '">' . $v['item_title'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Parent Branch</label>
                <select class="form-control" data-selected="<?php echo $data['fk_branch_id'] ?>" name="fk_branch_id" id="fk_branch_id"></select>
            </div>
            <div class="form-group">
                <label>Project</label>
                <select required class="form-control" id="fk_project_id" name="fk_project_id">
                    <option value="">Select One</option>
                    <?php
                    foreach ($projects as $i => $v) {
                        $selected = $data && $data['fk_project_id'] == $v['pk_project_id'] ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $v['pk_project_id'] . '">' . $v['project_short_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Branch Name</label>
                <input required type="text" name="branch_name" class="form-control char_limit" data-max-char="100" value="<?php echo $data['branch_name'] ?>" />
            </div>

            <div class="form-group">
                <label class="control-label input-label">Division</label>
                <div class="select2-primary">
                    <select class="form-control division" name="branch_division" style="text-transform: capitalize">
                        <option>Select One</option>
                        <?php foreach ($divisions as $division) : ?>
                            <option id="<?php echo $division['id'] ?>" value="<?php echo $division['name'] ?>" <?php echo ucfirst($data['branch_division']) == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label input-label">District</label>
                <div class="select2-primary">
                    <select class="form-control district" name="branch_district" style="text-transform: capitalize" id="districtList">
                        <?php if ($data['branch_district']) : ?>
                            <option value="<?php echo $data['branch_district'] ?>"><?php echo $data['branch_district'] ?></option>
                        <?php endif ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label input-label">Upazila</label>
                <div class="select2-primary">
                    <select class="form-control subdistrict" name="branch_sub_district" style="text-transform: capitalize" id="subdistrictList">
                        <?php if ($data['branch_sub_district']) : ?>
                            <option value="<?php echo $data['branch_sub_district'] ?>"><?php echo $data['branch_sub_district'] ?></option>
                        <?php endif ?>
                    </select>
                </div>
            </div>


            <script type="text/javascript">
                $('.division').change(function () {
                    var divisionId = $(this).find('option:selected').attr('id');
                    $.ajax({
                        type: 'GET',
                        url: 'manage_branches?action=get_districts&division_id=' + divisionId,
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
                        type: 'GET',
                        url: 'manage_branches?action=get_upazilas&district_id=' + districtId,
                        beforeSend: function () {
                            $('#subdistrictList').html("<option value=''>Loading...</option>");
                        },
                        success: function (result) {
                            $('#subdistrictList').html(result);
                        }}
                    );
                });

            </script>
            <div class="form-group">
                <label>Branch Address</label>
                <textarea name="branch_address" class="form-control" style="resize: none;"><?php echo $data['branch_address'] ?></textarea>
            </div>
        </form>
        <script type="text/javascript">
            $(document).off('change', '#fk_branch_type').on('change', '#fk_branch_type', function () {
                var ths = $(this);
                var target = $('#fk_branch_id');
                var _selected = target.attr('data-selected') ? target.attr('data-selected') : null;
                target.html('');
                if (!ths.val().length)
                    return;
                target.attr('readonly', true);
                basicAjaxCall({
                    data: {
                        ajax_type: 'get_parent_branches',
                        branch_type: ths.val()
                    },
                    success: function (ret) {
                        if (ret.success) {
                            ret = ret.success
                            var itemCount = Object.keys(ret).length;
                            if (!itemCount)
                                return false;
                            else if (Object.keys(ret).length == 1) {
                                for (var i in ret) {
                                    target.append('<option value="' + ret[i]['pk_branch_id'] + '" selected>' + ret[i]['branch_name'] + '</option>');
                                }
                                target.attr('readonly', true);
                            } else {
                                target.html('<option value="">Select One</option>');
                                for (var i in ret) {
                                    var selected = _selected == ret[i]['pk_branch_id'] ? 'selected' : '';
                                    target.append('<option value="' + ret[i]['pk_branch_id'] + '" ' + selected + '>' + ret[i]['branch_name'] + '</option>');
                                }
                                target.attr('readonly', false);
                            }
                        }

                    }
                });
            });
            $('#fk_branch_type').change();
        </script>
        <?php
        $out = ob_get_clean();
        return array('success' => $out);
    }

    function put_branch_form($data) {
        return $this->add_edit_branch($data);
    }

    function menu_item_edit_form($item) {
        global $_config;
        ob_start();
        ?>
        <div class="panel menu_edit_form" style="display: none;">
            <div class="panel-body">
                <form>
                    <input type="hidden" name="menu_item_id" value="<?php echo $item['pk_item_id'] ?>" />
                    <div class="form-group">
                        <label>Type Name</label>
                        <input type="text" name="item_title" data-max-char="100" class="form-control char_limit" value="<?php echo $item['item_title'] ?>" />
                    </div>
                    <div class="form-group">
                        <label>Type Short Name</label>
                        <input type="text" name="item_short_title" data-max-char="20" class="form-control char_limit" value="<?php echo $item['item_short_title'] ?>" />
                    </div>
                    <?php
                    echo buttonButtonGenerator(array(
                        'action' => 'update',
                        'icon' => 'icon_update',
                        'text' => 'Update',
                        'title' => 'Update Menu Item',
                        'classes' => 'edit_menu_item',
                        'size' => '',
                    ));
                    ?>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    function deleteMenuItems($args = array()) {
        global $devdb;

        $menu_items = $this->get_menuItems(true);
        $delete = $this->recur_deleteMenuItems($args['menu_item'], $menu_items);

        $sql = "DELETE FROM dev_branch_types WHERE pk_item_id IN (" . implode(',', $delete) . ")";
        $deleted = $devdb->query($sql);

        return $deleted;
    }

    function recur_deleteMenuItems($key, &$data) {
        $child = array();
        $child[] = $key;
        if ($data) {
            foreach ($data as $i => $v) {
                if ($v['fk_item_id'] == $key) {
                    $child[] = $v['pk_item_id'];
                    $temp = $this->recur_deleteMenuItems($v['pk_item_id'], $data);
                    if ($temp) {
                        foreach ($temp as $m => $n) {
                            $child[] = $n;
                        }
                    }
                }
            }
        }
        return $child;
    }

    function get_each_items($parent, &$childs) {
        $menu = '<ul>';
        $childs_found = false;
        foreach ($childs as $i => $v) {
            if ($v['fk_item_id'] != $parent)
                continue;
            $item_title = '';
            $item_title = $v['item_title'];

            $childs_found = true;
            $menu .= '<li id="ID_' . $v['pk_item_id'] . '"><div class="item"><span class="sortHandle"><i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-ellipsis-v"></i></span>&nbsp;&nbsp;<span class="title">' . $item_title . '</span><span class="pull-right"><a href="javascript:" class="btn btn-xs btn-primary mr5 show_item_detail"><i class="fa fa-edit"></i></a><a href="javascript:" class="remove_menu_item btn btn-xs btn-danger"><i class="icon fa fa-times-circle"></i></a></span></div>' . $this->menu_item_edit_form($v);
            $menu .= $this->get_each_items($v['pk_item_id'], $childs) . '</li>';
        }
        $menu .= '</ul>';
        if (!$childs_found)
            $menu = '';
        return $menu;
    }

    function get_menuItems($data_only = false) {
        global $devdb, $_config;
        $result = $devdb->get_results("SELECT * FROM dev_branch_types WHERE 1 ORDER BY item_sort_order ASC");

        if ($data_only)
            return $result;

        $nav_menu = '<ul class="sortable">';
        foreach ($result as $g => $item) {
            $item_title = '';
            $item_title = $item['item_title'];

            if ($item['fk_item_id'])
                continue;
            $nav_menu .= '<li id="ID_' . $item['pk_item_id'] . '"><div class="item"><span class="sortHandle"><i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-ellipsis-v"></i></span>&nbsp;&nbsp;<span class="title">' . $item_title . '</span><span class="pull-right"><a href="javascript:" class="btn btn-xs btn-primary mr5 show_item_detail"><i class="fa fa-edit"></i></a><a href="javascript:" class="remove_menu_item btn btn-xs btn-danger"><i class="icon fa fa-times-circle"></i></a></span></div>' . $this->menu_item_edit_form($item);
            $nav_menu .= $this->get_each_items($item['pk_item_id'], $result) . '</li>';
        }
        $nav_menu .= '</ul>';

        return $nav_menu;
    }

}

new dev_branch_management();
