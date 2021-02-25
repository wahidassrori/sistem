<?php require 'header.php'; ?>

<div class="content">
    <div class="header-content">
        <div class="header-content-menu">Gudang</div>
    </div>
    <div class="body-content">
        <div class="halaman-gudang">
            <button class="button-blue button-form-tambah-gudang">Tambah Gudang</button>
            <form id="form-tambah-gudang" class="form-tambah-gudang">
                <label class="label-ve">Gudang</label>
                <input type="text" name="gudang" id="gudang" required>
                <label class="label-ve">Alamat</label>
                <textarea id="alamat"></textarea>
                <input type="submit" class="button-green submit-tambah-gudang" value="Simpan">
            </form>
            <table>
                <thead>
                    <th>No</th>
                    <th>Gudang</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Update</th>
                </thead>
            </table>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>