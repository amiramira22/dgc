<!-- All Oos products -->
<div class="mt-element-list">
    <div class="mt-list-head list-simple font-white bg-red">
        <div class="list-head-title-container">
            <div class="list-date" align="right">%</div>
            <h3 class="list-title">Product</h3>
        </div>
    </div>
    <div class="mt-list-container list-simple">
        <ul>

            <?php
            $i = 1;
            foreach ($products as $p) {
                ?>

                <li id="id_model" class="mt-list-item">
                    <div class="list-icon-container done">
                        <i class="icon-check"></i> <?php echo $i; ?>
                    </div>

                    <div class="list-datetime"> <?php
                        $i++;
                        echo number_format(($p['oos']), 2);
                        ?> </div>

                    <div class="list-item-content">
                        <h3 class="uppercase">
                            <a href="javascript:;"><?php echo $p['product_name']; ?></a>
                        </h3>
                    </div>
                </li>

            <?php } ?>


            <li id="id_model" class="mt-list-item">
                <div class="list-icon-container done">

                </div>

                <div class="list-datetime"> <a href="<?php echo site_url('dashboard/top_oos_all_product/' . $date); ?>" target="_blank">view all</a> </div>

                <div class="list-item-content">
                    <h3 class="uppercase">
                        <a href="javascript:;"></a>
                    </h3>
                </div>
            </li>



        </ul>
    </div>
</div><!-- end mt-element-list -->