<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project_id = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;

$args = array(
    'select_fields' => array(
        'id' => 'dev_activities.pk_activity_id',
        'project_id' => 'dev_activities.fk_project_id',
        'project_name' => 'dev_projects.project_short_name',
        'activity_name' => 'dev_activities.activity_name',
    ),
    'project_id' => $filter_project_id,
    'activity_name' => $filter_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_activity_id',
        'order' => 'DESC'
    ),
);

$results = $this->get_misactivities($args);
$pagination = pagination($results['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_project_id)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Activities</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_misactivity',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Activity',
                'title' => 'Add New Activity',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
echo formProcessor::form_elements('name', 'name', array(
    'width' => 4, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Activities') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['project_short_name']; ?></td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td>
                        <?php if (has_permission('edit_misactivity')): ?>
                            <div class="btn-group">
                                <a href="<?php echo url('admin/dev_activity_report/manage_misactivities?action=add_edit_misactivity&edit=' . $value['pk_activity_id']) ?>" class="btn btn-primary btn btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
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
        <div class="pull-left">
            <?php echo $pagination ?>
        </div>
    </div>
</div>