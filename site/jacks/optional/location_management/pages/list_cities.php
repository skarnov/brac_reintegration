<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 20;

$filter_country = $_GET['country'] ? $_GET['country'] : null;
$filter_city = $_GET['city'] ? $_GET['city'] : null;

$args = array(
    'select_fields' => array(
        'city_id' => 'cities.id AS city_id',
        'country_name' => 'countries.nicename AS country_name',
        'city_name' => 'cities.name AS city_name',
    ),
    'country' => $filter_country,
    'city' => $filter_city,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'cities.name',
        'order' => 'ASC'
    ),
);

$result = $this->get_cities($args);
$pagination = pagination($result['total'], $per_page_items, $start);

$args = array(
    'listing' => true,
    'select_fields' => array(
        'country_id' => 'countries.id AS country_id',
        'country_name' => 'countries.nicename AS country_name',
    ),
    'order_by' => array(
        'col' => 'countries.nicename',
        'order' => 'ASC'
    ),
);

$all_countries = $this->get_countries($args);

doAction('render_start');
?>
<div class="page-header">
    <h1>All City Management</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_city',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Create New City',
                'title' => 'Create New City',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-4">
    <label>Country</label>
    <div class="select2-primary">
        <select class="form-control" name="country">
            <option value="">Select One</option>
            <?php foreach ($all_countries['data'] as $value) : ?>
                <option value="<?php echo $value['country_id'] ?>" <?php echo ($value['country_id'] == $filter_country) ? 'selected' : '' ?>><?php echo ucwords($value['country_name']) ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('name', 'city', array(
    'width' => 4, 'type' => 'text', 'label' => 'City Name',
        ), $filter_city);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Country</th>
                <th>Name</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result['data'] as $value) {
                ?>
                <tr>
                    <td><?php echo $value['country_name'] ?></td>              
                    <td><?php echo $value['city_name'] ?></td>           
                    <td class="tar action_column">
                        <?php if (has_permission('edit_location')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_city', 'edit' => $value['city_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit City',
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
                                    'attributes' => array('data-id' => $value['city_id']),
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