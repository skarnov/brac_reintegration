<?php

class dev_report_management {

    var $thsClass = 'dev_report_management';
    var $observation_report_pass_mark = 18;

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Reports',
            'permissions' => array(
                'manage_reports' => array(
                    'case_statistics' => 'Case Statistics',
                    'pdf_case_report' => 'PDF Case Report',
                    'income_comparison_report' => 'Income Comparison Report',
                    'observation_report' => 'Observation Report',
                    'event_validation_report' => 'Event Validation Report',
                    'reintegration_assistance_satisfaction_scale_report' => 'Reintegration Assistance Satisfaction Scale Report',
                    'case_report' => 'Case Report',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Case Reports',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );

        $params = array(
            'label' => 'Reports',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );

        $params = array(
            'label' => 'Case Statistics',
            'description' => 'Case Statistics',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'case_statistics',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'PDF Case Report',
            'description' => 'Generate Report By Selecting Case Modules',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'pdf_case_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('pdf_case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Income Comparison Report',
            'description' => 'Income Comparison Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'income_comparison_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('income_comparison_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Observation Report',
            'description' => 'Activities Observation Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'observation_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('observation_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Event Validation',
            'description' => 'Event Validation Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'event_validation',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('event_validation_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Reintegration Assistance Satisfaction Scale',
            'description' => 'Reintegration Assistance Satisfaction Scale Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'reintegration_assistance_satisfaction_scale_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('reintegration_assistance_satisfaction_scale_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Immediate Support Report',
            'description' => 'Immediate Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'immediate_support',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Preferred Services and Reintegration Plan',
            'description' => 'Preferred Services and Reintegration Plan Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'reintegration_plan',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Psychosocial Reintegration Support Services',
            'description' => 'Psychosocial Reintegration Support Services Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'psychosocial_reintegration',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Psychosocial Reintegration Session Activities',
            'description' => 'Psychosocial Reintegration Session Activities Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'psychosocial_session',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Family Counseling Session',
            'description' => 'Family Counseling Session Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'family_counseling',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Session Completion Status',
            'description' => 'Session Completion Status Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'session_completion',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Followup Psychosocial Reintegration',
            'description' => 'Followup Psychosocial Reintegration Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'psychosocial_followup',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'In Kind Support',
            'description' => 'In Kind Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'in_kind_support',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Economic Training',
            'description' => 'Economic Training Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'economic_training',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Financial Literacy & Remittance Management Training',
            'description' => 'Financial Literacy & Remittance Management Training Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'financial_literacy',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Referral and Linkage Support',
            'description' => 'Referral and Linkage Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'economic_referral',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Referral Received and Linkage Support',
            'description' => 'Referral Received and Linkage Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'referral_received',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Social Reintegration Support',
            'description' => 'Social Reintegration Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'social_reintegration',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Medical Support',
            'description' => 'Medical Support Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'medical_support',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Review and Follow-Up',
            'description' => 'Review and Follow-Up Report',
            'menu_group' => 'Case Reports',
            'position' => 'top',
            'action' => 'review_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('case_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Training Participant',
            'description' => 'Training Participant Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'training_participant',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_trainings'))
            admenu_register($params);

        $params = array(
            'label' => 'Workshop Participant',
            'description' => 'Workshop Participant Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'workshop_participant',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_workshops'))
            admenu_register($params);
    }

    function case_statistics() {
        if (!has_permission('case_statistics'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'case_statistics');

        include('pages/case_statistics.php');
    }

    function pdf_case_report() {
        if (!has_permission('pdf_case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'pdf_case_report');

        if ($_GET['action'] == 'download_pdf')
            include('pages/case_download_pdf.php');
        else
            include('pages/pdf_case_report.php');
    }

    function income_comparison_report() {
        if (!has_permission('income_comparison_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'income_comparison_report');

        if ($_GET['action'] == 'income_comparison')
            include('pages/income_comparison.php');
        else if ($_GET['action'] == 'pdf_download_income_comparison_report')
            include('pages/pdf_download_income_comparison_report.php');
        else
            include('pages/income_comparison_report.php');
    }

    function observation_report() {
        if (!has_permission('observation_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'observation_report');

        include('pages/observation_report.php');
    }

    function event_validation() {
        if (!has_permission('event_validation_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'event_validation');

        include('pages/event_validation.php');
    }

    function reintegration_assistance_satisfaction_scale_report() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'reintegration_assistance_satisfaction_scale_report');

        include('pages/reintegration_assistance_satisfaction_scale_report.php');
    }

    function immediate_support() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'immediate_support');

        include('pages/immediate_support.php');
    }

    function reintegration_plan() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'reintegration_plan');

        include('pages/reintegration_plan.php');
    }

    function psychosocial_reintegration() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'psychosocial_reintegration');

        include('pages/psychosocial_reintegration.php');
    }

    function psychosocial_session() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'psychosocial_session');

        include('pages/psychosocial_session.php');
    }

    function family_counseling() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'family_counseling');

        include('pages/family_counseling_session.php');
    }

    function session_completion() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'session_completion');

        include('pages/session_completion.php');
    }

    function psychosocial_followup() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'psychosocial_followup');

        include('pages/psychosocial_followup.php');
    }

    function in_kind_support() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'in_kind_support');

        include('pages/in_kind_support.php');
    }

    function economic_training() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'economic_training');

        include('pages/economic_training.php');
    }

    function financial_literacy() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'financial_literacy');

        include('pages/financial_literacy.php');
    }

    function economic_referral() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'economic_referral');

        include('pages/economic_referral.php');
    }

    function referral_received() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'referral_received');

        include('pages/referral_received.php');
    }

    function social_reintegration() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'social_reintegration');

        include('pages/social_reintegration.php');
    }

    function medical_support() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'medical_support');

        include('pages/medical_support.php');
    }

    function review_report() {
        if (!has_permission('case_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'review_report');

        include('pages/review_report.php');
    }

    function training_participant() {
        if (!has_permission('manage_trainings'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'training_participant');

        include('pages/training_participant.php');
    }

    function workshop_participant() {
        if (!has_permission('manage_workshops'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'workshop_participant');

        include('pages/workshop_participant.php');
    }

}

new dev_report_management();
