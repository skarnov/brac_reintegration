<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$complain_id = $_GET['complain_id'] ? $_GET['complain_id'] : null;
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_case_type = $_GET['type_case'] ? $_GET['type_case'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_upazila = $_GET['upazila'] ? $_GET['upazila'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'complain_file_id' => $complain_id,
    'project' => $filter_project,
    'gender' => $filter_gender,
    'type_case' => $filter_case_type,
    'division' => $filter_division,
    'district' => $filter_district,
    'upazila' => $filter_upazila,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_complain_investigations.pk_complain_investigation_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'entry_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$complain_investigations = $this->get_complain_investigations($args);
$pagination = pagination($complain_investigations['total'], $per_page_items, $start);

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
        echo "<option id='" . $subdistrict['id'] . "' value='" . strtolower($subdistrict['name']) . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$projectName = $projects->get_projects(array('id' => $filter_project, 'single' => true));

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_complain_investigations($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Complain_Investigations_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Complain Investigations Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Entry Date',
        'Data Submission Date',
        'Investigation Status',
        'Project Name',
        'Survivor Name',
        'Case Number',
        'Complain Date',
        'Month',
        'Age',
        'Gender',
        'Type of Case',
        'Division',
        'District',
        'Upazila',
        'Police Station',
        'Comments',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['entry_date'] && $value['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['entry_date'])) : 'N/A'
                , date('d-m-Y', strtotime($value['create_date']))
                , ucfirst($value['running_investigation'])
                , $value['project_short_name']
                , $value['full_name']
                , $value['case_id']
                , $value['complain_register_date'] && $value['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($value['complain_register_date'])) : 'N/A'
                , get_months()[$value['month']]
                , $value['age']
                , ucfirst($value['gender'])
                , ucfirst($value['type_case'])
                , ucfirst($value['division'])
                , ucfirst($value['district'])
                , ucfirst($value['upazila'])
                , ucfirst($value['police_station'])
                , $value['comments']
            );
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder . "*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if (function_exists('apache_setenv'))
        @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename=' . basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile, "rb");
    while (!feof($file)) {
        print(@fread($file, 1024 * 8));
        ob_flush();
        flush();
    }
    @fclose($file);
    exit;
}

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Complain Investigations</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_csv=1&project_id=' . $filter_project . '&gender=' . $filter_gender . '&type_case=' . $filter_type_case . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Complain Investigations',
                        'title' => 'Download Complain Investigations',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $complain_investigations['total'] ?></span><br>
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
?>
<!--<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>-->
<div class="form-group col-sm-3">
    <label>Gender</label>
    <div class="select2-primary">
        <select class="form-control" name="gender">
            <option value="">Select One</option>
            <option value="male" <?php echo ('male' == $filter_gender) ? 'selected' : '' ?>>Men (>=18)</option>
            <option value="female" <?php echo ('female' == $filter_gender) ? 'selected' : '' ?>>Women (>=18)</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Case Type</label>
    <div class="select2-primary">
        <select class="form-control" name="type_case">
            <option value="">Select One</option>
            <option value="Missing" <?php echo ('Missing' == $filter_case_type) ? 'selected' : '' ?>>Missing</option>
            <option value="Flee away with" <?php echo ('Flee away with' == $filter_case_type) ? 'selected' : '' ?>>Flee away with</option>
            <option value="Abduction" <?php echo ('Abduction' == $filter_case_type) ? 'selected' : '' ?>>Abduction</option>
        </select>
    </div>
</div>
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
        <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
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
                <option value="<?php echo $filter_upazila ?>"><?php echo $filter_upazila ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="entry_end_date" value="<?php echo $filter_entry_end_date ?>">
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
        <?php echo searchResultText($complain_investigations['total'], $start, $per_page_items, count($complain_investigations['data']), 'complain investigations') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
<!--                <th>Project</th>-->
                <th>Case Number</th>
                <th>Date</th>
                <th>Month</th>
                <th>Police Station</th>
                <th>Case Type</th>
                <th>Gender</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($complain_investigations['data'] as $i => $complain_investigation) {
                ?>
                <tr>
<!--                    <td><?php echo $complain_investigation['project_short_name']; ?></td>-->
                    <td><?php echo $complain_investigation['case_id']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($complain_investigation['complain_register_date'])) ?></td>
                    <td><?php echo $complain_investigation['month']; ?></td>
                    <td><?php echo $complain_investigation['police_station']; ?></td>
                    <td><?php echo $complain_investigation['type_case']; ?></td>
                    <td>
                        <?php
                        if ($complain_investigation['gender'] == 'male' && $complain_investigation['age'] <= 17) {
                            echo 'Boy (<18)';
                        } else if ($complain_investigation['gender'] == 'male' && $complain_investigation['age'] > 17) {
                            echo 'Men (>=18)';
                        } else if ($complain_investigation['gender'] == 'female' && $complain_investigation['age'] <= 17) {
                            echo 'Girl (<18)';
                        } else if ($complain_investigation['gender'] == 'female' && $complain_investigation['age'] > 17) {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_complain_investigation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_complain_investigation', 'edit' => $complain_investigation['pk_complain_investigation_id'], 'complain_id' => $complain_investigation['fk_complain_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Complain Investigation',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_complain_investigation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain_investigation['pk_complain_investigation_id']),
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
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
                beforeSend: function () {
                    $('#subdistrictList').html("<option value=''>Loading...</option>");
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
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteComplainInvestigation&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>