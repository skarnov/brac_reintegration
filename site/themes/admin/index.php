<?php
ini_set('max_execution_time', 3000);

load_js(array(
    common_files() . '/js/jcookies.js',
));
if ($_POST['ajax_type'] == 'save_dashboard_widget_order') {
    if (has_permission('save_dashboard_widget_order_for_system')) {
        $sql = "DELETE FROM dev_config WHERE config_name = 'dashboard_widget_order' ";
        $deleted = $devdb->query($sql);
        $insertOrder = array(
            'config_name' => 'dashboard_widget_order',
            'config_value' => json_encode($_POST['order']),
        );
        $inserted = $devdb->insert_update('dev_config', $insertOrder);
        removeCache('devConfig');
        echo json_encode(array('success' => 1));
    } else
        echo json_encode(array('error' => array('Not enough permission')));
    exit();
}

$manCustomer = "SELECT COUNT(pk_customer_id) AS manCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age > 18";
$manCustomer = $devdb->get_row($manCustomer)['manCustomer'];

$boyCustomer = "SELECT COUNT(pk_customer_id) AS boyCustomer FROM dev_customers WHERE customer_gender = 'male' AND customer_age < 18";
$boyCustomer = $devdb->get_row($boyCustomer)['boyCustomer'];

$womanCustomer = "SELECT COUNT(pk_customer_id) AS womanCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age > 18";
$womanCustomer = $devdb->get_row($womanCustomer)['womanCustomer'];

$girlCustomer = "SELECT COUNT(pk_customer_id) AS girlCustomer FROM dev_customers WHERE customer_gender = 'female' AND customer_age < 18";
$girlCustomer = $devdb->get_row($girlCustomer)['girlCustomer'];


$returneeManager = jack_obj('dev_customer_management');
$totalReturneeCustomer = $returneeManager->get_customers(array('select_fields' => array(
        'pk_customer_id' => 'dev_customers.pk_customer_id',
    ), 'listing' => TRUE, 'count_only' => true));

$projectManagement = jack_obj('dev_project_management');

$projects = $projectManagement->get_projects();
$totalProjects = $projects['total'];
$projects = $projects['data'];

$projectSupportCount = $devdb->get_results("SELECT fk_project_id, COUNT(*) AS total FROM dev_supports GROUP BY fk_project_id;", 'fk_project_id');

class stateTableLumenStats {

    private $pointer;
    private $stats;
    private $totalStats;

    function __construct() {
        $this->pointer = 0;
        $this->stats = ['', 'darken', 'darker'];
        $this->totalStats = count($this->stats);
    }

    function next() {
        $next = $this->stats[$this->pointer];
        $this->pointer++;
        if ($this->pointer == $this->totalStats)
            $this->pointer = 0;
        return $next;
    }

}

$supportsWithStatus = $devdb->get_results("SELECT support_name, support_status, COUNT(*) AS total FROM dev_supports GROUP BY support_name, support_status;");
$economicSupport = array('total' => 0, 'ongoing' => 0, 'completed' => 0, 'evaluated' => 0);
$psychoSupport = array('total' => 0, 'ongoing' => 0, 'completed' => 0, 'evaluated' => 0);
$socialSupport = array('total' => 0, 'ongoing' => 0, 'completed' => 0, 'evaluated' => 0);
if ($supportsWithStatus) {
    foreach ($supportsWithStatus as $i => $v) {
        if ($v['support_name'] == 'economic') {
            $economicSupport['data'] = $v;
            $economicSupport['total'] += $v['total'];
            $economicSupport[$v['support_status']] += $v['total'];
        } else if ($v['support_name'] == 'psychosocial') {
            $psychoSupport['data'] = $v;
            $psychoSupport['total'] += $v['total'];
            $psychoSupport[$v['support_status']] += $v['total'];
        } else if ($v['support_name'] == 'social') {
            $socialSupport['data'] = $v;
            $socialSupport['total'] += $v['total'];
            $socialSupport[$v['support_status']] += $v['total'];
        }
    }
}
$first_day = date('Y-m-01');
$last_day = date('Y-m-t');

$sql = "SELECT invoice_date, sale_total FROM dev_sales WHERE invoice_date BETWEEN '$first_day' AND '$last_day'";
$results = $devdb->get_results($sql);

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

include('header.php');
echo $notify_user->get_notification();
?>
<?php
if (has_permission('access_to_dashboard')) {
    ?>
    <div class="row">
        
        
        
    <div class="panel panel-primary">
      <div class="panel-heading">Panel with panel-primary class</div>
      <div class="panel-body">Panel Content</div>
    </div>   
        <div class="panel panel-primary">
      <div class="panel-heading">Panel with panel-primary class</div>
      <div class="panel-body">Panel Content</div>
    </div>
        
        
        
        <div class="col-sm-6">
            <div class="accordion-container">
                <div class="set">
                    <a href="#">
                        Filter 
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="content">
                        <form class="" role="form" name="filter_form" method="get" action="dev_customer_management/manage_customers">
                            <div class="form-group col-sm-4">
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
                            <div class="form-group col-sm-4">
                                <label>District</label>
                                <div class="select2-primary">
                                    <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
                                        <?php if ($filter_district) : ?>
                                            <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Upazila</label>
                                <div class="select2-primary">
                                    <select class="form-control subdistrict" name="sub_district" id="subdistrictList" style="text-transform: capitalize">
                                        <?php if ($filter_sub_district) : ?>
                                            <option value="<?php echo $filter_sub_district ?>"><?php echo $filter_sub_district ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Union</label>
                                <div class="select2-primary">
                                    <select class="form-control union" name="union" id="unionList" style="text-transform: capitalize">
                                        <?php if ($filter_union) : ?>
                                            <option value="<?php echo $filter_union ?>"><?php echo $filter_union ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-xs btn-success btn-flat btn-labeled"><i class="btn-label fa fa-filter"></i>FILTER</button>
                                <button type="button" class="btn btn-xs btn-danger btn-flat btn-labeled" onclick="clearFilters(this);"><i class="btn-label fa fa-trash"></i>CLEAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="stat-panel">
                <div class="stat-row">
                    <div class="stat-cell bg-color-dark-blue darker">
                        <i class="fa fa-lightbulb-o bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
                        <span class="text-xlg"><?php echo ($manCustomer + $womanCustomer + $boyCustomer + $girlCustomer) ?></span><br>
                        <span class="text-bg">Total Participants</span>
                    </div>
                </div>
                <div class="stat-row">
                    <div class="stat-counters bg-primary no-border-b no-padding text-center">
                        <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>" class="stat-cell col-xs-4 padding-sm no-padding-hr">
                            <span class="text-bg"><strong><?php echo $manCustomer ?></strong></span><br>
                            <span class="text-xs">Man</span>
                        </a>
                        <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                            <span class="text-bg"><strong><?php echo $womanCustomer ?></strong></span><br>
                            <span class="text-xs">Woman</span>
                        </a>
                        <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                            <span class="text-bg"><strong><?php echo $boyCustomer ?></strong></span><br>
                            <span class="text-xs">Boy</span>
                        </a>
                        <a target="_blank" href="<?php echo url('dev_customer_management/manage_customers') ?>"  class="stat-cell col-xs-4 padding-sm no-padding-hr">
                            <span class="text-bg"><strong><?php echo $girlCustomer ?></strong></span><br>
                            <span class="text-xs">Girl</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>



    </div>


















    <div class="dashboard_widgets block-area row">
        <?php echo $adminWidgets->render_widgets(); ?>
    </div>
    <style type="text/css">
        .widget_extender {
            position: absolute;
            left: 0;
            bottom: 0;
            right: 0;
            z-index: 999;
            background-color: rgba(0,0,0,.5);
            color: #fff;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }
        .dashboard_widgets .dashboardWidgetSortHandle{
            position: absolute;
            top: 0;
            left: 11px;
            bottom: 22px;
            width: 20px;
            background: #cecdcd;
            cursor: move;
        }
        dashboard_widgets .dashboardWidgetSortHandle:hover{
            background: #9e9e9e;
        }
        .dashboard_widgets .panel{
            *margin-left: 20px;
            -webkit-animation: all 1s;
            -o-animation: all 1s;
            animation: all 1s;
        }
        .dashboard_widgets .panel.shortened{
            height:350px !important;
            overflow: hidden;
        }
        .dashboard_widgets .panel.extended .panel-body{
            padding-bottom: 40px !important;
        }
        .dashboard_widget{

            float: left;
            box-sizing: border-box;
        }
        .widget_placeholder{
            background: repeating-linear-gradient( -45deg, #eeeeee, #eeeeee 2px, #ffffff 10px, #ffffff 15px );
            float:left;
            box-sizing: border-box;
            /*margin: 10px;*/
        }
    </style>
    <script type="text/javascript">
        var save_dashboard_widget_order_for_system = <?php echo has_permission('save_dashboard_widget_order_for_system') ? 1 : 0 ?>;
        var widget_update_notification_displayed = false;
        init.push(function () {
            $(document).off('click').on('click', '.widget_extender', function () {
                var ths = $(this);
                var panel_ = $(this).closest('.panel');
                if (panel_.hasClass('extended')) {
                    //need to short
                    panel_.removeClass('extended').addClass('shortened');
                    ths.find('.widget_extender_text').html('<i class="fa fa-hand-o-down"></i>&nbsp;EXPAND');
                } else {
                    //need to extended
                    panel_.removeClass('shortened').addClass('extended');
                    ths.find('.widget_extender_text').html('<i class="fa fa-hand-o-up"></i>&nbsp;SHORTEN');
                }
            });
            function init_widget_extender() {
                $('.dashboard_widgets .dashboard_widget:not(.col-sm-12)').each(function (index, element) {
                    var elm = $(element).find('>.panel');
                    if (elm.find('.widget_extender').length)
                        return true;
                    elm.append('<div class="widget_extender"><span class="widget_extender_text"><i class="fa fa-hand-o-down"></i>&nbsp;EXPAND</span></div>');
                    elm.addClass('shortened');
                });
            }
            $(document).ready(function () {
                init_widget_extender();

                var total = $('.dashboard_widgets .dashboard_widget').length;

                var system_sort_order = <?php echo isset($_config['dashboard_widget_order']) && strlen($_config['dashboard_widget_order']) ? $_config['dashboard_widget_order'] : "''" ?>;
                var user_sort_order = $.jCookies({get: 'sort_order', error: true});
                // var size_order = $.jCookies({ get : 'size_order', error: true});
                //console.log(size_order);
                var len = 0, i = 0;
                if (user_sort_order.length) {
                    len = user_sort_order.length;
                    for (i = 0; i < len; i++) {
                        $(".dashboard_widgets .dashboard_widget:eq(" + i + ")").before($('.dashboard_widgets .dashboard_widget[data-id="' + user_sort_order[i] + '"]'));
                    }
                } else if (system_sort_order.length) {
                    len = system_sort_order.length;
                    for (i = 0; i < len; i++) {
                        $(".dashboard_widgets .dashboard_widget:eq(" + i + ")").before($('.dashboard_widgets .dashboard_widget[data-id="' + system_sort_order[i] + '"]'));
                    }
                }

    <?php if (getProjectSettings('features,dashboard_widget_sortable') && has_permission('sort_dashboard_widget')): ?>
                    $('.dashboard_widgets').sortable({
                        handle: ".dashboardWidgetSortHandle",
                        cursor: "auto",
                        placeholder: 'widget_placeholder',
                        tolerance: "pointer",
                        start: function (event, ui) {
                            $('.widget_placeholder').css({
                                height: ui.item.height() - 30,
                                width: ui.item.width() - 30,
                                opacity: .5
                            });
                        },
                        update: function (event, ui) {
                            var total = $('.dashboard_widgets .dashboard_widget').length;
                            var order = [];
                            for (var i = 0; i < total; i++) {
                                var elm = $('.dashboard_widgets .dashboard_widget')[i];
                                elm = $(elm);
                                order[i] = elm.attr('data-id');
                            }

                            $.jCookies({
                                name: 'sort_order',
                                value: order
                            });
                            if (save_dashboard_widget_order_for_system) {
                                $.growl.notice({title: 'SAVED', message: 'Do you want to save current layout for overall system?<br /><span class="btn btn-xs btn-warning save_dashboard_widget_order_for_system">YES</span>', location: 'br', size: 'large'});
                            }
                        }
                    });
    <?php endif; ?>
                $(document).on('click', '.growl-close', function () {
                    if ($(this).closest('.growl').find('.widget_update_notification_displayed'))
                        widget_update_notification_displayed = false;
                });
    <?php if (getProjectSettings('features,dashboard_widget_sortable')): ?>
                    $(document).on('click', '.save_dashboard_widget_order_for_system', function () {
                        var order = $.jCookies({get: 'sort_order', error: true});
                        if (order) {
                            $.ajax({
                                url: _root_path_ + '/',
                                type: 'post',
                                //dataType: 'json',
                                data: {
                                    ajax_type: 'save_dashboard_widget_order',
                                    order: order
                                },
                                success: function (ret) {
                                    console.log(ret);
                                    if (ret.success) {
                                        $.growl.notice({message: 'Widget order saved for overall system.'});
                                    }
                                }
                            });
                        }
                    });
    <?php endif; ?>
            });
        });
    </script>
    <?php
} else {
    ?>

    <?php
}
?>
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
<?php
include('footer.php');
?>
