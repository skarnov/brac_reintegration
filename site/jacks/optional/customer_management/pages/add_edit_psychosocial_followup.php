<?php
$customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$case_id = $_GET['case_id'] ? $_GET['case_id'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_psychosocial_followup(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid psychosocial followup, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'followup_entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_psychosocial_followup($data);

    if ($ret['success']) {
        $msg = "Psychosocial Followup Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_psychosocial_followup&edit=' . $edit . '&customer_id=' . $customer_id . '&case_id=' . $case_id));
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
    <h1>Section 3.4: <?php echo $edit ? 'Update ' : 'New ' ?>Psychosocial Followup</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $case_id),
                'action' => 'list',
                'text' => 'Manage This Case ',
                'title' => 'Manage This Case ',
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
                        <label>Entry Date (*)</label>
                        <div class="input-group">
                            <input id="FollowupTimeDate" name="followup_entry_date" type="text" class="form-control" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('FollowupTimeDate');
                        });
                    </script>
                    <div class="form-group">
                        <label>Start Time</label>
                        <div class="input-group date">
                            <input type="text" name="followup_entry_time" value="<?php echo $pre_data['entry_time'] && $pre_data['entry_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['entry_time'])) : date('H:i:s'); ?>" class="form-control" id="FollowupTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                            $('#FollowupTime').timepicker(options2);
                        });
                    </script>
                    <div class="form-group">
                        <label>End Time</label>
                        <div class="input-group date">
                            <input type="text" name="session_end_time" value="<?php echo $pre_data['session_end_time'] && $pre_data['session_end_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['session_end_time'])) : ''; ?>" class="form-control" id="sessionEndTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                            $('#sessionEndTime').timepicker(options2);
                        });
                    </script>
                    <div class="form-group">
                        <label>Comment of Psychosocial Counselor (*)</label>
                        <textarea class="form-control" required name="followup_comments" rows="3" placeholder=""><?php echo $pre_data['followup_comments'] ? $pre_data['followup_comments'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_customer_management/manage_cases') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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