<?php
require 'core/core.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Password</th>
                <th>Usergrup</th>
                <th>Status</th>
                <th width="30px">Update</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (isset($_GET['tampil']) && $_GET['tampil'] != '') {
                $halaman = $_GET['tampil'];
            } else {
                $halaman = 0;
            }

            $jumlah_tampil = 2;

            $mulai = $halaman * $jumlah_tampil;

            $query = mysqli_query($mysqli, "SELECT * FROM user LIMIT $mulai, $jumlah_tampil");

            $query_all = mysqli_query($mysqli, "SELECT iduser FROM user");
            $jumlah_halaman = mysqli_num_rows($query_all);
            $total_page = floor($jumlah_halaman / $jumlah_tampil);

            echo $total_page;

            if ($halaman >= $total_page) {
                $next = $total_page;
            } else {
                $next = $halaman + 1;
            }

            if ($halaman <= 0) {
                $prev = 0;
            } else {
                $prev = $halaman - 1;
            }

            echo "<a href='?tampil=$prev'>PREV     </a>";
            if ($total_page >= 5) {
                for ($i = 1; $i <= 5; $i++) {
                    echo "<a href='?tampil=$i'>$i</a>     ";
                }
            } else {
                for ($i = 1; $i <= 3; $i++) {
                    echo "<a href='?tampil=$i'>$i</a>";
                }
            }
            echo "<a href='?tampil=$next'>      NEXT</a>";

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
            ?>
        </tbody>
    </table>
    <script>
        updateUsergrupUser();

        function updateUsergrupUser() {
            const up = document.querySelector('#kota');
            up.addEventListener('change', () => {
                alert('hai');
                //getDataUser();
                /*
                fetch('core/proses.php')
                .then(response => response.json())
                .then(res => console.log(res));
                
                fetch('core/proses.php', {
                    method : 'POST',
                    headers : {
                        'Content-Type' : 'application/json'
                    },
                    body : JSON.stringify({
                        proses : 'update_user_usergrup',
                        iduser : e.target.getAttribute('id'),
                        idusergrup : e.target.value
                    })
                })
                .then(response => response.json())
                .then(response => console.log(response));
                */
            });
        }
    </script>
</body>

</html>