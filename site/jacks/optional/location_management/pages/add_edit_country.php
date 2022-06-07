<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_location', 'edit_location')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_countries(array('id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid country, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_country($data);

    if ($ret['success']) {
        $msg = 'Success!';
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_location_management/manage_countries'));
        exit();
    } else {
        $pre_data = $_POST;
        add_notification($ret['error'], 'error');
    }
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Country</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Country Management',
                'title' => 'Manage Country Management',
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
                        <label>Country Name</label>
                        <input class="form-control" required type="text" name="name" value="<?php echo $pre_data['name'] ? $pre_data['name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>ISO</label>
                        <input class="form-control" required type="text" name="iso" value="<?php echo $pre_data['iso'] ? $pre_data['iso'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>ISO3</label>
                        <input class="form-control" required type="text" name="iso3" value="<?php echo $pre_data['iso3'] ? $pre_data['iso3'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Num-Code</label>
                        <input class="form-control" required type="text" name="numcode" value="<?php echo $pre_data['numcode'] ? $pre_data['numcode'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone-Code</label>
                        <input class="form-control" required type="text" name="phonecode" value="<?php echo $pre_data['phonecode'] ? $pre_data['phonecode'] : ''; ?>">
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