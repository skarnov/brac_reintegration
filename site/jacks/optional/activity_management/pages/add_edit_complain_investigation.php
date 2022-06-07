<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;
$complain_id = $_GET['complain_id'] ? $_GET['complain_id'] : null;

if (!checkPermission($edit, 'add_complain_investigation', 'edit_complain_investigation')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$complain_filed = $this->get_complain_fileds(array('id' => $complain_id, 'single' => true));

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
} else if (isset($_POST['districtIdForPoliceStation'])) {
    $policeStations = get_policeStation($_POST['districtIdForPoliceStation']);
    echo "<option value=''>Select One</option>";
    foreach ($policeStations as $policeStation) :
        echo "<option id='" . $policeStation['id'] . "' value='" . $policeStation['name'] . "'>" . $policeStation['name'] . "</option>";
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

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complain_investigations(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid complain investigation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
        ),
    );
    $data['complain_id'] = $complain_id;
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_complain_investigation($data);

    if ($ret) {
        $msg = "Complain Investigation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_complain_investigations?action=add_edit_complain_investigation&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_activity_management/manage_complain_investigations'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Complain Investigation </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Complain Investigations',
                'title' => 'Manage Complain Investigations',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Entry Date</label>
                    <div class="input-group">
                        <input id="date_of_collection" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y') ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('date_of_collection');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label>Investigation Status/Is It Continuing Investigation?</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="running_investigation" value="yes" <?php echo $pre_data && $pre_data['running_investigation'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                            <label><input class="px" type="radio" name="running_investigation" value="no" <?php echo $pre_data && $pre_data['running_investigation'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea class="form-control" name="comments"><?php echo $pre_data['comments']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Project</label>
                    <div class="select2-primary">
                        <select class="form-control" readonly name="fk_project_id">
                            <option value="" >Select One</option>
                            <?php foreach ($all_projects['data'] as $project) : ?>
                                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $complain_filed['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Full Name/Survivor Name</label>
                    <input type="text" class="form-control" readonly name="full_name" value="<?php echo $complain_filed['full_name'] ? $complain_filed['full_name'] : $pre_data['full_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="case_id">Case Number</label>
                    <input type="text" class="form-control" readonly id="case_id" name="case_id" value="<?php echo $complain_filed['case_id'] ? $complain_filed['case_id'] : $pre_data['case_id']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputName">Complain Date</label>
                    <input type="text" readonly class="form-control" name="complain_register_date" value="<?php echo $pre_data['complain_register_date'] && $pre_data['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['complain_register_date'])) : $complain_filed['complain_register_date']; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputBranch">Select Month</label>
                    <select class="form-control" readonly name="month" >
                        <option value="January" <?php echo $complain_filed['month'] == 'January' ? 'selected' : '' ?>>January</option>
                        <option value="February" <?php echo $complain_filed['month'] == 'February' ? 'selected' : '' ?>>February</option>
                        <option value="March" <?php echo $complain_filed['month'] == 'March' ? 'selected' : '' ?>>March</option>
                        <option value="April" <?php echo $complain_filed['month'] == 'April' ? 'selected' : '' ?>>April</option>
                        <option value="May" <?php echo $complain_filed['month'] == 'May' ? 'selected' : '' ?>>May</option>
                        <option value="June" <?php echo $complain_filed['month'] == 'June' ? 'selected' : '' ?>>June</option>
                        <option value="July" <?php echo $complain_filed['month'] == 'July' ? 'selected' : '' ?>>July</option>
                        <option value="August" <?php echo $complain_filed['month'] == 'August' ? 'selected' : '' ?>>August</option>
                        <option value="September" <?php echo $complain_filed['month'] == 'September' ? 'selected' : '' ?>>September</option>
                        <option value="October" <?php echo $complain_filed['month'] == 'October' ? 'selected' : '' ?>>October</option>
                        <option value="November" <?php echo $complain_filed['month'] == 'November' ? 'selected' : '' ?>>November</option>
                        <option value="December" <?php echo $complain_filed['month'] == 'December' ? 'selected' : '' ?>>December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="number" class="form-control" readonly name="age" value="<?php echo $complain_filed['age'] ? $complain_filed['age'] : $pre_data['age']; ?>">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <?php
                                    $all_gender = array(
                                        'male' => 'Men (>=18)',
                                        'female' => 'Women (>=18)');
                                    foreach ($all_gender as $key => $value) :
                                        $allGender[] = $key;
                                        ?>
                                        <label><input class="px oldGender" disabled type="radio" name="gender" value="<?php echo $key ?>" <?php echo $complain_filed['gender'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                        <?php
                                    endforeach;
                                    if ($complain_filed['gender']):
                                        ?>
                                        <label><input class="px" type="radio" name="gender" <?php
                                            if (!in_array($complain_filed['gender'], $allGender)): echo 'checked';
                                            endif;
                                            ?> id="newGender"><span class="lbl">Other</span></label>
                                                  <?php else : ?>
                                        <label><input class="px" type="radio" name="gender" id="newGender" value=""><span class="lbl">Other</span></label>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>           
                        <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                            <input class="form-control" readonly placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $complain_filed['gender'] ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#newGender').is(':checked');

                                if (isChecked == true) {
                                    $('#newGenderType').show();
                                }

                                $("#newGender").on("click", function () {
                                    $('#newGenderType').show();
                                });

                                $(".oldGender").on("click", function () {
                                    $('#newGenderType').hide();
                                    $('#newGenderText').val('');
                                });
                            });
                        </script>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="comments">Type of Case</label>
                            <div class="options_holder radio">
                                <?php
                                $all_type_cases = array(
                                    'Missing' => 'Missing',
                                    'Flee away with' => 'Flee away with',
                                    'Abduction' => 'Abduction',
                                );
                                foreach ($all_type_cases as $key => $value) :
                                    $typeCases[] = $key;
                                    ?>
                                    <label><input class="px Oldcase" disabled type="radio" name="type_case" value="<?php echo $key ?>" <?php echo $complain_filed['type_case'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                    <?php
                                endforeach;
                                if ($complain_filed['type_case']):
                                    ?>
                                    <label><input class="px" type="radio" name="type_case" <?php
                                        if (!in_array($complain_filed['type_case'], $typeCases)): echo 'checked';
                                        endif;
                                        ?> id="newCase"><span class="lbl">Other</span></label>
                                              <?php else : ?>
                                    <label><input class="px" type="radio" name="type_case" id="newCase" value=""><span class="lbl">Other</span></label>
                                <?php endif ?>
                            </div>                          
                        </div>  
                        <div id="newCaseType" style="display: none; margin-bottom: 1em;">
                            <input class="form-control" id="newCaseText" readonly placeholder="Please Specity" type="text" name="new_type_case" value="<?php echo $complain_filed['type_case'] ? $complain_filed['type_case'] : ''; ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#newCase').is(':checked');

                                if (isChecked == true) {
                                    $('#newCaseType').show();
                                }

                                $("#newCase").on("click", function () {
                                    $('#newCaseType').show();
                                });

                                $(".Oldcase").on("click", function () {
                                    $('#newCaseType').hide();
                                    $('#newCaseText').val('');
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Division</label>
                    <div class="select2-primary">
                        <select class="form-control division" readonly name="division" style="text-transform: capitalize">
                            <option>Select One</option>
                            <?php foreach ($divisions as $division) : ?>
                                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $complain_filed['division'] == strtolower($division['name']) ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">District</label>
                    <div class="select2-primary">
                        <select class="form-control district" readonly name="district" style="text-transform: capitalize" id="districtList">
                            <?php if ($complain_filed['district']) : ?>
                                <option value="<?php echo $complain_filed['district'] ?>"><?php echo $complain_filed['district'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Select Municipality/Upazila</label>
                    <div class="select2-primary">
                        <select class="form-control subdistrict" readonly name="upazila" style="text-transform: capitalize" id="subdistrictList">
                            <?php if ($complain_filed['upazila']) : ?>
                                <option value="<?php echo $complain_filed['upazila'] ?>"><?php echo $complain_filed['upazila'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Police Station</label>
                    <div class="select2-primary">
                        <select class="form-control" readonly name="police_station" style="text-transform: capitalize" id="policeStationList">
                            <?php if ($complain_filed['police_station']) : ?>
                                <option value="<?php echo $complain_filed['police_station'] ?>"><?php echo $complain_filed['police_station'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_complain_investigations') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update' : 'Save',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update' : 'Save'
            ))
            ?>
        </div>
    </div>
</form>
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

            $.ajax({
                type: 'POST',
                data: {districtIdForPoliceStation: districtId},
                beforeSend: function () {
                    $('#policeStationList').html("<option value=''>Loading...</option>");
                },
                success: function (result) {
                    $('#policeStationList').html(result);
                }}
            );
        });
    });
</script>