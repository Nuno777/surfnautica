<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
  header('Location: ../login.php');
  exit(0);
}

require_once '../conexao.php';
$query = "SELECT * FROM contacto WHERE email = '{$_SESSION['email']}' ORDER BY id_cont DESC";
$result = mysqli_query($conn, $query);
$resultMenssage = mysqli_query($conn, $query);
?>
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>Painel do Cliente | Mensagem</title>
  <?php
  require_once 'sheets/dashboardHead.php';
  ?>
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <div class="wrapper">
    <aside class="left-sidebar sidebar-dark" id="left-sidebar">
      <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Aplication Brand -->
        <div class="app-brand">
          <a href="../index.php">
            <img src="assets/images/favicon.png" style="height: 65px;" alt="Mono">
            <span class="brand-name text-light">SURFNAUTICA</span>
          </a>
        </div>
        <div class="sidebar-left" data-simplebar style="height: 100%;">
          <ul class="nav sidebar-inner" id="sidebar-menu">

            <?php
            $query = "SELECT permission from users where email = '{$_SESSION['email']}'";
            $perms = mysqli_query($conn, $query);
            $levelperm = mysqli_fetch_assoc($perms);
            if ($levelperm['permission'] == 0) {
            ?>

            <?php
            } else { ?>
              <li class="">
                <a class="sidenav-item-link" href="dashboard.php">
                  <i class="mdi mdi-monitor-dashboard"></i>
                  <span class="nav-text">Dashboard</span>
                </a>
              </li>
            <?php
            }
            ?>

            <li class="">
              <a class="sidenav-item-link" href="user_profile.php">
                <i class="mdi mdi-monitor-dashboard"></i>
                <span class="nav-text">Painel</span>
              </a>
            </li>

            <li class="active">
              <a class="sidenav-item-link" href="user_contacto.php">
                <i class="mdi mdi-phone"></i>
                <span class="nav-text">Contactos</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </aside>

    <div class="page-wrapper">
      <header class="main-header" id="header">
        <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
          <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <span class="page-title">Painel Do Cliente</span>

          <div class="navbar-right ">
            <?php
            require_once 'sheets/dashboardNavbar.php';
            ?>
          </div>
        </nav>
      </header>


      <div class="content-wrapper">
        <div class="content">
          <!-- Alerta - Operações (LOGIN) -->
          <?php
          if (isset($_SESSION["message"])) { ?>
            <div class='alert alert-<?php echo $_SESSION["message"]["type"] ?> alert-dismissible fade show' role='alert'>
              <?php echo $_SESSION["message"]["content"]; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
            </div>

          <?php unset($_SESSION["message"]);
          }
          ?>
          <!-- Top -->
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">Mensagem</h4>
              <br>
              <div class="single-table">
                <?php
                while ($row = $result->fetch_object()) {
                ?>
                  <div class="card d-inline-block" style="width: 18rem;">
                    <div class="card-body">
                      <span>Assunto</span>
                      <h5 class="card-title"><?php echo substr("$row->assunto", 0, 25); ?></h5>
                      <br>
                      <span>Resposta</span>
                      <p class="card-text" style="color: black;"><?php echo substr("$row->resposta", 0, 50); ?> ...</p>
                      <br>
                      <small><?php echo $row->data_pub; ?></small>
                      <br>


                      <?php echo "<a data-toggle='modal' data-target='#viewmensagem$row->id_cont' class='text-primary' name='Menssage'>Ver Mais</a>"; ?><br>
                    </div>
                  </div>
                <?php
                } ?>

              </div>
            </div>
          </div>
          <!-- Modal de ver mensagem -->
          <?php while ($row = $resultMenssage->fetch_object()) {
            $mensagem = $row->mensagem;
            $assunto = $row->assunto;
            $data = $row->data_pub;
            $resposta = $row->resposta;
          ?>
            <div class="modal fade" id='viewmensagem<?php echo $row->id_cont ?>' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mensagem</h5><span class="span-contat"><?php echo $row->email; ?><br><small><?php echo $data; ?></small></span>
                  </div>
                  <!-- assunto -->
                  <div class="modal-body">
                    <span>Assunto</span>
                    <div class="row">
                      <div class="col-md-12">
                        <textarea type="text" class="form-control" rows="3" style="resize: none" disabled><?= $assunto ?></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- mensagem -->
                  <div class="modal-body">
                    <span>Mensagem</span>
                    <div class="row">
                      <div class="col-md-12">
                        <textarea type="text" class="form-control" rows="7" style="resize: none" disabled><?= $mensagem ?></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- mensagem -->
                  <div class="modal-body">
                    <span>Resposta</span>
                    <div class="row">
                      <div class="col-md-12">
                        <textarea type="text" class="form-control" rows="7" style="resize: none" disabled><?= $resposta ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
          <!-- Modal de ver mensagem fechou -->

        </div>
      </div>
      <br>
      <footer class="footer mt-auto">
        <?php
        require_once 'sheets/dashboardFooter.php';
        ?>
      </footer>

    </div>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/simplebar/simplebar.min.js"></script>
    <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
    <script src="assets/plugins/apexcharts/apexcharts.js"></script>
    <script src="assets/plugins/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-us-aea.js"></script>
    <script src="assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script>
      jQuery(document).ready(function() {
        jQuery('input[name="dateRange"]').daterangepicker({
          autoUpdateInput: false,
          singleDatePicker: true,
          locale: {
            cancelLabel: 'Clear'
          }
        });
        jQuery('input[name="dateRange"]').on('apply.daterangepicker', function(ev, picker) {
          jQuery(this).val(picker.startDate.format('MM/DD/YYYY'));
        });
        jQuery('input[name="dateRange"]').on('cancel.daterangepicker', function(ev, picker) {
          jQuery(this).val('');
        });
      });
    </script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="assets/plugins/toaster/toastr.min.js"></script>
    <script src="assets/js/mono.js"></script>
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/map.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

</html>