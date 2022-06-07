<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_knowledge')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_knowledge_management/manage_stories'));
    exit();
} else {
    $pre_data = $this->get_knowledge(array('id' => $id, 'single' => true));
    @unlink(_path('uploads', 'absolute') . '/' . $pre_data['document_file']);
    
    $ret['knowledge'] = $devdb->query("DELETE FROM dev_knowledge WHERE pk_knowledge_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Success', 'warning');
        header('location: ' . url('admin/dev_knowledge_management/manage_stories'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_knowledge_management/manage_stories'));
        exit();
    }
}