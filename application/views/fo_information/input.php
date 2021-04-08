<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({

            dateFormat: 'dd mm yyyy',
            multidate: true,
            daysOfWeekDisabled: [0],
            clearBtn: true,
            todayHighlight: true,
            daysOfWeekHighlighted: [1, 2, 3, 4, 5, 6],
            autoclose: false,
            altField: '#datepicker_alt',
            altFormat: 'yy-mm-dd'

        });

        $('.datepicker').on('changeDate', function (evt) {
            console.log(evt.date);
        });

        $('.btn').on('click', function () {
            var the_date = $('.datepicker:first').datepicker('getDates');
            console.log(the_date);
        });
    });
</script>

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

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'id="form"'); ?>
                <?php echo form_open('reports/save_fo_information', $attributes); ?>
                <div class="form-body">
                    <div class="form-group">


                        <div class="row ">
                            <!-- *****************************CONTROL VISIT *************************************************************** -->
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <div class="datepicker control-label" data-date-format="yyyy-mm-dd">
                                    <input type="hidden" name="date" id="datepicker_alt" required/>	
                                </div>
                            </div>

                            <div class="col-md-6">
                                <br>
                                <br>
                                <?php
                                $fo_ids = array();
                                $fo_ids[''] = 'All Field Officer';
                                foreach ($fos as $ad) {
                                    $fo_ids[$ad->id] = $ad->name;
                                }
                                ?>
                                <?php echo form_dropdown('fo_id', $fo_ids, set_value(), 'required id= "specific_fo" class="form-control" '); ?>

                                <br>                           
                                <!--    <option value="" disabled selected>Select your option</option>-->
                                <select name="type" class="form-control" required>
                                    <option value="" disabled selected>select item type</option>
                                    <option value="Holiday">Holiday</option>
                                    <option value="Routing">Routing</option>
                                    <option value="Authorization">Authorization</option>
                                </select>
                                <br>                               
                                <br>  
<!--                                <textarea name='note' form="form"rows="4" cols="60" placeholder="Votre note...."></textarea>-->
                                <?php
                                $data = array(
                                    'name' => 'note',
                                    'rows' => '4',
                                    'cols' => '50',
                                    'placeholder' => "Votre note....",
                                    'class' => 'form-control'
                                );
                                echo form_textarea($data);
                                ?>

                            </div>
                        </div>

                    </div>
                </div> <!-- end form-body -->

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-7 col-md-5">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Submit</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>

                </form>

            </div> <!-- end portlet-body form -->
        </div>  <!-- end portlet box blue -->
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 1-->

<script>
    $(document).ready(function () {
        $('#specific_fo').each(function () {
            $(this).children('option:first').attr("disabled", "disabled selected");
        });
    });
</script>