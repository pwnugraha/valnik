<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data</h1>
    <?= form_open('', ['class' => 'form-inline']) ?>
    <select class="custom-select my-1 mr-sm-2" name="kec" id="kec">
        <option <?= is_null($current_kec) ? 'selected' : '' ?>>Pilih Kecamatan...</option>
        <?php if (!empty($kec)) :
            foreach ($kec as $i) : ?>
                <option value="<?= $i['kec'] ?>" <?= $current_kec == $i['kec'] ? 'selected' : '' ?>><?= $i['kec'] ?></option>
        <?php
            endforeach;
        endif; ?>
    </select>
    <select class="custom-select my-1 mr-sm-2" name="kel" id="kel">
        <option>Pilih Desa...</option>
        <?php if (!empty($data_kel)) :
            foreach ($data_kel as $i) : ?>
                <option value="<?= $i['kel'] ?>" <?= $current_kel == $i['kel'] ? 'selected' : '' ?>><?= $i['kel'] ?></option>
        <?php
            endforeach;
        endif; ?>
    </select>
    <button type="submit" class="btn btn-primary my-1">Tampilkan</button>
    <?= form_close() ?>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status</th>
                            <th>ID DTKS</th>
                            <th>ID ART</th>
                            <th>Alamat</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Perbaikan NIK</th>
                            <th>Perbaikan Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php
                    if (!empty($items)) :
                        echo '<tbody>';
                        $no = 1;
                        foreach ($items as $i) :
                    ?>

                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= get_status($i['status']) ?></td>
                                <td><?= $i['id_dtks'] ?></td>
                                <td><?= $i['id_art'] ?></td>
                                <td><?= $i['alamat'] ?></td>
                                <td><?= $i['nik_art'] ?></td>
                                <td><?= $i['nama_art'] ?></td>
                                <td><?= $i['update_nik'] ?></td>
                                <td><?= $i['update_nama'] ?></td>
                                <td><?php if ($i['status'] == 2) : ?>
                                        <a class="btn btn-sm btn-secondary" href="#" data-toggle="modal" data-target="#saveModal" id="update" data-idart="<?= $i['id_art'] ?>">
                                            Update
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php endforeach;
                        echo '</tbody>';
                    endif; ?>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger" id="errorMessageModal"></p>
                <?= form_open('data/save', ['id' => 'form_update']) ?>
                <div class="form-group">
                    <label>Perbaikan NIK</label>
                    <input type="text" class="form-control" id="update_nik" name="update_nik">
                </div>
                <div class="form-group">
                    <label>Perbaikan Nama</label>
                    <input type="text" class="form-control" id="update_nama" name="update_nama">
                </div>
                <input type="hidden" name="id_art_update" id="id_art_update" value="">
                <input type="hidden" name="kec_update" id="kec_update" value="<?= is_null($current_kec) ? '' : $current_kec ?>">
                <input type="hidden" name="kel_update" id="kel_update" value="<?= is_null($current_kel) ? '' : $current_kel ?>">
                <button type="button" class="btn btn-secondary" onclick="checkCapil()">Cek Capil</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?php
function get_status($status)
{
    switch ($status) {
        case 1:
            return '<span class="badge badge-success">Valid</span>';
        case 2:
            return '<span class="badge badge-secondary">Mohon perbaikan</span>';
        case 3:
            return '<span class="badge badge-info">Menunggu dicek operator</span>';
    }
}
?>