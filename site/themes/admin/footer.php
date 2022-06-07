<!-- / #content-wrapper -->

</div> <!-- / #content-wrapper -->


<div id="main-menu-bg"></div>
</div> <!-- / #main-wrapper -->
<style type="text/css">
    footer{
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2px 0;
        background: #23272e;
        color: #fff;
        z-index: 999;
        margin-left: 240px;
        font-size: 10px;
        line-height: 12px;
    }
    footer .row{margin-bottom: 0}
    @media (max-width: 767px) {
        footer .text-right, footer .text-left { text-align:center }
        footer {margin-left: 56px}
    }
    @media (max-width: 480px) {
        footer {margin-left: 0}
    }

</style>
<footer>
    <div class="row">
        <div class="col-sm-6 text-left">Version: SB1221X002-01</div>
        <div class="col-sm-6 text-right">Built with <i class="fa fa-heart text-danger"></i> by <a href="https://3-devs.com/">3-DEVS IT LTD.</a></div>
    </div>
</footer>
<!-- Get jQuery from Google CDN -->
<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write('<script src="<?php echo theme_path() . '/assets/javascripts/jquery-2.0.3.min.js' ?>">' + "<" + "/script>");
</script>
<!-- <![endif]-->
<!--[if lte IE 9]>

<script type="text/javascript"> window.jQuery || document.write('<script src="<?php echo theme_path() . '/assets/javascripts/jquery-1.8.3.min.js' ?>">'+"<"+"/script>"); </script>
<![endif]-->

<script src="<?php echo theme_path() . '/assets/javascripts/jquery.validate.min.js' ?>"></script>

<!-- Pixel Admin's javascripts -->
<?php
minify_handler::$jsBundle['thirdParty'] = array(
    theme_path('absolute') . '/assets/javascripts/bootstrap.min.js',
    theme_path('absolute') . '/assets/javascripts/pixel-admin.min.js',
    common_files('absolute') . '/js/jquery.fancybox.min.js',
    common_files('absolute') . '/js/jquery-ui.min.js',
    common_files('absolute') . '/js/jQuery.print.js',
    common_files('absolute') . '/js/jquery.mjs.nestedSortable.js',
    common_files('absolute') . '/js/calendar.min.js',
);

minify_handler::renderMinifiedJs('thirdParty', false, 9);
?>
<script src="<?php echo common_files(); ?>/js/tinymce_4.7.13/tinymce.min.js"></script>
<script src="<?php echo common_files(); ?>/js/jcookies.js"></script>
<script src="<?php echo common_files(); ?>/js/common.js?v=51"></script>
<script src="<?php echo common_files(); ?>/js/tinyMce.js?v=5"></script>
<script src="<?php echo theme_path(); ?>/assets/javascripts/custom.js?v=70"></script>
<?php echo get_footer('admin'); ?>
<script type="text/javascript">
    $('.submit_in_new_tab').on('click', function () {
        $(this).closest('form').attr('target', '_blank');
    });
    $('.submit_in_current_tab').on('click', function () {
        $(this).closest('form').attr('target', '');
    });
    function fixFooter() {
        var footerH = $('footer').outerHeight();
        $('#content-wrapper').css('margin-bottom', footerH);
    }
    window.addEventListener('resize', function () {
        fixFooter();
    }, true);
    fixFooter
    $('.activeSortColumn').closest('th').addClass('bg-info');

    var bd_new_location_selector = function (opt) {
        var config = {
            'post_office': null,
            'police_station': null,
            'sub_district': null,
            'district': null,
            'division': null,
        };
        $.extend(true, config, opt);

        var currentDivision = config.division && typeof $(config.division).attr('data-selected') !== 'undefined' ? $(config.division).attr('data-selected') : null;
        var currentDistrict = config.district && typeof $(config.district).attr('data-selected') !== 'undefined' ? $(config.district).attr('data-selected') : null;
        var currentSubDistrict = config.sub_district && typeof $(config.sub_district).attr('data-selected') !== 'undefined' ? $(config.sub_district).attr('data-selected') : null;
        var currentPoliceStation = config.police_station && typeof $(config.police_station).attr('data-selected') !== 'undefined' ? $(config.police_station).attr('data-selected') : null;
        var currentPostOffice = config.post_office && typeof $(config.post_office).attr('data-selected') !== 'undefined' ? $(config.post_office).attr('data-selected') : null;

        var i = null;

        config.updateDistrict = function () {
            currentDivision = config.division ? config.division.val() : null;
            if (config.district)
                config.district.html('');
            if (config.district && currentDivision) {
                config.district.append('<option value="">Any</option>');
                for (i in BD_LOCATIONS[currentDivision]) {
                    config.district.append('<option value="' + i + '" ' + (currentDistrict && currentDistrict == i ? 'selected' : '') + '>' + i + '</option>');
                }
            }
            config.district.change();
        };

        config.updateSubDistrict = function () {
            currentDivision = config.division ? config.division.val() : null;
            currentDistrict = config.district ? config.district.val() : null;
            if (config.sub_district)
                config.sub_district.html('');
            if (config.sub_district && currentDivision && currentDistrict) {
                config.sub_district.append('<option value="">Any</option>');
                for (i in BD_LOCATIONS[currentDivision][currentDistrict]['sub-district']) {
                    var thisSubDistrict = BD_LOCATIONS[currentDivision][currentDistrict]['sub-district'][i];
                    config.sub_district.append('<option value="' + thisSubDistrict + '" ' + (currentSubDistrict && currentSubDistrict == thisSubDistrict ? 'selected' : '') + '>' + thisSubDistrict + '</option>');
                }
            }
        };

        config.updatePoliceStation = function () {
            currentDivision = config.division ? config.division.val() : null;
            currentDistrict = config.district ? config.district.val() : null;
            if (config.police_station)
                config.police_station.html('');

            if (config.police_station && currentDivision && currentDistrict) {
                config.police_station.append('<option value="">Any</option>');
                for (var i in BD_LOCATIONS[currentDivision][currentDistrict]['police-stations']) {
                    var thisPoliceStation = BD_LOCATIONS[currentDivision][currentDistrict]['police-stations'][i];
                    config.police_station.append('<option value="' + thisPoliceStation + '" ' + (currentPoliceStation && currentPoliceStation == thisPoliceStation ? 'selected' : '') + '>' + thisPoliceStation + '</option>');
                }
            }
        };

        config.updatePostOffice = function () {
            currentDivision = config.division ? config.division.val() : null;
            currentDistrict = config.district ? config.district.val() : null;
            if (config.post_office)
                config.post_office.html('');
            if (config.post_office && currentDivision && currentDistrict) {
                config.post_office.append('<option value="">Any</option>');
                for (i in BD_LOCATIONS[currentDivision][currentDistrict]['post-office']) {
                    var thisPostOffice = i;
                    config.post_office.append('<option value="' + thisPostOffice + '" ' + (currentPostOffice && currentPostOffice == thisPostOffice ? 'selected' : '') + '>' + thisPostOffice + '</option>');
                }
            }
        };

        if (config.division) {
            config.division.append('<option value="">Any</option>');
            for (i in BD_LOCATIONS) {
                config.division.append('<option value="' + i + '" ' + (currentDivision && currentDivision == i ? 'selected' : '') + '>' + i + '</option>');
            }
            config.division.on('change', function () {
                config.updateDistrict()
            });
        }

        if (config.district) {
            config.district.on('change', function () {
                config.updateSubDistrict();
                config.updatePoliceStation();
                config.updatePostOffice();
            });
        }

        if (config.division && currentDivision) {
            config.division.change();
        }
    };
    if ($('body').hasClass('fixed_footer')) {
        window.onresize = function () {
            $('#content-wrapper').css({
                'padding-bottom': ($('.footerWrapper').outerHeight() + 4) + 'px'
            });
            if ($('#main-menu').is(':visible'))
                $('.footerWrapper').css({
                    'left': ($('#main-menu').position().left + $('#main-menu').width())
                });
        }
    }
//disabling all SUBMIT buttons on click
    $('form.preventDoubleClick').each(function (i, e) {
        $(e).on('submit', function () {
            return preventDoubleClick($(e));
        });
    });
    init.push(function () {

        var _url = '<?php echo current_url(true); ?>';
        var full_url = '<?php echo current_url(); ?>';
        if ($('#main-menu-inner .navigation a[href="' + full_url + '"]').length)
            $('#main-menu-inner .navigation a[href="' + full_url + '"]').closest('li').addClass('active').closest('.mm-dropdown-root').addClass('open');
        else if ($('#main-menu-inner .navigation a[href="' + _url + '"]').length)
            $('#main-menu-inner .navigation a[href="' + _url + '"]').closest('li').addClass('active').closest('.mm-dropdown-root').addClass('open');
    });

    $('.dropdown-toggle').dropdown();
    $('.navbar-toggle').each(function (index, element) {
        var toggleClass = $(element).attr('data-toggle');
        var target = $($(element).attr('data-target'));
        $(element).click(function () {
            target.toggleClass(toggleClass);
        });
    });

    window.PixelAdmin.start(init);

    initCharLimit();

    $('.autoHeight').each(function (i, e) {
        $(e).autosize().css('resize', 'none');
    });

    if ($('.filter-panel').length) {
        $('<p style="height: 40px;"></p>').insertAfter('.filter-panel');
    }

    hideLoading();

    $('.smart_action_btn tr').on('mouseenter', function () {
        var ths = $(this);
        var actions = ths.find('.action_column').html();
        if (!$('#row_action_container').length)
            $('body').append('<div id="row_action_container"><div class="action_btn_background"></div></div>');
        var actionContainer = $('#row_action_container');
        actionContainer.find('.action_btn_background').html(actions);
        actionContainer.css('visibility', 'visible');
        var _left = ths.position().left;
        //var _right = _left + ths.width();
        var _top = ths.position().top - ths.outerHeight();
        console.log(ths.outerHeight(), _top);
        actionContainer.css({
            left: _left,
            top: _top,
            //right: _right,
            width: ths.width(),
        });
    });
    $('.smart_action_btn tr').on('mouseleave', function () {
        $('#row_action_container .action_btn_background').html('');
        $('#row_action_container').css('visibility', 'hidden');
    });

    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();
</script>
<?php
$pullNotifier = jack_obj('dev_pull_notification');
if ($pullNotifier && has_permission('receive_pull_notification')) {
    ?>
    <script type="text/javascript">
        var push_notification_sound = new Audio();
        push_notification_sound.src = '<?php echo common_files() . '/audio/push_notification.mp3'; ?>';
    </script>
    <?php
    $pullNotifier->pop_notification_js();
}
?>
<div class="floating_right_panel dn">
    <div class="floating_panel_handle"><i class="fa fa-chevron-circle-left"></i></div>
    <div class="floating_right_panel_content">

    </div>
</div>
<script type="text/javascript">
    $('.floating_panel_handle').on('click', function () {
        var ths = $(this);
        var container = ths.closest('.floating_right_panel');
        if (container.hasClass('floating_right_panel_open')) {
            container.removeClass('floating_right_panel_open');
            ths.find('i').addClass('fa-chevron-circle-left').removeClass('fa-chevron-circle-right');
        } else {
            container.addClass('floating_right_panel_open');
            ths.find('i').removeClass('fa-chevron-circle-left').addClass('fa-chevron-circle-right');
        }
    });
</script>
<style>
    .accordion-container{
        position: relative;
        max-width: 500px;
        height: auto;
    }
    .accordion-container > h2{
        text-align: center;
        color: #fff;
        padding-bottom: 5px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
    }
    .set{
        position: relative;
        width: 100%;
        height: auto;
        background-color: #f5f5f5;
    }
    .set > a{
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #555;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
        -webkit-transition:all 0.2s linear;
        -moz-transition:all 0.2s linear;
        transition:all 0.2s linear;
    }
    .set > a i{
        float: right;
        margin-top: 2px;
    }
    .set > a.active{
        background-color:#3399cc;
        color: #fff;
    }
    .content{
        margin-top: 1rem;
        display:none;
    }
    .content p{
        padding: 10px 15px;
        margin: 0;
        color: #333;
    }
</style>
<script>
    $(document).ready(function () {
        $(".set > a").on("click", function () {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this)
                        .siblings(".content")
                        .slideUp(200);
                $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
            } else {
                $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                $(this)
                        .find("i")
                        .removeClass("fa-plus")
                        .addClass("fa-minus");
                $(".set > a").removeClass("active");
                $(this).addClass("active");
                $(".content").slideUp(200);
                $(this)
                        .siblings(".content")
                        .slideDown(200);
            }
        });
    });
</script>
</body>
</html>