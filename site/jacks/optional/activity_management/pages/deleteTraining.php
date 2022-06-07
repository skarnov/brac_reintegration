<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_training')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_trainings'));
    exit();
} else {
    $ret['training'] = $devdb->query("DELETE FROM dev_trainings WHERE pk_training_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Success', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings'));
        exit();
    }
}