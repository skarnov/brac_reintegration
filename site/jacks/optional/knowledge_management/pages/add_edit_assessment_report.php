<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_assessment_report', 'edit_assessment_report')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_knowledge(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid assessment report, no data found.', 'error');
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

    $ret = $this->add_edit_assessment_report($data);

    if ($ret) {
        $msg = "Assessment Report has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_knowledge_management/manage_assessment_reports?action=add_edit_assessment_report&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_knowledge_management/manage_assessment_reports'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_assessment_report_tags = $this->get_lookups('success_assessment_report');

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Project Report </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Project Reports',
                'title' => 'Manage Project Reports',
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
                    <label>Project Name</label>
                    <div class="select2-primary">
                        <select class="form-control" name="fk_project_id" required>
                            <option value="">Select One</option>
                            <?php foreach ($all_projects['data'] as $project) : ?>
                                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tags</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <?php
                            $tags = explode(',', $pre_data['tags']);
                            foreach ($all_assessment_report_tags['data'] as $tag) {
                                ?>
                                <label><input class="px" type="checkbox" name="tags[]" value="<?php echo $tag['lookup_value'] ?>" <?php
                                    if (in_array($tag['lookup_value'], $tags)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl"><?php echo $tag['lookup_value'] ?></span></label>
                                          <?php } ?>
                            <label><input class="px" type="checkbox" id="newTag"><span class="lbl">Others</span></label>
                        </div>
                    </div>
                </div>
                <div id="newTagType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" name="new_tag" value="">
                </div>
                <script>
                    init.push(function () {
                        $("#newTag").on("click", function () {
                            $('#newTagType').toggle();
                        });
                    });
                </script>
            </div>
            <div class="col-md-6">  
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input type="text" class="form-control" required name="name" value="<?php echo $pre_data['name']; ?>">
                </div>
                <div class="form-group">
                    <label>File</label>
                    <input type="hidden" name="document_old_file" value="<?php echo $pre_data['document_file'] ? $pre_data['document_file'] : '' ?>"/>
                    <input type="file" class="form-control" name="document_file">
                </div>
                <?php if ($edit): ?>
                    <div class="form-group">
                        <label>Uploaded File</label>
                        <a href="<?php echo image_url($pre_data['document_file']); ?>" target="_blank">Click Me</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_knowledge_management/manage_assessment_reports') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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