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
    $pre_data = $this->get_family_counseling(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid family counseling session, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'family_entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_family_counseling($data);

    if ($ret['success']) {
        $msg = "Family Counseling Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_family_counseling&edit=' . $edit . '&customer_id=' . $customer_id . '&case_id=' . $case_id));
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
    <h1>Section 3.2: <?php echo $edit ? 'Update ' : 'New ' ?>Family Counseling</h1>
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
                            <input id="FamilyCounselingDate" type="text" class="form-control" name="family_entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('FamilyCounselingDate');
                            });
                        </script>
                    </div>
                    <div class="form-group ">
                        <label>Start Time</label>
                        <div class="input-group date">
                            <input type="text" name="family_entry_time"  value="<?php echo $pre_data['entry_time'] && $pre_data['entry_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['entry_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Place of Family Counseling</label>
                        <input class="form-control" type="text" id="session_place" name="session_place" value="<?php echo $pre_data['session_place'] ? $pre_data['session_place'] : ''; ?>">
                    </div>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">No of Family Members Counseled</legend>
                        <div class="col-sm-4">   
                            <label class="control-label input-label">Men</label>
                            <div class="form-group">
                                <input type="number" class="filter form-control" id="maleMember" onchange="calc()" name="male_counseled" value="<?php echo $pre_data['male_counseled'] ? $pre_data['male_counseled'] : '0'; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label input-label">Women</label>
                            <div class="form-group">
                                <input type="number" class="filter form-control" id="femaleMember" onchange="calc()" name="female_counseled" value="<?php echo $pre_data['female_counseled'] ? $pre_data['female_counseled'] : '0'; ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">   
                            <label class="control-label input-label">Total</label>
                            <div class="form-group">
                                <input class="form-control" id="totalMember" type="number" value="<?php echo $pre_data['male_counseled'] + $pre_data['female_counseled'] ?>">
                            </div>
                        </div>
                    </fieldset>
                    <script>
                        function calc() {
                            var maleMember = $('#maleMember').val();
                            var femaleMember = $('#femaleMember').val();

                            var total = Number(maleMember) + Number(femaleMember);
                            $('#totalMember').val(total);
                        }
                    </script>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Description of Activities</label>
                        <textarea class="form-control" rows="5"  name="activities_description" placeholder="Description of Activities"><?php echo $pre_data['activities_description'] ? $pre_data['activities_description'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Comments/Remarks of Counselor</label>
                        <textarea class="form-control" name="session_comments" rows="5" placeholder="Comments/Remarks"><?php echo $pre_data['session_comments'] ? $pre_data['session_comments'] : ''; ?></textarea>
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