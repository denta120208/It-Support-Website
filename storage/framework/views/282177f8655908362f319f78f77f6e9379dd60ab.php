<?php
$menu = explode(",", session('menu'));
$project = explode(",", session('proyek'));
$level = session('level');
$default_project_no_char = session('default_project_no_char');
session(['is_residential' => false]);
session(['is_commercial' => false]);

if(empty(session('id'))) {
  header("Location: http://dashboard.metropolitanland.com/logout");
  die();
}
else {
if(session('level') != NULL) {
//   if(session('level') == 9 || session('level') == 10 || session('level') == 12 || session('level') == 13 ||
//   session('level') == 19 || session('level') == 99) {
    $project_arr_raw = $project;
    $project_arr_residential = array();
    $project_arr_commercial = array();
    for($i = 0; $i < count($project_arr_raw); $i++) {
      $project_tmp = DB::select("SELECT * FROM MD_PROJECT_MREPORT WHERE ID_PROJECT = '".$project_arr_raw[$i]."'");
      if($project_tmp[0]->PROJECT_DB == "METLANDSOFT") {
        array_push($project_arr_residential, $project_tmp[0]->PROJECT_NO_CHAR);
        session(['is_residential' => true]);
        if(empty(session('current_project'))) {
          session(['current_project' => $project_tmp[0]->PROJECT_NO_CHAR]);          
          session(['current_project_char' => strtoupper($project_tmp[0]->PROJECT_NAME)]);
        }
      }
      else {
        array_push($project_arr_commercial, $project_tmp[0]->PROJECT_NO_CHAR);
        session(['is_commercial' => true]);
        if(empty(session('current_project_commercial'))) {
          session(['current_project_commercial' => $project_tmp[0]->PROJECT_NO_CHAR]);          
          session(['current_project_char_commercial' => strtoupper($project_tmp[0]->PROJECT_NAME)]);
        }
      }
    }

    if(session('is_residential') == true) {
      $project_arr_tmp_residential = $project_arr_residential;
      $project_arr_tmp_residential = implode("','",$project_arr_tmp_residential);
      $proyek_residential = DB::select("SELECT * FROM MD_PROJECT WHERE PROJECT_NO_CHAR IN ('".$project_arr_tmp_residential."')");
    }
    if(session('is_commercial') == true) {
      $project_arr_tmp_commercial = $project_arr_commercial;
      $project_arr_tmp_commercial = implode("','",$project_arr_tmp_commercial);
      $proyek_commercial = DB::select("SELECT * FROM MTLA_MALL.dbo.MD_PROJECT AS a WHERE a.PROJECT_NO_CHAR IN ('".$project_arr_tmp_commercial."')");
    }
  }
  else {
    header("Location: http://dashboard.metropolitanland.com/logout");
    die();
  }
}
?>



<?php $__env->startSection('navbar_header'); ?>
    Homepage
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Homepage
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md">
                <?php if(session('is_residential') == true): ?>
                <div class="card">
                    <div class="card-body">
                        <!-- Untuk yang Residential -->
                        <div class="row">
                            <div class="col-md">
                                <h5><b>RESIDENTIAL (<?php echo session('current_project_char') ?>)</b></h5>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (array_search("1", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-success collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Marketing</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_sales')); ?>" class="small-box-footer">
                                            Marketing Sales
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_pembatalan')); ?>" class="small-box-footer">
                                            Pembatalan
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_payment_method')); ?>" class="small-box-footer">
                                            Payment Method
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_channel_distribution')); ?>" class="small-box-footer">
                                            Channel Distribution
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_analisa_npv')); ?>" class="small-box-footer">
                                            Analisa Net Present Value (NPV)
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_data_kpr')); ?>" class="small-box-footer">
                                            Data KPR
                                        </a>
                                    </div>
                                    <!-- <div class="card-body">
                                        <a href="<?php echo e(route('marketing_efektifitas_promosi')); ?>" class="small-box-footer">
                                            Efektifitas Promosi
                                        </a>
                                    </div> -->
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_demografi_asal_customer')); ?>" class="small-box-footer">
                                            Demografi Asal Customer
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_demografi_usia_pekerjaan')); ?>" class="small-box-footer">
                                            Demografi Usia & Pekerjaan
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_stock')); ?>" class="small-box-footer">
                                            Stock
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (array_search("2", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-primary collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Legal</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('legal_progress')); ?>" class="small-box-footer">
                                            Progress SPT, SPPJB, AJB
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('legal_imb_sertifikat')); ?>" class="small-box-footer">
                                            IMB & Sertifikat
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (array_search("3", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-warning collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Teknik</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_progress_konstruksi_prasarana')); ?>" class="small-box-footer">
                                            Progress Konstruksi Prasarana
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_hutang_bayar')); ?>" class="small-box-footer">
                                            Hutang Bayar
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_hutang_bangun')); ?>" class="small-box-footer">
                                            Hutang Bangun
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_proyeksi_bangun')); ?>" class="small-box-footer">
                                            Proyeksi Bangun
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_budget')); ?>" class="small-box-footer">
                                            Budget
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_budget2')); ?>" class="small-box-footer">
                                            Budget (Dev.Cost & Const.Cost)
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_serah_terima')); ?>" class="small-box-footer">
                                            Serah Terima
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (array_search("4", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-danger collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Finance & Accounting</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_collection_aging_schedule')); ?>" class="small-box-footer">
                                            Collection & Aging Schedule
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_collection_year')); ?>" class="small-box-footer">
                                            Collection
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_aging_over_90_days')); ?>" class="small-box-footer">
                                            Aging > 90 Days
                                        </a>
                                    </div>
                                    <!-- <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_reschedule_cuti_pembayaran')); ?>" class="small-box-footer">
                                            Reschedule & Cuti Pembayaran
                                        </a>
                                    </div> -->
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_future_collection')); ?>" class="small-box-footer">
                                            Future Collection
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_posisi_mutasi_escrow')); ?>" class="small-box-footer">
                                            Posisi Mutasi Escrow
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_pencairan_escrow_tahun')); ?>" class="small-box-footer">
                                            Pencairan Escrow Berdasarkan Tahun
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_posisi_escrow_tahun_bank')); ?>" class="small-box-footer">
                                            Posisi Escrow Berdasarkan Tahun & Bank
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_proyeksi_pencairan_escrow')); ?>" class="small-box-footer">
                                            Proyeksi Pencairan Escrow
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow')); ?>" class="small-box-footer">
                                            Aging Schedule Atas Escrow
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow_tahapan')); ?>" class="small-box-footer">
                                            Aging Schedule Atas Escrow Berdasarkan Tahapan
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_sales_backlog')); ?>" class="small-box-footer">
                                            Sales Backlog
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_laba_rugi')); ?>" class="small-box-footer">
                                            P & L
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_gop')); ?>" class="small-box-footer">
                                            GOP
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_promosi_expense')); ?>" class="small-box-footer">
                                            Promosi Expense
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_ga_expense')); ?>" class="small-box-footer">
                                            GA Expense
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(session('is_commercial') == true): ?>
                <div class="card">
                    <div class="card-body">
                        <!-- Untuk yang Commercial -->
                        <div class="row">
                            <div class="col-md">
                                <h5><b>COMMERCIAL (<?php echo session('current_project_char_commercial') ?>)</b></h5>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (array_search("1", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-success collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Marketing</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_branded_tenants')); ?>" class="small-box-footer">
                                            Branded Tenants
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_occupancy')); ?>" class="small-box-footer">
                                            Occupancy
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_occupied_rental_sc')); ?>" class="small-box-footer">
                                            Occupied Rental & Service Charge
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_occupied_tenant_rental_period')); ?>" class="small-box-footer">
                                            Occupied Tenant Based On Rental Period
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_upcoming_tenant')); ?>" class="small-box-footer">
                                            Upcoming Tenant
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_growth_member_metland_card')); ?>" class="small-box-footer">
                                            Growth Updated Member Metland Card
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_active_member_metland_card')); ?>" class="small-box-footer">
                                            Active Member Metland Card
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_demografi_metland_card')); ?>" class="small-box-footer">
                                            Data Penyebaran Wilayah Member Metland Card
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('marketing_demografi_bekasi_metland_card')); ?>" class="small-box-footer">
                                            Data Penyebaran Wilayah Kota Bekasi Member Metland Card
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (array_search("3", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-warning collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Teknik</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_komposisi_listrik')); ?>" class="small-box-footer">
                                            Konsumsi & Komposisi Listrik
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_listrik_gedung')); ?>" class="small-box-footer">
                                            Konsumsi Listrik Gedung
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_listrik_tenant')); ?>" class="small-box-footer">
                                            Konsumsi Listrik Tenant
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_komposisi_air')); ?>" class="small-box-footer">
                                            Konsumsi & Komposisi Air
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_air_gedung')); ?>" class="small-box-footer">
                                            Konsumsi Air Gedung
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_air_tenant')); ?>" class="small-box-footer">
                                            Konsumsi Air Tenant
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('teknik_konsumsi_gas')); ?>" class="small-box-footer">
                                            Konsumsi Gas
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (array_search("4", $menu) !== false) { ?>
                            <div class="col-md-3">
                                <div class="card card-danger collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Finance & Accounting</h3>
                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_arus_kas_commercial')); ?>" class="small-box-footer">
                                            Laporan Arus Kas
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_collection_aging_schedule_commercial')); ?>" class="small-box-footer">
                                            Collection & Aging Schedule
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_aging_over_90_days_commercial')); ?>" class="small-box-footer">
                                            Aging > 90 Days
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="<?php echo e(route('finance_accounting_hutang_usaha_commercial')); ?>" class="small-box-footer">
                                            Hutang Usaha
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/home.blade.php ENDPATH**/ ?>