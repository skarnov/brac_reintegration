<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_customer_management/manage_returnees'));
    exit();
} else {
    $ret = $devdb->query("DELETE FROM dev_returnees WHERE pk_returnee_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Deleted', 'warning');
        header('location: ' . url('admin/dev_customer_management/manage_returnees'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_customer_management/manage_returnees'));
        exit();
    }
}