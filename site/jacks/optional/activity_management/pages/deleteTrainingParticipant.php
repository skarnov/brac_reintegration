<?php

global $devdb;
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;
$gender = $_GET['gender'] ? $_GET['gender'] : null;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_training')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_trainings?action=participants_list&edit=' . $id . '&training_id=' . $training_id));
    exit();
} else {
    $ret = $devdb->query("DELETE FROM dev_training_participants WHERE pk_training_participant_id = '" . $id . "'");
    
    if ($gender == 'male') {
        $ret = $devdb->query("UPDATE dev_trainings SET male_participant = male_participant - 1 WHERE pk_training_id = '$training_id'");
    } else if ($gender == 'female') {
        $ret = $devdb->query("UPDATE dev_trainings SET female_participant = female_participant - 1 WHERE pk_training_id = '$training_id'");
    }
    
    if ($ret) {
        add_notification('Record Deleted', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings?action=participants_list&edit=' . $id . '&training_id=' . $training_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_trainings?action=participants_list&edit=' . $id . '&training_id=' . $training_id));
        exit();
    }
}