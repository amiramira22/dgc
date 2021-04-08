
<div class="col-md-6 col-sm-6">
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart font-dark hide"></i>
                <span style="color: #f36a5a;">Feeds</span>

            </div>
            <div class="inputs">
                <div class="portlet-input input-inline input-small ">
                    <div class="input-icon right">
                        <i class="icon-magnifier"></i>
                        <input type="text" class="form-control form-control-solid input-circle" placeholder="search..."> </div>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div ><div class="scroller" style="height: 790px;overflow: auto; overflow-y:20px;">
                    <div class="general-item-list">

                        <?php foreach ($feeds as $feed) { ?>
                            <div class="item">
                                <div class="item-head">
                                    <div class="item-details">
                                        <img class="item-pic rounded" src=<?php echo base_url('uploads/users/' . $feed->photos); ?>
                                             <a href="" class="item-name primary-link"><?php print_r($feed->name); ?></a>
                                        <span class="item-label"><?php print_r($feed->date); ?></span>
                                    </div>
                                    <span class="item-status">
                                        <span class="badge badge-empty badge-success"></span> <?php print_r($feed->outlet_name); ?></span>
                                </div>
                                <div class="item-body"> <?php print_r($feed->remark); ?> </div>
                            </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!--  row map-->

