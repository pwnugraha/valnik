<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name=”robots” content=”noindex,nofollow” />

    <title>VALNIK</title>
    <link rel="icon" href="<?= base_url() ?>assets/img/favico.png" type="image/gif">

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet">

    <!-- table style -->
    <link href="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Valnik Dashboard</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Tables -->
            <?php if (in_array($authorization_group, [1, 2])) { ?>
                <li class="nav-item <?= $this->uri->segment(1) == 'entry' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('entry') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Entry DTKS</span></a>
                </li>
                <li class="nav-item <?= $this->uri->segment(1) == 'entry_pbi' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('entry_pbi') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Entry PBI</span></a>
                </li>
            <?php } ?>

            <?php if ($authorization_group == 3) { ?>
                <li class="nav-item <?= $this->uri->segment(1) == 'data' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('data') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Data DTKS</span></a>
                </li>
                <li class="nav-item <?= $this->uri->segment(1) == 'data_pbi' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('data_pbi') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Data PBI</span></a>
                </li>
            <?php } ?>

            <!-- Nav Item - Dashboard -->
            <?php if ($authorization_group == 1) { ?>
                <li class="nav-item <?= $this->uri->segment(1) == 'statistik' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('statistik') ?>">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Statistik</span></a>
                </li>
            <?php } ?>
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

                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                                <span class="mr-2 d-lg-inline text-gray-600 small">Logout</span>
                            </a>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <?php $this->load->view($child_template) ?>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Valnik Dashboard v1.2.0</span>
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
    <script src="<?= base_url('assets/') ?>vendor/chart.js/utils.js"></script>


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
                    url: '<?= base_url() ?>' + '<?= $this->uri->segment(1) ?>' + '/get_kel',
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
                var oldNik = $(this).attr("data-oldnik");
                var oldName = $(this).attr("data-oldname");
                $("#id_art_update").val(idArt);
                $("#update_oldnik").html('NIK ART : ' + oldNik);
                $("#update_oldname").html('Nama ART : ' + oldName);
            });

            $(document).on("click", "#update_entry", function() {
                var idArt = $(this).attr("data-idart");
                var oldNik = $(this).attr("data-oldnik");
                var oldName = $(this).attr("data-oldname");
                var newNik = $(this).attr("data-newnik");
                var newName = $(this).attr("data-newname");
                $("#id_art_update").val(idArt);
                $("#update_idart").html('ID ART : ' + idArt);
                $("#update_oldnik").html('NIK ART : ' + oldNik);
                $("#update_oldname").html('Nama ART : ' + oldName);
                $("#update_newnik").html('Perbaikan NIK ART : ' + newNik);
                $("#update_newname").html('Perbaikan Nama ART : ' + newName);
            });

            $('#saveModal').on('hide.bs.modal', function(e) {
                $("#id_art_update").val("");
                $("#nama_capil").val("");
                $("#update_nik").val("");
                $("#update_oldnik").html('');
                $("#update_oldname").html('');
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
                            $('#nama_capil').val(obj.result.result.nama);
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

        function onKeyRelease() {
            $('#update_nama').val("");
            $('#nama_capil').val("");
            $("#errorMessageModal").html("");
            $('#btn_submit').remove();
        }

        $(document).on("click", "#history", function() {
            var art_id = $(this).attr('data-idart')
            $.ajax({
                url: '<?= base_url() ?>' + '<?= $this->uri->segment(1) ?>' + '/get_history',
                method: "POST",
                data: {
                    art_id: art_id
                },
                dataType: 'json',
                success: function(data) {
                    var html = '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"><thead><tr><th>No</th><th style="width:15%">Waktu</th><th>Aktivitas</th></tr></thead><tbody>';
                    if (data.status) {
                        var no = 1;
                        $.each(data.data, function(index, value) {
                            var date = new Date(value.created_at * 1000);
                            html += '<tr><td>' + no + '</td><td>' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + '</td><td>' + value.data + '</td></tr>';
                            no++;

                        });
                        html += '</tbody></table>';

                        $('#tableHistory').html(html);
                    } else {
                        $('#tableHistory').html('<p class="text-center">Belum memiliki riwayat aktivitas</p>');
                    }
                }
            });
        });
    </script>
    <?php if ($authorization_group == 1) { ?>
        <script>
            var MONTHS = ['Bambanglipuro', 'Banguntapan', 'Bantul', 'Dlingo', 'Imogiri', 'Jetis', 'Kasihan', 'Kretek', 'Pajangan', 'Pandak', 'Piyungan', 'Pleret', 'Pundong', 'Sanden', 'Sedayu', 'Sewon', 'Srandakan'];
            var color = Chart.helpers.color;
            var barChartData = {
                labels: ['Bambanglipuro', 'Banguntapan', 'Bantul', 'Dlingo', 'Imogiri', 'Jetis', 'Kasihan', 'Kretek', 'Pajangan', 'Pandak', 'Piyungan', 'Pleret', 'Pundong', 'Sanden', 'Sedayu', 'Sewon', 'Srandakan'],
                datasets: [{
                    label: 'Valid',
                    backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.green,
                    borderWidth: 1,
                    data: [
                        <?= $grafik_valid ?>
                    ]
                }, {
                    label: 'Perbaikan Data',
                    backgroundColor: color(window.chartColors.grey).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.grey,
                    borderWidth: 1,
                    data: [
                        <?= $grafik_perbaikan ?>
                    ]
                }, {
                    label: 'Dicek Operator',
                    backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.blue,
                    borderWidth: 1,
                    data: [
                        <?= $grafik_entri ?>
                    ]
                }, {
                    label: 'Konsolidasi NIK',
                    backgroundColor: color(window.chartColors.yellow).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.yellow,
                    borderWidth: 1,
                    data: [
                        <?= $grafik_konsolidasi ?>
                    ]
                }]

            };

            window.onload = function() {
                var ctx = document.getElementById('myBarChart').getContext('2d');
                window.myBar = new Chart(ctx, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Status Data'
                        }
                    }
                });

            };

            var colorNames = Object.keys(window.chartColors);
        </script>
    <?php } ?>

</body>

</html>