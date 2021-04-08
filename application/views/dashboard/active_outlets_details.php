


<?php //bcm ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <h2>Coverage</h2>
                <table class="table table-striped table-bordered table-hover " width="100%" >
                    <thead>
                        <tr>
                            <th>State</th>

                            <th class="text-center">MG</th>
                            <th class="text-center">Gemo</th>
                            <th class="text-center">UHD</th>

                            <th  class="text-center">Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($active_outlets as $outlet): ?>
                            <tr>

                                <td><?php echo $outlet->state_name; ?></td>

                                <td  class="text-center"><?php echo $outlet->mg; ?></td>
                                <td class="text-center"><?php echo $outlet->gemo; ?></td>
                                <td class="text-center"><?php echo $outlet->uhd; ?></td>

                                <td class="text-center"><?php echo $outlet->nb_state; ?></td>
                            </tr>

                        <?php endforeach; ?>

                        <tr>
                            <td>Total</td>
                            <td class="text-center"><?php echo $this->Outlet_model->get_number_outlet_by_classe('mg'); ?></td>
                            <td class="text-center"><?php echo $this->Outlet_model->get_number_outlet_by_classe('gemo'); ?></td>
                            <td class="text-center"><?php echo $this->Outlet_model->get_number_outlet_by_classe('uhd'); ?></td>
                            <td class="text-center"><?php echo $this->Outlet_model->get_number_outlet_by_classe(''); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Dispatching outlets by state</span>
                </div>
            </div>
            <div id="chartdiv" style="height:500px; width:100%;"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Dispatching Outlets by channel</span>
                </div>
            </div>
            <div id="chartdiv2" style="height:500px; width:100%;"></div>
        </div>
    </div>
</div>


<script>

    var chart = AmCharts.makeChart("chartdiv", {
        "type": "pie",
        "theme": "light",
        "dataProvider": <?php print_r($outlets_by_state); ?>,
        "valueField": "value",
        "titleField": "state",
        "outlineAlpha": 0.4,

        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",

        "export": {
            "enabled": true
        }
    });

    var chart = AmCharts.makeChart("chartdiv2", {
        "type": "pie",
        "theme": "light",
        "dataProvider": <?php print_r($outlets_by_channel); ?>,
        "valueField": "value",
        "titleField": "channel",
        "colorField": "color",
        "outlineAlpha": 0.4,

        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",

        "export": {
            "enabled": true
        }
    });

</script>


