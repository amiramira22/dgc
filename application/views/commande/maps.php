

<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});

    });
</script>


<!--<div class="row">
    <div class="col-md-12">
         BEGIN VALIDATION STATES
        <div class="portlet light">

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper"></span>
                </div>
            </div>  end portlet-title 

            <div class="portlet-body form">
                 BEGIN FORM

            </div>
             END VALIDATION STATES
        </div>
    </div>  END ROW FORM
</div>-->

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN MARKERS PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i>
                    <span class="caption-subject font-red bold uppercase"> daily visits</span>
                </div>

                <form method="post" action="maps" id="myForm">
                    <div class="col-md-4">
                        <input type="text" name="date" 
                               value="<?php echo $date; ?>" 
                               id="datepicker1" 
                               class="form-control" />		
                    </div>
                </form>


            </div>
            <div class="portlet-body">

                <?php echo $map['js']; ?>
                <?php echo $map['html']; ?>
            </div>
        </div>
        <!-- END MARKERS PORTLET-->
    </div>


</div>
<script>
    $(function () {
        $('#datepicker1').change(function () {
            this.form.submit();
        });
    });
</script>