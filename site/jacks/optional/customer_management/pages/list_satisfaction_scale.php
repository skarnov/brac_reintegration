<?php
$customer_id = $_GET['id'] ? $_GET['id'] : null;

if (!checkPermission($edit, 'add_customer', 'edit_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'customer_id' => $customer_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_satisfaction_scale',
        'order' => 'DESC'
    ),
);

$result = $this->get_satisfaction_scale($args);
$pagination = pagination($result['total'], $per_page_items, $start);

doAction('render_start');
?>
<div class="page-header">
    <h1>Reintegration Assistance Satisfaction Scale</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_satisfaction_scale&customer_id=' . $customer_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Satisfaction Scale',
                'title' => 'New Satisfaction Scale',
                'size' => 'sm'
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Participant Profile',
                'title' => 'Manage Participant Profile',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($result['total'], $start, $per_page_items, count($result['data']), 'scales') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Entry Date</th>
                <th>Total Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['entry_date']; ?></td>
                    <td><?php echo $value['total_score']; ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?php echo url('admin/dev_customer_management/manage_customers?action=add_edit_satisfaction_scale&edit=' . $value['pk_satisfaction_scale'] . '&customer_id=' . $value['fk_customer_id']) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
                        </div>
                        <?php if (has_permission('delete_customer')): ?>
<!--                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $value['pk_satisfaction_scale'], 'data-customer' => $customer_id),
                                    'classes' => 'delete_single_record'));
                                ?>
                            </div>-->
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="table-footer oh">
        <div class="pull-left">
            <?php echo $pagination ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');
            var customerId = ths.attr('data-customer');
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteScale&id=' + logId + '&customerId='+ customerId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>