<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;

$args = array(
    'select_fields' => array(
        'district_id' => 'bd_districts.id AS district_id',
        'division_name' => 'bd_divisions.name AS division_name',
        'district_name' => 'bd_districts.name AS district_name',
        'district_bn_name' => 'bd_districts.bn_name AS district_bn_name',
    ),
    'division' => $filter_division,
    'district' => $filter_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'bd_districts.name',
        'order' => 'ASC'
    ),
);

$result = $this->get_districts($args);
$pagination = pagination($result['total'], $per_page_items, $start);

$all_divisions = $this->get_divisions(array(
    'order_by' => array(
        'col' => 'bd_divisions.name',
        'order' => 'ASC'
        )));

doAction('render_start');
?>
<div class="page-header">
    <h1>All District Management</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_district',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Create New District',
                'title' => 'Create New District',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-4">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" name="division">
            <option value="">Select One</option>
            <?php foreach ($all_divisions['data'] as $value) : ?>
                <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] == $filter_division) ? 'selected' : '' ?>><?php echo ucwords($value['name']) ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('name', 'district', array(
    'width' => 4, 'type' => 'text', 'label' => 'District Name',
        ), $filter_district);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Division</th>
                <th>District Name</th>
                <th>Bengali Name</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result['data'] as $value) {
                ?>
                <tr>
                    <td><?php echo $value['division_name'] ?></td>              
                    <td><?php echo $value['district_name'] ?></td>              
                    <td><?php echo $value['district_bn_name'] ?></td>              
                    <td class="tar action_column">
                        <?php if (has_permission('edit_location')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_district', 'edit' => $value['district_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit District',
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
                                    'attributes' => array('data-id' => $value['district_id']),
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