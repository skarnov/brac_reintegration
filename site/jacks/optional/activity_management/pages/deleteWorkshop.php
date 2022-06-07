<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_workshop')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_workshops'));
    exit();
} else {
    $ret['workshop'] = $devdb->query("DELETE FROM dev_workshops WHERE pk_workshop_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Success', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_workshops'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_workshops'));
        exit();
    }
}