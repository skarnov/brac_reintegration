<?php

global $devdb;
$workshop_id = $_GET['workshop_id'] ? $_GET['workshop_id'] : null;
$gender = $_GET['gender'] ? $_GET['gender'] : null;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_workshop')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_activity_management/manage_workshops?action=participants_list&workshop_id=' . $workshop_id));
    exit();
} else {
    $ret = $devdb->query("DELETE FROM dev_workshop_participants WHERE pk_workshop_participant_id = '" . $id . "'");

    if ($gender == 'male') {
        $ret = $devdb->query("UPDATE dev_workshops SET male_participant = male_participant - 1 WHERE pk_workshop_id = '$workshop_id'");
    } else if ($gender == 'female') {
        $ret = $devdb->query("UPDATE dev_workshops SET female_participant = female_participant - 1 WHERE pk_workshop_id = '$workshop_id'");
    }

    if ($ret) {
        add_notification('Record Deleted', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_workshops?action=participants_list&workshop_id=' . $workshop_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_activity_management/manage_workshops?action=participants_list&workshop_id=' . $workshop_id));
        exit();
    }
}