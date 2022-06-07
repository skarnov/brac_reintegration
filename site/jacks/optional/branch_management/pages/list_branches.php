<?php
if ($_GET['ajax_type']) {
    $ret = array();

    if ($_GET['ajax_type'] == 'get_branch_form') {
        $ret = $this->get_branch_form($_POST['edit']);
    } else if ($_GET['ajax_type'] == 'put_branch_form') {
        $ret = $this->put_branch_form($_POST);
    } else if ($_GET['ajax_type'] == 'get_parent_branches') {
        $thisBranchType = $_POST['branch_type'];
        $parentBranchType = $devdb->get_row("SELECT fk_item_id FROM dev_branch_types WHERE pk_item_id = '$thisBranchType'");
        $parentBranchType = $parentBranchType['fk_item_id'];
        $parentBranches = $this->get_branches(array('data_only' => true, 'select_fields' => array('pk_branch_id', 'branch_name', 'fk_branch_type'), 'type' => $parentBranchType));
        $ret = array('success' => $parentBranches);
    }

    echo json_encode($ret);
    exit();
}

if ($_POST['ajax_type']) {
    $ret = array();

    if ($_POST['ajax_type'] == 'get_parent_branches') {
        $thisBranchType = $_POST['branch_type'];
        $parentBranchType = $devdb->get_row("SELECT fk_item_id FROM dev_branch_types WHERE pk_item_id = '$thisBranchType'");
        $parentBranchType = $parentBranchType['fk_item_id'];
        $parentBranches = $this->get_branches(array('data_only' => true, 'select_fields' => array('branches.pk_branch_id', 'branches.branch_name', 'branches.fk_branch_type'), 'type' => $parentBranchType));
        $ret = array('success' => $parentBranches['data']);
    }

    echo json_encode($ret);
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;

$args = array(
    'name' => $filter_name,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_branch_id',
        'order' => 'DESC'
    ),
);

$data = $this->get_branches($args);
$pagination = pagination($data['total'], $per_page_items, $start);

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;

doAction('render_start');
?>
<div class="page-header">
    <h1>Branches</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => build_url(array('action' => 'configure_branch_types')),
                'action' => 'config',
                'icon' => 'icon_config',
                'text' => 'Branch Types',
                'title' => 'Branch Types',
            ));
            echo linkButtonGenerator(array(
                'classes' => 'add_edit_branch',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Branch',
                'title' => 'Create New Branch',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('name', 'name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
?>
<div class="form-group col-sm-2">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Upazila</label>
    <div class="select2-success">
        <select class="form-control" id="filter_sub_district" name="sub_district" data-selected="<?php echo $filter_sub_district; ?>"></select>
    </div>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header text-capitalize">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($data['total'], $start, $per_page_items, count($data['data']), 'branches') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Mother Branch</th>
                <th>Type</th>
                <th>Project</th>
                <th>Division</th>
                <th>District</th>
                <th>Sub-District</th>
                <th>Address</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data['data'] as $i => $item) {
                ?>
                <tr>
                    <td><?php echo $item['branch_name']; ?></td>
                    <td><?php echo $item['parent_branch_name']; ?></td>
                    <td><?php echo $item['branch_type_name']; ?></td>
                    <td><?php echo $item['project_name']; ?></td>
                    <td><?php echo $item['branch_division']; ?></td>
                    <td><?php echo $item['branch_district']; ?></td>
                    <td><?php echo $item['branch_sub_district']; ?></td>
                    <td><?php echo $item['branch_address']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_branch')): ?>
                            <?php
                            echo linkButtonGenerator(array(
                                'classes' => 'add_edit_branch',
                                'attributes' => array('data-id' => $item['pk_branch_id']),
                                'action' => 'edit',
                                'icon' => 'icon_edit',
                                'text' => 'Update',
                                'title' => 'Update',
                            ));
                            ?>
                        <?php endif; ?>
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
<div class="dn">
    <div id="ajax_form_container"></div>
</div>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson() ?>;

    var can_add = <?php echo has_permission('add_branch') ? '1' : '0' ?>;
    var can_edit = <?php echo has_permission('edit_branch') ? '1' : '0' ?>;

    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });

        $(document).on('click', '.add_edit_branch', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : 0;
            var thsRow = is_update ? ths.closest('tr') : null;

            var form_api = '<?php echo build_url(array('ajax_type' => 'get_branch_form')) ?>';
            var form_submit_api = '<?php echo build_url(array('ajax_type' => 'put_branch_form')) ?>';

            if (is_update && !can_edit)
                return false;
            else if (!is_update && !can_add)
                return false;

            new in_page_add_event({
                edit_form_success_callback: function () {
                    initCharLimit();
                },
                additional_data: {
                    edit: is_update,
                },
                edit_mode: true,
                edit_form_url: form_api,
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Branch' : 'Add New Branch',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: form_submit_api,
                callback: function (data) {
                    window.location.reload();
                }
            });
        });
    });
</script>