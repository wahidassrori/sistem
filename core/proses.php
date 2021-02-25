<?php

require 'core.php';

$result = json_decode(file_get_contents('php://input'), true);

if (isset($result['proses'])) {
    if ($result['proses'] === 'login') {
        $query = mysqli_query($mysqli, "SELECT iduser, username, password,idusergrup FROM user WHERE username='{$result['username']}' AND password='{$result['password']}' AND status='Active'");
        $rows = mysqli_fetch_assoc($query);
        if (mysqli_num_rows($query) > 0) {
            $_SESSION['iduser'] = $rows['iduser'];
            $pesan = ['pesan' => 'sukses'];
        } else {
            $pesan = ['pesan' => 'error'];
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] === 'hak_akses_menu') {
        $query_user = mysqli_query($mysqli, "SELECT idusergrup FROM user WHERE iduser='{$_SESSION['iduser']}'");
        $rows = mysqli_fetch_assoc($query_user);
        $query = mysqli_query($mysqli, "SELECT akses FROM usergrup WHERE idusergrup='{$rows['idusergrup']}'");
        echo json_encode(mysqli_fetch_assoc($query));
    }
    if ($result['proses'] === 'update_user') {
        $query = mysqli_query($mysqli, "SELECT username, password FROM user WHERE iduser='{$result['iduser']}' ");
        echo json_encode(mysqli_fetch_assoc($query));
    }
    if ($result['proses'] === 'simpan_update_user') {
        $query_cekduplikat = mysqli_query($mysqli, "SELECT username, password FROM user WHERE username='{$result['username']}' AND password='{$result['password']}' ");
        if (mysqli_num_rows($query_cekduplikat) > 0) {
            $pesan = ['error' => 'Data gagal diupdate'];
        } else {
            $query = mysqli_query($mysqli, "UPDATE user SET username='{$result['username']}', password='{$result['password']}' WHERE iduser={$result['iduser']}");
            if ($query) {
                $pesan = ['sukses' => 'Data berhasil diupdate'];
            } else {
                $pesan = ['error' => 'Data gagal diupdate'];
            }
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] === 'update_user_usergrup') {
        $query = mysqli_query($mysqli, "UPDATE user SET idusergrup='{$result['idusergrup']}' WHERE iduser='{$result['iduser']}'");
        if ($query) {
            $pesan = ['sukses' => 'Data berhasil diupdate'];
        } else {
            $pesan = ['error' => 'Data Gagal diupdate'];
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] === 'tambah_user') {
        $username = input_validation($result['username']);
        $password = input_validation($result['password']);
        $idusergrup = input_validation($result['id_usergrup']);
        $query = mysqli_query($mysqli, "INSERT INTO user (username, password, idusergrup) VALUES ('$username', '$password', $idusergrup)");
        if ($query) {
            $pesan = ['sukses' => 'Data berhasil disimpan'];
        } else {
            $pesan = ['error' => 'Data gagal disimpan'];
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] == 'data_user') {
        $query = mysqli_query($mysqli, "SELECT * FROM user LIMIT 5");
        $nomor = 0;
        while ($rows = mysqli_fetch_assoc($query)) {
            $nomor++;
            echo '<tr>';
            echo '<td>' . $nomor . '</td>';
            echo '<td>' . $rows['username'] . '</td>';
            echo '<td>' . $rows['password'] . '</td>';
            echo '<td><select name="' . $rows['iduser'] . '" id="usergrup">';
            $query_usergrup = mysqli_query($mysqli, "SELECT idusergrup, usergrup FROM usergrup");
            while ($rows_usergrup = mysqli_fetch_assoc($query_usergrup)) {
                $selected = '';
                if ($rows['idusergrup'] == $rows_usergrup['idusergrup']) {
                    $selected = 'selected';
                }
                echo '<option value="' . $rows_usergrup['idusergrup'] . '" ' . $selected . '>' . $rows_usergrup['usergrup'] . '</option>';
            }
            echo '</select></td>';
            echo '<td><select name="' . $rows['iduser'] . '" class="status-user">';
            if ($rows['status'] === 'Active') {
                echo '<option value="Active" Selected>Active</option>';
                echo '<option value="Inactive">Inactive</option>';
            }
            if ($rows['status'] === 'Inactive') {
                echo '<option value="Active">Active</option>';
                echo '<option value="Inactive"  Selected>Inactive</option>';
            }
            echo '</select></td>';
            //echo '<td>' . $rows['status'] . '</td>';
            echo '<td><button class="button-edit-user button-orange" value="' . $rows['iduser'] . '">Update</button></td>';
            echo '</tr>';
        }
    }

    if ($result['proses'] == 'data_user_baru') {

        if ($result['kondisi'] !== null) {
            $query = mysqli_query($mysqli, "SELECT iduser, username, password, idusergrup, status FROM user WHERE username LIKE '%{$result['kondisi']}%' OR status LIKE '%{$result['kondisi']}%' ORDER BY iduser DESC LIMIT {$result['limit']}");
        } else {
            $query = mysqli_query($mysqli, "SELECT iduser, username, password, idusergrup, status FROM user ORDER BY iduser DESC LIMIT {$result['limit']}");
        }

        if (mysqli_num_rows($query) > 0) {
            while ($rows = mysqli_fetch_assoc($query)) {
                $hasil['data'][] = $rows;
            }
            $query_usergrup = mysqli_query($mysqli, "SELECT idusergrup, usergrup FROM usergrup");
            while ($rows_usergrup = mysqli_fetch_assoc($query_usergrup)) {
                $hasil['usergrup'][] = $rows_usergrup;
            }
        } else {
            $hasil = ['pesan' => 'kosong'];
        }

        echo json_encode($hasil);
    }

    if ($result['proses'] == 'delete_user') {

        $iduser = $result['iduser'];
        $query = mysqli_query($mysqli, "DELETE FROM user WHERE iduser=$iduser");
        if ($query) {
            $pesan = ['sukses' => 'Data berhasil dihapus'];
        } else {
            $pesan = ['error' => 'Error : ' . mysqli_error($mysqli)];
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] == 'update_status_user') {
        $query = mysqli_query($mysqli, "UPDATE user SET status='{$result['status']}' WHERE iduser='{$result['iduser']}'");
        if ($query) {
            $pesan = ['pesan' => 'Data berhasil diupdate'];
        } else {
            $pesan = ['pesan' => 'Error'];
        }
        echo json_encode($pesan);
    }
}

if (isset($result['proses_usergrup'])) {
    if ($result['proses_usergrup'] === 'data_usergrup') {
        $query = mysqli_query($mysqli, "SELECT usergrup, akses FROM usergrup");
        while ($rows = mysqli_fetch_assoc($query)) {
            $res[] = $rows;
        }
        echo json_encode($res);
    }
}
