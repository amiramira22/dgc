  
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
                      
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">  <?php echo date("Y"); ?> &copy; HCS Henkel SYSTEM by BeeSoft.
                <a href="beesoft.tn" title="BeeSoft" target="_blank">BeeSoft</a>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
       <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" type="text/javascript"></script>



<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<!-- END PAGE LEVEL PLUGINS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/x-editable/bootstrap-editable.css'); ?>"/>  
<script src="<?php echo base_url('assets/plugins/x-editable/bootstrap-editable.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script> 
	<script src="<?php echo base_url('assets/plugins/jquery-multi-select/js/jquery.multi-select.js'); ?>" type="text/javascript"></script> 
       <script src="<?php echo base_url('assets/plugins/select2/js/select2.full.min.js'); ?>" type="text/javascript"></script> 




<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url('assets/plugins/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'); ?>" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        
  <script src="<?php echo base_url('assets/js/table-datatables-responsive.min.js'); ?>" type="text/javascript"></script>


    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.min.js'); ?>" type="text/javascript"></script> 
	<script src="<?php echo base_url('assets/plugins/icheck/icheck.min.js'); ?>" type="text/javascript"></script>
	 <script src="<?php echo base_url('assets/plugins/counterup/jquery.waypoints.min.js'); ?>" type="text/javascript"></script>
     <script src="<?php echo base_url('assets/plugins/counterup/jquery.counterup.min.js'); ?>" type="text/javascript"></script>
 <!-- BEGIN Confirmation -->

 
 
 
		
		
 <!-- ENd Confirmation -->

 <!-- BEGIN RevolutionSlider -->
  
    <script src="<?php echo base_url('assets/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('assets/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('assets/js/revo-slider-init.js'); ?>" type="text/javascript"></script>
    <!-- END RevolutionSlider -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/app.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/layout.min.js'); ?>" type="text/javascript"></script>




    <script type="text/javascript">
	
	
	 $(function () {
        $('#my_multi_select1').multiSelect();
    });
    
     $(function () {
        $('#my_multi_select2').multiSelect();
    });
    
     $(function () {
        $('#my_multi_select3').multiSelect();
    });

    var save_method; //for save method string
    var table;
	
	
  
    $(document).ready(function() {
      table = $('#table').DataTable({ 
      	//bFilter: false,
	
        "bSort" : false,
		"bPaginate": false,
      
        
        "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            }, 
         
       buttons: [
                { extend: 'print', className: 'btn dark btn-outline' },
                { extend: 'pdf', className: 'btn red btn-outline' },
                { extend: 'excel', className: 'btn green-sharp btn-outline ' }
            ],
       responsive: {
                details: {
                   
                }
       },  
            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
	    aLengthMenu: [
        [20, 50, 100, 200, -1],
        [20, 50, 100, 200, "All"]
    ],
    iDisplayLength: 20,	
	

      });
      
      

    });
    
    
      $('#search').keyup(function(){
      table.search($(this).val()).draw() ;
     });
    
    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }


    

  </script>
 <script src="<?php echo base_url('assets/plugins/bootstrap-confirmation/confirm-new.js'); ?>" type="text/javascript"></script>

    </body>

</html>