<?php include('header.php'); //bcm ?>

<br>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-users font-red"></i><?php echo $sub_title; ?>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">

                <?php
                foreach ($admins as $admin):

                    if ($this->auth->check_access('Henkel')) {
                        $var = "disabled";
                    } else {
                        $var = "";
                    }
                    ?>
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <form>
                                    <i class="fa fa-user"></i><?php echo" " . $admin->name; ?>
                                </form>
                            </div>
                        </div>


                        <div class="portlet-body">

                            <img src="<?php echo base_url('uploads/users/' . $admin->photos); ?>" alt="current" style="width:100px;"/>
                            <br/>
                            <br>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">Name</label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'firstname' . $admin->id, 'name' => 'firstname' . $admin->id, 'value' => $admin->name, 'class' => 'form-control', 'placeholder' => "first name");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Age 
                                    </label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'age' . $admin->id, 'name' => 'age' . $admin->id, 'value' => $admin->age, 'class' => 'form-control', 'placeholder' => "Age");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Zone 
                                    </label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'zone' . $admin->id, 'name' => 'zone' . $admin->id, 'value' => $admin->zone, 'class' => 'form-control', 'placeholder' => "zone");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Tel 
                                    </label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'tel' . $admin->id, 'name' => 'tel' . $admin->id, 'value' => $admin->tel, 'class' => 'form-control', 'placeholder' => "tel");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Integration date 
                                    </label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'integration_date' . $admin->id, 'name' => 'integration_date' . $admin->id, 'value' => $admin->integration_date, 'class' => 'form-control', 'placeholder' => "integration date");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Study level 
                                    </label>
                                    <div class="col-md-8">
                                        <?php
                                        $data = array($var => '', 'id' => 'niveau' . $admin->id, 'name' => 'niveau' . $admin->id, 'value' => $admin->niveau, 'class' => 'form-control', 'placeholder' => "Study level");
                                        echo form_input($data);
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-5 col-md-9">
                                            <a class="btn btn-circle blue btn-outline" href="<?php echo site_url('reports/routing_report/' . $admin->id); ?>" target="_blank">
                                                <i class="fa fa-calendar "></i> Show Routing
                                            </a>
                                            <?php if ($this->auth->check_access('Admin')) { ?>
                                                <input class="btn btn-circle red-mint btn-outline sbold uppercase" type="button" onClick="update<?php echo $admin->id ?>(<?php echo $admin->id ?>)" value="Update"/>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>


                                <script>

                                    function update<?php echo $admin->id ?>(id)
                                    {
                                        name = document.getElementById('firstname<?php echo $admin->id ?>').value;
                                        age = document.getElementById('age<?php echo $admin->id ?>').value;
                                        zone = document.getElementById('zone<?php echo $admin->id ?>').value;
                                        tel = document.getElementById('tel<?php echo $admin->id ?>').value;
                                        date = document.getElementById('integration_date<?php echo $admin->id ?>').value;
                                        niveau = document.getElementById('niveau<?php echo $admin->id ?>').value;


                                        jQuery.ajax({
                                            url: "<?php echo site_url("users/update_fo/"); ?>",
                                            data: {
                                                id: <?php echo $admin->id ?>,
                                                name: name,
                                                age: age,
                                                zone: zone,
                                                tel: tel,
                                                date: date,
                                                niveau: niveau

                                            },
                                            type: "POST",
                                            success: function (data) {

                                                alert('updated');



                                            }
                                        });


                                    }
                                </script>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                </tbody>

            </div>
        </div>

    </div>

    <?php include('footer.php'); ?>
