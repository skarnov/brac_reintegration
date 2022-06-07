<?php

class dev_knowledge_management {

    var $thsClass = 'dev_knowledge_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Knowledge Base',
            'permissions' => array(
                'manage_stories' => array(
                    'add_story' => 'Add Story',
                    'edit_story' => 'Edit Story',
                    'delete_story' => 'Delete Story',
                ),
                'manage_study_reports' => array(
                    'add_study_report' => 'Add Study Report',
                    'edit_study_report' => 'Edit Study Report',
                    'delete_study_report' => 'Delete Study Report',
                ),
                'manage_research_reports' => array(
                    'add_research_report' => 'Add Research Report',
                    'edit_research_report' => 'Edit Research Report',
                    'delete_research_report' => 'Delete Research Report',
                ),
                'manage_assessment_reports' => array(
                    'add_assessment_report' => 'Add Assessment Report',
                    'edit_assessment_report' => 'Edit Assessment Report',
                    'delete_assessment_report' => 'Delete Assessment Report',
                ),
                'manage_organograms' => array(
                    'add_organogram' => 'Add Organogram',
                    'edit_organogram' => 'Edit Organogram',
                    'delete_organogram' => 'Delete Organogram',
                ),
                'manage_meeting_minutes' => array(
                    'add_meeting_minute' => 'Add Meeting Minute',
                    'edit_meeting_minute' => 'Edit Meeting Minute',
                    'delete_meeting_minute' => 'Delete Meeting Minute',
                ),
                'manage_project_documents' => array(
                    'add_project_document' => 'Add Project Document',
                    'edit_project_document' => 'Edit Project Document',
                    'delete_project_document' => 'Delete Project Document',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Knowledge Base',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Success Story',
            'description' => 'Manage All Success Stories',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_stories',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_stories'))
            admenu_register($params);

        $params = array(
            'label' => 'Case Study',
            'description' => 'Manage All Case Studies',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_study_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_study_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Research Report',
            'description' => 'Manage All Research Reports',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_research_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_research_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project Report',
            'description' => 'Manage All Project Reports',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_assessment_reports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_assessment_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Organogram',
            'description' => 'Manage All Organograms',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_organograms',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_organograms'))
            admenu_register($params);

        $params = array(
            'label' => 'Meeting Minutes',
            'description' => 'Manage All Meeting Minutes',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_meeting_minutes',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meeting_minutes'))
            admenu_register($params);

        $params = array(
            'label' => 'Project Documents',
            'description' => 'Manage All Project Documents',
            'menu_group' => 'Knowledge Base',
            'position' => 'default',
            'action' => 'manage_project_documents',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_project_documents'))
            admenu_register($params);
    }

    function manage_stories() {
        if (!has_permission('manage_stories'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_stories');

        if ($_GET['action'] == 'add_edit_story')
            include('pages/add_edit_story.php');

        elseif ($_GET['action'] == 'deleteStory')
            include('pages/deleteStory.php');
        else
            include('pages/list_stories.php');
    }

    function manage_study_reports() {
        if (!has_permission('manage_study_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_study_reports');

        if ($_GET['action'] == 'add_edit_study_report')
            include('pages/add_edit_study_report.php');

        elseif ($_GET['action'] == 'deleteStudyReport')
            include('pages/deleteStudyReport.php');
        else
            include('pages/list_study_reports.php');
    }

    function manage_research_reports() {
        if (!has_permission('manage_research_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_research_reports');

        if ($_GET['action'] == 'add_edit_research_report')
            include('pages/add_edit_research_report.php');

        elseif ($_GET['action'] == 'deleteResearchReport')
            include('pages/deleteResearchReport.php');
        else
            include('pages/list_research_reports.php');
    }

    function manage_assessment_reports() {
        if (!has_permission('manage_assessment_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_assessment_reports');

        if ($_GET['action'] == 'add_edit_assessment_report')
            include('pages/add_edit_assessment_report.php');

        elseif ($_GET['action'] == 'deleteAssessmentReport')
            include('pages/deleteAssessmentReport.php');
        else
            include('pages/list_assessment_reports.php');
    }

    function manage_organograms() {
        if (!has_permission('manage_organograms'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_organograms');

        if ($_GET['action'] == 'add_edit_organogram')
            include('pages/add_edit_organogram.php');

        elseif ($_GET['action'] == 'deleteOrganogram')
            include('pages/deleteOrganogram.php');
        else
            include('pages/list_organograms.php');
    }

    function manage_meeting_minutes() {
        if (!has_permission('manage_meeting_minutes'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meeting_minutes');

        if ($_GET['action'] == 'add_edit_meeting_minute')
            include('pages/add_edit_meeting_minute.php');

        elseif ($_GET['action'] == 'deleteMeetingMinute')
            include('pages/deleteMeetingMinute.php');
        else
            include('pages/list_meeting_minutes.php');
    }

    function manage_project_documents() {
        if (!has_permission('manage_project_documents'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_project_documents');

        if ($_GET['action'] == 'add_edit_project_document')
            include('pages/add_edit_project_document.php');

        elseif ($_GET['action'] == 'deleteProjectDocument')
            include('pages/deleteProjectDocument.php');
        else
            include('pages/list_project_documents.php');
    }

    function get_lookups($lookup_group) {
        $sql = "SELECT * FROM dev_lookups WHERE lookup_group = '$lookup_group'";
        $data = sql_data_collector($sql);
        return $data;
    }

    function get_knowledge($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_knowledge"
                . " LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_knowledge.fk_project_id)";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_knowledge.pk_knowledge_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_knowledge.pk_knowledge_id',
            'name' => 'dev_knowledge.name',
            'project' => 'dev_knowledge.fk_project_id',
            'tags' => 'dev_knowledge.tags',
            'type' => 'dev_knowledge.type',
            'create_date' => 'dev_knowledge.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $knowledges = sql_data_collector($sql, $count_sql, $param);
        return $knowledges;
    }

    function add_edit_story($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid story id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'story';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'success_story',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_study_report($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid study report id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'study';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'success_study_report',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_research_report($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid research report id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'research';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'success_research_report',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_assessment_report($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid assessment report id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'assessment';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'success_assessment_report',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_organogram($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid organogram id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'organogram';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'success_organogram',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_meeting_minute($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid meeting minute id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'meeting_minute';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'meeting_minute',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_project_document($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_knowledge(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid project document id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $knowledge_data = array();

            if ($params['form_data']['new_tag'] == NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $data_types;
            } elseif ($params['form_data']['tags'] == NULL) {
                $knowledge_data['tags'] = $params['form_data']['new_tag'];
            } elseif ($params['form_data']['tags'] != NULL && $params['form_data']['new_tag'] != NULL) {
                $data_type = $params['form_data']['tags'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $knowledge_data['tags'] = $params['form_data']['new_tag'] . ',' . $data_types;
            }

            $knowledge_data['type'] = 'project_document';
            $knowledge_data['name'] = $params['form_data']['name'];
            $knowledge_data['fk_project_id'] = $params['form_data']['fk_project_id'];

            if ($_FILES['document_file']['name']) {
                $supported_ext = array('jpg', 'png', 'pdf', 'docx');
                $max_filesize = 512000;
                $target_dir = _path('uploads', 'absolute') . "/";
                if (!file_exists($target_dir))
                    mkdir($target_dir);
                $target_file = $target_dir . basename($_FILES['document_file']['name']);
                $fileinfo = pathinfo($target_file);
                $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if (in_array(strtolower($imageFileType), $supported_ext)) {
                    if ($max_filesize && $_FILES['document_file']['size'] <= $max_filesize) {
                        if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
                            $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                            $knowledge_data['document_file'] = '';
                        } else {
                            $fileinfo = pathinfo($target_file);
                            $knowledge_data['document_file'] = $fileinfo['basename'];
                            @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['document_old_file']);
                        }
                    } else
                        $ret['error'][] = 'Upload : <strong>' . $_FILES['document_file']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
                } else
                    $ret['error'][] = 'Upload : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
            } else {
                $knowledge_data['document_file'] = $params['form_data']['document_old_file'];
            }

            if ($is_update) {
                $knowledge_data['update_date'] = date('Y-m-d');
                $knowledge_data['update_time'] = date('H:i:s');
                $knowledge_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_update'] = $devdb->insert_update('dev_knowledge', $knowledge_data, " pk_knowledge_id = '" . $is_update . "'");
            } else {
                $knowledge_data['create_date'] = date('Y-m-d');
                $knowledge_data['create_time'] = date('H:i:s');
                $knowledge_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['knowledge_insert'] = $devdb->insert_update('dev_knowledge', $knowledge_data);
            }

            if ($params['form_data']['new_tag']) {
                $data = array(
                    'fk_content_id' => $ret['knowledge_insert']['success'] ? $ret['knowledge_insert']['success'] : $is_update,
                    'lookup_group' => 'project_document',
                    'lookup_value' => $params['form_data']['new_tag'],
                );
                $ret['knowledge_lookup'] = $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

}

new dev_knowledge_management();
