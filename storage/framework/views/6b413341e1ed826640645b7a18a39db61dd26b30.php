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
  // if(session('level') == 9 || session('level') == 10 || session('level') == 12 || session('level') == 13 ||
  // session('level') == 19 || session('level') == 99) {
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
  <title>Dashboard Report</title>
  <link rel="icon" href="<?php echo e(asset('adminlte/dist/img/favicon.ico')); ?>" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/fontawesome-free/css/all.min.css')); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')); ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/jqvmap/jqvmap.min.css')); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/dist/css/adminlte.min.css')); ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/daterangepicker/daterangepicker.css')); ?>">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/summernote/summernote-bs4.min.css')); ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/select2/css/select2.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.4/sweetalert2.min.css">
  <!-- JQuery -->
  <script src="<?php echo e(asset('adminlte/plugins/jquery/jquery.min.js')); ?>"></script>
  <!-- Datepicker -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

  <style>
    #profileImage {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background: #1c8282;
      font-size: 25px;
      color: #fff;
      text-align: center;
      line-height: 35px;
      font-weight: bold;
    }
  </style>
</head>

<div class="container">
  <div class="modal fade" id="changeProject" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Project</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="col-md">
              <div class="form-group">
                  <?php if(session('is_residential') == true): ?>
                  <label>Proyek Residential</label>
                  <select class="form-control select2" id="ddlChangeProject" name="ddlChangeProject" style="width: 100%;" onchange="changeProject()" required>
                      <?php $__currentLoopData = $proyek_residential; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proyek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if(session('current_project') == $proyek->PROJECT_NO_CHAR): ?>
                          <option value="<?php echo e($proyek->PROJECT_NO_CHAR); ?>" selected="selected"><?php echo e(strtoupper($proyek->PROJECT_NAME)); ?></option>
                          <?php else: ?>
                          <option value="<?php echo e($proyek->PROJECT_NO_CHAR); ?>"><?php echo e(strtoupper($proyek->PROJECT_NAME)); ?></option>
                          <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <?php endif; ?>
              </div>
              <div class="form-group">
                  <?php if(session('is_commercial') == true): ?>
                  <label>Proyek Commercial</label>
                  <select class="form-control select2" id="ddlChangeProjectCommercial" name="ddlChangeProjectCommercial" style="width: 100%;" onchange="changeProjectCommercial()" required>
                      <?php $__currentLoopData = $proyek_commercial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proyek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if(session('current_project_commercial') == $proyek->PROJECT_NO_CHAR): ?>
                          <option value="<?php echo e($proyek->PROJECT_NO_CHAR); ?>" selected="selected"><?php echo e(strtoupper($proyek->PROJECT_NAME)); ?></option>
                          <?php else: ?>
                          <option value="<?php echo e($proyek->PROJECT_NO_CHAR); ?>"><?php echo e(strtoupper($proyek->PROJECT_NAME)); ?></option>
                          <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <?php endif; ?>
              </div>
          </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="changeProject();">Change</button>
        </div> -->
      </div>
    </div>
  </div>
</div>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo e(asset('adminlte/dist/img/AdminLTELogo.png')); ?>" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:void(0)" class="nav-link"><?php echo $__env->yieldContent('navbar_header'); ?></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo e(route('logout')); ?>" class="btn btn-block btn-danger">Logout</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:void(0)" class="brand-link">
      <img src="<?php echo e(asset('adminlte/dist/img/logo_metland.png')); ?>" alt="Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light">Dashboard Report</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Version Apps -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="text-align: center !important;">
        <div class="info">
          <a href="javascript:void(0)" class="d-block">VERSION : 1.0.2</a>
        </div>
      </div>
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <div id="profileImage"></div>
          <!-- <img src="<?php echo e(asset('adminlte/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <a href="javascript:void(0)" class="d-block" id="first_name"><?php echo strtoupper(session('first_name')) ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
              
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link" data-toggle="modal" data-target="#changeProject">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Change Project
              </p>
            </a>
          </li>

          <li class="nav-item">
            <?php if(session('menuParentActive') == "homepage"): ?>
            <a href="<?php echo e(route('home')); ?>" class="nav-link active">
            <?php else: ?>
            <a href="<?php echo e(route('home')); ?>" class="nav-link">
            <?php endif; ?>
              <i class="nav-icon fas fa-th"></i>
              <p>
                Homepage
              </p>
            </a>
          </li>

          <?php if (array_search("1", $menu) !== false) { ?>
          <?php if(session('menuParentActive') == "marketing"): ?>
          <li class="nav-item menu-open">
            <a href="javascript:void(0)" class="nav-link active">
          <?php else: ?>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
          <?php endif; ?>
              <i class="nav-icon fas fa-table"></i>
              <p>
                Marketing
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <?php if(session('is_residential') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-header">RESIDENTIAL</li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_sales"): ?>
                <a href="<?php echo e(route('marketing_sales')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_sales')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Marketing Sales</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_pembatalan"): ?>
                <a href="<?php echo e(route('marketing_pembatalan')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_pembatalan')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pembatalan</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_payment_method"): ?>
                <a href="<?php echo e(route('marketing_payment_method')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_payment_method')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Method</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_channel_distribution"): ?>
                <a href="<?php echo e(route('marketing_channel_distribution')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_channel_distribution')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Channel Distribution</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_analisa_npv"): ?>
                <a href="<?php echo e(route('marketing_analisa_npv')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_analisa_npv')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Analisa Net Present Value (NPV)</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_data_kpr"): ?>
                <a href="<?php echo e(route('marketing_data_kpr')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_data_kpr')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data KPR</p>
                </a>
              </li>
            </ul>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_efektifitas_promosi"): ?>
                <a href="<?php echo e(route('marketing_efektifitas_promosi')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_efektifitas_promosi')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Efektifitas Promosi</p>
                </a>
              </li>
            </ul> -->
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_demografi_asal_customer"): ?>
                <a href="<?php echo e(route('marketing_demografi_asal_customer')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_demografi_asal_customer')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Demografi Asal Customer</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_demografi_usia_pekerjaan"): ?>
                <a href="<?php echo e(route('marketing_demografi_usia_pekerjaan')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_demografi_usia_pekerjaan')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Demografi Usia & Pekerjaan</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_stock"): ?>
                <a href="<?php echo e(route('marketing_stock')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_stock')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
            <?php if(session('is_commercial') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <li class="nav-header">COMMERCIAL</li>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_branded_tenants"): ?>
                <a href="<?php echo e(route('marketing_branded_tenants')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_branded_tenants')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Branded Tenants</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_occupancy"): ?>
                <a href="<?php echo e(route('marketing_occupancy')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_occupancy')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Occupancy</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_occupied_rental_sc"): ?>
                <a href="<?php echo e(route('marketing_occupied_rental_sc')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_occupied_rental_sc')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Occupied Rental & Service Charge</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_occupied_tenant_rental_period"): ?>
                <a href="<?php echo e(route('marketing_occupied_tenant_rental_period')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_occupied_tenant_rental_period')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Occupied Tenant Based On Rental Period</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_upcoming_tenant"): ?>
                <a href="<?php echo e(route('marketing_upcoming_tenant')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_upcoming_tenant')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Upcoming Tenant</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_growth_member_metland_card"): ?>
                <a href="<?php echo e(route('marketing_growth_member_metland_card')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_growth_member_metland_card')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Growth Updated Member Metland Card</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_active_member_metland_card"): ?>
                <a href="<?php echo e(route('marketing_active_member_metland_card')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_active_member_metland_card')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Member Metland Card</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_demografi_metland_card"): ?>
                <a href="<?php echo e(route('marketing_demografi_metland_card')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_demografi_metland_card')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Penyebaran Wilayah Member Metland Card</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "marketing_demografi_bekasi_metland_card"): ?>
                <a href="<?php echo e(route('marketing_demografi_bekasi_metland_card')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('marketing_demografi_bekasi_metland_card')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Penyebaran Wilayah Kota Bekasi Member Metland Card</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
          </li>
          <?php } ?>
          <?php if (array_search("2", $menu) !== false) { ?>
          <?php if(session('menuParentActive') == "legal"): ?>
          <li class="nav-item menu-open">
            <a href="javascript:void(0)" class="nav-link active">
          <?php else: ?>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
          <?php endif; ?>
              <i class="nav-icon fas fa-table"></i>
              <p>
                Legal
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-header">RESIDENTIAL</li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "legal_progress"): ?>
                <a href="<?php echo e(route('legal_progress')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('legal_progress')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Progress SPT, SPPJB, AJB</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "legal_imb_sertifikat"): ?>
                <a href="<?php echo e(route('legal_imb_sertifikat')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('legal_imb_sertifikat')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>IMB & Sertifikat</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if (array_search("3", $menu) !== false) { ?>
          <?php if(session('menuParentActive') == "teknik"): ?>
          <li class="nav-item menu-open">
            <a href="javascript:void(0)" class="nav-link active">
          <?php else: ?>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
          <?php endif; ?>
              <i class="nav-icon fas fa-table"></i>
              <p>
                Teknik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <?php if(session('is_residential') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-header">RESIDENTIAL</li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_progress_konstruksi_prasarana"): ?>
                <a href="<?php echo e(route('teknik_progress_konstruksi_prasarana')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_progress_konstruksi_prasarana')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Progress Konstruksi Prasarana</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_hutang_bayar"): ?>
                <a href="<?php echo e(route('teknik_hutang_bayar')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_hutang_bayar')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hutang Bayar</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_hutang_bangun"): ?>
                <a href="<?php echo e(route('teknik_hutang_bangun')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_hutang_bangun')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hutang Bangun</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_proyeksi_bangun"): ?>
                <a href="<?php echo e(route('teknik_proyeksi_bangun')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_proyeksi_bangun')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Proyeksi Bangun</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_budget"): ?>
                <a href="<?php echo e(route('teknik_budget')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_budget')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Budget</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_budget2"): ?>
                <a href="<?php echo e(route('teknik_budget2')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_budget2')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Budget (Dev.Cost & Const.Cost)</p>
                </a>
              </li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_serah_terima"): ?>
                <a href="<?php echo e(route('teknik_serah_terima')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_serah_terima')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Serah Terima</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
            <?php if(session('is_commercial') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <li class="nav-header">COMMERCIAL</li>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_komposisi_listrik"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_komposisi_listrik')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_komposisi_listrik')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi & Komposisi Listrik</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_listrik_gedung"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_listrik_gedung')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_listrik_gedung')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi Listrik Gedung</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_listrik_tenant"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_listrik_tenant')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_listrik_tenant')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi Listrik Tenant</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_komposisi_air"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_komposisi_air')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_komposisi_air')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi & Komposisi Air</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_air_gedung"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_air_gedung')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_air_gedung')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi Air Gedung</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_air_tenant"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_air_tenant')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_air_tenant')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi Air Tenant</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "teknik_konsumsi_gas"): ?>
                <a href="<?php echo e(route('teknik_konsumsi_gas')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('teknik_konsumsi_gas')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konsumsi Gas</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
          </li>
          <?php } ?>
          <?php if (array_search("4", $menu) !== false) { ?>
          <?php if(session('menuParentActive') == "finance_accounting"): ?>
          <li class="nav-item menu-open">
            <a href="javascript:void(0)" class="nav-link active">
          <?php else: ?>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
          <?php endif; ?>
              <i class="nav-icon fas fa-table"></i>
              <p>
                Finance & Accounting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <?php if(session('is_residential') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-header">RESIDENTIAL</li>
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_collection_aging_schedule"): ?>
                <a href="<?php echo e(route('finance_accounting_collection_aging_schedule')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_collection_aging_schedule')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collection & Aging Schedule</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_collection_year"): ?>
                <a href="<?php echo e(route('finance_accounting_collection_year')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_collection_year')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collection</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_aging_over_90_days"): ?>
                <a href="<?php echo e(route('finance_accounting_aging_over_90_days')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_aging_over_90_days')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aging > 90 Days</p>
                </a>
              </li>
            </ul>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_reschedule_cuti_pembayaran"): ?>
                <a href="<?php echo e(route('finance_accounting_reschedule_cuti_pembayaran')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_reschedule_cuti_pembayaran')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reschedule & Cuti Pembayaran</p>
                </a>
              </li>
            </ul> -->
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_future_collection"): ?>
                <a href="<?php echo e(route('finance_accounting_future_collection')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_future_collection')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Future Collection</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_posisi_mutasi_escrow"): ?>
                <a href="<?php echo e(route('finance_accounting_posisi_mutasi_escrow')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_posisi_mutasi_escrow')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Posisi Mutasi Escrow</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_pencairan_escrow_tahun"): ?>
                <a href="<?php echo e(route('finance_accounting_pencairan_escrow_tahun')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_pencairan_escrow_tahun')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pencairan Escrow Berdasarkan Tahun</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_posisi_escrow_tahun_bank"): ?>
                <a href="<?php echo e(route('finance_accounting_posisi_escrow_tahun_bank')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_posisi_escrow_tahun_bank')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Posisi Escrow Berdasarkan Tahun & Bank</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_proyeksi_pencairan_escrow"): ?>
                <a href="<?php echo e(route('finance_accounting_proyeksi_pencairan_escrow')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_proyeksi_pencairan_escrow')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Proyeksi Pencairan Escrow</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_aging_schedule_escrow"): ?>
                <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aging Schedule Atas Escrow</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_aging_schedule_escrow_tahapan"): ?>
                <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow_tahapan')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_aging_schedule_escrow_tahapan')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aging Schedule Atas Escrow Berdasarkan Tahapan</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_sales_backlog"): ?>
                <a href="<?php echo e(route('finance_accounting_sales_backlog')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_sales_backlog')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Backlog</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_laba_rugi"): ?>
                <a href="<?php echo e(route('finance_accounting_laba_rugi')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_laba_rugi')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>P & L</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_gop"): ?>
                <a href="<?php echo e(route('finance_accounting_gop')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_gop')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>GOP</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_promosi_expense"): ?>
                <a href="<?php echo e(route('finance_accounting_promosi_expense')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_promosi_expense')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Promosi Expense</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_ga_expense"): ?>
                <a href="<?php echo e(route('finance_accounting_ga_expense')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_ga_expense')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>GA Expense</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
            <?php if(session('is_commercial') == true): ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <li class="nav-header">COMMERCIAL</li>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_arus_kas_commercial"): ?>
                <a href="<?php echo e(route('finance_accounting_arus_kas_commercial')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_arus_kas_commercial')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Arus Kas</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_collection_aging_schedule_commercial"): ?>
                <a href="<?php echo e(route('finance_accounting_collection_aging_schedule_commercial')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_collection_aging_schedule_commercial')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collection & Aging Schedule</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_aging_over_90_days_commercial"): ?>
                <a href="<?php echo e(route('finance_accounting_aging_over_90_days_commercial')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_aging_over_90_days_commercial')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aging > 90 Days</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if(session('menuSubActive') == "finance_accounting_hutang_usaha_commercial"): ?>
                <a href="<?php echo e(route('finance_accounting_hutang_usaha_commercial')); ?>" class="nav-link active">
                <?php else: ?>
                <a href="<?php echo e(route('finance_accounting_hutang_usaha_commercial')); ?>" class="nav-link">
                <?php endif; ?>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hutang Usaha</p>
                </a>
              </li>
            </ul>
            <?php endif; ?>
          </li>
          <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0"><?php echo $__env->yieldContent('header_title'); ?></h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <?php echo $__env->yieldContent('content'); ?>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <!-- <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="http://dashboard.metropolitanland.com/">Metland Report</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version 1.0.2</b>
    </div>
  </footer> -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo e(asset('adminlte/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo e(asset('adminlte/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- ChartJS -->
<script src="<?php echo e(asset('adminlte/plugins/chart.js/Chart.min.js')); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo e(asset('adminlte/plugins/sparklines/sparkline.js')); ?>"></script>
<!-- JQVMap -->
<script src="<?php echo e(asset('adminlte/plugins/jqvmap/jquery.vmap.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js')); ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo e(asset('adminlte/plugins/jquery-knob/jquery.knob.min.js')); ?>"></script>
<!-- daterangepicker -->
<script src="<?php echo e(asset('adminlte/plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminlte/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo e(asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')); ?>"></script>
<!-- Summernote -->
<script src="<?php echo e(asset('adminlte/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo e(asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('adminlte/dist/js/adminlte.js')); ?>"></script>
<!-- Datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="<?php echo e(asset('adminlte/dist/js/demo.js')); ?>"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo e(asset('adminlte/dist/js/pages/dashboard.js')); ?>"></script>
<!-- Select2 -->
<script src="<?php echo e(asset('adminlte/plugins/select2/js/select2.full.min.js')); ?>"></script>
<script>
  $(function () {
    $('.select2').select2({
      theme: 'bootstrap4'
    });
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });
</script>
<!-- Sweetalert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.4/sweetalert2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var intials = $('#first_name').text().charAt(0);
    var profileImage = $('#profileImage').text(intials);
  });

  function changeProject() {
      var proyek = document.getElementById("ddlChangeProject").value;
      var url = '<?php echo e(url("change_project/proyek")); ?>';
      url = url.replace('proyek', proyek);
      window.location.href = url;
  }

  function changeProjectCommercial() {
      var proyek = document.getElementById("ddlChangeProjectCommercial").value;
      var url = '<?php echo e(url("change_project_commercial/proyek")); ?>';
      url = url.replace('proyek', proyek);
      window.location.href = url;
  }
</script>
</body>
</html>
<?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/layouts/mainLayouts.blade.php ENDPATH**/ ?>