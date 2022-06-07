<?php
$rolePermissionManager = jack_obj('dev_role_permission_management');

$edit = $_GET['edit'] ? $_GET['edit'] : NULL;
$user = array();

if (!checkPermission($edit, 'add_staff', 'edit_staff')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

if ($edit) {
    $args = array(
        'user_id' => $edit,
        'single' => true,
    );
    $user = $this->get_staffs($args);

    $user['roles_list'] = strlen($user['user_roles']) ? explode(',', $user['user_roles']) : array();

    if (!$user) {
        add_notification('Staff not found for editing.', 'error');
        header('location:' . $myUrl);
        exit();
    }
}

global $paths;

if ($_POST['ajax_type'] == 'delete_old_image') {
    if ($_POST['user_id']) {
        $sql = "SELECT user_picture FROM dev_users WHERE pk_user_id = '" . $_POST['user_id'] . "'";
        $user_picture = $devdb->get_row($sql);
        if ($user_picture['user_picture']) {
            if (file_exists($paths['absolute']['profile_pictures'] . '/' . $user_picture['user_picture'])) {
                if (@unlink($paths['absolute']['profile_pictures'] . '/' . $user_picture['user_picture'])) {
                    $data = array(
                        'user_picture' => ''
                    );
                    $update = $devdb->insert_update('dev_users', $data, " pk_user_id='" . $_POST['user_id'] . "'");
                    echo json_encode(array('success' => 'Profile Picture Removed'));
                    $this->reCacheUser($_POST['user_id']);
                } else
                    echo json_encode(array('error' => 'Failed to delete the picture.'));
            } else
                echo json_encode(array('success' => 'Picture wasn\'t found, upload a new one.'));
        } else
            echo json_encode(array('error' => 'No Profile Picture Found.'));
    } else
        echo json_encode(array('error' => 'No User Found.'));
    exit();
}

if ($_POST) {
    $profileManager = jack_obj('dev_profile_management');
    $data = $_POST;
    $ret = array('error' => array(), 'success' => array());

    if ($data['user_designation'] == 'add_new_position') {
        if (!strlen($data['new_user_designation']))
            $ret['error'][] = 'Please specify the new designation';
        else {
            $insertData = array(
                'lookup_group' => 'staff_designation',
                'lookup_value' => $data['new_user_designation'],
            );
            $ret = $devdb->insert_update('dev_lookups', $insertData);
            if ($ret['success'])
                $data['user_designation'] = $ret['success'];
        }
    }

    if ($ret['error']) {
        print_errors($ret['error']);
        $user = $data;
    } else {
        $data['user_meta_type'] = 'user';
        $data['user_type'] = 'admin';
        $data['user_religion'] = '';
        $data['user_country'] = '';
        $data['roles_list'] = $data['roles_list'];
        $data['edit'] = $edit ? $edit : NULL;

        $ret = $profileManager->add_edit_user($data);

        if ($ret['error']) {
            print_errors($ret['error']);
            $user = $data;
        } else {
            $user_id = $edit ? $edit : $ret['success'];
            add_notification('The staff has been ' . ($edit ? 'updated.' : 'added.'), 'success');
            user_activity::add_activity('The staff (ID: ' . $user_id . ') has been ' . ($edit ? 'updated.' : 'created.'), 'success', ($edit ? 'update' : 'create'));
            header('location:' . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}

load_js(array(
    theme_path() . '/js/fileupload.min.js'
));

$branchManagement = jack_obj('dev_branch_management');

$_branches = $branchManagement->get_branches();
$branches = array();
if ($_branches['data']) {
    foreach ($_branches['data'] as $branch) {
        $branches[$branch['pk_branch_id']] = $branch['branch_name'];
    }
}

$_designation = $this->get_lookups('staff_designation');
$designation = array();
if ($_designation['data']) {
    foreach ($_designation['data'] as $i => $v) {
        $designation[$v['pk_lookup_id']] = $v['lookup_value'];
    }
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$roles = $rolePermissionManager->get_roles(array('data_only' => true));

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Edit ' : 'Add ' ?>Staff</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Staffs',
                'title' => 'Manage All Staffs',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<div class="panel">
    <form class="preventDoubleClick" onsubmit="return true;" name="widget_pos_add_edit" method="post" action="" enctype="multipart/form-data">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <input type="hidden" name="user_fb_id" value="<?php echo $user ? $user['user_fb_id'] : '' ?>">
                    <?php
                    echo formProcessor::form_elements('user_branch', 'user_branch', array(
                        'width' => 12, 'type' => 'select', 'label' => 'Branch', 'attributes' => 'required',
                        'data' => array('static' => $branches), 'skip_wrapper' => true
                            ), $user['user_branch']);
                    ?>
                    <div class="form-group">
                        <label>Select Project</label>
                        <select class="form-control" name="project_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_project_id'] ?>" <?php
                                if ($value['pk_project_id'] == $user['fk_project_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['project_name'] . ' [' . $value['project_code'] . ']' ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <select class="form-control" id="user_designation" name="user_designation">
                            <option value="">Any</option>
                            <?php
                            if ($_designation['data']) {
                                foreach ($_designation['data'] as $i => $v) {
                                    $selected = $user && $user['user_designation'] == $v['pk_lookup_id'] ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $v['pk_lookup_id'] ?>" <?php echo $selected ?>><?php echo $v['lookup_value'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="add_new_position">+ Add New Position</option>
                        </select>
                    </div>
                    <div class="form-group ml20" id="new_designation_form" style="display: none;">
                        <label>New Designation</label>
                        <input class="form-control" type="text" name="new_user_designation" value="">    
                    </div>
                    <script>
                        init.push(function () {
                            $("#user_designation").on("change", function () {
                                if ($(this).val() == 'add_new_position')
                                    $('#new_designation_form').slideDown();
                                else
                                    $('#new_designation_form').slideUp();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input class="form-control char_limit" data-max-char="490" type="text" name="user_fullname" id="user_fullname" value="<?php echo $user ? $user['user_fullname'] : '' ?>" required/>
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input class="form-control char_limit" data-max-char="250" type="text" name="user_name" id="user_name" value="<?php echo $user ? $user['user_name'] : '' ?>" required/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control char_limit" data-max-char="390" type="email" name="user_email" id="user_email" value="<?php echo $user ? $user['user_email'] : '' ?>" required/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <?php if ($user['user_picture']) { ?>
                            <div class="old_image_holder">
                                <div class="old_image">
                                    <img src="<?php echo user_picture($user['user_picture']); ?>" />
                                    <a href="javascript:" class="delete_old_image btn btn-danger btn-xs" title="Remove Image"/><i class="fa fa-times-circle"></i></a>
                                </div>
                                <p class="help-block">To upload new image, <a href="javascript:" class="delete_old_image">remove the old image</a> first.</p>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="new_image">
                                <input type="file" id="profile_picture" name="user_picture">
                                <script type="text/javascript">
                                    init.push(function () {
                                        $('#profile_picture').pixelFileInput({placeholder: 'No file selected...'});
                                    })
                                </script>
                                <p class="help-block">JPG or PNG image with max file size 500KB &amp; MAX 300x300 resolution.</p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label>Password - <span class="fa fa-lock text-danger removeReadOnly" title="Change Password"></span></label>
                        <input <?php echo $edit ? 'readonly' : '' ?> class="form-control char_limit" data-max-char="100" type="password" name="user_password" id="user_password" value="" placeholder="<?php echo $edit ? '*********' : '' ?>" <?php echo $edit ? '' : 'required' ?>/>
                        <?php echo $edit ? '<p class="help-block">Leave password blank if you don\'t want to change.</p>' : '' ?>
                    </div>
                    <div class="form-group">
                        <label>User Status</label>
                        <select class="form-control" name="user_status" required>
                            <option value="active" <?php echo $user && $user['user_status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?php echo $user && $user['user_status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="not_verified" <?php echo $user && $user['user_status'] == 'not_verified' ? 'selected' : '' ?>>Not Verified</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Birthdate</label>
                        <div class="input-group">
                            <input id="birthdate" type="text" class="form-control" name="user_birthdate" value="<?php echo $user['user_birthdate'] && $user['user_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($user['user_birthdate'])) : date('d-m-Y'); ?>"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('birthdate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" name="user_gender">
                            <option value="male" <?php echo $user['user_gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?php echo $user['user_gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control char_limit" data-max-char="45" name="user_mobile" value="<?php echo $user['user_mobile'] ? $user['user_mobile'] : ''; ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="db">Roles</label>
                        <?php
                        foreach ($roles['data'] as $i => $v) {
                            $selected = $user && in_array($v['pk_role_id'], $user['roles_list']) !== false ? 'checked' : '';
                            ?>
                            <label class="checkbox">
                                <input type="radio" class="px" name="roles_list" value="<?php echo $v['pk_role_id']; ?>" <?php echo $selected; ?> />
                                <span class="lbl"><?php echo $v['role_name']; ?></span>
                            </label>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <?php
            echo submitButtonGenerator(array(
                'href' => $myUrl,
                'action' => $edit ? 'edit' : 'add',
                'icon' => $edit ? 'icon_save' : 'icon_update',
                'text' => $edit ? 'Update Staff' : 'Save Staff',
                'title' => $edit ? 'Update Staff' : 'Save Staff',
                'size' => ''
            ));
            ?>
        </div>
    </form>
</div>
<script type="text/javascript">
    init.push(function () {
        initCharLimit();
        function handleReadOnlyIcon(ths, event) {
            //var ths = $(this);
            var inputElem = ths.closest('.form-group').find('input');
            if (inputElem.attr('readonly')) {
                if (event == 'click')
                    inputElem.removeAttr('readonly');
                ths.removeClass('fa-lock text-danger');
                ths.addClass('fa-unlock text-success');
            } else {
                if (event == 'click')
                    inputElem.attr('readonly', true);
                ths.removeClass('fa-unlock text-success');
                ths.addClass('fa-lock text-danger');
            }
        }
        $('.removeReadOnly').click(function () {
            handleReadOnlyIcon($(this), 'click');
        }).mouseenter(function () {
            //handleReadOnlyIcon($(this),'hover');
        }).mouseout(function () {
            //handleReadOnlyIcon($(this),'hover');
        });
        $('.delete_old_image').click(function () {
            var ths = $(this);
            var container = ths.closest('.form-group');
            var old_image = container.find('.old_image_holder');
            var new_image_text = '<div class="new_image">\
                                        <input type="file" id="profile_picture" name="data[user_picture]">\
                                        <p class="help-block">JPG or PNG image with max file size 500KB &amp; MAX 300x300 resolution.</p>\
                                    </div>';
            if (confirm('Do you really want to delete the picture?')) {
                var _data = {
                    'ajax_type': 'delete_old_image',
                    'user_id': <?php echo $edit ? $edit : -1 ?>
                };
                $.ajax({
                    type: "POST",
                    url: '<?php echo current_url() ?>',
                    data: _data,
                    cache: false,
                    dataType: 'json',
                    success: function (reply_data) {
                        if (reply_data.success) {
                            $.growl.warning({title: "Success", message: "User Picture is removed.", size: 'large'});
                            old_image.slideUp(200, function () {
                                old_image.remove();
                                container.append(new_image_text);
                                $('#profile_picture').pixelFileInput({placeholder: 'No file selected...'});
                            });
                        } else
                            $.growl.error({title: "Error", message: "User Picture wasn't removed.<br />Please try again.", size: 'large'});
                    }
                });
            }
        });
    });
</script>
