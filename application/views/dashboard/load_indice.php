<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    
    
    <div class="dashboard-stat2 ">
        <div class="display">
            <div class="number">
                <h3 class="font-green-sharp">
                    <span data-counter="counterup" data-value="<?php echo $active_outlets ?>"><?php echo $active_outlets ?></span>
                </h3>
                <small>Active outlets</small>
            </div>

            <div class="icon">
                <a class="more" target="_blank" href="<?php echo site_url('dashboard/outlets_details'); ?>"> <i class="icon-home" title="View more"></i> </a>
            </div>
        </div>
        <div class="progress-info">
            <div class="progress">
                <span style="width:<?php echo ($active_outlets / $all_outlets) * 100; ?>%;" class="progress-bar progress-bar-success green-sharp">
                    <span class="sr-only">85% change</span>
                </span>
            </div>
            <div class="status">
                <div class="status-title">  </div>
                <div class="status-number"> <?php //echo number_format(($active_outlets / $all_outlets) * 100, 2, '.', '') . '%'; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="dashboard-stat2 ">
        <div class="display">
            <div class="number">
                <h3 class="font-red-haze">
                    <span data-counter="counterup" data-value="<?php echo $number_today_visits; ?>"><?php echo $number_today_visits; ?></span>
                </h3>
                <small> Daily visits vs target</small>
            </div>
            <div class="icon">
                <a class="more" target="_blank" href="<?php echo site_url('dashboard/daily_details'); ?>"> <i class="icon-target" title="View more"></i> </a>
            </div>
        </div>
        <div class="progress-info">
            <div class="progress">
                <span style="width: <?php echo number_format($perc_visit_day, 2, '.', ''); ?>%;" class="progress-bar progress-bar-success red-haze">
                    <span class="sr-only">85% change</span>
                </span>
            </div>
            <div class="status">
                <div class="status-title"> Achivement  </div>
                <div class="status-number"> <?php echo number_format($perc_visit_day, 2, ',', ''); ?>% </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="dashboard-stat2 ">
        <div class="display">
            <div class="number">
                <h3 class="font-blue-sharp">
                    <span data-counter="counterup" data-value="<?php echo $number_visit_month; ?>"><?php echo $number_visit_month; ?></span>
                </h3>
                <small> Monthly visits vs Target</small>
            </div>
            <div class="icon">
                <a class="more" target="_blank" href="<?php echo site_url('dashboard/monthly_details'); ?>"> <i class="icon-target" title="View more"></i> </a>
            </div>
        </div>
        <div class="progress-info">
            <div class="progress">
                <span style="width: <?php echo $perc_visit_month; ?>%;" class="progress-bar progress-bar-success blue-sharp">
                    <span class="sr-only"></span>
                </span>
            </div>
            <div class="status">
                <div class="status-title"> Achivement </div>
                <div class="status-number"> <?php echo $perc_visit_month; ?>% </div>
            </div>
        </div>
    </div>
</div>


