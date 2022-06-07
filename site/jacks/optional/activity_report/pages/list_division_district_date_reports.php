<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . $district['name'] . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
}

$filter_division = $_GET['division'] ? $_GET['division'] : 'Khulna';
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'branch_division' => 'dev_targets.branch_division',
        'branch_district' => 'dev_targets.branch_district',
        'create_date' => 'dev_targets.create_date',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'division' => $filter_division,
    'district' => $filter_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$results = $this->get_division_district_date_reports($args);

$args = array(
    'select_fields' => array(
        'branch_division' => 'dev_targets.branch_division',
        'branch_district' => 'dev_targets.branch_district',
        'create_date' => 'dev_targets.create_date',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'SUM(dev_targets.activity_target) AS activity_target',
        'activity_achievement' => 'SUM(dev_targets.activity_achievement) AS activity_achievement',
    ),
    'group_by' => 'GROUP BY dev_activities.activity_name',
    'division' => $filter_division,
    'district' => $filter_district,
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$count_results = $this->get_division_district_date_reports($args);
$results['total'] = $count_results['result_total'];
$pagination = pagination($results['total'], $per_page_items, $start);

$filterString = array();
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $data = $this->get_division_district_date_reports($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Division_District_Date_Wise_MIS_Report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('Division, District and Date Wise MIS Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array(
        'SL',
        'Division Name',
        'District Name',
        'Create Date',
        'Activity Name',
        'Activity Target',
        'Activity Achievement',
    );

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $value) {
            $dataToSheet = array(
                ++$count
                , $value['branch_division']
                , $value['branch_district']
                , $value['create_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($value['create_date']))
                , $value['activity_name']
                , $value['activity_target']
                , $value['activity_achievement']
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
    <h1>Division, District and Date MIS Reports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&division=' . $filter_division . '&district=' . $filter_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download',
                'title' => 'Download',
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
                <option id="<?php echo $division['id'] ?>" value="<?php echo $division['name'] ?>"><?php echo $division['name'] ?></option>
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
        <div class="table-header text-capitalize">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Division, District and Date MIS Report') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Division</th>
                <th>District</th>
                <th>Create Date</th>
                <th>Activity Name</th>
                <th>Target</th>
                <th>Achievement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['branch_division']; ?></td>
                    <td><?php echo $value['branch_district']; ?></td>
                    <td><?php echo $value['create_date'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($value['create_date'])) ?></td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td><?php echo $value['activity_target']; ?></td>
                    <td><?php echo $value['activity_achievement']; ?></td>
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
                    $('#districtList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#districtList').html(result);
                }}
            );
        });
    });
</script>