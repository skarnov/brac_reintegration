<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_training', 'edit_training')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . $district['name'] . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . $subdistrict['name'] . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . $union['name'] . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_trainings(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid training, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_training($data);

    if ($ret['success']) {
        $msg = "Training has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_trainings?action=add_edit_training&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_activity_management/manage_trainings'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Training</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Trainings',
                'title' => 'Manage Trainings',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Project</label>
                    <div class="select2-primary">
                        <select class="form-control" name="fk_project_id">
                            <option value="">Select One</option>
                            <?php foreach ($all_projects['data'] as $project) : ?>
                                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Entry Date</label>
                    <div class="input-group">
                        <input id="entryDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : ''; ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('entryDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Training Start Date</label>
                    <div class="input-group">
                        <input id="startDate" type="text" class="form-control" name="training_start_date" value="<?php echo $pre_data['training_start_date'] && $pre_data['training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_start_date'])) : ''; ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('startDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Training End Date</label>
                    <div class="input-group">
                        <input id="endDate" type="text" class="form-control" name="training_end_date" value="<?php echo $pre_data['training_end_date'] && $pre_data['training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_end_date'])) : ''; ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('endDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Name of the training</label>
                    <input type="text" class="form-control" name="training_name" value="<?php echo $pre_data['training_name'] ? $pre_data['training_name'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Duration of training</label>
                    <input type="text" class="form-control" name="training_duration" value="<?php echo $pre_data['training_duration'] ? $pre_data['training_duration'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Geographical Information</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label input-label">Division</label>
                            <div class="select2-primary">
                                <select class="form-control division" name="event_division" style="text-transform: capitalize">
                                    <option value="">Select One</option>
                                    <?php foreach ($divisions as $division) : ?>
                                        <option id="<?php echo $division['id'] ?>" <?php echo ($division['id'] == $pre_data['event_division']) ? 'selected' : '' ?> value="<?php echo $division['name'] ?>" <?php echo $pre_data && $pre_data['event_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">District</label>
                            <div class="select2-primary">
                                <select class="form-control district" name="event_district" style="text-transform: capitalize" id="districtList">
                                    <?php if ($pre_data['event_district']) : ?>
                                        <option value="<?php echo $pre_data['event_district'] ?>"><?php echo $pre_data['event_district'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label input-label">Upazila</label>
                            <div class="select2-primary">
                                <select class="form-control subdistrict" name="event_upazila" style="text-transform: capitalize" id="subdistrictList">
                                    <?php if ($pre_data['event_upazila']) : ?>
                                        <option value="<?php echo $pre_data['event_upazila'] ?>"><?php echo $pre_data['event_upazila'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">Union</label>
                            <div class="select2-primary">
                                <select class="form-control" name="event_union" style="text-transform: capitalize" id="unionList">
                                    <?php if ($pre_data['event_union']) : ?>
                                        <option value="<?php echo $pre_data['event_union'] ?>"><?php echo $pre_data['event_union'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
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
                            $('.subdistrict').change(function () {
                                var subdistrictId = $(this).find('option:selected').attr('id');

                                $.ajax({
                                    type: 'POST',
                                    data: {subdistrict_id: subdistrictId},
                                    beforeSend: function () {
                                        $('#unionList').html("<option value=''>Loading...</option>");
                                    },
                                    success: function (result) {
                                        $('#unionList').html(result);
                                    }}
                                );
                            });
                        });
                    </script>
                </fieldset>
                <div class="form-group">
                    <label>Training Venue</label>
                    <input type="text" class="form-control" name="training_venue" value="<?php echo $pre_data['training_venue'] ? $pre_data['training_venue'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Training Held at</label>
                    <div class="select2-primary">
                        <select class="form-control" name="training_held" style="text-transform: capitalize">
                            <option value="">Select One</option>
                            <?php foreach ($this->all_workshop_held as $key => $value) : ?>
                                <option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['training_held'] == $key ? 'selected' : '' ?>><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_trainings') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update' : 'Save',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update' : 'Save'
            ))
            ?>
        </div>
    </div>
</form>
<script type="text/javascript">
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>