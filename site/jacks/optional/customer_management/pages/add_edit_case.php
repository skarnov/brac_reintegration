<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_psycho_family_counselling_id',
        'order' => 'DESC'
    ),
);
$family_counsellings = $this->get_family_counseling($args);

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_psycho_session_id',
        'order' => 'DESC'
    ),
);
$psychosocial_sessions = $this->get_psychosocial_session($args);

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_psycho_completion_id',
        'order' => 'DESC'
    ),
);

$psychosocial_completions = $this->get_psychosocial_completion($args);

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_psycho_followup_id',
        'order' => 'DESC'
    ),
);

$psychosocial_followups = $this->get_psychosocial_followup($args);

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_economic_referral_id',
        'order' => 'DESC'
    ),
);

$economic_referrals = $this->get_economic_referrals($args);

$args = array(
    'customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_economic_referral_id',
        'order' => 'DESC'
    ),
);

$received_economic_referrals = $this->get_economic_referral_received($args);

$args = array(
    'fk_customer_id' => $edit,
    'order_by' => array(
        'col' => 'pk_followup_id',
        'order' => 'DESC'
    ),
);

$reviews = $this->get_case_review($args);

$pre_data = array();
if ($edit) {
    $args = array(
        'select_fields' => array(
            'full_name' => 'dev_customers.full_name',
            'customer_id' => 'dev_customers.customer_id',
            'pk_immediate_support_id' => 'dev_immediate_supports.pk_immediate_support_id',
            'fk_staff_id' => 'dev_immediate_supports.fk_staff_id',
            'fk_customer_id' => 'dev_immediate_supports.fk_customer_id',
            'immediate_support' => 'dev_immediate_supports.immediate_support',
            'support_date' => 'dev_immediate_supports.entry_date AS support_date',
            'arrival_place' => 'dev_immediate_supports.arrival_place',
            'immediate_comment' => 'dev_immediate_supports.comment AS immediate_comment',
            'selected_supports' => 'dev_initial_evaluation.selected_supports',
            'plan_date' => 'dev_reintegration_plan.plan_date',
            'reintegration_financial_service' => 'dev_reintegration_plan.reintegration_financial_service',
            'service_requested' => 'dev_reintegration_plan.service_requested',
            'other_service_requested' => 'dev_reintegration_plan.other_service_requested',
            'social_protection' => 'dev_reintegration_plan.social_protection',
            'security_measure' => 'dev_reintegration_plan.security_measure',
            'service_requested_note' => 'dev_reintegration_plan.service_requested_note',
            'first_meeting' => 'dev_psycho_supports.first_meeting',
            'problem_identified' => 'dev_psycho_supports.problem_identified',
            'other_problem_identified' => 'dev_psycho_supports.other_problem_identified',
            'problem_description' => 'dev_psycho_supports.problem_description',
            'initial_plan' => 'dev_psycho_supports.initial_plan',
            'family_counseling' => 'dev_psycho_supports.family_counseling',
            'session_place' => 'dev_psycho_supports.session_place',
            'session_number' => 'dev_psycho_supports.session_number',
            'session_duration' => 'dev_psycho_supports.session_duration',
            'other_requirements' => 'dev_psycho_supports.other_requirements',
            'reffer_to' => 'dev_psycho_supports.reffer_to',
            'referr_address' => 'dev_psycho_supports.referr_address',
            'contact_number' => 'dev_psycho_supports.contact_number',
            'reason_for_reffer' => 'dev_psycho_supports.reason_for_reffer',
            'other_reason_for_reffer' => 'dev_psycho_supports.other_reason_for_reffer',
            'economic_inkind_date' => 'dev_economic_inkind.entry_date AS economic_inkind_date',
            'in_kind_type' => 'dev_economic_inkind.in_kind_type',
            'organization_name' => 'dev_economic_inkind.organization_name',
            'support_delivery_date' => 'dev_economic_inkind.support_delivery_date',
            'inkind_project' => 'dev_economic_inkind.inkind_project',
            'other_inkind_project' => 'dev_economic_inkind.other_inkind_project',
            'support_amount' => 'dev_economic_inkind.support_amount',
            'economic_support_delivered' => 'dev_economic_inkind.economic_support_delivered',
            'business_type' => 'dev_economic_inkind.business_type',
            'other_business_type' => 'dev_economic_inkind.other_business_type',
            'economic_other_comments' => 'dev_economic_inkind.economic_other_comments',
            'economic_training_entry_date' => 'dev_economic_training.entry_date AS economic_training_entry_date',
            'training_type' => 'dev_economic_training.training_type',
            'direct_training_type' => 'dev_economic_training.direct_training_type',
            'other_direct_training_type' => 'dev_economic_training.other_direct_training_type',
            'training_institution_name' => 'dev_economic_training.training_institution_name',
            'training_place' => 'dev_economic_training.training_place',
            'economic_training_start_date' => 'dev_economic_training.economic_training_start_date',
            'economic_training_end_date' => 'dev_economic_training.economic_training_end_date',
            'is_certification_received' => 'dev_economic_training.is_certification_received',
            'economic_training_comment' => 'dev_economic_training.economic_training_comment',
            'referral_training_type' => 'dev_economic_training.referral_training_type',
            'other_referral_training_type' => 'dev_economic_training.other_referral_training_type',
            'referral_training_institution_name' => 'dev_economic_training.referral_training_institution_name',
            'referral_training_place' => 'dev_economic_training.referral_training_place',
            'referral_economic_training_start_date' => 'dev_economic_training.referral_economic_training_start_date',
            'referral_economic_training_end_date' => 'dev_economic_training.referral_economic_training_end_date',
            'referral_certification_received' => 'dev_economic_training.referral_certification_received',
            'referral_economic_training_comment' => 'dev_economic_training.referral_economic_training_comment',
            'financial_training_entry' => 'dev_financial_literacy.entry_date AS financial_training_entry',
            'financial_training_received' => 'dev_financial_literacy.financial_training_received',
            'financial_institution_name' => 'dev_financial_literacy.financial_institution_name',
            'financial_training_place' => 'dev_financial_literacy.financial_training_place',
            'financial_training_start_date' => 'dev_financial_literacy.financial_training_start_date',
            'financial_training_end_date' => 'dev_financial_literacy.financial_training_end_date',
            'financial_certification_received' => 'dev_financial_literacy.financial_certification_received',
            'financial_training_comment' => 'dev_financial_literacy.financial_training_comment',
            'social_support_entry_date' => 'dev_social_supports.social_support_entry_date',
            'support_referred' => 'dev_social_supports.support_referred',
            'other_support_referred' => 'dev_social_supports.other_support_referred',
            'other_social_protection' => 'dev_social_supports.other_social_protection',
            'social_referred_entry_date' => 'dev_social_supports.social_referred_entry_date',
            'social_referred_organization' => 'dev_social_supports.social_referred_organization',
            'social_organization_type' => 'dev_social_supports.social_organization_type',
            'social_organization_address' => 'dev_social_supports.social_organization_address',
            'social_organization_comment' => 'dev_social_supports.social_organization_comment',
            'medical_support_entry_date' => 'dev_social_supports.medical_support_entry_date',
            'medical_support_type' => 'dev_social_supports.medical_support_type',
            'medical_institution_name' => 'dev_social_supports.medical_institution_name',
            'treatment_allowance' => 'dev_social_supports.treatment_allowance',
            'treatment_allowance_date' => 'dev_social_supports.treatment_allowance_date',
            'treatment_allowance_comment' => 'dev_social_supports.treatment_allowance_comment',
            'present_income' => 'dev_economic_profile.present_income',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_cases($args);

    $selected_supports = explode(',', $pre_data['selected_supports']);

    if (!$pre_data) {
        add_notification('Invalid case, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_case($data);

    if ($ret['support_update']) {
        $msg = "Information of case has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_cases'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$staffs = jack_obj('dev_staff_management');
$branch_id = $_config['user']['user_branch'];
if ($branch_id) {
    $all_staffs = $staffs->get_staffs(array('branch' => $branch_id));
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Case  </h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Case of: <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">Case ID: <?php echo $pre_data['pk_immediate_support_id'] ?></h4>
        <h4 class="text-primary">Case ID: <?php echo $pre_data['customer_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Case ',
                'title' => 'Manage Case ',
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
            <div class="side_aligned_tab">
                <ul id="uidemo-tabs-default-demo" class="nav nav-tabs">
                    <li class="active">
                        <a href="#SupportProvided" data-toggle="tab">Section 1: Immediate Support Provided After Arrival</a>
                    </li>
                    <li class="">
                        <a href="#PreferredServices" data-toggle="tab">Section 2: Preferred Services and Reintegration Plan</a>
                    </li>
                    <li class="">
                        <a href="#PsychosocialReintegration" data-toggle="tab">Section 3: Psychosocial Reintegration Support Services</a>
                    </li>
                    <li class="">
                        <a href="#ReintegrationSession" data-toggle="tab">Section 3.1: Psychosocial Reintegration Session Activities</a>
                    </li>
                    <li class="">
                        <a href="#FamilyCounseling" data-toggle="tab">Section 3.2: Family Counseling Session</a>
                    </li>
                    <li class="">
                        <a href="#SessionCompletion" data-toggle="tab">Section 3.3: Session Completion Status</a>
                    </li>
                    <li class="">
                        <a href="#ReintegrationFollowup" data-toggle="tab">Section 3.4: Psychosocial Reintegration (Followup)</a>
                    </li>
                    <li class="">
                        <a href="#EconomicReintegration" data-toggle="tab">Section 4: Economic Reintegration Support</a>
                    </li>
                    <li class="">
                        <a href="#EconomicInKind" data-toggle="tab">Section 4.1: In Kind Support</a>
                    </li>
                    <li class="">
                        <a href="#EconomicTraining" data-toggle="tab">Section 4.2: Training</a>
                    </li>
                    <li class="">
                        <a href="#EconomicFinancial" data-toggle="tab">Section 4.3: Financial Literacy & Remittance Management Training</a>
                    </li>
                    <li class="">
                        <a href="#EconomicReferrals" data-toggle="tab">Section 4.4: Referral and Linkage Support</a>
                    </li>
                    <li class="">
                        <a href="#EconomicReferralReceived" data-toggle="tab">Section 4.5: Referral Received and Linkage Support</a>
                    </li>
                    <li class="">
                        <a href="#SocialReintegrationSupport" data-toggle="tab">Section 5: Social Reintegration Support</a>
                    </li>
                    <li class="">
                        <a href="#SocialMedical" data-toggle="tab">Section 5.1: Medical Support</a>
                    </li>
                    <li class="">
                        <a href="#ReviewFollowUp" data-toggle="tab">Section 6: Review and Follow-up:</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-bordered">
                    <div class="tab-pane fade active in" id="SupportProvided">
                        <fieldset>
                            <legend>Section 1: Immediate Support Provided After Arrival</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Entry Date/Support Date</label>
                                        <div class="input-group">
                                            <input id="supportDate" type="text" class="form-control" name="support_date" value="<?php echo $pre_data['support_date'] && $pre_data['support_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['support_date'])) : ''; ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('supportDate');
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Arrival Place</label>
                                        <input type="text" class="form-control" name="arrival_place" value="<?php echo $pre_data['arrival_place'] ? $pre_data['arrival_place'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Select Case Manager (*)</label>
                                        <select class="form-control" name="fk_staff_id">
                                            <option value="">Select One</option>
                                            <?php foreach ($all_staffs['data'] as $staff) : ?>
                                                <option value="<?php echo $staff['pk_user_id'] ?>" <?php echo ($pre_data['fk_staff_id'] == $staff['pk_user_id']) ? 'selected' : '' ?>><?php echo $staff['user_fullname'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Immediate support services received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $immediate_support = explode(',', $pre_data['immediate_support']);
                                                $immediate_support = $immediate_support ? $immediate_support : array($immediate_support);
                                                ?> 
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Meet and greet at port of entry" <?php
                                                    if (in_array('Meet and greet at port of entry', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Meet and greet at port of entry</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Information provision" <?php
                                                    if (in_array('Information provision', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Information provision</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Pocket money" <?php
                                                    if (in_array('Pocket money', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Pocket money</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Shelter and accommodation" <?php
                                                    if (in_array('Shelter and accommodation', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Shelter and accommodation</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Onward transportation" <?php
                                                    if (in_array('Onward transportation', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Onward transportation</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Health assessment and health assistance" <?php
                                                    if (in_array('Health assessment and health assistance', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Health assessment and health assistance</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Food and nutrition" <?php
                                                    if (in_array('Food and nutrition', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Food and nutrition</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Non-Food Items (hygiene kits, etc.)" <?php
                                                    if (in_array('Non-Food Items (hygiene kits, etc.)', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Non-Food Items (hygiene kits, etc.)</span></label>
                                                <label><input class="px" type="checkbox" name="immediate_support[]" value="Psychosocial Conseling" <?php
                                                    if (in_array('Psychosocial Conseling', $immediate_support)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Psychosocial Conseling</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Comments</label>
                                        <textarea class="form-control" rows="2" name="immediate_comment" placeholder="Comments"><?php echo $pre_data['immediate_comment'] ? $pre_data['immediate_comment'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="PreferredServices">
                        <fieldset>
                            <legend>Section 2: Preferred Services and Reintegration Plan</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date/Plan Date</label>
                                            <div class="input-group">
                                                <input id="planDate" type="text" class="form-control" name="plan_date" value="<?php echo $pre_data['plan_date'] && $pre_data['plan_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['plan_date'])) : ''; ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('planDate');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
                                    <div class="col-sm-12">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Type of Services Requested</legend>
                                            <div class="form-group">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <div class="col-sm-6">   
                                                            <?php
                                                            $service_requested = explode(',', $pre_data['service_requested']);
                                                            $service_requested = $service_requested ? $service_requested : array($service_requested);
                                                            ?>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Child Care" <?php
                                                                if (in_array('Child Care', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Child Care</span></label>
                                                            <label class="col-sm-6"><input class="px" type="checkbox" id="education" name="service_requested[]" value="Education" <?php
                                                                if (in_array('Education', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Education</span></label>
                                                            <div id="educationAttr" style="display: none;" class="form-group col-sm-8">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Admission" <?php
                                                                        if (in_array('Admission', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Admission</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Scholarship/Stipend" <?php
                                                                        if (in_array('Scholarship/Stipend', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Scholarship/Stipend</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#education').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#educationAttr').show();
                                                                    }

                                                                    $("#education").on("click", function () {
                                                                        $('#educationAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="financialService" type="checkbox" name="service_requested[]" value="Financial Service" <?php
                                                                if (in_array('Financial Service', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Financial Services</span></label>
                                                            <div id="financialServiceAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Loan" <?php
                                                                        if (in_array('Loan', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Loan</span></label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Other Financial Service</label>
                                                                    <input class="form-control" placeholder="Other financial service" type="text" name="reintegration_financial_service" value="<?php echo $pre_data['reintegration_financial_service'] ? $pre_data['reintegration_financial_service'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#financialService').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#financialServiceAttr').show();
                                                                    }

                                                                    $("#financialService").on("click", function () {
                                                                        $('#financialServiceAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-6"><input class="px" id="housing" type="checkbox" name="service_requested[]" value="Housing" <?php
                                                                if (in_array('Housing', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Housing</span></label>
                                                            <div id="housingAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation for khas land" <?php
                                                                        if (in_array('Allocation for khas land', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Allocation for khas land</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Support for land allocation" <?php
                                                                        if (in_array('Support for land allocation', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Support for land allocation</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#housing').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#housingAttr').show();
                                                                    }

                                                                    $("#housing").on("click", function () {
                                                                        $('#housingAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Job Placement" <?php
                                                                if (in_array('Job Placement', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Job Placement</span></label>
                                                            <label class="col-sm-12"><input class="px" id="legalServices" type="checkbox" name="service_requested[]" value="Legal Services" <?php
                                                                if (in_array('Legal Services', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Legal Services</span></label>
                                                            <div id="legalServicesAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Legal Aid" <?php
                                                                        if (in_array('Legal Aid"', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Legal Aid</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Claiming Compensation" <?php
                                                                        if (in_array('Claiming Compensation', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Claiming Compensation</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Assistance in resolving family dispute" <?php
                                                                        if (in_array('Assistance in resolving family dispute', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#legalServices').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#legalServicesAttr').show();
                                                                    }

                                                                    $("#legalServices").on("click", function () {
                                                                        $('#legalServicesAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="training" type="checkbox" name="service_requested[]" value="Training" <?php
                                                                if (in_array('Training', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Training</span></label>
                                                            <div id="trainingAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Financial Literacy Training" <?php
                                                                        if (in_array('Financial Literacy Training', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Financial Literacy Training</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training from project" <?php
                                                                        if (in_array('Advance training from project', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Advance training from project</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training through referrals" <?php
                                                                        if (in_array('Advance training through referrals', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Advance training through referrals</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#training').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#trainingAttr').show();
                                                                    }

                                                                    $("#training").on("click", function () {
                                                                        $('#trainingAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" id="materialAssistance" name="service_requested[]" value="Material Assistance" <?php
                                                                if (in_array('Material Assistance', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Material Assistance</span></label>
                                                            <div id="materialAssistanceAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Business equipment/tools" <?php
                                                                        if (in_array('Business equipment/tools', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Business equipment/tools</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation of land or pond for business" <?php
                                                                        if (in_array('Allocation of land or pond for business', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Allocation of land or pond for business</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#materialAssistance').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#materialAssistanceAttr').show();
                                                                    }

                                                                    $("#materialAssistance").on("click", function () {
                                                                        $('#materialAssistanceAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="medicalSupport" type="checkbox" name="service_requested[]" value="Medical Support" <?php
                                                                if (in_array('Medical Support', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Medical Support</span></label>
                                                            <div id="medicalSupportAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value=">Medical treatment" <?php
                                                                        if (in_array('Medical treatment', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Medical treatment</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Psychiatric treatment" <?php
                                                                        if (in_array('Psychiatric treatment', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Psychiatric treatment</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#medicalSupport').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#medicalSupportAttr').show();
                                                                    }

                                                                    $("#medicalSupport").on("click", function () {
                                                                        $('#medicalSupportAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Business incubation" <?php
                                                                if (in_array('Business incubation', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business incubation</span></label>
                                                            <label class="col-sm-12"><input class="px" id="microbusiness" type="checkbox" name="service_requested[]" value="Microbusiness" <?php
                                                                if (in_array('Microbusiness', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Microbusiness</span></label>
                                                            <div id="microbusinessAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Business grant" <?php
                                                                        if (in_array('Business grant', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Business grant</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#microbusiness').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#microbusinessAttr').show();
                                                                    }

                                                                    $("#microbusiness").on("click", function () {
                                                                        $('#microbusinessAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="psychosocialSupport" type="checkbox" name="service_requested[]" value="Psychosocial Support" <?php
                                                                if (in_array('Psychosocial Support', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Psychosocial Support</span></label>
                                                            <div id="psychosocialSupportAttr" style="display: none;" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Individual Counselling" <?php
                                                                        if (in_array('Individual Counselling', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Individual Counselling</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Family counselling" <?php
                                                                        if (in_array('Family counselling', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Family counselling</span></label>
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Trauma Counseling" <?php
                                                                        if (in_array('Trauma Counseling', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Trauma Counseling</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#psychosocialSupport').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#psychosocialSupportAttr').show();
                                                                    }

                                                                    $("#psychosocialSupport").on("click", function () {
                                                                        $('#psychosocialSupportAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Trade License" <?php
                                                                if (in_array('Trade License', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Trade License</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="NID" <?php
                                                                if (in_array('NID', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">NID</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Passport" <?php
                                                                if (in_array('Passport', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Passport</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Business incubation" <?php
                                                                if (in_array('Business incubation', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business incubation</span></label>
                                                        </div>
                                                        <div class="col-sm-6">   
                                                            <div class="form-group">
                                                                <label><input class="px" id="socialProtection" type="checkbox" value="Social Protection Schemes" <?php echo $pre_data && $pre_data['social_protection'] != NULL ? 'checked' : '' ?>><span class="lbl">Social Protection Schemes</span></label>
                                                            </div>
                                                            <div id="socialProtectionAttr" style="display: none;" class="form-group">
                                                                <input class="form-control" placeholder="Specify Social Protection Schemes" type="text" name="new_social_protection" value="<?php echo $pre_data['social_protection'] ? $pre_data['social_protection'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#socialProtection').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#socialProtectionAttr').show();
                                                                    }

                                                                    $("#socialProtection").on("click", function () {
                                                                        $('#socialProtectionAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label><input class="px" id="securityMeasures" type="checkbox" value="Special Security Measures" <?php echo $pre_data && $pre_data['security_measure'] != NULL ? 'checked' : '' ?>><span class="lbl">Special Security Measures</span></label>
                                                            </div>
                                                            <div id="securityMeasuresAttr" style="display: none;" class="form-group">
                                                                <input class="form-control" placeholder="Specify Security Measures" type="text" name="new_security_measures" value="<?php echo $pre_data['security_measure'] ? $pre_data['security_measure'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#securityMeasures').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#securityMeasuresAttr').show();
                                                                    }

                                                                    $("#securityMeasures").on("click", function () {
                                                                        $('#securityMeasuresAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label><input class="px" id="servicesRequested" type="checkbox" value="Other Services Requested" <?php echo $pre_data && $pre_data['other_service_requested'] != NULL ? 'checked' : '' ?>><span class="lbl">Other Services Requested</span></label>
                                                            </div>
                                                            <div id="servicesRequestedAttr" style="display: none;" class="form-group">
                                                                <input class="form-control" placeholder="Specify Services Requested" type="text" name="new_service_requested" value="<?php echo $pre_data['other_service_requested'] ? $pre_data['other_service_requested'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    var isChecked = $('#servicesRequested').is(':checked');

                                                                    if (isChecked == true) {
                                                                        $('#servicesRequestedAttr').show();
                                                                    }

                                                                    $("#servicesRequested").on("click", function () {
                                                                        $('#servicesRequestedAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label>Note (If any)</label>
                                                                <textarea class="form-control" name="service_requested_note" rows="5" placeholder="Note"><?php echo $pre_data['service_requested_note'] ? $pre_data['service_requested_note'] : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="PsychosocialReintegration">
                        <fieldset>
                            <legend>Section 3: Psychosocial Reintegration Support Services</legend>
                            <?php
                            if (in_array('Psychosocial Reintegration Support Services', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date/Date of first meeting</label>
                                            <div class="input-group">
                                                <input id="Datefirstmeeting" type="text" class="form-control" name="first_meeting" value="<?php echo $pre_data['first_meeting'] && $pre_data['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['first_meeting'])) : ''; ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('Datefirstmeeting');
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label>Problems Identified</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $problem_identified = explode(',', $pre_data['problem_identified']);
                                                    $problem_identified = $problem_identified ? $problem_identified : array($problem_identified);
                                                    ?> 
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Anxiety"<?php
                                                        if (in_array('Anxiety', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Anxiety</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Depression"<?php
                                                        if (in_array('Depression', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Depression</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Suicidal Ideation/Thought"<?php
                                                        if (in_array('Suicidal Ideation/Thought', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Suicidal Ideation/Thought</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Sleep Problems"<?php
                                                        if (in_array('Sleep Problems', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Sleep Problems</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Phobia/Fear"<?php
                                                        if (in_array('Phobia/Fear', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Phobia/Fear</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Acute Stress"<?php
                                                        if (in_array('Acute Stress', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Acute Stress</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Anger problems"<?php
                                                        if (in_array('Anger problems', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Anger problems</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Addiction issues (substance abuse of any kinds)"<?php
                                                        if (in_array('Addiction issues (substance abuse of any kinds)', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Addiction issues (substance abuse of any kinds)</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Schizophrenia"<?php
                                                        if (in_array('Schizophrenia', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Schizophrenia</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Bipolar Mood Disorder"<?php
                                                        if (in_array('Bipolar Mood Disorder', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Bipolar Mood Disorder</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Repetitive thought or Repetitive Behavior (OCD)"<?php
                                                        if (in_array('Repetitive thought or Repetitive Behavior (OCD)', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Repetitive thought or Repetitive Behavior (OCD)</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Conversion Reactions"<?php
                                                        if (in_array('Conversion Reactions', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Conversion Reactions</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Problems in Socialization"<?php
                                                        if (in_array('Problems in Socialization', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Problems in Socialization</span></label>
                                                    <label><input class="px" type="checkbox" name="problem_identified[]" value="Family Problems"<?php
                                                        if (in_array('Family Problems', $problem_identified)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Family Problems</span></label>
                                                    <label><input class="px col-sm-12" type="checkbox" name="problem_identified[]" value="" id="newProblemIdentified" <?php echo $pre_data && $pre_data['other_problem_identified'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                                <div id="newProblemIdentifiedType" style="display: none; margin-bottom: 1em;">
                                                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newProblemIdentifiedTypeText" name="new_problem_identified" value="<?php echo $pre_data['other_problem_identified'] ? $pre_data['other_problem_identified'] : ''; ?>">
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
                                        <div class="form-group">
                                            <label>Description of the problem</label>
                                            <textarea class="form-control" name="problem_description" rows="5"><?php echo $pre_data['problem_description'] ? $pre_data['problem_description'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Initial Plan</label>
                                            <textarea class="form-control" name="initial_plan" rows="5" placeholder=""><?php echo $pre_data['initial_plan'] ? $pre_data['initial_plan'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Place of Session</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px newPlaceSession" type="radio" name="session_place" value="home"  <?php echo $pre_data && $pre_data['session_place'] == 'home' ? 'checked' : '' ?>><span class="lbl">Home</span></label>
                                                    <label><input class="px newPlaceSession" type="radio" name="session_place" value="jashore_office" <?php echo $pre_data && $pre_data['session_place'] == 'jashore_office' ? 'checked' : '' ?>><span class="lbl">Jashore office</span></label>
                                                    <?php
                                                    $session_place = array('home', 'jashore_office');
                                                    ?>
                                                    <label><input class="px" type="radio" <?php
                                                        if (!in_array($pre_data['session_place'], $session_place)): echo 'checked';
                                                        endif;
                                                        ?> name="session_place" id="newPlaceSession"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newPlaceSessionType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" id="newPlaceSessionTypeText" name="new_session_place" value="<?php echo $pre_data['session_place'] ? $pre_data['session_place'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newPlaceSession').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newPlaceSessionType').show();
                                                }

                                                $("#newPlaceSession").on("click", function () {
                                                    $('#newPlaceSessionType').show();
                                                });

                                                $(".newPlaceSession").on("click", function () {
                                                    $('#newPlaceSessionType').hide();
                                                    $('#newPlaceSessionTypeText').val('');
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Number of Sessions (Estimate)</label>
                                            <input class="form-control" type="number" id="session_number" name="session_number" value="<?php echo $pre_data['session_number'] ? $pre_data['session_number'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Duration of Session (With Unit. Example: Hour, Minutes)</label>
                                            <input class="form-control" type="text" id="session_duration" name="session_duration" value="<?php echo $pre_data['session_duration'] ? $pre_data['session_duration'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Other Requirements</label>
                                            <input class="form-control" type="text" id="other_requirements" name="other_requirements" value="<?php echo $pre_data['other_requirements'] ? $pre_data['other_requirements'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Referrals</label>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Referred to" type="text" name="reffer_to" value="<?php echo $pre_data['reffer_to'] ? $pre_data['reffer_to'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Address of organization/individual" type="text" name="referr_address" value="<?php echo $pre_data['referr_address'] ? $pre_data['referr_address'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Phone Number" type="number" name="contact_number" value="<?php echo $pre_data['contact_number'] ? $pre_data['contact_number'] : ''; ?>">
                                        </div>
                                        <div class="form-group ">
                                            <label>Reason for Referral</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $reason_for_reffer = explode(',', $pre_data['reason_for_reffer']);
                                                    $reason_for_reffer = $reason_for_reffer ? $reason_for_reffer : array($reason_for_reffer);
                                                    ?> 
                                                    <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Trauma Counseling" <?php
                                                        if (in_array('Trauma Counseling', $reason_for_reffer)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Trauma Counseling </span></label>
                                                    <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Family Counseling" <?php
                                                        if (in_array('Family Counseling', $reason_for_reffer)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Family Counseling </span></label>
                                                    <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Psychiatric Treatment" <?php
                                                        if (in_array('Psychiatric Treatment', $reason_for_reffer)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Psychiatric Treatment  </span></label>
                                                    <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Community counseling" <?php
                                                        if (in_array('Community counseling', $reason_for_reffer)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Community counseling</span></label>
                                                    <label><input class="px col-sm-12" type="checkbox" name="reason_for_reffer[]" value="" id="newReasonReferral" <?php echo $pre_data && $pre_data['other_reason_for_reffer'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newReasonReferralTypes" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newReasonReferralTypesText" name="new_reason_for_reffer" value="<?php echo $pre_data['other_reason_for_reffer'] ? $pre_data['other_reason_for_reffer'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newReasonReferral').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newReasonReferralTypes').show();
                                                }

                                                $("#newReasonReferral").on("click", function () {
                                                    $('#newReasonReferralTypes').toggle();
                                                    $('#newReasonReferralTypesText').val('');
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationSession">
                        <fieldset>
                            <legend>Section 3.1: Psychosocial Reintegration Session Activities</legend>
                            <?php
                            if (in_array('Psychosocial Reintegration Support Services', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($psychosocial_sessions['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                                <td><?php echo $value['entry_time'] ?></td>
                                                <td><?php echo $value['session_end_time'] ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_session&edit=' . $value['pk_psycho_session_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_session&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Psychosocial Session</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="FamilyCounseling">
                        <fieldset>
                            <legend>Section 3.2: Family Counseling Session</legend>
                            <?php
                            if (in_array('Family Counseling Session', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($family_counsellings['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                                <td><?php echo $value['entry_time'] ?></td>
                                                <td><?php echo $value['session_end_time'] ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_family_counseling&edit=' . $value['pk_psycho_family_counselling_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_family_counseling&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Family Counseling</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SessionCompletion">
                        <fieldset>
                            <legend>Section 3.3: Session Completion Status</legend>
                            <?php
                            if (in_array('Psychosocial Reintegration Support Services', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Final Evaluation</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($psychosocial_completions['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                                <td><?php echo $value['final_evaluation'] ?></td>
                                                <td><?php echo ucfirst($value['is_completed']) ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_session_completion&edit=' . $value['pk_psycho_completion_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_session_completion&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Session Completion</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationFollowup">
                        <fieldset>
                            <legend>Section 3.4: Psychosocial Reintegration (Followup)</legend>
                            <?php
                            if (in_array('Psychosocial Reintegration Support Services', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($psychosocial_followups['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                                <td><?php echo $value['entry_time'] ?></td>
                                                <td><?php echo $value['session_end_time'] ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_followup&edit=' . $value['pk_psycho_followup_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_followup&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Psychosocial Followup</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicReintegration">
                        <fieldset>
                            <legend>Section 4: Economic Reintegration</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicInKind">
                        <fieldset>
                            <legend>Section 4.1: In Kind Support</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date</label>
                                            <div class="input-group">
                                                <input id="economicInkindDate" type="text" class="form-control" name="economic_inkind_date" value="<?php echo $pre_data['economic_inkind_date'] && $pre_data['economic_inkind_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_inkind_date'])) : ''; ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('economicInkindDate');
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label>Type of In Kind Support</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="in_kind_type" value="direct" <?php echo $pre_data && $pre_data['in_kind_type'] == 'direct' ? 'checked' : '' ?>><span class="lbl">Direct</span></label>
                                                    <label><input class="px" type="radio" name="in_kind_type" value="referral" <?php echo $pre_data && $pre_data['in_kind_type'] == 'referral' ? 'checked' : '' ?>><span class="lbl">Referral</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Support Received From</legend>
                                            <div class="form-group">
                                                <label>Organization Name</label>
                                                <input type="text" class="form-control" name="organization_name" value="<?php echo $pre_data['organization_name'] ? $pre_data['organization_name'] : ''; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label>Support Delivery Date</label>
                                                <div class="input-group">
                                                    <input id="SupportDeliveryDate" type="text" class="form-control" name="support_delivery_date" value="<?php echo $pre_data['support_delivery_date'] && $pre_data['support_delivery_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['support_delivery_date'])) : ''; ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('SupportDeliveryDate');
                                                    });
                                                </script>
                                            </div>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Support Type</legend>
                                                <div class="form-group ">
                                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                        <div class="options_holder radio">
                                                            <?php
                                                            $support_type = explode(',', $pre_data['inkind_project']);
                                                            ?>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Microbusiness (Business Grant)" <?php
                                                                if (in_array('Microbusiness (Business Grant)', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Microbusiness (Business Grant)</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Microbusiness (Enrolled in Community Enterprise)" <?php
                                                                if (in_array('Microbusiness (Enrolled in Community Enterprise)', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Microbusiness (Enrolled in Community Enterprise)</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Material Assistance ( Business Equipment/Tools)" <?php
                                                                if (in_array('Material Assistance ( Business Equipment/Tools)', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Material Assistance ( Business Equipment/Tools)</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Material Assistance (Allocation of land or pond for business)" <?php
                                                                if (in_array('Material Assistance (Allocation of land or pond for business)', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Material Assistance (Allocation of land or pond for business)</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Material Assistance Others" <?php
                                                                if (in_array('Material Assistance Others', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Material Assistance Others</span></label>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Remigration" <?php
                                                                if (in_array('Remigration', $support_type)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Remigration</span></label>
                                                            <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="inkind_project[]" value="" id="newInkind" <?php echo $pre_data && $pre_data['other_inkind_project'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="newInkindType" style="display: none; margin-bottom: 1em;">
                                                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newInkindTypeText" name="new_inkind_project" value="<?php echo $pre_data['other_inkind_project'] ? $pre_data['other_inkind_project'] : ''; ?>">
                                                </div>
                                                <script>
                                                    init.push(function () {
                                                        var isChecked = $('#newInkind').is(':checked');

                                                        if (isChecked == true) {
                                                            $('#newInkindType').show();
                                                        }

                                                        $("#newInkind").on("click", function () {
                                                            $('#newInkindType').toggle();
                                                            $('#newInkindTypeText').val('');
                                                        });
                                                    });
                                                </script>
                                            </fieldset>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Support Amount</label>
                                            <input type="number" class="form-control" name="support_amount" value="<?php echo $pre_data['support_amount'] ? $pre_data['support_amount'] : ''; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Description of Support Delivered</label>
                                            <textarea class="form-control" rows="2" name="economic_support_delivered" placeholder=""><?php echo $pre_data['economic_support_delivered'] ? $pre_data['economic_support_delivered'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Type of Business</legend>
                                            <div class="form-group ">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        $business_type = explode(',', $pre_data['business_type']);
                                                        ?>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Agri business" <?php
                                                            if (in_array('Agri business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Agri business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Agriculture" <?php
                                                            if (in_array('Agriculture', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Agriculture</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Automobile and/spare parts business" <?php
                                                            if (in_array('Automobile and/spare parts business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Automobile and/spare parts business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Construction business" <?php
                                                            if (in_array('Construction business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Construction business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Fishery business" <?php
                                                            if (in_array('Fishery business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Fishery business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Food/restaurant business" <?php
                                                            if (in_array('Food/restaurant business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Food/restaurant business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Fruit/vegetable selling business" <?php
                                                            if (in_array('Fruit/vegetable selling business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Fruit/vegetable selling business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Furniture/Timber/Sawmill business" <?php
                                                            if (in_array('Furniture/Timber/Sawmill business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Furniture/Timber/Sawmill business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Garments/clothing business" <?php
                                                            if (in_array('Garments/clothing business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Garments/clothing business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Grocery/varieties/confectionary" <?php
                                                            if (in_array('Grocery/varieties/confectionary', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Grocery/varieties/confectionary</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Hardware/electronics/engineering shop" <?php
                                                            if (in_array('Hardware/electronics/engineering shop', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Hardware/electronics/engineering shop</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Mobile/Internet/telecom" <?php
                                                            if (in_array('Mobile/Internet/telecom', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Mobile/Internet/telecom</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Pharmacy/medicine business" <?php
                                                            if (in_array('Pharmacy/medicine business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Pharmacy/medicine business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Poultry/livestock/dairy" <?php
                                                            if (in_array('Poultry/livestock/dairy', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Poultry/livestock/dairy</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Rental business" <?php
                                                            if (in_array('Rental business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Rental business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Retailer/distributor/wholesaler" <?php
                                                            if (in_array('Retailer/distributor/wholesaler', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Retailer/distributor/wholesaler</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Sanitary" <?php
                                                            if (in_array('Sanitary', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Sanitary</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Stationary" <?php
                                                            if (in_array('Stationary', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Stationary</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Stock business" <?php
                                                            if (in_array('Stock business', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Stock business</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="business_type[]" value="Transport" <?php
                                                            if (in_array('Transport', $business_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Transport</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="business_type[]" value="" id="newBusiness" <?php echo $pre_data && $pre_data['other_business_type'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newBusinessType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newBusinessTypeText" name="new_business_type" value="<?php echo $pre_data['other_business_type'] ? $pre_data['other_business_type'] : ''; ?>">
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
                                            <label>Comments</label>
                                            <textarea class="form-control" rows="2" name="economic_other_comments" placeholder="Any other Comments"><?php echo $pre_data['economic_other_comments'] ? $pre_data['economic_other_comments'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="EconomicTraining">
                        <fieldset>
                            <legend>Section 4.2: Training</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date</label>
                                            <div class="input-group">
                                                <input id="economicTrainingEntry" type="text" class="form-control" name="economic_training_entry_date" value="<?php echo $pre_data['economic_training_entry_date'] && $pre_data['economic_training_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_training_entry_date'])) : '' ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('economicTrainingEntry');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Training Type</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" id="directTrainingType" name="training_type" value="direct" <?php echo $pre_data && $pre_data['training_type'] == 'direct' ? 'checked' : '' ?>><span class="lbl">Direct</span></label>
                                                    <label><input class="px" type="radio" id="referralTrainingType" name="training_type" value="referral" <?php echo $pre_data && $pre_data['training_type'] == 'referral' ? 'checked' : '' ?>><span class="lbl">Referral</span></label>
                                                    <label><input class="px" type="radio" id="bothTrainingType" name="training_type" value="both" <?php echo $pre_data && $pre_data['training_type'] == 'both' ? 'checked' : '' ?>><span class="lbl">Both</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 directTrainingTypeAttr" style="display:none">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Direct Training Type</legend>
                                            <div class="form-group ">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        $direct_training_type = explode(',', $pre_data['direct_training_type']);
                                                        $direct_training_type = $direct_training_type ? $direct_training_type : array($direct_training_type);
                                                        ?>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Entrepreneurship Development" <?php
                                                            if (in_array('Entrepreneurship Development', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Entrepreneurship Development</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Marketing and Sales" <?php
                                                            if (in_array('Marketing and Sales', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Marketing and Sales</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Product Development" <?php
                                                            if (in_array('Product Development', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Product Development</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Financial Management of Enterprises" <?php
                                                            if (in_array('Financial Management of Enterprises', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Financial Management of Enterprises</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Fishery" <?php
                                                            if (in_array('Fishery', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Fishery</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Graphics Design" <?php
                                                            if (in_array('Graphics Design', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Graphics Design</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Refrigeration and Air Conditioning (RAC)" <?php
                                                            if (in_array('Refrigeration and Air Conditioning (RAC)', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Refrigeration and Air Conditioning (RAC)</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Electrical Installation and Maintenance (EIM)" <?php
                                                            if (in_array('Electrical Installation and Maintenance (EIM)', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Electrical Installation and Maintenance (EIM)</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Plumbing" <?php
                                                            if (in_array('Plumbing', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Plumbing</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Tiles Fittings" <?php
                                                            if (in_array('Tiles Fittings', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Tiles Fittings</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Rod Binding" <?php
                                                            if (in_array('Rod Binding', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Rod Binding</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Floriculture" <?php
                                                            if (in_array('Floriculture', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Floriculture</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Driving" <?php
                                                            if (in_array('Driving', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Driving</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Livestock/Dairy/Beef fattening/Goat rearing" <?php
                                                            if (in_array('Livestock/Dairy/Beef fattening/Goat rearing', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Livestock/Dairy/Beef fattening/Goat rearing</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Poultry" <?php
                                                            if (in_array('Poultry', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Poultry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Masonry" <?php
                                                            if (in_array('Masonry', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Masonry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Carpentry" <?php
                                                            if (in_array('Carpentry', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Carpentry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Beautification" <?php
                                                            if (in_array('Beautification', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Beautification</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Language Course" <?php
                                                            if (in_array('Language Course', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Language Course</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Basic Computer course" <?php
                                                            if (in_array('Basic Computer course', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Basic Computer course</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Hotel Management" <?php
                                                            if (in_array('Hotel Management', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Hotel Management</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="House Keeping" <?php
                                                            if (in_array('House Keeping', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">House Keeping</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Cooking" <?php
                                                            if (in_array('Cooking', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Cooking</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Nursing and Midwifery" <?php
                                                            if (in_array('Nursing and Midwifery', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Nursing and Midwifery</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Motor Rewinding" <?php
                                                            if (in_array('Motor Rewinding', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Motor Rewinding</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Welding/Metal Sheet Cutting" <?php
                                                            if (in_array('Welding/Metal Sheet Cutting', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Welding/Metal Sheet Cutting</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Sewing Machine Operator/Tailoring" <?php
                                                            if (in_array('Sewing Machine Operator/Tailoring', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Sewing Machine Operator/Tailoring</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="direct_training_type[]" value="Financial Literacy & Remittance Management" <?php
                                                            if (in_array('Financial Literacy & Remittance Management', $direct_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Financial Literacy & Remittance Management</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="direct_training_type[]" value="" id="newDirectTraining" <?php echo $pre_data && $pre_data['other_direct_training_type'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newDirectTrainingType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" id="newDirectTrainingTypeText" placeholder="Please Specity" type="text" name="new_direct_training_type" value="<?php echo $pre_data['other_direct_training_type'] ? $pre_data['other_direct_training_type'] : ''; ?>">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    var isChecked = $('#newDirectTraining').is(':checked');

                                                    if (isChecked == true) {
                                                        $('#newDirectTrainingType').show();
                                                    }

                                                    $("#newDirectTraining").on("click", function () {
                                                        $('#newDirectTrainingType').toggle();
                                                        $('#newDirectTrainingTypeText').val('');
                                                    });
                                                });
                                            </script>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Institution Name</label>
                                            <input type="text" class="form-control" name="training_institution_name" value="<?php echo $pre_data['training_institution_name'] ? $pre_data['training_institution_name'] : ''; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Place of Training</label>
                                            <input type="text" class="form-control" name="training_place" value="<?php echo $pre_data['training_place'] ? $pre_data['training_place'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 directTrainingTypeAttr" style="display:none">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Training Duration</legend>
                                            <div class="form-group">
                                                <label>Training Start Date</label>
                                                <div class="input-group">
                                                    <input id="trainingEconomicStartDate" type="text" class="form-control" name="economic_training_start_date" value="<?php echo $pre_data['economic_training_start_date'] && $pre_data['economic_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_training_start_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('trainingEconomicStartDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Training End Date</label>
                                                <div class="input-group">
                                                    <input id="trainingEconomicEndDate" type="text" class="form-control" name="economic_training_end_date" value="<?php echo $pre_data['economic_training_end_date'] && $pre_data['economic_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_training_end_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('trainingEconomicEndDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Certificate Received</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label><input class="px" type="radio" id="yes_certification_received" name="is_certification_received" value="yes" <?php echo $pre_data && $pre_data['is_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                        <label><input class="px" type="radio" id="no_certification_received" name="is_certification_received" value="no" <?php echo $pre_data && $pre_data['is_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Comment</label>
                                            <textarea class="form-control" rows="2" name="economic_training_comment" placeholder=""><?php echo $pre_data['economic_training_comment'] ? $pre_data['economic_training_comment'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 referralTrainingTypeAttr" style="display:none">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Referral Training Type</legend>
                                            <div class="form-group ">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        $referral_training_type = explode(',', $pre_data['referral_training_type']);
                                                        $referral_training_type = $referral_training_type ? $referral_training_type : array($referral_training_type);
                                                        ?>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Entrepreneurship Development" <?php
                                                            if (in_array('Entrepreneurship Development', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Entrepreneurship Development</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Marketing and Sales" <?php
                                                            if (in_array('Marketing and Sales', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Marketing and Sales</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Product Development" <?php
                                                            if (in_array('Product Development', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Product Development</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Financial Management of Enterprises" <?php
                                                            if (in_array('Financial Management of Enterprises', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Financial Management of Enterprises</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Fishery" <?php
                                                            if (in_array('Fishery', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Fishery</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Graphics Design" <?php
                                                            if (in_array('Graphics Design', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Graphics Design</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Refrigeration and Air Conditioning (RAC)" <?php
                                                            if (in_array('Refrigeration and Air Conditioning (RAC)', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Refrigeration and Air Conditioning (RAC)</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Electrical Installation and Maintenance (EIM)" <?php
                                                            if (in_array('Electrical Installation and Maintenance (EIM)', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Electrical Installation and Maintenance (EIM)</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Plumbing" <?php
                                                            if (in_array('Plumbing', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Plumbing</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Tiles Fittings" <?php
                                                            if (in_array('Tiles Fittings', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Tiles Fittings</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Rod Binding" <?php
                                                            if (in_array('Rod Binding', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Rod Binding</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Floriculture" <?php
                                                            if (in_array('Floriculture', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Floriculture</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Driving" <?php
                                                            if (in_array('Driving', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Driving</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Livestock/Dairy/Beef fattening/Goat rearing" <?php
                                                            if (in_array('Livestock/Dairy/Beef fattening/Goat rearing', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Livestock/Dairy/Beef fattening/Goat rearing</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Poultry" <?php
                                                            if (in_array('Poultry', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Poultry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Masonry" <?php
                                                            if (in_array('Masonry', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Masonry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Carpentry" <?php
                                                            if (in_array('Carpentry', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Carpentry</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Beautification" <?php
                                                            if (in_array('Beautification', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Beautification</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Language Course" <?php
                                                            if (in_array('Language Course', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Language Course</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Basic Computer course" <?php
                                                            if (in_array('Basic Computer course', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Basic Computer course</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Hotel Management" <?php
                                                            if (in_array('Hotel Management', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Hotel Management</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="House Keeping" <?php
                                                            if (in_array('House Keeping', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">House Keeping</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Cooking" <?php
                                                            if (in_array('Cooking', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Cooking</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Nursing and Midwifery" <?php
                                                            if (in_array('Nursing and Midwifery', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Nursing and Midwifery</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Motor Rewinding" <?php
                                                            if (in_array('Motor Rewinding', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Motor Rewinding</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Welding/Metal Sheet Cutting" <?php
                                                            if (in_array('Welding/Metal Sheet Cutting', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Welding/Metal Sheet Cutting</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Sewing Machine Operator/Tailoring" <?php
                                                            if (in_array('Sewing Machine Operator/Tailoring', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Sewing Machine Operator/Tailoring</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="referral_training_type[]" value="Financial Literacy & Remittance Management" <?php
                                                            if (in_array('Financial Literacy & Remittance Management', $referral_training_type)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Financial Literacy & Remittance Management</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="referral_training_type[]" value="" id="newReferralTraining" <?php echo $pre_data && $pre_data['other_referral_training_type'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newReferralTrainingType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" id="newReferralTrainingTypeText" placeholder="Please Specity" type="text" name="new_referral_training_type" value="<?php echo $pre_data['other_referral_training_type'] ? $pre_data['other_referral_training_type'] : ''; ?>">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    var isChecked = $('#newReferralTraining').is(':checked');

                                                    if (isChecked == true) {
                                                        $('#newReferralTrainingType').show();
                                                    }

                                                    $("#newReferralTraining").on("click", function () {
                                                        $('#newReferralTrainingType').toggle();
                                                        $('#newReferralTrainingTypeText').val('');
                                                    });
                                                });
                                            </script>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Referral Institution Name</label>
                                            <input type="text" class="form-control" name="referral_training_institution_name" value="<?php echo $pre_data['referral_training_institution_name'] ? $pre_data['referral_training_institution_name'] : ''; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Place of Training (Referral)</label>
                                            <input type="text" class="form-control" name="referral_training_place" value="<?php echo $pre_data['referral_training_place'] ? $pre_data['referral_training_place'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 referralTrainingTypeAttr" style="display:none">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Training Duration (Referral)</legend>
                                            <div class="form-group">
                                                <label>Training Start Date</label>
                                                <div class="input-group">
                                                    <input id="referralTrainingEconomicStartDate" type="text" class="form-control" name="referral_economic_training_start_date" value="<?php echo $pre_data['referral_economic_training_start_date'] && $pre_data['referral_economic_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['referral_economic_training_start_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('referralTrainingEconomicStartDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Training End Date</label>
                                                <div class="input-group">
                                                    <input id="referralTrainingEconomicEndDate" type="text" class="form-control" name="referral_economic_training_end_date" value="<?php echo $pre_data['referral_economic_training_end_date'] && $pre_data['referral_economic_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['referral_economic_training_end_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('referralTrainingEconomicEndDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Certificate Received (Referral)</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label><input class="px" type="radio" id="yes_certification_received" name="referral_certification_received" value="yes" <?php echo $pre_data && $pre_data['referral_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                        <label><input class="px" type="radio" id="no_certification_received" name="referral_certification_received" value="no" <?php echo $pre_data && $pre_data['referral_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Comment (Referral)</label>
                                            <textarea class="form-control" rows="2" name="referral_economic_training_comment" placeholder=""><?php echo $pre_data['referral_economic_training_comment'] ? $pre_data['referral_economic_training_comment'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isDirect = $('#directTrainingType').is(':checked');

                                            if (isDirect == true) {
                                                $('.directTrainingTypeAttr').show();
                                                $('.referralTrainingTypeAttr').hide();
                                            }

                                            $("#directTrainingType").on("click", function () {
                                                $('.directTrainingTypeAttr').show();
                                                $('.referralTrainingTypeAttr').hide();
                                            });

                                            var isReferral = $('#referralTrainingType').is(':checked');

                                            if (isReferral == true) {
                                                $('.referralTrainingTypeAttr').show();
                                                $('.directTrainingTypeAttr').hide();
                                            }

                                            $("#referralTrainingType").on("click", function () {
                                                $('.referralTrainingTypeAttr').show();
                                                $('.directTrainingTypeAttr').hide();
                                            });

                                            var isBoth = $('#bothTrainingType').is(':checked');

                                            if (isBoth == true) {
                                                $('.directTrainingTypeAttr').show();
                                                $('.referralTrainingTypeAttr').show();
                                            }

                                            $("#bothTrainingType").on("click", function () {
                                                $('.referralTrainingTypeAttr').show();
                                                $('.directTrainingTypeAttr').show();
                                            });

                                        });
                                    </script>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicFinancial">
                        <fieldset>
                            <legend>Section 4.3: Financial Literacy & Remittance Management Training</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date</label>
                                            <div class="input-group">
                                                <input id="financialTrainingEntry" type="text" class="form-control" name="financial_training_entry" value="<?php echo $pre_data['financial_training_entry'] && $pre_data['financial_training_entry'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['financial_training_entry'])) : '' ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('financialTrainingEntry');
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label>Who has received the training?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $financial_training_received = explode(',', $pre_data['financial_training_received']);
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="financial_training_received[]" value="Beneficiary" <?php
                                                        if (in_array('Beneficiary', $financial_training_received)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Beneficiary</span></label>
                                                    <label><input class="px" type="checkbox" name="financial_training_received[]" value="Family Member" <?php
                                                        if (in_array('Family Member', $financial_training_received)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Family Member</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Institution Name</label>
                                            <input type="text" class="form-control" name="financial_institution_name" value="<?php echo $pre_data['financial_institution_name'] ? $pre_data['financial_institution_name'] : ''; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Place of Training</label>
                                            <input type="text" class="form-control" name="financial_training_place" value="<?php echo $pre_data['financial_training_place'] ? $pre_data['financial_training_place'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Training Duration</legend>
                                            <div class="form-group">
                                                <label>Training Start Date</label>
                                                <div class="input-group">
                                                    <input id="trainingStartDate" type="text" class="form-control" name="financial_training_start_date" value="<?php echo $pre_data['financial_training_start_date'] && $pre_data['financial_training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['financial_training_start_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('trainingStartDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Training End Date</label>
                                                <div class="input-group">
                                                    <input id="trainingEndDate" type="text" class="form-control" name="financial_training_end_date" value="<?php echo $pre_data['financial_training_end_date'] && $pre_data['financial_training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['financial_training_end_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('trainingEndDate');
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Certificate Received</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label><input class="px" type="radio" id="yes_certification_received" name="financial_certification_received" value="yes" <?php echo $pre_data && $pre_data['financial_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                        <label><input class="px" type="radio" id="no_certification_received" name="financial_certification_received" value="no" <?php echo $pre_data && $pre_data['financial_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <label>Comment</label>
                                            <textarea class="form-control" rows="2" name="financial_training_comment" placeholder=""><?php echo $pre_data['financial_training_comment'] ? $pre_data['financial_training_comment'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicReferrals">
                        <fieldset>
                            <legend>Section 4.4: Referral and Linkage Support</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Referral Date</th>
                                            <th>Referred For</th>
                                            <th>Referred Organization</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($economic_referrals['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['referral_date'])) ?></td>
                                                <td><?php echo $value['referred_for'] . ' ' . $value['other_referred_for'] ?></td>
                                                <td><?php echo $value['referred_organization'] ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_economic_referral&edit=' . $value['pk_economic_referral_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_economic_referral&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Economic Referrals</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicReferralReceived">
                        <fieldset>
                            <legend>Section 4.5: Referral Received and Linkage Support</legend>
                            <?php
                            if (in_array('Economic Reintegration Support', $selected_supports)) :
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Received Date</th>
                                            <th>Referred For</th>
                                            <th>Service Provider</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($received_economic_referrals['data'] as $value):
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($value['received_date'])) ?></td>
                                                <td><?php echo $value['referred_for'] . ' ' . $value['other_referred_for'] ?></td>
                                                <td><?php echo $value['referral_service_provider'] ?></td>
                                                <td>
                                                    <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_received_economic_referral&edit=' . $value['pk_economic_referral_id'] . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_received_economic_referral&customer_id=' . $edit . '&case_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Economic Referrals Received</a>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SocialReintegrationSupport">
                        <fieldset>
                            <legend>Section 5: Social Reintegration Support</legend>
                            <?php
                            if (in_array('Social Reintegration Support', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date</label>
                                            <div class="input-group">
                                                <input id="socialSupportEntryDate" type="text" class="form-control" name="social_support_entry_date" value="<?php echo $pre_data['social_support_entry_date'] && $pre_data['social_support_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['social_support_entry_date'])) : '' ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('socialSupportEntryDate');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Types of Support Referred for</legend>
                                            <div class="form-group ">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        $support_referred = explode(',', $pre_data['support_referred']);
                                                        $support_referred = $support_referred ? $support_referred : array($support_referred);
                                                        ?>
                                                        <label class="col-sm-12"><input class="px" id="socialProtectionSchemes" type="checkbox" name="support_referred[]" value="Social Protection Schemes(Place to access to public services & Social Protection)" <?php
                                                            if (in_array('Social Protection Schemes(Place to access to public services & Social Protection)', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                        <div id="socialProtectionSchemesAttr" style="display: none;" class="form-group col-sm-12">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Union Parished" <?php
                                                                    if (in_array('Union Parished', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Union Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Upazila Parished" <?php
                                                                    if (in_array('Upazila Parished', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Upazila Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Social Welfare office" <?php
                                                                    if (in_array('District/Upazila Social Welfare office', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Youth Development office" <?php
                                                                    if (in_array('District/Upazila Youth Development office', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/Upazila Youth Development office</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="District/ Upazila Women Affairs Department" <?php
                                                                    if (in_array('District/ Upazila Women Affairs Department', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Private/ NGO" <?php
                                                                    if (in_array('Private/ NGO', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Private/ NGO</span></label>
                                                                <label><input class="px col-sm-12" type="checkbox" name="other_social_protection" id="newSocialProtection" <?php echo $pre_data && $pre_data['other_social_protection'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                            </div>
                                                        </div>
                                                        <div id="otherSocialProtection" style="display: none" class="form-group col-sm-12">
                                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="otherSocialReferredText" name="other_social_protection" value="<?php echo $pre_data['other_social_protection'] ? $pre_data['other_social_protection'] : ''; ?>">
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                var isChecked = $('#socialProtectionSchemes').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialProtectionSchemesAttr').show();
                                                                }

                                                                $("#socialProtectionSchemes").on("click", function () {
                                                                    $('#socialProtectionSchemesAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <script>
                                                            init.push(function () {
                                                                var isChecked = $('#newSocialProtection').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#otherSocialProtection').show();
                                                                }

                                                                $("#newSocialProtection").on("click", function () {
                                                                    $('#otherSocialProtection').toggle();
                                                                    $('#otherSocialReferredText').val('');
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialEducation" type="checkbox" name="reintegration_economic[]" value="Education" <?php
                                                            if (in_array('Education', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Education</span></label>
                                                        <div id="socialEducationAttr" class="form-group col-sm-12">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Admission" <?php
                                                                    if (in_array('Admission', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Stipend/ Scholarship" <?php
                                                                    if (in_array('Stipend/ Scholarship', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Stipend/ Scholarship</span></label>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialEducationAttr').hide();

                                                                var isChecked = $('#socialEducation').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialEducationAttr').show();
                                                                }

                                                                $("#socialEducation").on("click", function () {
                                                                    $('#socialEducationAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialHousing" type="checkbox" name="reintegration_economic[]" value="Housing" <?php
                                                            if (in_array('Housing', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Housing</span></label>
                                                        <div id="socialHousingAttr" class="form-group col-sm-12">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Allocation of Khas land" <?php
                                                                    if (in_array('Allocation of Khas land', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Support for housing loan" <?php
                                                                    if (in_array('Support for housing loan', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Support for housing loan</span></label>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialHousingAttr').hide();

                                                                var isChecked = $('#socialHousing').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialEducationAttr').show();
                                                                }

                                                                $("#socialHousing").on("click", function () {
                                                                    $('#socialHousingAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialLegal" type="checkbox" name="support_referred[]" value="Legal Services" <?php
                                                            if (in_array('Legal Services', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Services</span></label>
                                                        <div id="socialLegalReferredAttr" style="display: none" class="form-group col-sm-12">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Legal Aid" <?php
                                                                    if (in_array('Legal Aid"', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Claiming Compensation" <?php
                                                                    if (in_array('Claiming Compensation', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="support_referred[]" value="Assistance in resolving family dispute" <?php
                                                                    if (in_array('Assistance in resolving family dispute', $support_referred)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialHousingAttr').hide();

                                                                var isChecked = $('#socialLegal').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialLegalReferredAttr').show();
                                                                }

                                                                $("#socialLegal").on("click", function () {
                                                                    $('#socialLegalReferredAttr').toggle();
                                                                    $('#socialLegalReferredAttr').val('');
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialLegalReferred" type="checkbox" name="support_referred[]" value="NID" <?php
                                                            if (in_array('NID', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">NID</span></label>
                                                        <label class="col-sm-12"><input class="px" id="socialLegalReferred" type="checkbox" name="support_referred[]" value="Passport" <?php
                                                            if (in_array('Passport', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Passport</span></label>
                                                        <label class="col-sm-12"><input class="px" id="socialLegalReferred" type="checkbox" name="support_referred[]" value="Trade License" <?php
                                                            if (in_array('Trade License', $support_referred)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Trade License</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="support_referred[]" value="" id="socialSupportReferred" <?php echo $pre_data && $pre_data['other_support_referred'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                    </div>
                                                    <div id="newSocialReferredType" style="display: none; margin-bottom: 1em;">
                                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" id="newSocialReferredTypeText" name="new_support_referred" value="<?php echo $pre_data['other_support_referred'] ? $pre_data['other_support_referred'] : ''; ?>">
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            var isChecked = $('#socialSupportReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#newSocialReferredType').show();
                                                            }

                                                            $("#socialSupportReferred").on("click", function () {
                                                                $('#newSocialReferredType').toggle();
                                                                $('#newSocialReferredTypeText').val('');
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Referred Date</label>
                                            <div class="input-group">
                                                <input id="socialReferredEntryDate" type="text" class="form-control" name="social_referred_entry_date" value="<?php echo $pre_data['social_referred_entry_date'] && $pre_data['social_referred_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['social_referred_entry_date'])) : '' ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('socialReferredEntryDate');
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label>Referred Organization Name</label>
                                            <input type="text" class="form-control" name="social_referred_organization" value="<?php echo $pre_data['social_referred_organization'] ? $pre_data['social_referred_organization'] : ''; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Organization Type</label>
                                            <input type="text" class="form-control" name="social_organization_type" value="<?php echo $pre_data['social_organization_type'] ? $pre_data['social_organization_type'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Organization Address</label>
                                            <textarea class="form-control" name="social_organization_address" rows="2"><?php echo $pre_data['social_organization_address'] ? $pre_data['social_organization_address'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Comment</label>
                                            <textarea class="form-control" name="social_organization_comment" rows="2"><?php echo $pre_data['social_organization_comment'] ? $pre_data['social_organization_comment'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SocialMedical">
                        <fieldset>
                            <legend>Section 5.1: Medical Support</legend>
                            <?php
                            if (in_array('Social Reintegration Support', $selected_supports)) :
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Entry Date</label>
                                            <div class="input-group">
                                                <input id="medicalSupportEntryDate" type="text" class="form-control" name="medical_support_entry_date" value="<?php echo $pre_data['medical_support_entry_date'] && $pre_data['medical_support_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['medical_support_entry_date'])) : '' ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('medicalSupportEntryDate');
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label>Medical Support Type</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="medical_support_type" value="direct" <?php echo $pre_data && $pre_data['medical_support_type'] == 'direct' ? 'checked' : '' ?>><span class="lbl">Direct</span></label>
                                                    <label><input class="px" type="radio" name="medical_support_type" value="referral" <?php echo $pre_data && $pre_data['medical_support_type'] == 'referral' ? 'checked' : '' ?>><span class="lbl">Referral</span></label>
                                                    <label><input class="px" type="radio" name="medical_support_type" value="both" <?php echo $pre_data && $pre_data['medical_support_type'] == 'both' ? 'checked' : '' ?>><span class="lbl">Both</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Type of Medical Issue</legend>
                                            <div class="form-group">
                                                <label>Institution Name (Hospital/Clinic)</label>
                                                <input type="text" class="form-control" name="medical_institution_name" value="<?php echo $pre_data['medical_institution_name'] ? $pre_data['medical_institution_name'] : ''; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label>Treatment Allowance</label>
                                                <textarea name="treatment_allowance" class="form-control"><?php echo $pre_data['treatment_allowance'] ? $pre_data['treatment_allowance'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Support Date</label>
                                                <div class="input-group">
                                                    <input id="medicalStartDate" type="text" class="form-control" name="treatment_allowance_date" value="<?php echo $pre_data['treatment_allowance_date'] && $pre_data['treatment_allowance_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['treatment_allowance_date'])) : '' ?>">
                                                </div>
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker('medicalStartDate');
                                                    });
                                                </script>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Comment</label>
                                            <textarea class="form-control" rows="2" name="treatment_allowance_comment" placeholder=""><?php echo $pre_data['treatment_allowance_comment'] ? $pre_data['treatment_allowance_comment'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-primary">
                                    <p>Not Applicable!</p>
                                </div>
                            <?php endif ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReviewFollowUp">
                        <fieldset>
                            <legend>Section 6: Review and Follow-Up</legend>
                            <p>Monthly income of survivor after return was: <?php echo $pre_data['present_income'] . ' BDT' ?></p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entry Date</th>
                                        <th>Income</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($reviews['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['entry_date'] ? $value['entry_date'] : $value['entry_date'])) ?></td>
                                            <td><?php echo $value['monthly_average_income'] ?></td>
                                            <td><?php echo ucfirst($value['support_status']) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_review&edit=' . $value['pk_followup_id'] . '&case_id=' . $edit . '&customer_id=' . $edit) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_review&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Review and Follow-Up</a>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_customer_management/manage_returnee_migrants') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
    </div>
</form>
<script type="text/javascript">
    init.push(function () {
        $("select[name='branch_id']").change(function () {
            $('#availableStaffs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectStaff'
                    },
                    success: function (data) {
                        $('#availableStaffs').html(data);
                    }
                });
            }
        }).change();

        $('#profile_picture').change(function () {
            var thsName = $(this).prop('name');
            $('[name="' + thsName + '_hidden"]').val($(this).val());
        });
    });
</script>