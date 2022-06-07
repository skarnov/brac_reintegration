<?php
$customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_customer', 'edit_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_initial_evaluation(array('customer_id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid evaluation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'is_participant' => 'Selected as a participant'
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_initial_evaluation($data);

    if ($ret['success']) {
        $msg = "Evaluation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_customers?action=add_edit_evaluate&edit=' . $edit));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Initial Evaluation</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Participant Profile',
                'title' => 'Manage Participant Profile',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Selected as a participant (*)</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="Yesparticipant" name="is_participant" value="yes" <?php echo $pre_data && $pre_data['is_participant'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="Noparticipant" name="is_participant" value="no" <?php echo $pre_data && $pre_data['is_participant'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12" id="Justification" style="margin-bottom: 1em; display: none;">
                        <?php
                        $all_justifications = array(
                            'Autism' => 'Autism',
                            'Physical' => 'Physical',
                            'Psychosocial' => 'Psychosocial',
                            'Visually Impaired' => 'Visually Impaired',
                            'Speech Disability' => 'Speech Disability',
                            'Intellectual Disability' => 'Intellectual Disability',
                            'Hearing Disability' => 'Hearing Disability',
                            'Hearing - Visual Disability' => 'Hearing - Visual Disability',
                            'Multiple Disabilities' => 'Multiple Disabilities',
                            'Cerebral Palsy' => 'Cerebral Palsy',
                            'Down Syndrome' => 'Down Syndrome',
                        );
                        ?>
                        <div class="form-group">
                            <label class="control-label input-label">Justification of selecting for project</label>
                            <div class="select2-primary">
                                <select class="form-control" name="justification_project" style="text-transform: capitalize">
                                    <option value="">Select One</option>
                                    <?php foreach ($all_justifications as $key => $value) : ?>
                                        <option id="<?php echo $key ?>" value="<?php echo $value ?>" <?php echo $pre_data && $pre_data['justification_project'] == $key ? 'selected' : '' ?>><?php echo $value ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <script>
                        init.push(function () {
                            var isChecked = $('#Yesparticipant').is(':checked');

                            if (isChecked == true) {
                                $('#Justification').show();
                            }

                            $("#Yesparticipant").on("click", function () {
                                $('#Justification').show();
                            });

                            $("#Noparticipant").on("click", function () {
                                $('#Justification').hide();
                            });
                        });
                    </script>
                </div>
                <div class="col-sm-6">
                    <?php
                    $all_supports = array(
                        'Psychosocial Reintegration Support Services' => 'Psychosocial Reintegration Support Services',
                        'Family Counseling Session' => 'Family Counseling Session',
                        'Economic Reintegration Support' => 'Economic Reintegration Support',
                        'Social Reintegration Support' => ' Social Reintegration Support'
                    );
                    $selected_supports = explode(',', $pre_data['selected_supports']);
                    $selected_supports = $selected_supports ? $selected_supports : array($selected_supports);
                    ?>
                    <div class="form-group">
                        <label>Selected for which supports</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php foreach ($all_supports as $key => $value) :
                                    ?>
                                    <label><input class="px" type="checkbox" name="selected_supports[]" value="<?php echo $key ?>" <?php
                                        if (in_array($key, $selected_supports)) :
                                            echo 'checked';
                                        endif
                                        ?>><span class="lbl"><?php echo $value ?></span></label>
                                              <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_customer_management/manage_customers') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
</form>