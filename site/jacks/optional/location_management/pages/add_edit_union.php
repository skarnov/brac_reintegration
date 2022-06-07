<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_location', 'edit_location')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option value='" . $subdistrict['id'] . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
}

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'bd_unions.id' => 'bd_unions.id AS union_id',
            'bd_divisions.name' => 'bd_divisions.name AS division_name',
            'bd_districts.name' => 'bd_districts.name AS district_name',
            'bd_upazilas.name' => 'bd_upazilas.name AS upazila_name',
            'bd_unions.name' => 'bd_unions.name AS union_name',
            'bd_unions.bn_name' => 'bd_unions.bn_name AS union_bn_name ',
        ),
        'id' => $edit,
        'single' => true
    );
    $pre_data = $this->get_unions($args);

    if (!$pre_data) {
        add_notification('Invalid police station, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_union($data);

    if ($ret['success']) {
        $msg = 'Success!';
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_location_management/manage_unions'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Union</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Union Management',
                'title' => 'Manage Union Management',
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
                        <label class="control-label input-label">Division</label>
                        <div class="select2-primary">
                            <select class="form-control division" required style="text-transform: capitalize">
                                <?php if ($pre_data['division_name']) : ?>
                                    <option value="<?php echo $pre_data['division_name'] ?>"><?php echo $pre_data['division_name'] ?></option>
                                <?php else: ?>
                                    <option>Select One</option>
                                <?php endif ?>
                                <?php foreach ($divisions as $division) : ?>
                                    <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['permanent_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label input-label">District</label>
                        <div class="select2-primary">
                            <select class="form-control district" required style="text-transform: capitalize" id="districtList">
                                <?php if ($pre_data['district_name']) : ?>
                                    <option value="<?php echo $pre_data['district_name'] ?>"><?php echo $pre_data['district_name'] ?></option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label input-label">Upazila</label>
                        <div class="select2-primary">
                            <select class="form-control subdistrict" name="upazila_id" required style="text-transform: capitalize" id="subdistrictList">
                                <?php if ($pre_data['upazila_name']) : ?>
                                    <option value="<?php echo $pre_data['upazila_name'] ?>"><?php echo $pre_data['upazila_name'] ?></option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Union Name</label>
                        <input class="form-control" required type="text" name="name" value="<?php echo $pre_data['union_name'] ? $pre_data['union_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Bengali Name</label>
                        <input class="form-control" required type="text" name="bn_name" value="<?php echo $pre_data['union_bn_name'] ? $pre_data['union_bn_name'] : ''; ?>">
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
<script type="text/javascript">
    init.push(function () {
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
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
                type: 'POST',
                data: {district_id: districtId},
                beforeSend: function () {
                    $('#subdistrictList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#subdistrictList').html(result);
                }}
            );
        });
    });
</script>