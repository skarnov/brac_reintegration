<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_upazila = $_GET['upazila'] ? $_GET['upazila'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;

$args = array(
    'select_fields' => array(
        'bd_unions.id' => 'bd_unions.id AS union_id',
        'bd_districts.name' => 'bd_districts.name AS district_name',
        'bd_upazilas.name' => 'bd_upazilas.name AS upazila_name',
        'bd_unions.name' => 'bd_unions.name AS union_name',
        'bd_unions.bn_name' => 'bd_unions.bn_name AS union_bn_name ',
    ),
    'upazila' => $filter_upazila,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'bd_unions.name',
        'order' => 'ASC'
    ),
);

$result = $this->get_unions($args);
$pagination = pagination($result['total'], $per_page_items, $start);

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option value='" . $subdistrict['id'] . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
}

if ($filter_upazila):
    $args = array(
        'select_fields' => array(
            'upazila_name' => 'bd_upazilas.name AS name',
        ),
        'id' => $filter_upazila,
        'single' => true
    );
    $upazila_info = $this->get_upazilas($args);

endif;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Union Management</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_union',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Create New Union',
                'title' => 'Create New Union',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control division" name="division" style="text-transform: capitalize">
            <?php if ($filter_division) : ?>
                <option value="<?php echo $filter_division ?>"><?php echo $filter_division ?></option>
            <?php else: ?>
                <option value="">Select One</option>
            <?php endif ?>
            <?php foreach ($divisions as $division) : ?>
                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>"><?php echo $division['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control district" id="districtList" name="district" style="text-transform: capitalize">
            <?php if ($filter_district) : ?>
                <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Upazila</label>
    <div class="select2-primary">
        <select class="form-control subdistrict" name="upazila" id="subdistrictList" style="text-transform: capitalize">
            <?php if ($filter_upazila) : ?>
                <option value="<?php echo $filter_upazila ?>"><?php echo $upazila_info['name'] ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('name', 'union', array(
    'width' => 3, 'type' => 'text', 'label' => 'Union Name',
        ), $filter_union);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>District</th>
                <th>Upazila</th>
                <th>Union Name</th>
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
                    <td><?php echo $value['upazila_name'] ?></td>              
                    <td><?php echo $value['union_name'] ?></td>              
                    <td><?php echo $value['union_bn_name'] ?></td>              
                    <td class="tar action_column">
                        <?php if (has_permission('edit_location')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_union', 'edit' => $value['union_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Union',
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
                                    'attributes' => array('data-id' => $value['union_id']),
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
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
                beforeSend: function () {
                    $('#districtList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#districtList').html(result);
                }}
            );
        });
        $('.district').change(function () {
            var districtId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {district_id: districtId},
                beforeSend: function () {
                    $('#subdistrictList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#subdistrictList').html(result);
                }}
            );
        });
    });
</script>
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