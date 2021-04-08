

<?php echo form_open('visits/bulk_save/' . $id); ?>

<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <input class="btn btn-circle btn-lg red" type="submit" value="Save"/> <i class="m-icon-big-swapdown m-icon-white"></i> 
            <!--<a href="javascript:;" class="btn btn-circle btn-lg blue"> Save
          <i class="fa fa-save"></i>
     </a>
            -->
            </a>
        </div>

    </div>
</div>
</br>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->


        <div class="table-scrollable">
            <table class="search-table table table-bordered table-striped table-condensed flip-content">
                <thead>
                    <tr>
                        <th> Brand</th>
                        <th> Model</th>

                        <?php if ($monthly == 0) { ?>
                            <th colspan="2"> Av</th>
                        <?php } ?>

                        <?php if ($monthly == 1 || $monthly == 3) { ?>
                            <th > Shelf</th>
                        <?php } ?>

                        <?php if ($monthly == 2 || $monthly == 3) { ?>
                            <th > Price</th>
                        <?php } ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($models as $model):
                        //print_r($model);
                        $shelf = $model->shelf;
                        $price = $model->price;
                        ?>
                        <tr>
                            <td><?php echo $model->brand_name; ?></td>


                            <td>
                                <?php
                                //if monthly == 0 product_name is product 
                                //if monthly == 1 2 3  product_name is product group name
                                echo $model->product_name;

                                if ($monthly == 0) {

                                    echo form_input(array(
                                        'type' => 'hidden',
                                        'name' => 'model[' . $model->id . '][product_id]',
                                        'value' => form_decode($model->product_id)
                                    ));
                                } else {

                                    echo form_input(array(
                                        'type' => 'hidden',
                                        'name' => 'model[' . $model->id . '][product_group_id]',
                                        'value' => form_decode($model->product_group_id)
                                    ));
                                }
                                ?>

                                <?php
                                echo form_input(array(
                                    'type' => 'hidden',
                                    'name' => 'model[' . $model->id . '][brand_id]',
                                    'value' => form_decode($model->brand_id)
                                ));
                                ?>

                            </td>

                            <?php if ($monthly == 0) { ?>
                                <?php if ($model->brand_id == 1) { ?>
                                    <td>
                                        <?php
                                        echo form_input(array('type' => 'hidden', 'name' => 'model[' . $model->id . '][sku_display]', 'value' => form_decode($model->sku_display), 'class' => 'form-control'));
                                        echo form_input(array('id' => 'model_' . $model->product_id, 'name' => 'model[' . $model->id . '][av]', 'value' => form_decode($model->av), 'type' => 'hidden', 'class' => 'span1'));
                                        ?>

                                        <input type="radio" name=<?php echo 'model[' . $model->id . '][av]'; ?> value="0"  <?php echo ($model->av == 0) ? 'checked' : ''; ?>     onClick="document.getElementById('model_<?php echo $model->product_id; ?>').value = this.value"  /> 
                                        <FONT color="red"><b>OOS</b></FONT>
                                    </td>
                                    <td>
                                        <input type="radio" name=<?php echo 'model[' . $model->id . '][av]'; ?> value="1"  <?php echo ($model->av == 1) ? 'checked' : ''; ?>     onClick="document.getElementById('model_<?php echo $model->product_id; ?>').value = this.value"  /> 
                                        <FONT color="green"><b>AV </b></FONT>
                                    </td>

                                <?php } else { ?>
                                    <td colspan="2">           
                                        <?php
                                        echo form_input(array('id' => 'model_' . $model->product_id, 'name' => 'model[' . $model->id . '][av]', 'value' => form_decode($model->av), 'type' => 'hidden', 'class' => 'span1'));
                                        echo form_input(array('id' => 'model_' . $model->product_id, 'name' => 'model[' . $model->id . '][nb_sku]', 'value' => form_decode($model->nb_sku), 'type' => 'hidden', 'class' => 'span1'));

                                        echo form_input(array('type' => 'text', 'name' => 'model[' . $model->id . '][sku_display]', 'value' => form_decode($model->sku_display), 'class' => 'form-control'));
                                        ?>
                                    </td>
                                <?php } ?>
                            <?php } ?>

                            <?php if ($monthly == 1 || $monthly == 3) { ?>
                                <td>
                                    <?php
                                    echo form_input(array(
                                        'name' => 'model[' . $model->id . '][shelf]',
                                        'value' => form_decode($shelf),
                                        'class' => 'form-control'
                                    ));
                                    ?>
                                </td>
                            <?php } ?>

                            <?php if ($monthly == 2 || $monthly == 3) { ?>
                                <td>
                                    <?php
                                    echo form_input(array(
                                        'name' => 'model[' . $model->id . '][price]',
                                        'value' => form_decode($price),
                                        'class' => 'form-control'
                                    ));
                                    ?>

                                    <?php
                                    echo form_input(array(
                                        'type' => 'hidden',
                                        'name' => 'model[' . $model->id . '][nb_sku]',
                                        'value' => form_decode($model->nb_sku),
                                        'class' => 'form-control'
                                    ));
                                    ?>
                                </td>

                            <?php } ?>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>            
    </div> <!--end col-md-12-->
</div> <!--end row-->

</br>


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <input class="btn btn-circle btn-lg red" type="submit" value="Save"/> <i class="m-icon-big-swapdown m-icon-white"></i> 
            <!--<a href="javascript:;" class="btn btn-circle btn-lg blue"> Save
          <i class="fa fa-save"></i>
     </a>
            -->
            </a>
        </div>

    </div>
</div>




</form>

<style >

    input[type="radio"] {
        width:1.5em;
        height:1.5em;
    }

    @media screen and (max-width: 700px) {

        table input.form-control {
            width: 60px;
        }

    }
</style>

<script>
    /**
     **options to have following keys:
     **searchText: this should hold the value of search text
     **searchPlaceHolder: this should hold the value of search input box placeholder
     **/
    (function ($) {
        $.fn.tableSearch = function (options) {
            if (!$(this).is('table')) {
                return;
            }
            var tableObj = $(this),
                    searchText = (options.searchText) ? options.searchText : 'Search: ',
                    searchPlaceHolder = (options.searchPlaceHolder) ? options.searchPlaceHolder : '',
                    divObj = $('<div style="float:right; " >' + searchText + '</div>'),
                    inputObj = $('<input type="text" class="form-control "placeholder="' + searchPlaceHolder + '" /><br />'),
                    caseSensitive = (options.caseSensitive === true) ? true : false,
                    searchFieldVal = '',
                    pattern = '';
            inputObj.off('keyup').on('keyup', function () {
                searchFieldVal = $(this).val();
                pattern = (caseSensitive) ? RegExp(searchFieldVal) : RegExp(searchFieldVal, 'i');
                tableObj.find('tbody tr').hide().each(function () {
                    var currentRow = $(this);
                    currentRow.find('td').each(function () {
                        if (pattern.test($(this).html())) {
                            currentRow.show();
                            return false;
                        }
                    });
                });
            });
            tableObj.before(divObj.append(inputObj));
            return tableObj;
        }
    }(jQuery));
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('table.search-table').tableSearch({
            searchText: 'Search Table',
            searchPlaceHolder: 'Input Value'
        });
    });
</script>






