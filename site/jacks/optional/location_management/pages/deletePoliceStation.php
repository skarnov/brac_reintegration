<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_data')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_location_management/manage_police_stations'));
    exit();
} else {
    $ret['customer'] = $devdb->query("DELETE FROM bd_police_stations WHERE id = '" . $id . "'");

    if ($ret) {
        add_notification('Record Deleted.', 'warning');
        header('location: ' . url('admin/dev_location_management/manage_police_stations'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_location_management/manage_police_stations'));
        exit();
    }
}