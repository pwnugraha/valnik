<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data</h1>
    <?= form_open('data', ['class' => 'form-inline']) ?>
    <select class="custom-select my-1 mr-sm-2" name="kec" id="kec">
        <option>Pilih Kecamatan...</option>
        <option value="<?= $current_kec ?>" selected><?= $current_kec ?></option>
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
    <select class="custom-select my-1 mr-sm-2" name="status" id="status">
        <option value="0" <?= $current_status == 0 ? 'selected' : '' ?>>Tampilkan semua status</option>
        <option value="2" <?= $current_status == 2 ? 'selected' : '' ?>>Tampilkan status "Mohon perbaikan"</option>
        <option value="3" <?= $current_status == 3 ? 'selected' : '' ?>>Tampilkan status "Menunggu dicek operator"</option>
        <option value="1" <?= $current_status == 1 ? 'selected' : '' ?>>Tampilkan status "Valid"</option>
    </select>
    <button type="submit" class="btn btn-primary my-1">Tampilkan</button>
    <?= form_close() ?>
    <!-- DataTales Example -->
    <?php
    if (!empty($items)) : ?>
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
                        <tbody>
                            <?php
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
                                            <a class="btn btn-sm btn-secondary" href="#" data-toggle="modal" data-target="#saveModal" id="update" data-idart="<?= $i['id_art'] ?>" data-oldnik="<?= $i['nik_art'] ?>" data-oldname="<?= $i['nama_art'] ?>">
                                                Update
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="mt-5">
            <h5 class="text-center">Tentukan wilayah yang akan ditampilkan datanya.</h5>
        </div>

    <?php
    endif; ?>

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
                <p><strong>Data sebelumnya:</strong></p>
                <p id="update_oldnik"></p>
                <p id="update_oldname"></p>
                <p><strong>Diupdate menjadi:</strong></p>
                <?= form_open('data/save', ['id' => 'form_update']) ?>
                <div class="form-group">
                    <label>Perbaikan NIK</label>
                    <input type="text" class="form-control" id="update_nik" name="update_nik" onkeyup="onKeyRelease()" placeholder="Masukkan NIK" required>
                </div>
                <div class="form-group">
                    <label>Nama Capil</label>
                    <input type="text" class="form-control" disabled id="nama_capil">
                </div>
                <input type="hidden" name="id_art_update" id="id_art_update" value="">
                <input type="hidden" name="update_nama" id="update_nama" value="">
                <input type="hidden" name="kec_update" id="kec_update" value="<?= is_null($current_kec) ? '' : $current_kec ?>">
                <input type="hidden" name="kel_update" id="kel_update" value="<?= is_null($current_kel) ? '' : $current_kel ?>">
                <input type="hidden" name="status_update" id="kel_update" value="<?= $current_status ?>">
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