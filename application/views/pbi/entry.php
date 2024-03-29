<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data</h1>
    <?= form_open('entry_pbi', ['class' => 'form-inline']) ?>
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
    <select class="custom-select my-1 mr-sm-2" name="status" id="status">
        <option value="0" <?= $current_status == 0 ? 'selected' : '' ?>>Tampilkan semua status</option>
        <option value="2" <?= $current_status == 2 ? 'selected' : '' ?>>Tampilkan status "Mohon perbaikan"</option>
        <option value="3" <?= $current_status == 3 ? 'selected' : '' ?>>Tampilkan status "Menunggu dicek operator"</option>
        <option value="1" <?= $current_status == 1 ? 'selected' : '' ?>>Tampilkan status "Valid"</option>
        <option value="4" <?= $current_status == 4 ? 'selected' : '' ?>>Tampilkan status "Sedang diajukan konsolidasi NIK"</option>
        <option value="5" <?= $current_status == 5 ? 'selected' : '' ?>>Tampilkan status "Tidak bisa dientri (Perbaikan meliputi NIK dan Nama)"</option>
        <option value="6" <?= $current_status == 6 ? 'selected' : '' ?>>Tampilkan status "ID ART tidak ditemukan di SIKS Online"</option>
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
                                <th>Aksi</th>
                                <th>ID ART</th>
                                <th>Alamat</th>
                                <th>NIK ART</th>
                                <th>Nama ART</th>
                                <th>Perbaikan NIK</th>
                                <th>Perbaikan Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($items as $i) :
                            ?>

                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= get_status($i['status']) ?>
                                        <div class="block">
                                            <a class="btn btn-sm btn-default" href="#" data-toggle="modal" data-target="#historyModal" id="history" data-idart="<?= $i['id'] ?>">
                                                Lihat Riwayat
                                            </a>
                                        </div>
                                    </td>
                                    <td><?php if ($i['status'] == 3 || $i['status'] == 5 || $i['status'] == 6) : ?>
                                            <a class="btn btn-sm btn-info" href="#" data-toggle="modal" data-target="#saveModal" id="update_entry" data-idart="<?= $i['id_art'] ?>" data-oldnik="<?= $i['nik_art'] ?>" data-oldname="<?= $i['nama_art'] ?>" data-newnik="<?= $i['update_nik'] ?>" data-newname="<?= $i['update_nama'] ?>">
                                                Update
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $i['id_art'] ?></td>
                                    <td><?= $i['alamat'] ?></td>
                                    <td><?= $i['nik_art'] ?></td>
                                    <td><?= $i['nama_art'] ?></td>
                                    <td><?= $i['update_nik'] ?></td>
                                    <td><?= $i['update_nama'] ?></td>
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
            <h5 class="text-center">Data Kosong. Tentukan wilayah dan status yang akan ditampilkan datanya.</h5>
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
                <p><strong>Data awal:</strong></p>
                <p id="update_idart"></p>
                <p id="update_oldnik"></p>
                <p id="update_oldname"></p>
                <p><strong>Diupdate menjadi:</strong></p>
                <p class="text-info" id="update_newnik"></p>
                <p class="text-info" id="update_newname"></p>
                <p style="margin-bottom: 0;"><strong>Hasil Entry (pilih salah satu):</strong></p>
                <?= form_open('entry_pbi/save', ['id' => 'form_update']) ?>
                <select class="custom-select my-2 mr-sm-2" name="status_valid" id="status_valid">
                    <option value="1">Data Valid berhasil dientri</option>
                    <option value="4">Data tidak berhasil dientri. Ajukan konsolidasi NIK</option>
                    <option value="2">Perbaikan data belum meyakinkan. Ajukan perbaikan kembali</option>
                    <option value="5">Tidak bisa dientri (Perbaikan NIK dan Nama)</option>
                    <option value="6">ID ART tidak ditemukan di SIKS Online</option>
                </select>
                <input type="hidden" name="id_art_update" id="id_art_update" value="">
                <input type="hidden" name="kec_update" id="kec_update" value="<?= is_null($current_kec) ? '' : $current_kec ?>">
                <input type="hidden" name="kel_update" id="kel_update" value="<?= is_null($current_kel) ? '' : $current_kel ?>">
                <input type="hidden" name="status_update" id="status_update" value="<?= $current_status ?>">
                <button type="submit" class="btn btn-primary">Submit</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Riwayat</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="tableHistory">
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
        case 4:
            return '<span class="badge badge-warning">Sedang diajukan konsolidasi NIK</span>';
        case 5:
            return '<span class="badge badge-danger">Tidak bisa dientri (Perbaikan NIK dan Nama)</span>';
        case 6:
            return '<span class="badge badge-danger">ID ART tidak ditemukan di SIKS Online</span>';
    }
}
?>