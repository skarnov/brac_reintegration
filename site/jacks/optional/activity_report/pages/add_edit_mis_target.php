<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$year = $_GET['year'] ? $_GET['year'] : null;
$month = $_GET['month'] ? $_GET['month'] : null;
$project_id = $_GET['project_id'] ? $_GET['project_id'] : null;
$branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;

if (!checkPermission($edit, 'add_target', 'edit_target')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$all_activities = $this->get_misactivities(array('project_id' => $project_id));

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_targets(array('id' => $edit, 'single' => true));

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
            'year' => 'Year',
            'month' => 'Month',
            'project_id' => 'Project',
            'branch_id' => 'Branch'
        ),
    );    
    $data['branch_info'] = $branch_info;
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    foreach ($data['required'] as $i => $v) {
        if (isset($data['form_data'][$i]))
            $temp = form_validator::required($data['form_data'][$i]);
        if ($temp !== true) {
            $ret['error'][] = $v . ' ' . $temp;
        }
    }

    $ret = $this->add_edit_target($data);

    if ($ret['success']) {
        $msg = "Information of target has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_report/manage_targets'));
        } else {
            header('location: ' . url('admin/dev_activity_report/manage_targets'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        add_notification($ret['error'], 'error');
        print_errors($ret['error']);
    }

    if (!$ret['error']) {
        header('location: ' . url('admin/dev_activity_report/manage_targets?action=add_edit_mis_target&year=' . $data['form_data']['year'] . ' &month=' . $data['form_data']['month'] . '&project_id=' . $data['form_data']['project_id'] . '&branch_id=' . $data['form_data']['branch_id']));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Set Target</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Targets',
                'title' => 'Manage Targets',
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
            <div class="form-group col-sm-3">
                <label class="text-primary">Project</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach ($all_projects['data'] as $project) : ?>
                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-3">
                <label class="text-primary">Branch</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach ($all_branches['data'] as $branch) : ?>
                            <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $branch_id) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-3">
                <div class="form-group">
                    <label class="text-primary">Year</label>
                    <input type="text" readonly class="form-control" value="<?php echo $year ?>">
                </div>
            </div>
            <div class="form-group col-sm-3">
                <label class="text-primary">Month</label>
                <div class="select2-primary">
                    <select class="form-control" readonly>
                        <option value="">Select One</option>
                        <?php foreach (get_months() as $i => $value) :
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
            <input type="hidden" name="year" value="<?php echo $year ?>"/>
            <input type="hidden" name="month" value="<?php echo $month ?>"/>
            <input type="hidden" name="project_id" value="<?php echo $project_id ?>"/>
            <input type="hidden" name="branch_id" value="<?php echo $branch_id ?>"/>
            <div class="row">
                <?php if ($pre_data) { ?>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-10 control-label"><?php echo $pre_data['activity_name'] ?></label>
                                            <div class="col-md-2 inputGroupContainer">
                                                <input type="hidden" name="activity_id" value="<?php echo $pre_data['pk_activity_id'] ?>">
                                                <div class="input-group"><input type="number" name="target" value="<?php echo $pre_data['activity_target'] ?>" class="form-control"></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <fieldset>
                                        <?php
                                        foreach ($all_activities['data'] as $value) {
                                            ?>
                                            <div class="form-group">
                                                <label class="col-md-10 control-label"><?php echo $value['activity_name'] ?></label>
                                                <div class="col-md-2 inputGroupContainer">
                                                    <input type="hidden" name="activity_id[]" value="<?php echo $value['pk_activity_id'] ?>">
                                                    <div class="input-group"><input type="number" name="target[]" value="" class="form-control"></div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_report/manage_targets') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update Target' : 'Save Target',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update Target' : 'Save Target'
            ))
            ?>
        </div>
    </div>
</form>