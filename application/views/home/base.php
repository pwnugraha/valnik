<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VALNIK</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet">

    <!-- table style -->
    <link href="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Valnik Dashboard</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Tables -->
            <li class="nav-item <?= empty($this->uri->segment(1)) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url() ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data</span></a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= $this->uri->segment(1) == 'statistik' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('statistik') ?>">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Statistik</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                </nav>
                <!-- End of Topbar -->

                <?php $this->load->view($child_template) ?>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/') ?>vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets/') ?>js/demo/chart-area-demo.js"></script>
    <script src="<?= base_url('assets/') ?>js/demo/chart-pie-demo.js"></script>

    <!-- table page -->
    <script src="<?= base_url('assets/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets/') ?>js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function() {

            $('#kec').change(function() {
                var kec = $(this).val();

                $.ajax({
                    url: '<?= base_url() ?>' + '/data/get_kel',
                    method: "POST",
                    data: {
                        kec: kec
                    },
                    dataType: 'json',
                    success: function(data) {
                        var html = '';
                        if (data.status) {
                            $.each(data.data, function(index, value) {
                                html += '<option value="' + value.kel + '">' + value.kel + '</option>';
                            });

                            $('#kel').html('<option selected>Pilih Desa...</option>' + html);
                        }
                    }
                });
                return false;
            });

            $(document).on("click", "#update", function() {
                var idArt = $(this).attr("data-idart");
                $("#id_art_update").val(idArt);
            });

            $('#saveModal').on('hide.bs.modal', function(e) {
                $("#id_art_update").val("");
            })
        });

        function checkCapil() {
            $('#btn_submit').remove();
            $.ajax({
                url: '<?= base_url() ?>' + 'data/get_capil',
                method: "POST",
                data: {
                    nik: $("#update_nik").val()
                },
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    if (data.status) {
                        $("#errorMessageModal").html("");
                        var obj = JSON.parse(data.data);
                        if (obj.result.result.length === 0) {
                            $("#errorMessageModal").removeClass("text-success").addClass("text-danger").html("NIK tidak ditemukan di dukcapil");
                            $('#update_nama').val("");
                        } else {
                            $("#errorMessageModal").removeClass("text-danger").addClass("text-success").html("NIK ditemukan di dukcapil");
                            $('#update_nama').val(obj.result.result.nama);
                            $('#form_update').append('<button type="submit" class="btn btn-primary" id="btn_submit">Simpan</button>');
                        }
                    } else {
                        $("#errorMessageModal").removeClass("text-success").addClass("text-danger").html(data.message);
                        $('#btn_submit').remove();
                    }
                }
            });
            return false;
        }
    </script>

</body>

</html>