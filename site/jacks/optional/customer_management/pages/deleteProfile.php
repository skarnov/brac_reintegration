<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_customer_management/manage_customers'));
    exit();
} else {
    $ret['customer'] = $devdb->query("DELETE FROM dev_customers WHERE pk_customer_id = '" . $id . "'");
    $ret['migration'] = $devdb->query("DELETE FROM dev_migrations WHERE fk_customer_id = '" . $id . "'");    
    $ret['economic_profile'] = $devdb->query("DELETE FROM dev_economic_profile WHERE fk_customer_id = '" . $id . "'");
    $ret['customer_skills'] = $devdb->query("DELETE FROM dev_customer_skills WHERE fk_customer_id = '" . $id . "'");
    $ret['customer_health'] = $devdb->query("DELETE FROM dev_customer_health WHERE fk_customer_id = '" . $id . "'");
    
    $ret['immediate_supports'] = $devdb->query("DELETE FROM dev_immediate_supports WHERE fk_customer_id = '" . $id . "'");
    $ret['reintegration_plan'] = $devdb->query("DELETE FROM dev_reintegration_plan WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_supports'] = $devdb->query("DELETE FROM dev_psycho_supports WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_family_counselling'] = $devdb->query("DELETE FROM dev_psycho_family_counselling WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_sessions'] = $devdb->query("DELETE FROM dev_psycho_sessions WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_completions'] = $devdb->query("DELETE FROM dev_psycho_completions WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_followups'] = $devdb->query("DELETE FROM dev_psycho_followups WHERE fk_customer_id = '" . $id . "'");
    $ret['economic_inkind'] = $devdb->query("DELETE FROM dev_economic_inkind WHERE fk_customer_id = '" . $id . "'");
    $ret['economic_reintegration_referrals'] = $devdb->query("DELETE FROM dev_economic_reintegration_referrals WHERE fk_customer_id = '" . $id . "'");
    $ret['social_supports'] = $devdb->query("DELETE FROM dev_social_supports WHERE fk_customer_id = '" . $id . "'");
    $ret['followups'] = $devdb->query("DELETE FROM dev_followups WHERE fk_customer_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Deleted.', 'warning');
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    }
}