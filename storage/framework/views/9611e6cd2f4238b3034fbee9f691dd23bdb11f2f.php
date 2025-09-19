




<html>
    <header>
        <script>
            $(document).ready(function() 
            {
                $('#cicilan_table').DataTable();
            } );
        </script>
        
        <style>
            @page  { margin: 10mm;}
            table, th, td {
                border: 0px black;
            }
            th, td {
                padding: 1px;
            }
            div.headeratas{
                padding-left: 2em;
                margin-left: 9em;
                text-align:center;
                margin-top:-1em;
                font-size: 10px
            }

            div.headeratas5{
                padding-left: 2em;
                margin-left: 9em;
                text-align:center;
                margin-top:5em;
                font-size: 10px
            }

            div.headeratastengah{
                padding-left: 2em;
                /*margin-left: 8em;*/
                text-align:left;
                margin-top:-1em;
                margin-left:20em;
                font-size: 10px
            }
            div.headerbawahtengah{
                padding-left: 2em;
                margin-left: -1em;
                text-align:center;
                margin-top: 1em;
                font-size: 12px;
            }
            div.headerbawahtengah2{
            /*    padding-left: 5em;*/
                margin-top: 1em;
                font-size: 12px;
            }
            div.headerbawahtengah3{
            /*    padding-left: 5em;*/
                margin-left: 5em;
                text-align:center;
                margin-top: -1em;
                font-size: 12px;
            }

            div.headerataskiri{
                text-align:left;
                margin-right:-7em;
                margin-top: -1em;
            }

            div.headerataskiri5{
                text-align:left;
                margin-right:-7em;
                margin-top: -30px;
                font-size: 9px;
            }

            div.headeratastengah5{
                padding-left: 2em;
                /*margin-left: 8em;*/
                text-align:left;
                margin-top: -30px;
                margin-left:20em;
                font-size: 9px;
            }

            div.headerataskanan5{
                margin-top:-30px;
                text-align:right;
                font-size: 9px;
            }
            div.headerbawahtengah5{
            /*    padding-left: 5em;*/
                margin-left: 5em;
                text-align:center;
                margin-top: 1em;
                font-size: 12px;
            }

            div.headerbawahkiri{
                text-align:left; 
            }
            div.headerataskanan{
                margin-top:2em;
                text-align:right;
                 font-size: 8px;
            }

            div.headerbawahkanan{
                margin-top:-3em;
                text-align:right;
                font-size: 8px;
            }
            table.kananatas{
                align:right;
            }

            div.tanggalbawah{
                 text-align: left;
                 font-size: 11px;
            }


            div.ttdatas{
               margin-top:0em;

               text-align: left;
               font-size: 11px;
            }

            div.ttdbawah{
               margin-top:2em;
               text-align: left;
            }

            div.salesbawah{
               margin-top:0em;
               margin-right:8em;
               margin-bottom:5em;
               text-align: right;
            }

            div.bawah{
               margin-right:6em;
               text-align: right;
            }

            div.tableCustomer{
                 text-align:left;
                 margin-left: 2em;
                 padding-left: 2em;
            }
            div.termAndConditionAtas{
                 text-align:left;
                 font-size: 10px;
                 margin-bottom:-1em;
            }

            div.termAndConditionBawah{
                 text-align:left;
                 margin-left: 3em;
                 font-size: 10px;
            }
            div.termAndConditionNextPage{
                 text-align:left;
                 padding: 1em;
                 font-size: 12px;
                 margin-top:0em;
            }

            div.rekeningAtasTermConditions{
                 text-align:center;
                 margin-left: 2em;
                 font-size: 12px;
            }
            div.rekeningBawahTermConditions{
                 text-align:center;
                 margin-left: -2em;
                 font-size: 12px;
            }

            div.Customer{
                padding-left: 3em;
                font-size: 11px;
            }

            div.dataBookingEntry{
                 font-size: 11px;
                 margin-top: -5em;
            }

            div.page-break {
                page-break-after: always;
            }

            div.tabelCicilan{
                font-size: 13px;
                text-align: center;
                padding: 1em;
                padding-bottom: 1em;
            }

            div.parafTermCondition{
                 text-align: right;
                 font-size: 11px;    
            /*     margin-bottom: -1000px;  */
            }


            thead{
                font-size: 11px;
                text-align: center;
                margin-bottom: 1em;  
            }
            tbody{
                font-size: 11px;
                text-align: left;
            }
            
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                padding:4px;
            }
        </style>
    </header>

    <body>
        <div class="row">
            <div>
                <center>
                    <div class="headerbawahtengah">
                        <h3><b>Metland Expense</b></h3>
                        <h3><b><?php echo e(strtoupper($project_name)); ?></b></h3>
                        <h3><b><?php echo e(date('d/m/Y',strtotime($start_date_param))); ?> s/d <?php echo e(date('d/m/Y',strtotime($end_date_param))); ?></b></h3>
                    </div>
                </center>
            </div>
            <hr />
            <table class="stripe hover compact" id="PrintServiceData" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Ticket</th>
                    <th>Type</th>
                    <th>Aplikasi</th>
                    <th>Status</th>
                </tr>
                </thead>
                 <?php
                    $no = 1;
                ?>
                <tbody>
                <?php $__currentLoopData = $dataTable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="text-align: center;"><?php echo e($no++); ?></td>
                        <td style="text-align: center;"><?php echo e($data['JUDUL_TICKET']); ?></td>
                        <td style="text-align: center;"><?php echo e($data['DESC_KELUHAN']); ?></td>
                        <td style="text-align: center;"><?php echo e($data['DESC_APLIKASI']); ?></td>
                        <td style="text-align: center;"><?php echo e($data['DESC_CHAR']); ?></td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="headerbawahtengah2">
                Date Print : <?php echo e($dateNow); ?>

                <br>
                Printed by : <?php echo e($userName); ?>

            </div> 
        </div>
    </body>
</html>


<?php /**PATH /home/support/public_html/metland_support/resources/views/ReportTicketing/printDataDetail.blade.php ENDPATH**/ ?>