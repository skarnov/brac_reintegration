<?php
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$training_info = $this->get_trainings(array('id' => $training_id, 'single' => true));

$args = array(
    'training_id' => $training_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_training_validation_id',
        'order' => 'DESC'
    ),
);

$trainings = $this->get_training_validations($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);

doAction('render_start');
ob_start();
?>
<div class="page-header">
    <h1>All Training Validations</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_training_validation&training_id=' . $training_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Training Validation',
                'title' => 'New Training Validation',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'training validation') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Training Date</th>
                <th>Evaluator Profession</th>
                <th>Recommendation</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($training['entry_date'])) ?></td>
                    <td><?php echo $training['evaluator_profession'] ?></td>
                    <td><?php echo $training['recommendation'] ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_training_validation', 'edit' => $training['pk_training_validation_id'], 'training_id' => $training_id)),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Edit',
                                    'title' => 'Edit Training',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $training['pk_training_validation_id'], 'data-training' => $training_id),
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
            var training = ths.attr('data-training');
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
                        window.location.href = '?action=deleteTrainingValidation&id=' + logId + '&training_id=' + training;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>