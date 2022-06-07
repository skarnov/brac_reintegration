<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$event_id = $_GET['event_id'] ? $_GET['event_id'] : null;

$args = array(
    'id' => $event_id,
    'single' => true,
);

$event_info = $this->get_events($args);

if (!checkPermission($edit, 'add_event', 'edit_event')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_event_validations(array('id' => $edit, 'single' => true));
    $message = explode(',', $pre_data['message']);

    if (!$pre_data) {
        add_notification('Invalid event validation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
        ),
    );
    $data['event_id'] = $event_id;
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_event_validation($data);

    if ($ret) {
        $msg = "Event Validation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_event_validations?event_id=' . $event_id));
        } else {
            header('location: ' . url('admin/dev_activity_management/manage_event_validations?event_id=' . $event_id));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Event Validation</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'manage_event_validations?event_id=' . $event_id,
                'action' => 'list',
                'text' => 'All Event Validations',
                'title' => 'Manage Event Validations',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'manage_events',
                'action' => 'list',
                'text' => 'All Events',
                'title' => 'Manage Events',
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
            <h4>Event Name: <?php echo $event_info['activity_name'] ?></h4>
            <p><?php echo $event_info['user_fullname'] . ', ' . $event_info['event_location'] . ', ' ?><?php echo $event_info['event_start_date'] ? date('l jS \of F Y', strtotime($event_info['event_start_date'])) : 'N/A' ?></p>
            <hr/>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Interview Date</label>
                    <div class="input-group">
                        <input id="InterviewDate" type="text" class="form-control" name="interview_date" value="<?php echo $pre_data['interview_date'] && $pre_data['interview_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['interview_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('InterviewDate');
                        });
                    </script>
                </div>
                <div class="form-group ">
                    <label>Interview Time</label>
                    <div class="input-group date">
                        <input type="text" name="interview_time" value="<?php echo $pre_data['interview_time'] && $pre_data['interview_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['interview_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    </div>
                </div>
                <script>
                    init.push(function () {
                        var options2 = {
                            minuteStep: 1,
                            showSeconds: true,
                            showMeridian: false,
                            showInputs: false,
                            orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                        }
                        $('#bs-timepicker-component').timepicker(options2);
                    });
                </script>
                <div class="form-group">
                    <label>Reviewed By</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="reviewed_by" value="internal" <?php echo $pre_data && $pre_data['reviewed_by'] == 'internal' ? 'checked' : '' ?>><span class="lbl">Internal (BRAC Employee)</span></label>
                            <label><input class="px" type="radio" name="reviewed_by" value="external" <?php echo $pre_data && $pre_data['reviewed_by'] == 'external' ? 'checked' : '' ?>><span class="lbl">External</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Beneficiary ID (If any)</label>
                    <input class="form-control" type="text" name="beneficiary_id" value="<?php echo $pre_data['beneficiary_id']; ?>">
                </div>
                <label class="control-label input-label">Participant Name (*)</label>
                <div class="form-group">
                    <input class="form-control" type="text" name="participant_name" required value="<?php echo $pre_data['participant_name']; ?>">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                            <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                            <label><input class="px" type="radio" name="gender" id="newGender"><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>
                <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" name="new_gender" value="<?php echo $pre_data && $pre_data['gender'] ?>">
                </div>
                <script>
                    init.push(function () {
                        $("#newGender").on("click", function () {
                            $('#newGenderType').show();
                        });

                        $(".oldGender").on("click", function () {
                            $('#newGenderType').hide();
                            $('#newGenderText').val('');
                        });
                    });
                </script>
            </div>
            <div class="col-md-4">
                <label class="control-label input-label">Participant Age</label>
                <div class="form-group">
                    <input class="form-control" type="number" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <label class="control-label input-label">Participant Mobile</label>
                <div class="form-group">
                    <input class="form-control" type="number" name="mobile" value="<?php echo $pre_data['mobile']; ?>">
                </div>
                <div class="form-group">
                    <label>Do you enjoy this Event?</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="enjoyment" value="yes" <?php echo $pre_data && $pre_data['enjoyment'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                            <label><input class="px" type="radio" name="enjoyment" value="no" <?php echo $pre_data && $pre_data['enjoyment'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            <label><input class="px" type="radio" name="enjoyment" value="no-comment" <?php echo $pre_data && $pre_data['enjoyment'] == 'no-comment' ? 'checked' : '' ?>><span class="lbl">No Comment</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Trafficked Victim</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="victim" value="yes" <?php echo $pre_data && $pre_data['victim'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                            <label><input class="px " type="radio" name="victim" value="no" <?php echo $pre_data && $pre_data['victim'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Trafficked Victim Family Member</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="victim_family" value="yes" <?php echo $pre_data && $pre_data['victim_family'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                            <label><input class="px " type="radio" name="victim_family" value="no" <?php echo $pre_data && $pre_data['victim_family'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $message = $message ? $message : array($message);
            ?>   
            <div class="col-md-4">
                <div class="form-group">
                    <label>What were the messages or issues delivered in the event?</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="message[]" value="Trafficking in persons" <?php
                                if (in_array('Trafficking in persons', $message)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Trafficking in persons</span></label>
                            <label><input class="px" type="checkbox" name="message[]" value="Result of human trafficking" <?php
                                if (in_array('Result of human trafficking', $message)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Result of human trafficking</span></label>
                            <label><input class="px" type="checkbox" name="message[]" value="Who are most vulnerable" <?php
                                if (in_array('Who are most vulnerable', $message)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Who are most vulnerable</span></label>
                            <label><input class="px" type="checkbox" name="message[]" value="Reintegration" <?php
                                if (in_array('Reintegration', $message)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Reintegration</span></label>
                            <label><input class="px" type="checkbox" name="message[]" value="Irregular Migration" <?php
                                if (in_array('Irregular Migration', $message)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Irregular Migration</span></label>
                            <label><input class="px col-sm-12" type="checkbox" id="newissuesdelivered" <?php echo $pre_data && $pre_data['other_message'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                        </div>
                    </div>
                </div>
                <div id="newissuesdeliveredType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_message" value="<?php echo $pre_data['other_message'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newissuesdelivered').is(':checked');

                        if (isChecked == true) {
                            $('#newissuesdeliveredType').show();
                        }

                        $("#newissuesdelivered").on("click", function () {
                            $('#newissuesdeliveredType').toggle();
                        });
                    });
                </script>
                <div class="form-group">
                    <label>How do you intend to use these messages in your personal life?</label>
                    <textarea class="form-control" name="use_message"><?php echo $pre_data['use_message'] ? $pre_data['use_message'] : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label>What was mentioned in the event show that was not clear to you?</label>
                    <textarea class="form-control" name="mentioned_event"><?php echo $pre_data['mentioned_event'] ? $pre_data['mentioned_event'] : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label>Additional comments (if any)</label>
                    <textarea class="form-control" name="additional_comments"><?php echo $pre_data['additional_comments'] ? $pre_data['additional_comments'] : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label>Quote</label>
                    <textarea class="form-control" name="quote"><?php echo $pre_data['quote'] ? $pre_data['quote'] : '' ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_event_validations') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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