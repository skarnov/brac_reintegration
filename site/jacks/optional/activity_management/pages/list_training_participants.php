<?php
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$training_info = $this->get_trainings(array('id' => $training_id, 'single' => true));

$args = array(
    'fk_training_id' => $training_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_training_participant_id',
        'order' => 'DESC'
    ),
);

$trainings = $this->get_training_participants($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);

doAction('render_start');

ob_start();
?>
<div class="page-header">
    <h1>All Training Participants</h1>
    <h4 class="text-primary">Training Name : <?php echo $training_info['training_name'] ?></h4>
    <h4 class="text-primary">Training Duration : <?php echo $training_info['training_duration'] ?></h4>
    <h4 class="text-primary">Training Start Date : <?php echo date('d-m-Y', strtotime($training_info['training_start_date'])) ?></h4>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_participant&training_id=' . $training_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Training Participant',
                'title' => 'New Training Participant',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'participants') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Profession</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Mobile Number</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
                    <td><?php echo $training['participant_name'] ?></td>
                    <td class="text-capitalize"><?php echo $training['participant_type'] ?></td>
                    <td><?php echo $training['participant_profession'] ?></td>
                    <td><?php echo ucfirst($training['participant_gender']) ?></td>
                    <td><?php echo $training['participant_age'] ?></td>
                    <td><?php echo $training['participant_mobile'] ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('add_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_participant', 'edit' => $training['pk_training_participant_id'], 'training_id' => $training_id)),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Edit',
                                    'title' => 'Edit Participant',
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
                                    'attributes' => array('data-id' => $training['pk_training_participant_id'], 'data-training' => $training_id, 'data-gender' => $training['participant_gender']),
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
            var gender = ths.attr('data-gender');
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
                        window.location.href = '?action=deleteTrainingParticipant&id=' + logId + '&training_id=' + training + '&gender='+ gender;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>