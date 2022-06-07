<?php
if (!has_permission('configure_branch_types')) {
    add_notification('You do not have enough permission to configure branch types', 'error');
    header('location: ' . url());
    exit();
}

$thisDate = date('Y-m-d');
$thisTime = date('H:i:s');
$thisUser = $_config['user']['pk_user_id'];

if ($_POST['ajax_type'] == 'add_menu_item') {
    $data = array(
        'fk_item_id' => 0,
        'item_sort_order' => 0,
        'item_title' => $_POST['item_title'],
        'item_short_title' => $_POST['item_short_title'],
        'create_date' => $thisDate,
        'create_time' => $thisTime,
        'created_by' => $thisUser,
        'update_date' => $thisDate,
        'update_time' => $thisTime,
        'updated_by' => $thisUser,
    );

    $insert = $devdb->insert_update('dev_branch_types', $data);

    if ($insert['success']) {
        $data['pk_item_id'] = $insert['success'];
        $return_data = array();
        $return_data['menu_item_id'] = $insert['success'];
        $return_data['item_title'] = $data['item_title'];
        $return_data['item_short_title'] = $data['item_short_title'];
        $return_data['the_item_form'] = $this->menu_item_edit_form($data);
        $return_data['data'] = $data;
        echo json_encode(array('success' => $return_data));
        exit();
    } else {
        echo json_encode($insert);
        exit();
    }
}
if ($_POST['ajax_type'] == 'edit_menu_item') {
    if (!isset($_POST['menu_item_id'])) {
        echo json_encode(array('error' => 'Invalid Branch Type'));
        exit();
    }

    $sql = "SELECT * FROM dev_branch_types WHERE pk_item_id = '" . $_POST['menu_item_id'] . "'";
    $preData = $devdb->get_row($sql);

    $data = array(
        'item_title' => $_POST['item_title'],
        'item_short_title' => $_POST['item_short_title'],
        'update_date' => $thisDate,
        'update_time' => $thisTime,
        'updated_by' => $thisUser,
    );

    $insert = $devdb->insert_update('dev_branch_types', $data, " pk_item_id = '" . $_POST['menu_item_id'] . "'");

    if ($insert['success']) {
        $data['pk_item_id'] = $_POST['menu_item_id'];
        $return_data = array();
        $return_data['menu_item_id'] = $_POST['menu_item_id'];
        $return_data['item_title'] = $data['item_title'];
        $return_data['item_short_title'] = $data['item_short_title'];
        $return_data['the_item_form'] = $this->menu_item_edit_form($data);
        echo json_encode(array('success' => $return_data));
        exit();
    } else {
        echo json_encode($insert);
        exit();
    }
    exit();
}
if ($_POST['ajax_type'] == 'save_sorting') {
    $data = $_POST['data'];
    unset($data[0]);

    foreach ($data as $i => $v) {
        $update = array(
            'fk_item_id' => $v['parent_id'] == 'root' ? '0' : $v['parent_id'],
            'item_sort_order' => $i,
        );
        $update = $devdb->insert_update('dev_branch_types', $update, " pk_item_id = '" . $v['item_id'] . "'");
    }

    $ret = array('success' => 1);
    echo json_encode($ret);
    exit();
}
if ($_POST['ajax_type'] == 'remove_menu_item') {
    if ($_POST['item_id']) {
        $deleted = $this->deleteMenuItems(array('menu_item' => $_POST['item_id']));
        if ($deleted) {
            echo json_encode(array('success' => 'Item Deleted'));
        } else echo json_encode(array('error' => 'Item not found'));
    } else echo json_encode(array('error' => 'No Item to Remove'));
    exit();
}
//--------

$menuItems = $this->get_menuItems();

doAction('render_start');
?>
<div class="page-header">
    <h1>Branch Types</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
        <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Branches',
                'title' => 'Manage All Branches',
                'icon' => 'icon_list',
                'size' => 'sm'
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Add New Item</span>
            </div>
            <div class="panel-body">
                <form id="menu_item_add_form" action="" class="oh" method="post">
                    <div class="form-group">
                        <label>Type Name</label>
                        <input type="text" name="item_title" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label>Type Short Name</label>
                        <input type="text" name="item_short_title" class="form-control" value="" />
                    </div>
                    <?php
                    echo buttonButtonGenerator(array(
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'Add to Hierarchy',
                        'title' => 'Add Item to Hierarchy',
                        'classes' => 'addMenuItem',
                        'size' => '',
                    ));
                    ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Sort, Arrange &amp; Save </span>
            </div>
            <div class="panel-body">
                <div class="menuItemList">
                    <form action="" method="post">
                        <?php echo $menuItems; ?>
                    </form>
                    <script type="text/javascript">
                        var process_page = '<?php echo $_SERVER['REQUEST_URI'] ?>';
                        init.push(function() {
                            function saveMenuSorting(showNoti) {
                                arraied = $('ul.sortable').nestedSortable('toArray', {
                                    startDepthCount: 0
                                });
                                var _data = {
                                    'ajax_type': 'save_sorting',
                                    'data': arraied
                                };
                                $.ajax({
                                    beforeSend: function() {},
                                    complete: function() {},
                                    type: "POST",
                                    url: process_page,
                                    data: _data,
                                    cache: false,
                                    dataType: 'json',
                                    success: function(reply_data) {
                                        if (reply_data.success) {
                                            if (showNoti) $.growl.warning({
                                                title: "Success",
                                                message: "Order was saved successfully",
                                                size: 'large'
                                            });
                                        } else
                                            $.growl.error({
                                                title: "Error",
                                                message: "Order was not saved successfully",
                                                size: 'large'
                                            });
                                    }
                                });
                            }

                            $('ul.sortable').nestedSortable({
                                forcePlaceholderSize: true,
                                handle: '.sortHandle',
                                helper: 'clone',
                                items: 'li',
                                opacity: .6,
                                placeholder: 'placeholder',
                                revert: 250,
                                tabSize: 25,
                                tolerance: 'pointer',
                                toleranceElement: '> div',
                                maxLevels: 5,
                                isTree: true,
                                startCollapsed: true,
                                update: function() {
                                    saveMenuSorting(true);
                                }
                            });

                            $(document).on('click', '.show_item_detail', function() {
                                if ($(this).hasClass('open')) {
                                    $(this).closest('li').find('>.menu_edit_form').slideUp('slow');
                                    $(this).removeClass('open');
                                } else {
                                    $(this).closest('li').find('>.menu_edit_form').slideDown('slow');
                                    $(this).addClass('open');
                                }
                            });
                            $(document).on('click', '.addMenuItem', function() {
                                var ths = $(this);
                                var serialized_data = ths.closest('form').serialize(); //$('#menu_item_add_form').serialize();

                                $.ajax({
                                    beforeSend: function() {
                                        show_button_overlay_working(ths);
                                    },
                                    complete: function() {
                                        hide_button_overlay_working(ths);
                                    },
                                    type: "POST",
                                    url: process_page,
                                    data: 'ajax_type=add_menu_item&' + serialized_data,
                                    cache: false,
                                    dataType: 'json',
                                    success: function(reply_data) {
                                        if (reply_data.success) {
                                            $.growl.warning({
                                                title: "Success",
                                                message: "Item is Added",
                                                size: 'large'
                                            });
                                            var newItem = $('<li />', {
                                                'id': 'ID_' + reply_data.success.menu_item_id,
                                                'html': '<div class="item"><span class="sortHandle"><i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-ellipsis-v"></i></span>&nbsp;&nbsp;<span class="title">' + reply_data.success.item_title + '</span><span class="pull-right"><a href="javascript:" class="btn btn-xs btn-primary mr5 show_item_detail"><i class="fa fa-edit"></i></a><a href="javascript:" class="remove_menu_item btn btn-xs btn-danger"><i class="icon fa fa-times-circle"></i></a></span></div>' + reply_data.success.the_item_form
                                            });
                                            newItem.appendTo("ul.sortable");
                                            newItem.find('.use_page_title').change();
                                            saveMenuSorting(false);
                                            //clear_form(ths.closest('form').closest('form'));
                                        } else $.growl.error({
                                            title: "Error",
                                            message: "Item was not Added, please try again.",
                                            size: 'large'
                                        });
                                    }
                                });
                            });

                            $('#SaveMenuItems').click(function(e) {
                                saveMenuSorting();
                            });

                            $(document).on('click', '.edit_menu_item', function() {
                                var ths = $(this);
                                var serialized_data = ths.closest('form').serialize();
                                var ths_li = ths.closest('li');

                                $.ajax({
                                    beforeSend: function() {
                                        show_button_overlay_working(ths);
                                    },
                                    complete: function() {
                                        hide_button_overlay_working(ths);
                                    },
                                    type: "POST",
                                    url: process_page,
                                    data: 'ajax_type=edit_menu_item&' + serialized_data,
                                    cache: false,
                                    dataType: 'json',
                                    success: function(reply_data) {
                                        if (reply_data.success) {
                                            $.growl.warning({
                                                title: "Success",
                                                message: "Item is Updated",
                                                size: 'large'
                                            });
                                            ths_li.find('>.item >.title').html(reply_data.success.item_title);
                                            ths_li.find('>.menu_edit_form').remove();
                                            ths_li.find('>.item').after(reply_data.success.the_item_form);
                                        } else $.growl.error({
                                            title: "Error",
                                            message: "Item was not updated, please try again.",
                                            size: 'large'
                                        });
                                    }
                                });
                            });
                            $(document).on('click', '.remove_menu_item', function(e) {
                                var ths = $(this);
                                bootbox.confirm({
                                    message: '<span class="text-danger">Do you really want to delete this item?</span><br /><br />All dependent items of this item will be deleted.',
                                    buttons: {
                                        confirm: {
                                            label: 'Delete',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function(result) {
                                        if (result) {
                                            var item_id = $(ths).closest('li').attr('id').replace('ID_', '');

                                            var _data = {
                                                'ajax_type': 'remove_menu_item',
                                                'item_id': item_id
                                            };
                                            $.ajax({
                                                beforeSend: show_working('Removing Item ...'),
                                                complete: hide_working(),
                                                type: "POST",
                                                url: process_page,
                                                data: _data,
                                                cache: false,
                                                dataType: 'json',
                                                success: function(reply_data) {
                                                    if (reply_data.success) {
                                                        $.growl.warning({
                                                            title: "Success",
                                                            message: "Item is Removed",
                                                            size: 'large'
                                                        });
                                                        $(ths).closest('li').slideUp(300, function() {
                                                            $(ths).closest('li').remove();
                                                        });
                                                        saveMenuSorting();
                                                    } else
                                                        $.growl.error({
                                                            title: "Error",
                                                            message: "Item Not Removed.<br />Please try again",
                                                            size: 'large'
                                                        });
                                                }
                                            });
                                        }
                                    },
                                    className: "bootbox-sm"
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>