<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_complain', 'edit_complain')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

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

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complains(array('id' => $edit, 'single' => true));

    $type_service = explode(',', $pre_data['type_service']);
    $know_service = explode(',', $pre_data['know_service']);

    if (!$pre_data) {
        add_notification('Invalid complain, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $branch_id = $_POST['branch_id'];
    $branch_info = $branches->get_branches(array('id' => $branch_id, 'single' => true));

    $data = array(
        'required' => array(
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_complain($data);

    if ($ret['success']) {
        $msg = "Complain has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_activity_management/manage_complains?action=add_edit_complain&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_activity_management/manage_complains'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Community Service</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Community Services',
                'title' => 'Manage Community Services',
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
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Address</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Branch</label>
                            <div class="select2-primary">
                                <select class="form-control" name="branch_id" required>
                                    <option value="">Select One</option>
                                    <?php foreach ($all_branches['data'] as $branch) : ?>
                                        <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">Division</label>
                            <div class="select2-primary">
                                <select class="form-control division" name="division" style="text-transform: capitalize">
                                    <?php if ($pre_data['division']) : ?>
                                        <option value="<?php echo strtolower($pre_data['division']) ?>"><?php echo $pre_data['division'] ?></option>
                                    <?php else: ?>
                                        <option>Select One</option>
                                    <?php endif ?>
                                    <?php foreach ($divisions as $division) : ?>
                                        <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">District</label>
                            <div class="select2-primary">
                                <select class="form-control district" name="branch_district" style="text-transform: capitalize" id="districtList">
                                    <?php if ($pre_data['branch_district']) : ?>
                                        <option value="<?php echo $pre_data['branch_district'] ?>"><?php echo $pre_data['branch_district'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label input-label">Upazila</label>
                            <div class="select2-primary">
                                <select class="form-control subdistrict" name="upazila" style="text-transform: capitalize" id="subdistrictList">
                                    <?php if ($pre_data['upazila']) : ?>
                                        <option value="<?php echo $pre_data['upazila'] ?>"><?php echo $pre_data['upazila'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">Union</label>
                            <div class="select2-primary">
                                <select class="form-control union" name="branch_union" style="text-transform: capitalize" id="unionList">
                                    <?php if ($pre_data['branch_union']) : ?>
                                        <option value="<?php echo $pre_data['branch_union'] ?>"><?php echo $pre_data['branch_union'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Village</label>
                            <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
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
                                $('.subdistrict').change(function () {
                                    var subdistrictId = $(this).find('option:selected').attr('id');
                                    $.ajax({
                                        type: 'POST',
                                        data: {subdistrict_id: subdistrictId},
                                        beforeSend: function () {
                                            $('#unionList').html("<option value=''>Loading...</option>");
                                        },
                                        success: function (result) {
                                            $('#unionList').html(result);
                                        }}
                                    );
                                });
                            });
                        </script>
                    </div>
                </fieldset>
            </div>
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
                    <label>Project</label>
                    <div class="select2-primary">
                        <select class="form-control" name="fk_project_id">
                            <option value="">Select One</option>
                            <?php foreach ($all_projects['data'] as $project) : ?>
                                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Service Date</label>
                    <div class="input-group">
                        <input id="ComplainRegisterDate" type="text" class="form-control" name="complain_register_date" value="<?php echo $pre_data['complain_register_date'] && $pre_data['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['complain_register_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('ComplainRegisterDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="inputName">Name of service recipient</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $pre_data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="number" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <?php
                $type_service = $type_service ? $type_service : array($type_service);
                ?> 
                <div class="form-group">
                    <label>Type of service seeking</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="type_service[]" value="Case filing support" <?php
                                if (in_array('Case filing support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Case filing support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Trafficking information" <?php
                                if (in_array('Trafficking information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Trafficking information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Safe migration information" <?php
                                if (in_array('Safe migration information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Safe migration information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Missing information" <?php
                                if (in_array('Missing information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Missing information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Rescue support" <?php
                                if (in_array('Rescue support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Rescue support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Dead body recover support" <?php
                                if (in_array('Dead body recover support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Dead body recover support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Claim compensation" <?php
                                if (in_array('Claim compensation', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Claim compensation</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Legal support" <?php
                                if (in_array('Legal support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Legal support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Project information" <?php
                                if (in_array('Project information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Project information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Training support" <?php
                                if (in_array('Training support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Training support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Loan support" <?php
                                if (in_array('Loan support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Loan support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Job placement" <?php
                                if (in_array('Job placement', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Job placement</span></label>
                            <label><input class="px" type="checkbox" id="newTypeService" name="type_service[]" value="Others" <?php
                                if (in_array('Others', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>
                <div id="newTypeServiceType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newTypeServiceText" name="new_type_service" value="<?php echo $pre_data['other_type_service'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newTypeService').is(':checked');

                        if (isChecked == true) {
                            $('#newTypeServiceType').show();
                        }

                        $("#newTypeService").on("click", function () {
                            $('#newTypeServiceType').toggle();
                        });
                    });
                </script>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Service recipient</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <?php
                            foreach ($this->type_recipient as $key => $value) :
                                $typeRecipient[] = $key;
                                ?>
                                <label><input class="px oldRecipient" type="radio" name="type_recipient" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['type_recipient'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                <?php
                            endforeach;
                            if ($pre_data['type_recipient']):
                                ?>
                                <label><input class="px" type="radio" name="type_recipient" <?php
                                    if (!in_array($pre_data['type_recipient'], $typeRecipient)): echo 'checked';
                                    endif;
                                    ?> id="newRecipient"><span class="lbl">Others, Please specify…</span></label>
                                          <?php else: ?>
                                <label><input class="px" type="radio" name="type_recipient" value="" id="newRecipient"><span class="lbl">Others</span></label> 
                            <?php endif ?>
                        </div>
                    </div>
                </div>   
                <div id="newRecipientType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newRecipientText" name="new_recipient" value="<?php echo $pre_data['type_recipient'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newRecipient').is(':checked');

                        if (isChecked == true) {
                            $('#newRecipientType').show();
                        }

                        $("#newRecipient").on("click", function () {
                            $('#newRecipientType').show();
                        });

                        $(".oldRecipient").on("click", function () {
                            $('#newRecipientType').hide();
                            $('#newRecipientText').val('');
                        });
                    });
                </script>
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
                                <label><input class="px oldGender" type="radio" name="gender" value="<?php echo $key ?>" <?php echo $pre_data && $pre_data['gender'] == $key ? 'checked' : '' ?>><span class="lbl"><?php echo $value ?></span></label>
                                <?php
                            endforeach;
                            if ($pre_data['gender']):
                                ?>
                                <label><input class="px" type="radio" name="gender" <?php
                                    if (!in_array($pre_data['gender'], $allGender)): echo 'checked';
                                    endif;
                                    ?> id="newGender"><span class="lbl">Other</span></label>
                                          <?php else : ?>
                                <label><input class="px" type="radio" name="gender" id="newGender" value=""><span class="lbl">Other</span></label>
                            <?php endif ?>
                        </div>
                    </div>
                </div>     
                <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['gender'] ?>">
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
                <?php
                $know_service = $know_service ? $know_service : array($know_service);
                ?> 
                <div class="form-group">
                    <label>How to know about this service of the project</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="know_service[]" value="IPT show" <?php
                                if (in_array('IPT show', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">IPT show</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Video show" <?php
                                if (in_array('Video show', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Video show</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="School quiz" <?php
                                if (in_array('School quiz', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">School quiz</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Palli somaj" <?php
                                if (in_array('Palli somaj', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Palli somaj</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="CTC" <?php
                                if (in_array('CTC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">CTC</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="DLAC" <?php
                                if (in_array('DLAC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">DLAC</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Social media" <?php
                                if (in_array('Social media', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Social media</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="IEC/BCC" <?php
                                if (in_array('IEC/BCC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">IEC/BCC</span></label>
                            <label><input class="px" type="checkbox" id="newKnowService" name="know_service[]" value="Others" <?php
                                if (in_array('Others', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>
                <div id="newKnowServiceType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newKnowServiceText" name="new_know_service" value="<?php echo $pre_data['other_know_service'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newKnowService').is(':checked');

                        if (isChecked == true) {
                            $('#newKnowServiceType').show();
                        }

                        $("#newKnowService").on("click", function () {
                            $('#newKnowServiceType').toggle();
                            $('#newKnowServiceText').val('');
                        });
                    });
                </script>
                <div class="form-group">
                    <label for="inputRemark">Remark</label>
                    <textarea class="form-control" name="remark"><?php echo $pre_data['remark']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_activity_management/manage_complains') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>