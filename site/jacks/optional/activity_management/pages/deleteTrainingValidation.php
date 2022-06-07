<?php

global $devdb;
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_training_validation')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_trainings?action=training_validation&training_id=' . $training_id));
    exit();
} else {
    $ret = $devdb->query("DELETE FROM dev_training_validations WHERE pk_training_validation_id = '" . $id . "'");

    if ($ret) {
        add_notification('Record Deleted', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings?action=training_validation&training_id=' . $training_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings?action=training_validation&training_id=' . $training_id));
        exit();
    }
}