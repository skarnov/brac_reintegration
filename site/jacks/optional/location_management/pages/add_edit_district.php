<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_location', 'edit_location')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'division_id' => 'bd_districts.division_id',
            'district_name' => 'bd_districts.name AS name',
            'district_bn_name' => 'bd_districts.bn_name AS bn_name',
        ),
        'id' => $edit,
        'single' => true
    );
    $pre_data = $this->get_districts($args);
   
    if (!$pre_data) {
        add_notification('Invalid district, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_district($data);

    if ($ret['success']) {
        $msg = 'Success!';
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_location_management/manage_districts'));
        exit();
    } else {
        $pre_data = $_POST;
        add_notification($ret['error'], 'error');
    }
}

$all_divisions = $this->get_divisions();

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>District</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All District Management',
                'title' => 'Manage District Management',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form class="preventDoubleClick" onsubmit="return validate_invoice(this);" method="post" action="">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Division</label>
                        <div class="select2-primary">
                            <select class="form-control" required name="division_id">
                                <option value="">Select One</option>
                                <?php foreach ($all_divisions['data'] as $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] ==  $pre_data['division_id']) ? 'selected' : '' ?>><?php echo ucwords($value['name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>District Name</label>
                        <input class="form-control" required type="text" name="name" value="<?php echo $pre_data['name'] ? $pre_data['name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Bengali Name</label>
                        <input class="form-control" required type="text" name="bn_name" value="<?php echo $pre_data['bn_name'] ? $pre_data['bn_name'] : ''; ?>">
                    </div>
                </div>
                <div class="panel-footer tar">
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update' : 'Save',
                        'icon' => $edit ? 'icon_update' : 'icon_save',
                        'text' => $edit ? 'Update' : 'Save'))
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>