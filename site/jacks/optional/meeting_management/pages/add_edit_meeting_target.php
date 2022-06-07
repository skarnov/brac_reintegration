<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$month = $_GET['month'] ? $_GET['month'] : null;
$project_id = $_GET['project_id'] ? $_GET['project_id'] : null;
$branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;

if (!checkPermission($edit, 'add_meeting_target', 'edit_meeting_target')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$all_meetings = $this->get_meetings(array('project_id' => $project_id));

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$all_months = $this->get_months();

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_meeting_targets(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid target, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $branch_id = $_POST['branch_id'];
    $branch_info = $branches->get_branches(array('id' => $branch_id, 'single' => true));

    $data = array(
        'required' => array(
            'month' => 'Month',
            'project_id' => 'Project',
            'branch_id' => 'Branch'
        ),
    );
    $data['branch_district'] = $branch_info['branch_district'];
    $data['branch_sub_district'] = $branch_info['branch_sub_district'];
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    foreach ($data['required'] as $i => $v) {
        if (isset($data['form_data'][$i]))
            $temp = form_validator::required($data['form_data'][$i]);
        if ($temp !== true) {
            $ret['error'][] = $v . ' ' . $temp;
        }
    }

    $ret = $this->add_edit_meeting_target($data);

    if ($ret['success']) {
        $msg = "Information of meeting target has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $meetingType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $meetingType);
        if ($edit) {
            header('location: ' . url('admin/dev_meeting_management/manage_meeting_targets'));
        } else {
            header('location: ' . url('admin/dev_meeting_management/manage_meeting_targets'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        add_notification($ret['error'], 'error');
        print_errors($ret['error']);
    }

    if (!$ret['error']) {
        header('location: ' . url('admin/dev_meeting_management/manage_meeting_targets?action=add_edit_meeting_target&month=' . $data['form_data']['month'] . '&project_id=' . $data['form_data']['project_id'] . '&branch_id=' . $data['form_data']['branch_id']));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Set Meeting Target</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Meeting Targets',
                'title' => 'Manage Meeting Targets',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group col-sm-4">
                <label>Project</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach ($all_projects['data'] as $project) : ?>
                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label>Branch</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach ($all_branches['data'] as $branch) : ?>
                            <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $branch_id) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label>Month</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach ($all_months as $i => $value) :
                            ?>
                            <option value="<?php echo $i ?>" <?php echo ($i == $month) ? 'selected' : '' ?>><?php echo $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <input type="hidden" name="project_id" value="<?php echo $project_id ?>"/>
            <input type="hidden" name="branch_id" value="<?php echo $branch_id ?>"/>
            <input type="hidden" name="month" value="<?php echo $month ?>"/>
            <?php if ($pre_data) { ?>
                <div class="col-md-12">
                    <label><?php echo $pre_data['meeting_name'] ?> - Target &nbsp;</label>
                    <input type="hidden" name="meeting_id" value="<?php echo $pre_data['fk_meeting_id'] ?>" class="form-control">
                    <input type="number" name="target" value="<?php echo $pre_data['meeting_target'] ?>" class="form-control" style="display: inline; width: 60%;">
                    <br/>
                    <br/>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <?php
                    foreach ($all_meetings['data'] as $value) {
                        ?>
                        <label><?php echo $value['meeting_name'] ?> - Target &nbsp;</label>
                        <input type="hidden" name="meeting_id[]" value="<?php echo $value['pk_meeting_id'] ?>" class="form-control">
                        <input type="number" name="target[]" class="form-control" style="display: inline; width: 60%;">
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_meeting_management/manage_meeting_targets') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Save Target' : 'Save Target',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Save Target' : 'Save Target'
            ))
            ?>
        </div>
    </div>
</form>