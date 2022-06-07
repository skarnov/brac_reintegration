<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_tag = $_GET['tag'] ? $_GET['tag'] : null;
$filter_start_date = $_GET['start_date'] ? $_GET['start_date'] : null;
$filter_end_date = $_GET['end_date'] ? $_GET['end_date'] : null;

$args = array(
    'select_fields' => array(
        '*' => 'dev_knowledge.*',
        'project_short_name' => 'dev_projects.project_short_name',
    ),
    'name' => $filter_name,
    'project' => $filter_project,
    'tags' => $filter_tag,
    'type' => 'meeting_minute',
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_knowledge_id',
        'order' => 'DESC'
    ),
);

if ($filter_start_date && $filter_end_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_start_date),
            'right' => date_to_db($filter_end_date),
        ),
    );
}

$meeting_minutes = $this->get_knowledge($args);
$pagination = pagination($meeting_minutes['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_tag)
    $filterString[] = 'Tag: ' . $filter_tag;
if ($filter_project)
    $filterString[] = 'Project: ' . $projectName['project_short_name'];
if ($filter_start_date)
    $filterString[] = 'Start Date: ' . $filter_start_date;
if ($filter_end_date)
    $filterString[] = 'End Date: ' . $filter_end_date;

$all_meeting_minute_tags = $this->get_lookups('meeting_minute');

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Meeting Minutes</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_meeting_minute',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'New Meeting Minute',
                        'title' => 'New Meeting Minute',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $meeting_minutes['total'] ?></span><br>
                    <span class="text-sm">Stored in Database</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-cell bg-warning padding-sm no-padding-t text-center">
                    <div id="stats-sparklines-2" class="stats-sparklines" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Name',
        ), $filter_name)
?>
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Tag</label>
    <select name="tag" class="form-control">
        <option value="">Select Tag</option>
        <?php
        foreach ($all_meeting_minute_tags['data'] as $tag) {
            ?>
            <option value="<?php echo $tag['lookup_value'] ?>" <?php
            if ($tag['lookup_value'] == $filter_tag) {
                echo 'selected';
            }
            ?>><?php echo $tag['lookup_value'] ?></option>
                <?php } ?>
    </select>
</div>
<div class="form-group col-sm-3">
    <label>Entry Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="start_date" value="<?php echo $filter_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>Entry End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="end_date" value="<?php echo $filter_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
</div>
<?php
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
        <?php echo searchResultText($meeting_minutes['total'], $start, $per_page_items, count($meeting_minutes['data']), 'Meeting Minutes') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project</th>
                <th>Date</th>
                <th>Name</th>
                <th>View/Download</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($meeting_minutes['data'] as $i => $meeting_minute) {
                ?>
                <tr>
                    <td><?php echo $meeting_minute['project_short_name']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($meeting_minute['create_date'])) ?></td>
                    <td><?php echo $meeting_minute['name']; ?></td>
                    <td><a href="<?php echo image_url($meeting_minute['document_file']); ?>" target="_blank">Click Here</a></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_meeting_minute')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_meeting_minute', 'edit' => $meeting_minute['pk_knowledge_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Story',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_meeting_minute')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $meeting_minute['pk_knowledge_id']),
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
                        window.location.href = '?action=deleteMeetingMinute&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>