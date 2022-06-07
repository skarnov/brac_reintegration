<?php
$workshop_id = $_GET['workshop_id'] ? $_GET['workshop_id'] : null;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$workshop_info = $this->get_workshops(array('id' => $workshop_id, 'single' => true));

$args = array(
    'fk_workshop_id' => $workshop_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_workshop_participant_id',
        'order' => 'DESC'
    ),
);

$workshops = $this->get_workshop_participants($args);
$pagination = pagination($workshops['total'], $per_page_items, $start);

doAction('render_start');

ob_start();
?>
<div class="page-header">
    <h1>All Workshop Participants</h1>
    <h4 class="text-primary">Workshop Name : <?php echo $workshop_info['workshop_name'] ?></h4>
    <h4 class="text-primary">Workshop Duration : <?php echo $workshop_info['workshop_duration'] ?></h4>
    <h4 class="text-primary">Workshop Start Date : <?php echo date('d-m-Y', strtotime($workshop_info['workshop_start_date'])) ?></h4>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_participant&workshop_id=' . $workshop_id,
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Workshop Participant',
                'title' => 'New Workshop Participant',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($workshops['total'], $start, $per_page_items, count($workshops['data']), 'participants') ?>
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
            foreach ($workshops['data'] as $i => $workshop) {
                ?>
                <tr>
                    <td><?php echo $workshop['participant_name'] ?></td>
                    <td class="text-capitalize"><?php echo $workshop['participant_type'] ?></td>
                    <td><?php echo $workshop['participant_profession'] ?></td>
                    <td><?php echo ucfirst($workshop['participant_gender']) ?></td>
                    <td><?php echo $workshop['participant_age'] ?></td>
                    <td><?php echo $workshop['participant_mobile'] ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('add_workshop')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_participant', 'edit' => $workshop['pk_workshop_participant_id'], 'workshop_id' => $workshop_id)),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Edit',
                                    'title' => 'Edit Participant',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_workshop')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $workshop['pk_workshop_participant_id'], 'data-workshop' => $workshop_id, 'data-gender' => $workshop['participant_gender']),
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
                        window.location.href = '?action=deleteWorkshopParticipant&id=' + logId + '&workshop_id=' + workshop + '&gender=' + gender;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>