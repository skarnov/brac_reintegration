<?php
$customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$case_id = $_GET['case_id'] ? $_GET['case_id'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$initial_evaluation = $this->get_initial_evaluation(array('customer_id' => $customer_id, 'single' => true));
$selected_supports = explode(',', $initial_evaluation['selected_supports']);

$pre_data = array();
if ($edit) {
    $args = array(
        'select_fields' => array(
            'entry_date' => 'dev_followups.entry_date',
            'support_status' => 'dev_followups.support_status',
            'support_received' => 'dev_followups.support_received',
            'confirm_services' => 'dev_followups.confirm_services',
            'followup_financial_service' => 'dev_followups.followup_financial_service',
            'psychosocial_date' => 'dev_followups.psychosocial_date',
            'psychosocial_problem' => 'dev_followups.psychosocial_problem',
            'psychosocial_action' => 'dev_followups.psychosocial_action',
            'psychosocial_participant' => 'dev_followups.psychosocial_participant',
            'psychosocial_counselor' => 'dev_followups.psychosocial_counselor',
            'economic_date' => 'dev_followups.economic_date',
            'monthly_average_income' => 'dev_followups.monthly_average_income',
            'economic_challenges' => 'dev_followups.economic_challenges',
            'economic_action' => 'dev_followups.economic_action',
            'significant_changes' => 'dev_followups.significant_changes',
            'economic_participant' => 'dev_followups.economic_participant',
            'economic_officer' => 'dev_followups.economic_officer',
            'economic_manager' => 'dev_followups.economic_manager',
            'social_date' => 'dev_followups.social_date',
            'social_challenges' => 'dev_followups.social_challenges',
            'social_action' => 'dev_followups.social_action',
            'social_changes' => 'dev_followups.social_changes',
            'social_participant' => 'dev_followups.social_participant',
            'social_officer' => 'dev_followups.social_officer',
            'social_manager' => 'dev_followups.social_manager',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_case_review($args);

    if (!$pre_data) {
        add_notification('Invalid review id, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date'
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_review($data);

    if ($ret['success']) {
        $msg = "Case Review Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_review&edit=' . $edit . '&customer_id=' . $customer_id . '&case_id=' . $case_id));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Case Review</h1>
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
                <fieldset>
                    <legend>Section 6: Review and Follow-up</legend>
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Entry Date</label>
                            <div class="input-group">
                                <input id="supportDate" required type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : ''; ?>">
                            </div>
                            <script type="text/javascript">
                                init.push(function () {
                                    _datepicker('supportDate');
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label>Present Support Status</label>
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <label><input class="px" type="radio" name="support_status" value="completed" <?php echo $pre_data && $pre_data['support_status'] == 'completed' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                    <label><input class="px" type="radio" name="support_status" value="ongoing" <?php echo $pre_data && $pre_data['support_status'] == 'ongoing' ? 'checked' : '' ?>><span class="lbl">Ongoing</span></label>
                                    <label><input class="px" type="radio" name="support_status" value="dropout" <?php echo $pre_data && $pre_data['support_status'] == 'dropout' ? 'checked' : '' ?>><span class="lbl">Dropout</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Type of support received</label>
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <?php
                                    $support_received = explode(',', $pre_data['support_received']);
                                    $support_received = $support_received ? $support_received : array($support_received);
                                    ?>
                                    <label><input class="px" type="checkbox" name="support_received[]" value="Psycho-social Support" <?php
                                        if (in_array('Psycho-social Support', $support_received)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Psycho-social Support</span></label>
                                    <label><input class="px" type="checkbox" name="support_received[]" value="Economic Support" <?php
                                        if (in_array('Economic Support', $support_received)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Economic Support</span></label>
                                    <label><input class="px" type="checkbox" name="support_received[]" value="Social Support" <?php
                                        if (in_array('Social Support', $support_received)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Social Support</span></label>
                                </div>
                            </div>
                        </div>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Confirmed Services Received after 3 Months</legend>
                            <div class="form-group">
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $confirm_services = explode(',', $pre_data['confirm_services']);
                                        $confirm_services = $confirm_services ? $confirm_services : array($confirm_services);
                                        ?>
                                        <label class="col-sm-12"><input class="px" id="educationConfirm" type="checkbox" name="confirm_services[]" value="Psychosocial Support" <?php
                                            if (in_array('Psychosocial Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Psychosocial Support</span></label>
                                        <div id="educationConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Individual Counseling" <?php
                                                    if (in_array('Individual Counseling', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Individual Counseling</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Family Counseling" <?php
                                                    if (in_array('Family Counseling', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Family Counseling</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Trauma Counseling" <?php
                                                    if (in_array('Trauma Counseling', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Trauma Counseling</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#educationConfirmAttr').hide();

                                                var isChecked = $('#educationConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#educationConfirmAttr').show();
                                                }

                                                $("#educationConfirm").on("click", function () {
                                                    $('#educationConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Economic Support" <?php
                                            if (in_array('Economic Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Economic Support</span></label>
                                        <label class="col-sm-12"><input class="px" id="financialServiceConfirm" type="checkbox" name="confirm_services[]" value="Financial Services" <?php
                                            if (in_array('Financial Services', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Financial Services</span></label>
                                        <div id="financialServiceConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Loan" <?php
                                                    if (in_array('Loan', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Loan</span></label>
                                            </div>
                                            <div class="form-group">
                                                <label>Other Financial Service</label>
                                                <input class="form-control" placeholder="Other financial service" type="text" name="followup_financial_service" value="<?php echo $pre_data['followup_financial_service'] ? $pre_data['followup_financial_service'] : ''; ?>">
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#financialServiceConfirmAttr').hide();

                                                var isChecked = $('#financialServiceConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#financialServiceConfirmAttr').show();
                                                }

                                                $("#financialServiceConfirm").on("click", function () {
                                                    $('#financialServiceConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="trainingConfirm" type="checkbox" name="confirm_services[]" value="Training" <?php
                                            if (in_array('Training', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Training</span></label>
                                        <div id="trainingConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Financial Literacy Training" <?php
                                                    if (in_array('Financial Literacy Training', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Financial Literacy Training</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training from project" <?php
                                                    if (in_array('Advance training from project"', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Advance training from project</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training through referrals" <?php
                                                    if (in_array('Advance training through referrals', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Advance training through referrals</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#trainingConfirmAttr').hide();

                                                var isChecked = $('#trainingConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#trainingConfirmAttr').show();
                                                }

                                                $("#trainingConfirm").on("click", function () {
                                                    $('#trainingConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="materialAssistanceConfirm" type="checkbox" name="confirm_services[]" value="Material Assistance" <?php
                                            if (in_array('Material Assistance', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Material Assistance</span></label>
                                        <div id="materialAssistanceConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Business equipment/tools" <?php
                                                    if (in_array('Business equipment/tools', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Business equipment/tools</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation of land or pond for business" <?php
                                                    if (in_array('Allocation of land or pond for business', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Allocation of land or pond for business</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#materialAssistanceConfirmAttr').hide();

                                                var isChecked = $('#materialAssistanceConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#materialAssistanceConfirmAttr').show();
                                                }

                                                $("#materialAssistanceConfirm").on("click", function () {
                                                    $('#materialAssistanceConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement" <?php
                                            if (in_array('Job Placement', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Job Placement</span></label>
                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Set up enterprise" <?php
                                            if (in_array('Set up enterprise', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Set up enterprise</span></label>

                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Business incubation" <?php
                                            if (in_array('Business incubation', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Business incubation</span></label>

                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Microbusiness" <?php
                                            if (in_array('Microbusiness', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Microbusiness</span></label>

                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Business grant" <?php
                                            if (in_array('Business grant', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Business grant</span></label>

                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Social Support" <?php
                                            if (in_array('Social Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Social Support</span></label>

                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Child Care" <?php
                                            if (in_array('Child Care', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Child Care</span></label>
                                        <label class="col-sm-12"><input class="px" id="education" type="checkbox" name="confirm_services[]" value="Education" <?php
                                            if (in_array('Education', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Education</span></label>
                                        <div id="educationAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Admission" <?php
                                                    if (in_array('Admission', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Admission</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Stipend/ Scholarship" <?php
                                                    if (in_array('Stipend/ Scholarship', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Stipend/ Scholarship</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#educationAttr').hide();
                                                var isChecked = $('#education').is(':checked');

                                                if (isChecked == true) {
                                                    $('#educationAttr').show();
                                                }

                                                $("#education").on("click", function () {
                                                    $('#educationAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="housingConfirm" type="checkbox" name="confirm_services[]" value="Housing" <?php
                                            if (in_array('Housing', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Housing</span></label>
                                        <div id="housingConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation for khas land" <?php
                                                    if (in_array('Allocation for khas land', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Allocation for khas land</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Support for land allocation" <?php
                                                    if (in_array('Support for land allocation', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Support for land allocation</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#housingConfirmAttr').hide();

                                                var isChecked = $('#housingConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#housingConfirmAttr').show();
                                                }

                                                $("#housingConfirm").on("click", function () {
                                                    $('#housingConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="legalServicesConfirm" type="checkbox" name="confirm_services[]" value="Legal Services" <?php
                                            if (in_array('Legal Services', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Legal Services</span></label>
                                        <div id="legalServicesConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Aid" <?php
                                                    if (in_array('Legal Aid"', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Legal Aid</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Claiming Compensation" <?php
                                                    if (in_array('Claiming Compensation', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Claiming Compensation</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Assistance in resolving family dispute" <?php
                                                    if (in_array('Assistance in resolving family dispute', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#legalServicesConfirmAttr').hide();

                                                var isChecked = $('#legalServicesConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#legalServicesConfirmAttr').show();
                                                }

                                                $("#legalServicesConfirm").on("click", function () {
                                                    $('#legalServicesConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="remigrationConfirm" type="checkbox" name="confirm_services[]" value="Remigration" <?php
                                            if (in_array('Remigration', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Remigration</span></label>
                                        <div id="remigrationConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Direct Assistance from project" <?php
                                                    if (in_array('Direct Assistance from project', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Direct Assistance from project</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Referrals support from project" <?php
                                                    if (in_array('Referrals support from project', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Referrals support from project</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#remigrationConfirmAttr').hide();

                                                var isChecked = $('#remigrationConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#remigrationConfirmAttr').show();
                                                }

                                                $("#remigrationConfirm").on("click", function () {
                                                    $('#remigrationConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="medicalSupportConfirm" type="checkbox" name="confirm_services[]" value="Medical Support" <?php
                                            if (in_array('Medical Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Medical Support</span></label>
                                        <div id="medicalSupportConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Medical treatment" <?php
                                                    if (in_array('Medical treatment', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Medical treatment</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Psychiatric treatment" <?php
                                                    if (in_array('Psychiatric treatment', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Psychiatric treatment</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#medicalSupportConfirmAttr').hide();

                                                var isChecked = $('#medicalSupportConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#medicalSupportConfirmAttr').show();
                                                }

                                                $("#medicalSupportConfirm").on("click", function () {
                                                    $('#medicalSupportConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="socialProtectionConfirm" type="checkbox" value="Social Protection Schemes" <?php echo $pre_data && $pre_data['followup_social_protection'] != NULL ? 'checked' : '' ?>><span class="lbl">Social Protection Schemes</span></label>
                                        <div id="socialProtectionConfirmAttr" class="form-group">
                                            <input class="form-control" placeholder="Specify Social Protection Schemes" type="text" name="social_protection" value="<?php echo $pre_data['followup_social_protection'] ? $pre_data['followup_social_protection'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#socialProtectionConfirmAttr').hide();

                                                var isChecked = $('#socialProtectionConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#socialProtectionConfirmAttr').show();
                                                }

                                                $("#socialProtectionConfirm").on("click", function () {
                                                    $('#socialProtectionConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="securityMeasuresConfirm" type="checkbox" value="Special Security Measures" <?php echo $pre_data && $pre_data['special_security'] != NULL ? 'checked' : '' ?>><span class="lbl">Special Security Measures</span></label>
                                        <div id="securityMeasuresConfirmAttr" class="form-group">
                                            <input class="form-control" placeholder="Specify Security Measures" type="text" name="special_security" value="<?php echo $pre_data['special_security'] ? $pre_data['special_security'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#securityMeasuresConfirmAttr').hide();

                                                var isChecked = $('#securityMeasuresConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#securityMeasuresConfirmAttr').show();
                                                }

                                                $("#securityMeasuresConfirm").on("click", function () {
                                                    $('#securityMeasuresConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                            <?php
                            if (in_array('Psychosocial Reintegration Support Services', $selected_supports)) :
                                ?>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Psychosocial Support</legend>
                                    <div class="form-group">
                                        <label>Visit Date</label>
                                        <div class="input-group">
                                            <input id="commentPsychosocialDate" type="text" class="form-control" name="psychosocial_date" value="<?php echo $pre_data['psychosocial_date'] && $pre_data['psychosocial_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['psychosocial_date'])) : ''; ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker("commentPsychosocialDate");
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Problem</label>
                                        <textarea class="form-control" name="psychosocial_problem" rows="3"><?php echo $pre_data['psychosocial_problem'] ? $pre_data['psychosocial_problem'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Action Taken</label>
                                        <textarea class="form-control" name="psychosocial_action" rows="3"><?php echo $pre_data['psychosocial_action'] ? $pre_data['psychosocial_action'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Remark of the participant (If any)</label>
                                        <textarea class="form-control" name="psychosocial_participant" rows="3"><?php echo $pre_data['psychosocial_participant'] ? $pre_data['psychosocial_participant'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Remark of the counselor</label>
                                        <textarea class="form-control" name="psychosocial_counselor" rows="3"><?php echo $pre_data['psychosocial_counselor'] ? $pre_data['psychosocial_counselor'] : ''; ?></textarea>
                                    </div>
                                </fieldset>
                            <?php endif ?>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Economic Support</legend>
                                    <div class="form-group">
                                        <label>Visit Date</label>
                                        <div class="input-group">
                                            <input id="reviewPsychosocialDate" type="text" class="form-control" name="economic_date" value="<?php echo $pre_data['economic_date'] && $pre_data['economic_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_date'])) : ''; ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker("reviewPsychosocialDate");

                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Monthly Average Income (BDT)</label>
                                        <input type="number" class="form-control" name="monthly_average_income" value="<?php echo $pre_data['monthly_average_income'] ? $pre_data['monthly_average_income'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Challenges</label>
                                        <textarea class="form-control" name="economic_challenges" rows="3"><?php echo $pre_data['economic_challenges'] ? $pre_data['economic_challenges'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Action Taken</label>
                                        <textarea class="form-control" name="economic_action" rows="3"><?php echo $pre_data['economic_action'] ? $pre_data['economic_action'] : ''; ?></textarea>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Significant changes</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $significant_changes = explode(',', $pre_data['significant_changes']);
                                                    $significant_changes = $significant_changes ? $significant_changes : array($significant_changes);
                                                    ?> 
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="significant_changes[]" value="Placed in job" <?php
                                                        if (in_array('Placed in job', $significant_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Placed in job</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="significant_changes[]" value="Set up new enterprise" <?php
                                                        if (in_array('Set up new enterprise', $significant_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Set up new enterprise</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="significant_changes[]" value="Set up a new IGA" <?php
                                                        if (in_array('Set up a new IGA', $significant_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Set up a new IGA</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="significant_changes[]" value="Invest in productive purpose" <?php
                                                        if (in_array('Invest in productive purpose', $significant_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Invest in productive purpose</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="significant_changes[]" value="Improved business" <?php
                                                        if (in_array('Improved business', $significant_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Improved business</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="significant_changes[]" id="newBusiness" <?php echo $pre_data && $pre_data['other_significant_changes'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newBusinessType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newBusinessTypeText" name="new_significant_changes" value="<?php echo $pre_data['other_significant_changes'] ? $pre_data['other_significant_changes'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newBusiness').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newBusinessType').show();
                                                }

                                                $("#newBusiness").on("click", function () {
                                                    $('#newBusinessType').toggle();
                                                    $('#newBusinessTypeText').val('');
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Remark of the participant (If any)</label>
                                        <textarea class="form-control" name="economic_participant" rows="3"><?php echo $pre_data['economic_participant'] ? $pre_data['economic_participant'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Comment of BRAC Officer Responsible For Participant</label>
                                        <textarea class="form-control" name="economic_officer" rows="3"><?php echo $pre_data['economic_officer'] ? $pre_data['economic_officer'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Remark of RSC Manager</label>
                                        <textarea class="form-control" name="economic_manager" rows="3"><?php echo $pre_data['economic_manager'] ? $pre_data['economic_manager'] : ''; ?></textarea>
                                    </div>
                                </fieldset>
                            <?php endif ?>
                            <?php
                            if (in_array('Social Reintegration Support', $selected_supports)) :
                                ?>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Social Support</legend>
                                    <div class="form-group">
                                        <label>Visit Date</label>
                                        <div class="input-group">
                                            <input id="reviewSocialDate" type="text" class="form-control" name="social_date" value="<?php echo $pre_data['social_date'] && $pre_data['social_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['social_date'])) : ''; ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker("reviewSocialDate");

                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Challenges</label>
                                        <textarea class="form-control" name="social_challenges" rows="3"><?php echo $pre_data['social_challenges'] ? $pre_data['social_challenges'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Action Taken</label>
                                        <textarea class="form-control" name="social_action" rows="3"><?php echo $pre_data['social_action'] ? $pre_data['social_action'] : ''; ?></textarea>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Significant changes</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $social_changes = explode(',', $pre_data['social_changes']);
                                                    $social_changes = $social_changes ? $social_changes : array($social_changes);
                                                    ?>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="social_changes[]" value="Communicated with referred organization" <?php
                                                        if (in_array('Communicated with referred organization', $social_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Communicated with referred organization</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="social_changes[]" value="Already availed the service" <?php
                                                        if (in_array('Already availed the service', $social_changes)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Already availed the service</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Remark of the participant (If any)</label>
                                        <textarea class="form-control" name="social_participant" rows="3"><?php echo $pre_data['social_participant'] ? $pre_data['social_participant'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Comment of BRAC Officer Responsible For Participant</label>
                                        <textarea class="form-control" name="social_officer" rows="3"><?php echo $pre_data['social_officer'] ? $pre_data['social_officer'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Remark of RSC Manager</label>
                                        <textarea class="form-control" name="social_manager" rows="3"><?php echo $pre_data['social_manager'] ? $pre_data['social_manager'] : ''; ?></textarea>
                                    </div>
                                </fieldset>
                            <?php endif ?>
                        </fieldset>
                    </div>
                </fieldset>
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