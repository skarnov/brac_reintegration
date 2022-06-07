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
            'district_id' => 'bd_city_corporations.district_id',
            'city_corporation_name' => 'bd_city_corporations.name AS name',
            'city_corporation_bn_name' => 'bd_city_corporations.bn_name AS bn_name',
        ),
        'id' => $edit,
        'single' => true
    );
    $pre_data = $this->get_city_corporations($args);
   
    if (!$pre_data) {
        add_notification('Invalid city corporation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_city_corporation($data);

    if ($ret['success']) {
        $msg = 'Success!';
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_location_management/manage_city_corporations'));
        exit();
    } else {
        $pre_data = $_POST;
        add_notification($ret['error'], 'error');
    }
}

$args = array(
    'listing' => true,
    'select_fields' => array(
        'district_id' => 'bd_districts.id AS district_id',
        'district_name' => 'bd_districts.name AS district_name',
    ),
    'order_by' => array(
        'col' => 'bd_districts.name',
        'order' => 'ASC'
    ),
);

$all_districts = $this->get_districts($args);

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>City Corporation</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All City Corporation Management',
                'title' => 'Manage City Corporation Management',
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
                        <label>District</label>
                        <div class="select2-primary">
                            <select class="form-control" required name="district_id">
                                <option value="">Select One</option>
                                <?php foreach ($all_districts['data'] as $value) : ?>
                                    <option value="<?php echo $value['district_id'] ?>" <?php echo ($value['district_id'] ==  $pre_data['district_id']) ? 'selected' : '' ?>><?php echo ucwords($value['district_name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>City Corporation Name</label>
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