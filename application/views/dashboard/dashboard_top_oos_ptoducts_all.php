

<div class="mt-element-list">
    <div class="mt-list-head list-simple font-white bg-red">
        <div class="list-head-title-container">
            <div class="list-date" align="right">%</div>
            <h3 class="list-title">Product</h3>
        </div>
    </div>
    <div class="mt-list-container list-simple">
        <table class="table" id="myTable" width="100%">


            <?php
            $i = 0;
            foreach ($products as $p) {
                $i++;
                ?>
                <tr>

                    <td width="5%">   <?php echo $i; ?> </td>							
                    <td width="90%">   <?php echo $p['product_name']; ?> </td>


                    <td width="5%">   <?php
                        echo number_format(($p['oos']), 2);
                        ?> </td>
                </tr>	
            <?php } ?>
        </table>

    </div>
    <script>
        $(document).ready(function () {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;
            /*Make a loop that will continue until
             no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.getElementsByTagName("TR");
                /*Loop through all table rows (except the
                 first, which contains table headers):*/
                for (i = 0; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                     one from current row and one from the next:*/
                    x = rows[i].getElementsByTagName("TD")[2];
                    y = rows[i + 1].getElementsByTagName("TD")[2];
                    //check if the two rows should switch place:
                    if (parseInt(x.innerHTML.toLowerCase()) < parseInt(y.innerHTML.toLowerCase())) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                     and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }


            }

            for (i = 0; i < (rows.length - 1); i++) {
                rows[i].getElementsByTagName("TD")[0].innerHTML = i + 1;
            }

        });
    </script>
