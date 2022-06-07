<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_complain_filed')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_complain_fileds'));
    exit();
} else {
    $ret['complain'] = $devdb->query("DELETE FROM dev_complain_fileds WHERE pk_complain_filed_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Success', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_complain_fileds'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_complain_fileds'));
        exit();
    }
}