<?php include('header.php'); ?>

<style>
    td{
        text-align: center;
        width: 125px;
    }

    th{

        text-align: center;
        width: 125px;
    }
</style>

<!-- Datatable-->


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <a class="btn blue" href="<?php echo site_url('messages/form'); ?>">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>

    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="fa fa-cogs"></i><?php echo $sub_title; ?>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Message</th>
                            <th>viewed</th>

                            <th>Date</th>
                            <?php if (!$this->auth->check_access('Field Officer')) { ?>
                                <th>Actions</th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>


                        <?php
//print_r($messages);
                        foreach ($messagess as $message):
                            ?>
                            <tr>
                                <td><?php echo $this->Admin_model->get_admin_name($message->sender_id); ?></td>


                                <td><?php echo $this->Admin_model->get_admin_name($message->receiver_id); ?></td>

                                <td><?php echo $message->message; ?></td>
                                <?php if ($message->viewed == 1) { ?>
                                    <td>  <i class="fa fa-check font-red"></i></td>
                                <?php } else { ?>
                                    <td>   <i class="fa fa-times font-blue"></i> </td>
                                <?php }
                                ?>
                                <td class="gc_cell_left"><?php echo $message->created; ?></td>

                                <td>
                                    <div class="btn-group">		
                                        <a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder') . '/messages/delete/' . $message->id); ?>" onclick="return confirm('Are you sure you want to delete this Message?')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>