<?php
$workshop_id = $_GET['workshop_id'] ? $_GET['workshop_id'] : null;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$workshop_info = $this->get_workshops(array('id' => $workshop_id, 'single' => true));

$args = array(
    'workshop_id' => $workshop_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_workshop_validation_id',
        'order' => 'DESC'
    ),
);

$workshops = $this->get_workshop_validations($args);
$pagination = pagination($workshops['total'], $per_page_items, $start);

doAction('render_start');
ob_start();
?>
<div class="page-header">
    <h1>All Workshop Validations</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_workshop_validation&workshop_id=' . $workshop_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Workshop Validation',
                'title' => 'New Workshop Validation',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($workshops['total'], $start, $per_page_items, count($workshops['data']), 'workshop validation') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Entry Date</th>
                <th>Evaluator Profession</th>
                <th>Recommendation</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($workshops['data'] as $i => $workshop) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($workshop['entry_date'])) ?></td>
                    <td><?php echo $workshop['evaluator_profession'] ?></td>
                    <td><?php echo $workshop['recommendation'] ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_workshop_validation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_workshop_validation', 'edit' => $workshop['pk_workshop_validation_id'], 'workshop_id' => $workshop_id)),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Edit',
                                    'title' => 'Edit Workshop',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_workshop_validation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $workshop['pk_workshop_validation_id'], 'data-workshop' => $workshop_id),
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
            var workshop = ths.attr('data-workshop');
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
                        window.location.href = '?action=deleteWorkshopValidation&id=' + logId + '&workshop_id=' + workshop;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>