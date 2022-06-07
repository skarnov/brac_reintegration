<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_meeting_entry')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_meeting_management/manage_meeting_entries'));
    exit();
} else {
    $ret = $devdb->query("DELETE FROM dev_meeting_entries WHERE pk_meeting_entry_id = '" . $id . "'");
    
    if ($ret) {
         add_notification('Record Deleted.', 'warning');
        header('location: ' . url('admin/dev_meeting_management/manage_meeting_entries'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_meeting_management/manage_meeting_entries'));
        exit();
    }
}