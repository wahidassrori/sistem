<?php

require 'core.php';

$result = json_decode(file_get_contents('php://input'), true);

if (isset($result['proses'])) {
    if ($result['proses'] === 'login') {
        $query = mysqli_query($mysqli, "SELECT username, password,idusergrup FROM user WHERE username='{$result['username']}' AND password='{$result['password']}' AND status='Active'");
        $rows = mysqli_fetch_assoc($query);
        if (mysqli_num_rows($query) > 0) {
            $_SESSION['username_login'] = $result['username'];
            $_SESSION['idusergrup'] = $rows['idusergrup'];
            $pesan = ['pesan' => 'sukses'];
        } else {
            $pesan = ['pesan' => 'error'];
        }
        echo json_encode($pesan);
    }
    if ($result['proses'] === 'hak_akses_menu') {
        $query = mysqli_query($mysqli, "SELECT akses FROM usergrup WHERE idusergrup='{$_SESSION['idusergrup']}'");
        echo json_encode(mysqli_fetch_assoc($query));
    }
    if ($result['proses'] === 'update_user') {
        $query = mysqli_query($mysqli, "SELECT username, password FROM user WHERE iduser='{$result['iduser']}' ");
        echo json_encode(mysqli_fetch_assoc($query));
    }
}

if (isset($_POST['proses']) && $_POST['proses'] == 'tambah_user') {
    $username = input_validation($_POST['username']);
    $password = input_validation($_POST['password']);
    $idusergrup = input_validation($_POST['id_usergrup']);
    $query = mysqli_query($mysqli, "INSERT INTO user (username, password, idusergrup) VALUES ('$username', '$password', $idusergrup)");
    if ($query) {
        $pesan = ['sukses' => 'Data berhasil dihapus'];
    } else {
        $pesan = ['error' => 'Error : ' . mysqli_error($mysqli)];
    }
    echo json_encode($pesan);
}
if (isset($result['proses']) && $result['proses'] == 'data_user') {
    $query = mysqli_query($mysqli, "SELECT * FROM user");
    $nomor = 0;
    while ($rows = mysqli_fetch_assoc($query)) {
        $nomor++;
        echo '<tr>';
        echo '<td>' . $nomor . '</td>';
        echo '<td>' . $rows['username'] . '</td>';
        echo '<td>' . $rows['password'] . '</td>';
        echo '<td><select name="usergrup">';
        $query_usergrup = mysqli_query($mysqli, "SELECT idusergrup, usergrup FROM usergrup");
        while ($rows_usergrup = mysqli_fetch_assoc($query_usergrup)) {
            $selected = '';
            if ($rows['idusergrup'] == $rows_usergrup['idusergrup']) {
                $selected = 'selected';
            }
            echo '<option value="' . $rows_usergrup['idusergrup'] . '" ' . $selected . '>' . $rows_usergrup['usergrup'] . '</option>';
        }
        echo '</select></td>';
        echo '<td>' . $rows['status'] . '</td>';
        echo '<td><button class="button-edit-user button-orange" value="' . $rows['iduser'] . '">Update</button> <button class="button-delete-user button-red" value="' . $rows['iduser'] . '">Delete</button></td>';
        echo '</tr>';
    }
}
if (isset($result['proses']) && $result['proses'] == 'delete_user') {

    $iduser = $result['iduser'];
    $query = mysqli_query($mysqli, "DELETE FROM user WHERE iduser=$iduser");
    if ($query) {
        $pesan = ['sukses' => 'Data berhasil dihapus'];
    } else {
        $pesan = ['error' => 'Error : ' . mysqli_error($mysqli)];
    }
    echo json_encode($pesan);
}
