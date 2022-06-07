<?php

class dev_location_management {

    var $thsClass = 'dev_location_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Locations',
            'permissions' => array(
                'manage_locations' => array(
                    'add_location' => 'Add Location',
                    'edit_location' => 'Edit Location',
                    'delete_location' => 'Delete Location',
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
            'label' => 'Locations',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Country',
            'description' => 'Manage All Countries',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_countries',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'City',
            'description' => 'Manage All Cities',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_cities',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Division',
            'description' => 'Manage All Divisions',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_divisions',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'District',
            'description' => 'Manage All Districts',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_districts',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Upazila',
            'description' => 'Manage All Upazila',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_upazilas',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Police Staton',
            'description' => 'Manage All Police Statons',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_police_stations',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Post Office',
            'description' => 'Manage All Post Office',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_post_offices',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Municipality',
            'description' => 'Manage All Municipalites',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_municipalities',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'City Corporation',
            'description' => 'Manage All City Corporations',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_city_corporations',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);

        $params = array(
            'label' => 'Union',
            'description' => 'Manage All City Corporations',
            'menu_group' => 'Locations',
            'position' => 'default',
            'action' => 'manage_unions',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_locations'))
            admenu_register($params);
    }

    function manage_countries() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_countries');

        if ($_GET['action'] == 'add_edit_country')
            include('pages/add_edit_country.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteCountry.php');
        else
            include('pages/list_countries.php');
    }

    function manage_cities() {
        if (!has_permission('manage_cities'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_cities');

        if ($_GET['action'] == 'add_edit_city')
            include('pages/add_edit_city.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteCity.php');
        else
            include('pages/list_cities.php');
    }

    function manage_divisions() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_divisions');

        if ($_GET['action'] == 'add_edit_division')
            include('pages/add_edit_division.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteDivision.php');
        else
            include('pages/list_divisions.php');
    }

    function manage_districts() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_districts');

        if ($_GET['action'] == 'add_edit_district')
            include('pages/add_edit_district.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteDistrict.php');
        else
            include('pages/list_districts.php');
    }

    function manage_upazilas() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_upazilas');

        if ($_GET['action'] == 'add_edit_upazila')
            include('pages/add_edit_upazila.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteUpazila.php');
        else
            include('pages/list_upazilas.php');
    }

    function manage_police_stations() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_police_stations');

        if ($_GET['action'] == 'add_edit_police_station')
            include('pages/add_edit_police_station.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deletePoliceStation.php');
        else
            include('pages/list_police_stations.php');
    }

    function manage_post_offices() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_post_offices');

        if ($_GET['action'] == 'add_edit_post_office')
            include('pages/add_edit_post_office.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deletePostOffice.php');
        else
            include('pages/list_post_offices.php');
    }

    function manage_municipalities() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_municipalities');

        if ($_GET['action'] == 'add_edit_municipality')
            include('pages/add_edit_municipality.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteMunicipality.php');
        else
            include('pages/list_municipalities.php');
    }

    function manage_city_corporations() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_city_corporations');

        if ($_GET['action'] == 'add_edit_city_corporation')
            include('pages/add_edit_city_corporation.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteCityCorporation.php');
        else
            include('pages/list_city_corporations.php');
    }

    function manage_unions() {
        if (!has_permission('manage_locations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_unions');

        if ($_GET['action'] == 'add_edit_union')
            include('pages/add_edit_union.php');
        elseif ($_GET['action'] == 'deleteData')
            include('pages/deleteUnion.php');
        else
            include('pages/list_unions.php');
    }

    function get_countries($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM countries ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'id',
            'name' => 'nicename',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_country($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_countries(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['iso'] = ucfirst($params['form_data']['iso']);
            $insert_data['name'] = strtoupper($params['form_data']['name']);
            $insert_data['nicename'] = ucfirst($insert_data['name']);
            $insert_data['iso3'] = $params['form_data']['iso3'];
            $insert_data['numcode'] = $params['form_data']['numcode'];
            $insert_data['phonecode'] = $params['form_data']['phonecode'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM countries WHERE NOT id='$is_update' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('countries', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM countries WHERE name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('countries', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_cities($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM cities"
                . " LEFT JOIN countries ON (countries.id = cities.fk_country_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'cities.id',
            'country' => 'countries.id',
            'city' => 'cities.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_city($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_cities(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_country_id'] = ucfirst($params['form_data']['fk_country_id']);
            $insert_data['name'] = ucfirst($params['form_data']['name']);

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM cities WHERE NOT id='$is_update' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('cities', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM cities WHERE name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('cities', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_divisions($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_divisions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_division($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_divisions(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];
            $insert_data['url'] = $params['form_data']['url'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_divisions WHERE NOT id='$is_update' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_divisions', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_divisions WHERE name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_divisions', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_districts($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']):
            $from = "FROM bd_districts ";
        else:

            $from = "FROM bd_districts "
                    . "LEFT JOIN bd_divisions ON (bd_divisions.id = bd_districts.division_id) ";
        endif;
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_districts.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_districts.id',
            'division' => 'bd_districts.division_id',
            'district' => 'bd_districts.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_district($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_districts(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['division_id'] = $params['form_data']['division_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_districts WHERE NOT id='$is_update' AND division_id='" . $insert_data['division_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_districts', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_districts WHERE division_id='" . $insert_data['division_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_districts', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_upazilas($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_upazilas "
                . "LEFT JOIN bd_districts ON (bd_districts.id = bd_upazilas.district_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_upazilas.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_upazilas.id',
            'district' => 'bd_upazilas.district_id',
            'upazila' => 'bd_upazilas.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_upazila($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_upazilas(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['district_id'] = $params['form_data']['district_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_upazilas WHERE NOT id='$is_update' AND district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_upazilas', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_upazilas WHERE district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_upazilas', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_police_stations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_police_stations "
                . "LEFT JOIN bd_districts ON (bd_districts.id = bd_police_stations.district_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_police_stations.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_police_stations.id',
            'district' => 'bd_police_stations.district_id',
            'police_station' => 'bd_police_stations.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_police_station($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_police_stations(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['district_id'] = $params['form_data']['district_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_police_stations WHERE NOT id='$is_update' AND district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_police_stations', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_police_stations WHERE district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_police_stations', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_post_offices($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_post_offices "
                . "LEFT JOIN bd_districts ON (bd_districts.id = bd_post_offices.district_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_post_offices.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_post_offices.id',
            'district' => 'bd_post_offices.district_id',
            'post_office' => 'bd_post_offices.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_post_office($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_post_offices(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['district_id'] = $params['form_data']['district_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_post_offices WHERE NOT id='$is_update' AND district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_post_offices', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_post_offices WHERE district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_post_offices', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_municipalities($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_municipalities "
                . "LEFT JOIN bd_districts ON (bd_districts.id = bd_municipalities.district_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_municipalities.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_municipalities.id',
            'district' => 'bd_municipalities.district_id',
            'municipality' => 'bd_municipalities.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_municipality($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_municipalities(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['district_id'] = $params['form_data']['district_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_municipalities WHERE NOT id='$is_update' AND district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_municipalities', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_municipalities WHERE district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_municipalities', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_city_corporations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_city_corporations "
                . "LEFT JOIN bd_districts ON (bd_districts.id = bd_city_corporations.district_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_city_corporations.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_city_corporations.id',
            'district' => 'bd_city_corporations.district_id',
            'city_corporation' => 'bd_city_corporations.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_city_corporation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_city_corporations(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['district_id'] = $params['form_data']['district_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_city_corporations WHERE NOT id='$is_update' AND district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_city_corporations', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_city_corporations WHERE district_id='" . $insert_data['district_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_city_corporations', $insert_data);
                endif;
            }
        }
        return $ret;
    }

    function get_unions($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM bd_unions "
                . "LEFT JOIN bd_upazilas ON (bd_upazilas.id = bd_unions.upazilla_id) "
                . "LEFT JOIN bd_districts  ON (bd_districts.id = bd_upazilas.district_id) "
                . "LEFT JOIN bd_divisions  ON (bd_divisions.id = bd_districts.division_id) ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(bd_unions.id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'bd_unions.id',
            'upazila' => 'bd_unions.upazilla_id',
            'union' => 'bd_unions.name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $result = sql_data_collector($sql, $count_sql, $param);

        return $result;
    }

    function add_edit_union($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_unions(array('edit' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid ID, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['upazilla_id'] = $params['form_data']['upazila_id'];
            $insert_data['name'] = ucfirst($params['form_data']['name']);
            $insert_data['bn_name'] = $params['form_data']['bn_name'];

            if ($is_update) {
                $checkDb = $devdb->get_results("SELECT name FROM bd_unions WHERE NOT id='$is_update' AND upazilla_id='" . $insert_data['upazilla_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_unions', $insert_data, " id = '" . $is_update . "'");
                endif;
            } else {
                $checkDb = $devdb->get_results("SELECT name FROM bd_unions WHERE upazilla_id='" . $insert_data['upazilla_id'] . "' AND name ='" . $insert_data['name'] . "' ");
                if ($checkDb):
                    $ret['error'] = 'Already Exixts!';
                else:
                    $ret = $devdb->insert_update('bd_unions', $insert_data);
                endif;
            }
        }
        return $ret;
    }

}

new dev_location_management();
