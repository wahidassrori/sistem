<?php require 'header.php' ?>
<div class="content">
    <div class="header-content">
        <div class="header-content-menu menu-data-user">Data User</div>
        <div class="header-content-menu menu-usergrup">Usergrup</div>
    </div>
    <div class="body-content">
        <div class="halaman-user">
            <div class="content-data-user">
                <button class="button-form-tambah-user button-blue">Tambah User</button>
                <form class="form-tambah-user">
                    <label class="label-ve">Username</label>
                    <input type="text" name="username" autocomplete="off" required>
                    <label class="label-ve">Password</label>
                    <input type="text" name="password" autocomplete="off" required>
                    <label class="label-ve">Usergrup</label>
                    <select name="id_usergrup" id="id-usergrup" required>
                        <option value="">Pilih Usergrup</option>
                        <?php
                        $query = mysqli_query($mysqli, "SELECT idusergrup, usergrup FROM usergrup");
                        while ($rows = mysqli_fetch_assoc($query)) {
                            echo '<option value="' . $rows['idusergrup'] . '">' . $rows['usergrup'] . '</option>';
                        }
                        ?>
                    </select>
                    <input class="button-simpan-user button-green" type="submit" value="Simpan">
                </form>
                <form class="form-update-user">
                    <label class="label-ve">Username</label>
                    <input type="text" name="username" autocomplete="off" required>
                    <label class="label-ve">Password</label>
                    <input type="text" name="password" autocomplete="off" required>
                    <label class="label-ve">Usergrup</label>
                    <select name="idusergrup" id="idusergrup" required>
                        <option value="">Pilih Usergrup</option>
                        <?php
                        $query = mysqli_query($mysqli, "SELECT idusergrup, usergrup FROM usergrup");
                        while ($rows = mysqli_fetch_assoc($query)) {
                            echo '<option value="' . $rows['idusergrup'] . '">' . $rows['usergrup'] . '</option>';
                        }
                        ?>
                    </select>
                    <input class="button-update-user button-orange" type="submit" value="Update">
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Usergrup</th>
                            <th>Status</th>
                            <th width="14%">Update/Delete</th>
                        </tr>
                    </thead>
                    <tbody class="data-user"></tbody>
                </table>
            </div>
            <div class="content-usergrup">
                <form class="form-tambah-usergrup">
                    <label class="label-ve">Usergrup</label>
                    <input type="text" name="usergrup" autocomplete="off" required>
                    <div class="checkbox-akses">
                        <label>
                            <input type="checkbox" value=""> User
                        </label>
                        <label>
                            <input type="checkbox" value=""> Perusahaan
                        </label>
                        <label>
                            <input type="checkbox" value=""> Produk
                        </label>
                        <label>
                            <input type="checkbox" value=""> Penjualan
                        </label>
                        <label>
                            <input type="checkbox" value=""> Pembelian
                        </label>
                        <label>
                            <input type="checkbox" value=""> Laporan
                        </label>
                    </div>
                    <input class="button-tambah-usergrup button-green" type="submit" value="Tambah Usergrup">
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Usergrup</th>
                            <th>Akses</th>
                            <th>Update/Delete</th>
                        </tr>
                    </thead>
                    <tbody class="data-usergrup"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div></div>
</div>
<?php require 'footer.php' ?>