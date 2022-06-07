<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_police_station = $_GET['police_station'] ? $_GET['police_station'] : null;

$args = array(
    'select_fields' => array(
        'police_station_id' => 'bd_police_stations.id AS police_station_id',
        'district_name' => 'bd_districts.name AS district_name',
        'police_station_name' => 'bd_police_stations.name AS police_station_name',
        'police_station_bn_name' => 'bd_police_stations.bn_name AS police_station_bn_name',
    ),
    'district' => $filter_district,
    'police_station' => $filter_police_station,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'bd_police_stations.name',
        'order' => 'ASC'
    ),
);

$result = $this->get_police_stations($args);
$pagination = pagination($result['total'], $per_page_items, $start);

$args = array(
    'listing' => true,
    'select_fields' => array(
        'district_id' => 'bd_districts.id AS district_id',
        'district_name' => 'bd_districts.name AS district_name',
    ),
    'order_by' => array(
        'col' => 'bd_districts.name',
        'order' => 'ASC'
    ),
);

$all_districts = $this->get_districts($args);

doAction('render_start');
?>
<div class="page-header">
    <h1>All Police Station Management</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_police_station',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Create New Police Station',
                'title' => 'Create New Police Station',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-4">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control" name="district">
            <option value="">Select One</option>
            <?php foreach ($all_districts['data'] as $value) : ?>
                <option value="<?php echo $value['district_id'] ?>" <?php echo ($value['district_id'] == $filter_district) ? 'selected' : '' ?>><?php echo ucwords($value['district_name']) ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('name', 'police_station', array(
    'width' => 4, 'type' => 'text', 'label' => 'Police Station Name',
        ), $filter_police_station);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>District</th>
                <th>Police Station Name</th>
                <th>Bengali Name</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result['data'] as $value) {
                ?>
                <tr>
                    <td><?php echo $value['district_name'] ?></td>              
                    <td><?php echo $value['police_station_name'] ?></td>              
                    <td><?php echo $value['police_station_bn_name'] ?></td>              
                    <td class="tar action_column">
                        <?php if (has_permission('edit_location')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_police_station', 'edit' => $value['police_station_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Police Station',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_location')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $value['police_station_id']),
                                    'classes' => 'delete_single_record'));
                                ?>
                            </div>
                        <?php endif ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="table-footer oh">
        <?php echo $pagination ?>
    </div>
</div>
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Data Will Be Completely Deleted. Are You Sure To Continue?',
                        value: 'deleteData'
                    }],
                callback: function (result) {
                    if (result == 'deleteData') {
                        window.location.href = '?action=deleteData&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>