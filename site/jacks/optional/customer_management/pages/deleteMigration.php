<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;
$customer = $_GET['customer'] ? $_GET['customer'] : null;

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
    $ret = $devdb->query("DELETE FROM dev_migration_documents WHERE pk_document_id = '" . $id . "'");

    if ($ret) {
        add_notification('Success', 'warning');
        header('location: ' . url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $customer));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $customer));
        exit();
    }
}