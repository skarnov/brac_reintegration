<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;

if (!checkPermission($edit, 'add_training_validation', 'edit_training_validation')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_training_validations(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid training validation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
        ),
    );
    $data['training_id'] = $training_id;
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_training_validation($data);

    if ($ret) {
        $msg = "Training validation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_trainings?action=training_validation&training_id=' . $training_id));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Training Validation </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=training_validation&training_id=' . $training_id,
                'action' => 'list',
                'text' => 'All Training Validations',
                'title' => 'Manage Training Validations',
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Entry Date</label>
                    <div class="input-group">
                        <input id="Date" type="text" class="form-control" required name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : ''; ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('Date');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Evaluator Profession</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="Judicial govt. employee" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'Judicial govt. employee' ? 'checked' : '' ?>><span class="lbl">Judicial govt. employee</span></label>
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="Non-judicial Govt. employee" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'Non-judicial Govt. employee' ? 'checked' : '' ?>><span class="lbl">Non-judicial Govt. employee</span></label>
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="Lawyers" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'Lawyers' ? 'checked' : '' ?>><span class="lbl">Lawyers</span></label>
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="NGO" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'NGO' ? 'checked' : '' ?>><span class="lbl">NGO</span></label>
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="Journalist" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'Journalist' ? 'checked' : '' ?>><span class="lbl">Journalist</span></label>
                            <label><input class="px educations" type="radio" name="evaluator_profession" value="Public representative" <?php echo $pre_data && $pre_data['evaluator_profession'] == 'Public representative' ? 'checked' : '' ?>><span class="lbl">Public representative</span></label>
                            <label><input class="px col-sm-12" type="radio" value="" name="evaluator_profession" id="newEvaluatorProfession"><span class="lbl">Others</span></label>
                        </div>
                    </div>
                </div>
                <div id="newEvaluatorProfessionType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control col-sm-12" id="newEvaluatorProfessionTypeText" placeholder="Please Specity" type="text" name="new_evaluator_profession" value="<?php echo $pre_data['evaluator_profession']; ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newEvaluatorProfession').is(':checked');

                        if (isChecked == true) {
                            $('#newEvaluatorProfessionType').show();
                        }

                        $("#newEvaluatorProfession").on("click", function () {
                            $('#newEvaluatorProfessionType').show();
                        });

                        $(".educations").on("click", function () {
                            $('#newEvaluatorProfessionType').hide();
                            $('#newEvaluatorProfessionTypeText').val('');
                        });
                    });
                </script>
            </div>
            <div class="col-md-4">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Organization of The Training</legend>
                    <div class="form-group">
                        <label>How satisfied are you with the contents of training/workshop</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="satisfied_training" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['satisfied_training'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['satisfied_training'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['satisfied_training'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['satisfied_training'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['satisfied_training'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How satisfied are you with the training venue and other logistic supports</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="satisfied_supports" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['satisfied_supports'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['satisfied_supports'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['satisfied_supports'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['satisfied_supports'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['satisfied_supports'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How satisfied are you with the training/workshop facilitation</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="satisfied_facilitation" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['satisfied_facilitation'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['satisfied_facilitation'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['satisfied_facilitation'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['satisfied_facilitation'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['satisfied_facilitation'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Outcome of The Training</legend>
                    <div class="form-group">
                        <label>What extent your knowledge increased on NPA</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="outcome_training" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['outcome_training'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['outcome_training'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['outcome_training'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['outcome_training'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['outcome_training'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on trafficking law</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="trafficking_law" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['trafficking_law'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['trafficking_law'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['trafficking_law'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['trafficking_law'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['trafficking_law'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on policy process</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="policy_process" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['policy_process'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['policy_process'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['policy_process'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['policy_process'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['policy_process'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on over all contents</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="all_contents" >
                                <option value="">Select One</option>
                                <option value="5" <?php echo $pre_data && $pre_data['all_contents'] == '5' ? 'selected' : '' ?>>Very Satisfied</option>
                                <option value="4" <?php echo $pre_data && $pre_data['all_contents'] == '4' ? 'selected' : '' ?>>Satisfied</option>
                                <option value="3" <?php echo $pre_data && $pre_data['all_contents'] == '3' ? 'selected' : '' ?>>Ok</option>
                                <option value="2" <?php echo $pre_data && $pre_data['all_contents'] == '2' ? 'selected' : '' ?>>Dissatisfied</option>
                                <option value="1" <?php echo $pre_data && $pre_data['all_contents'] == '1' ? 'selected' : '' ?>>Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Recommendation (If Any)" name="recommendation"><?php echo $pre_data['recommendation']; ?></textarea>
                    </div>
                </fieldset>
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
</form>
<script type="text/javascript">
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>