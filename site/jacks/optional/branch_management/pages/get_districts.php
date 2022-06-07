<?php

if (!checkPermission($edit, 'add_branch', 'edit_branch')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$districts = get_district($_GET['division_id']);
echo "<option value=''>Select One</option>";
foreach ($districts as $district) :
    echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
endforeach;
exit;
