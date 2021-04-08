
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

<link href="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.min.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('assets/plugins/fullcalendar/lib/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/fullcalendar/gcal.js'); ?>"></script>

<style>

    h2 {
        font-size: 20px;
    }
</style>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper">Fo Information</span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body">
                <div class="row ">
                    <div class="col-md-5">
                        <br>
                        <div id="calendar">
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="col-md-7" id="load_tab">

                    </div>
                </div>
            </div> <!-- end portlet-body form -->
        </div>  <!-- end portlet box blue -->
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 1-->








<script type="text/javascript">
    var date_last_clicked = null;

    $('#calendar').fullCalendar({

        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay',

        },
        titleFormat: {

        },
        buttonText: {
            prev: "",
            next: "",
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },

        eventSources: [
            {
                events: function (start, end, timezone, callback) {
                    $.ajax({

                        url: "<?php echo site_url('reports/get_events'); ?>",
                        dataType: 'json',

                        success: function (msg) {
                            var events = msg.events;
                            callback(events);
                            console.log(msg.events);
                        }
                    });
                }
            },
        ],

        dayClick: function (date, jsEvent, view) {
            date_last_clicked = $(this);
            $(this).css('background-color', '#bed7f3');

        },
        eventClick: function (event, jsEvent, view) {
            console.log(moment(event.start).format('YYYY-MM-DD'));
            $('#load_tab').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
            jQuery.ajax({
                url: "<?php echo site_url("reports/load_tab"); ?>",
                data: {date: moment(event.start).format('YYYY-MM-DD')},
                type: "POST",
                success: function (data) {

                    $('#load_tab').html(data);
                }
            });
        }


    });
    $(document).ready(function () {

        var d = new Date();
        var month = d.getMonth() + 1;
        var day = d.getDate();
        console.log(d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day);
        
        $('#load_tab').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
        jQuery.ajax({
            url: "<?php echo site_url("reports/load_tab"); ?>",
            data: {date: "<?php echo $default_date ?>"},
            type: "POST",
            success: function (data) {

                $('#load_tab').html(data);
            }
        });


    });


</script>