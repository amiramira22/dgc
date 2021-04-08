<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<style>
    td{
        text-align: center !important;
        width: 125px !important;
    }

    th{

        text-align: center !important;
        width: 125px !important;
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
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('commande_report/nbr_cde_per_fo', $attributes) ?>
                <div class="form-body">

                    <div class="form-group">

                        <label class="control-label col-md-2">From </label>
                        <div class="col-md-4">
                            <input type="text" name="start_date" 
                                   value="<?php echo $start_date; ?>" 
                                   id="datepicker1" 
                                   class="form-control" />		
                        </div>

                        <label class="control-label col-md-2">To</label>
                        <div class="col-md-4">
                            <input type="text" name="end_date" 
                                   value="<?php echo $end_date; ?>" 
                                   id="datepicker2" 
                                   class="form-control" />		
                        </div>
                    </div>
                </div> <!--  end form-body-->

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>

                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
</div> <!-- END ROW FORM-->

<?php if ($data) { ?>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet light">

                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-magnifier font-red"></i>
                        <span class="caption-subject bold uppercase"> Historique des Cde per FO</span>
                        <span class="caption-helper"></span>
                    </div>
                </div> <!-- end portlet-title -->

                <div class="portlet-body form">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" width="100%" >
                            <thead>
                                <tr>
                                    <th  align="center">FO</th>
                                    <th  align="center">Nbre Cde</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($data as $row) {
                                ?>
                                <tbody>
                                    <tr>
                                        <td  align="center"><?php echo $row->fo; ?></td>
                                        <td  align="center"><?php echo $row->nbr_cde; ?></td>
                                    </tr>
                                </tbody>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<?php //print_r($data); ?>