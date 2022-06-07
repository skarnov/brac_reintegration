<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_target', 'edit_target')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

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
    $data = array(
        'required' => array(
            'month' => 'Month',
            'year' => 'Year',
            'project_id' => 'Project',
            'branch_id' => 'Branch'
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    foreach ($data['required'] as $i => $v) {
        if (isset($data['form_data'][$i]))
            $temp = form_validator::required($data['form_data'][$i]);
        if ($temp !== true) {
            $ret['error'][] = $v . ' ' . $temp;
        }
    }

    if (!$ret['error']) {
        header('location: ' . url('admin/dev_activity_report/manage_targets?action=add_edit_mis_target&year=' . $data['form_data']['year'] . ' &month=' . $data['form_data']['month'] . '&project_id=' . $data['form_data']['project_id'] . '&branch_id=' . $data['form_data']['branch_id']));
    }
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

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
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Year</label>
                    <div class="select2-primary">
                        <select class="form-control" name="year" required>
                            <option value="">Select One</option>
                            <?php foreach (get_years() as $year) : ?>
                                <option value="<?php echo $year ?>" ><?php echo $year ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" required name="month">
                        <option value="">Select One</option>
                        <?php foreach (get_months() as $i => $month) : ?>
                            <option value="<?php echo $i ?>"><?php echo $month ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Project</label>
                    <select class="form-control" required name="project_id">
                        <option value="">Select One</option>
                        <?php foreach ($all_projects['data'] as $project) : ?>
                            <option value="<?php echo $project['pk_project_id'] ?>"><?php echo $project['project_short_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Branch</label>
                    <select class="form-control" required name="branch_id">
                        <option value="">Select One</option>
                        <?php foreach ($all_branches['data'] as $branch) : ?>
                            <option value="<?php echo $branch['pk_branch_id'] ?>"><?php echo $branch['branch_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_report/manage_targets') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Show Activities' : 'Show Activities',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Show Activities' : 'Show Activities'
            ))
            ?>
        </div>
    </div>
</form>