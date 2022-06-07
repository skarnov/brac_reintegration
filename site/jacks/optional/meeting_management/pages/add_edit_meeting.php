<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_meeting', 'edit_meeting')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'id' => 'dev_meetings.pk_meeting_id',
            'project_id' => 'dev_meetings.fk_project_id',
            'project_name' => 'dev_projects.project_short_name',
            'meeting_name' => 'dev_meetings.meeting_name',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_meetings($args);

    if (!$pre_data) {
        add_notification('Invalid staff meeting, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'project_id' => 'Project Name',
            'meeting_name' => 'Meeting Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_meeting($data);

    if ($ret) {
        $msg = "Information of staff meeting has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $meetingType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $meetingType);
        if ($edit) {
            header('location: ' . url('admin/dev_meeting_management/manage_meetings?action=add_edit_meeting&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_meeting_management/manage_meetings'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Staff Meeting</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Staff Meetings',
                'title' => 'Manage Staff Meetings ',
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
                    <select class="form-control" required name="project_id">
                        <option value="">Select One</option>
                        <?php foreach ($all_projects['data'] as $project) : ?>
                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Meeting Name</label>
                    <input type="text" required class="form-control" name="meeting_name" value="<?php echo $pre_data['meeting_name']; ?>">
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_meeting_management/manage_meetings') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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