<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;
$customer_id = $_GET['customerId'] ? $_GET['customerId'] : null;

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
    $ret = $devdb->query("DELETE FROM dev_reintegration_satisfaction_scale WHERE pk_satisfaction_scale = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Deleted', 'warning');
        header('location: ' . url('admin/dev_customer_management/manage_customers?action=list_satisfaction_scale&id='.$customer_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    }
}