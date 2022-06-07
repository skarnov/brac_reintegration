<?php
$event_id = $_GET['event_id'] ? $_GET['event_id'] : null;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'event_id' => $event_id,
    'listing' => TRUE,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_validation_id',
        'order' => 'DESC'
    ),
);

$events = $this->get_event_validations($args);
$pagination = pagination($events['total'], $per_page_items, $start);

doAction('render_start');
ob_start();
?>
<div class="page-header">
    <h1>All Event Validations</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_event_validation&event_id=' . $event_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Event Validation',
                'title' => 'New Event Validation',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'manage_events',
                'action' => 'list',
                'text' => 'All Events',
                'title' => 'Manage Events',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'event validation') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Interview Date</th>
                <th>Reviewed By</th>
                <th>Participant Name</th>
                <th>Quote</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events['data'] as $i => $event) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($event['interview_date'])) ?></td>
                    <td><?php echo ucfirst($event['reviewed_by']); ?></td>
                    <td><?php echo $event['participant_name']; ?></td>
                    <td><?php echo $event['quote']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_event_validation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_event_validation', 'edit' => $event['pk_validation_id'] . '&event_id=' . $event_id)),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Edit',
                                    'title' => 'Edit Event',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $event['pk_validation_id'], 'data-event' => $event_id),
                                    'classes' => 'delete_single_record'));
                                ?>
                            </div>
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
            var event = ths.attr('data-event');
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
                        window.location.href = '?action=deleteEventValidation&id=' + logId + '&event_id=' + event;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>