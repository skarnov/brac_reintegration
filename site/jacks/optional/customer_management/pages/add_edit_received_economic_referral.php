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
    $pre_data = $this->get_economic_referral_received(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid ID, no data found.', 'error');
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
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_received_economic_referral($data);

    if ($ret['success']) {
        $msg = "Economic Referral Received Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_received_economic_referral&edit=' . $edit . '&customer_id=' . $customer_id . '&case_id=' . $case_id));
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
    <h1>Section 4.5: <?php echo $edit ? 'Update ' : 'New ' ?>Referral Received and Linkage Support</h1>
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
                        <label>Entry Date</label>
                        <div class="input-group">
                            <input id="FamilyCounselingDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('FamilyCounselingDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Referral ID</label>
                        <input class="form-control" type="text" name="referral_id" value="<?php echo $pre_data['referral_id'] ? $pre_data['referral_id'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Referred For</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                $referred_for = explode(',', $pre_data['referred_for']);
                                $referred_for = $referred_for ? $referred_for : array($referred_for);
                                ?>
                                <label><input class="px" type="checkbox" name="referred_for[]" value="Loan"<?php
                                    if (in_array('Loan', $referred_for)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Loan</span></label>
                                <label><input class="px" type="checkbox" name="referred_for[]" value="Job Placement"<?php
                                    if (in_array('Job Placement', $referred_for)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Job Placement</span></label>
                                <label><input class="px col-sm-12" type="checkbox" name="referred_for[]" value="" id="newProblemIdentified" <?php echo $pre_data && $pre_data['other_referred_for'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                            </div>
                            <div id="newProblemIdentifiedType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newProblemIdentifiedTypeText" name="new_referred_for" value="<?php echo $pre_data['other_referred_for'] ? $pre_data['other_referred_for'] : ''; ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newProblemIdentified').is(':checked');

                                    if (isChecked == true) {
                                        $('#newProblemIdentifiedType').show();
                                    }

                                    $("#newProblemIdentified").on("click", function () {
                                        $('#newProblemIdentifiedType').toggle();
                                        $('#newProblemIdentifiedTypeText').val('');
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Referral Service Provider</label>
                        <input class="form-control" type="text" name="referral_service_provider" value="<?php echo $pre_data['referral_service_provider'] ? $pre_data['referral_service_provider'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Referral Service Provider Address</label>
                        <input class="form-control" type="text" name="referral_service_provider_address" value="<?php echo $pre_data['referral_service_provider_address'] ? $pre_data['referral_service_provider_address'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Referral Service Received Date</label>
                        <div class="input-group">
                            <input id="ReferralDate" type="text" class="form-control" name="received_date" value="<?php echo $pre_data['received_date'] && $pre_data['received_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['received_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('ReferralDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea class="form-control" name="referral_comment" rows="2"><?php echo $pre_data['referral_comment'] ? $pre_data['referral_comment'] : ''; ?></textarea>
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