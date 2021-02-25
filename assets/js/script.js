window.addEventListener('load', () => { 
    // hamburger menu
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    if (hamburgerMenu) {
        document.querySelector('.hamburger-menu').addEventListener('click', () => hamburgerMenu());
        function hamburgerMenu()
        {
            const sidebar = document.querySelector('.sidebar');
            const gridContainer = document.querySelector('.grid-container');
            gridContainer.classList.toggle('grid-container-toggle-menu');
            sidebar.classList.toggle('sidebar-toggle-menu');
        }   
    }
    // hak akses user
    const halamanIndex = document.querySelector('.grid-container');
    if (halamanIndex) {
        
        hakAksesMenu();

        async function hakAksesMenu()
        {
            fetch('core/proses.php', {
                method : 'POST',
                headers : {
                    'Content-Type' : 'application/json'
                },
                body : JSON.stringify({
                    proses : 'hak_akses_menu'
                })
            })
            .then(response => response.json())
            .then(response => response.akses.split('-'))
            .then(response => {
                response.forEach(item => {
                    document.querySelector(`.${item}`).style.display = 'block';
                })
            });
        }

    }
    
    /* -------------- Halaman Login ------------------ */

    const containerLogin = document.querySelector('.container-login');
    if (containerLogin) {

        document.querySelector('#login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            login();
        });

        async function login()
        {
            const user = document.querySelector('#username').value;
            const pass = document.querySelector('#password').value;
            const sendData = {
                proses : 'login',
                username : user,
                password : pass
            };
            await fetch('core/proses.php',{
                method : 'POST',
                headers : {
                    'Content-Type': 'application/json'
                },
                body : JSON.stringify(sendData)
            })
            .then(response => response.json())
            .then(response => {
                if (response.pesan === 'error') {
                    document.querySelector('.pesan-login').innerHTML = 'Username/password salah!!';
                    document.querySelector('.pesan-login').style.color = 'red';
                    setTimeout(() => {
                        document.querySelector('.pesan-login').style.display = 'none';        
                    }, 2000);
                    document.querySelector('.pesan-login').style.display = 'block';
                    document.querySelector('#login-form').reset();
                } else {
                    window.location.replace('index.php');
                }
            });
        }
    }
    
    /* -------------- Halaman User ------------------ */

    const halamanUser = document.querySelector('.halaman-user');
    if (halamanUser)
    {
        // menu tab
        document.querySelector('.menu-data-user').addEventListener('click', () => {
            document.querySelector('.content-data-user').style.display = 'block';
            document.querySelector('.content-usergrup').style.display = 'none';
        });

        document.querySelector('.menu-usergrup').addEventListener('click', () => {
            document.querySelector('.content-data-user').style.display = 'none';
            document.querySelector('.content-usergrup').style.display = 'block';
        });
        // menampilkan form tambah user
        document.querySelector('.button-form-tambah-user').addEventListener('click', () => buttonFormTambahUser());
        function buttonFormTambahUser()
        {
        const addUserForm = document.querySelector('.form-tambah-user');
        addUserForm.classList.toggle('form-tambah-user-toggle');
        }

        document.querySelector('#jumlah-tampil-data-user').addEventListener('change', () => showDataUser());
        document.querySelector('.search-data-user').addEventListener('input', (e) => {
            const sendData = e.target.value;
            showDataUser(sendData);
        });

        // menampilkan data user
        showDataUser();

        function showDataUser(where=null)
        {   
            const jumlahTampilDataUser = document.querySelector('#jumlah-tampil-data-user').value;
            fetch('core/proses.php', {
                method : 'POST',
                headers : { 'Content-Type' : 'application/json' },
                body : JSON.stringify({
                    proses : 'data_user_baru',
                    kondisi : where,
                    limit : jumlahTampilDataUser
                })
            })
            .then(res => res.json())
            .then(res => {
                let data = '';
                if (typeof res.pesan === 'undefined') {
                    let status = '';
                    let nomor = 0;
                    res.data.forEach(value => {
                    nomor++;
                    data += `
                        <tr>
                            <td>${nomor}</td>
                            <td>${value['username']}</td>
                            <td>${value['password']}</td>
                            <td>
                            <select name="${value['iduser']}" id="usergrup">
                            `;

                            let idusergrup = value['idusergrup'];
                            res.usergrup.forEach(value => {
                            
                                if (idusergrup === value['idusergrup']) {
                                    selected = 'selected';
                                }
                                else {
                                    selected = '';
                                }

                                data +=`
                                <option value="${value['idusergrup']}" ${selected}>${value['usergrup']}</option>
                                `;

                            });
                            data +=`
                            </select>
                            </td>
                            <td><select name="${value['iduser']}" class="status-user">`;

                            if (value['status'] === 'Active') {
                               status =`<option value="Active" Selected>Active</option>
                                        <option value="Inactive">Inactive</option>`;
                            }
                            else {
                                status =`<option value="Active">Active</option>
                                        <option value="Inactive"  Selected>Inactive</option>`;
                            }
                            
                            data +=`${status}</select></td>
                            <td><button class="button-edit-user button-orange button-small" value="${value['iduser']}">Update</button></td>
                        </tr>
                    `;
                });
                } else {
                    data = `<tr><td colspan="6" align="center">Data tidak ditemukan!</td></tr>`;
                }
                document.querySelector('.data-user').innerHTML = data;
            })
            .then(() => {
                deleteUser();
                editUser();
                updateUsergrupUser()
                updateStatusUser();
            });
            
        }

        // update user
        function editUser() {
            const buttonEditUser = document.querySelectorAll('.button-edit-user');
            for (let i = 0; i < buttonEditUser.length; i++) {
                buttonEditUser[i].addEventListener('click', (e) => {
                    document.querySelector('.update-user').style.display = 'block';
                    fetch('core/proses.php', {
                        method : 'POST',
                        headers : {
                            'Content-Type' : 'application/json'
                        },
                        body : JSON.stringify({
                            proses : 'update_user',
                            iduser : buttonEditUser[i].value
                        })
                    })
                    .then(response => response.json())
                    .then(response => {
                        let data = `
                            <form class="form-update-user">
                            <label class="label-ve">Username</label>
                            <input type="text" name="username" id="username" value="${response.username}" required>
                            <label class="label-ve">Password</label>
                            <input type="text" name="password" id="password" value="${response.password}" required>
                            <input class="button-update-user button-orange" type="submit" value="Update">
                            </form>
                        `;
                        document.querySelector('.update-user').innerHTML = data;
                    })
                    .then(() => {
                        document.querySelector('.form-update-user').addEventListener('submit', (e) => {
                            e.preventDefault();
                            const userName = document.querySelector('#username').value;
                            const passWord = document.querySelector('#password').value;
                            fetch('core/proses.php', {
                                method : 'POST',
                                headers : {
                                    'Content-Type' : 'application/json'
                                },
                                body : JSON.stringify({
                                    proses : 'simpan_update_user',
                                    iduser : buttonEditUser[i].value,
                                    username : userName,
                                    password : passWord
                                })
                            })
                            .then(response => response.json())
                            .then(response => {
                                let result = Object.keys(response).toString();
                                if (result === 'sukses') {
                                    document.querySelector('.update-user').style.display = 'none';
                                    document.querySelector('.pesan').innerHTML = response.sukses;
                                    document.querySelector('.pesan').style.color = 'green';
                                    document.querySelector('.pesan').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan').style.display = 'none';        
                                    }, 1000);
                                    showDataUser();
                                } else {
                                    document.querySelector('.pesan').innerHTML = response.error;
                                    document.querySelector('.pesan').style.color = 'red';
                                    document.querySelector('.pesan').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan').style.display = 'none';        
                                    }, 1000);
                                    showDataUser();
                                }
                            });
                        });
                    });
                });   
            }
        }
        // delete user tidak digunakan
        // diganti dengan active dan inactive 
        function deleteUser() {
            const buttonDeleteUser = document.querySelectorAll('.button-delete-user');
            for (let i = 0; i < buttonDeleteUser.length; i++) {
                buttonDeleteUser[i].addEventListener('click', async() => {
                    let id = buttonDeleteUser[i].value;
                    let sendData = {
                        proses : 'delete_user',
                        iduser : id
                    };
                    let data = await fetch('core/proses.php', {
                        method : 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                            //'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body : JSON.stringify(sendData)
                    })
                    .then(response => response.json())
                    .then(() => showDataUser());
                });   
            }
        }
        // update usergrup user (select box)
        function updateUsergrupUser()
        {
            let up = document.querySelectorAll('#usergrup');
            for (let i = 0; i < up.length; i++) {
                up[i].addEventListener('change', (e) => {
                    fetch('core/proses.php', {
                        method : 'POST',
                        headers : {
                            'Content-Type' : 'application/json'
                        },
                        body : JSON.stringify({
                            proses : 'update_user_usergrup',
                            iduser : e.target.getAttribute('name'),
                            idusergrup : e.target.value
                        })
                    })
                    .then(response => response.json())
                    .then(response => {
                        let result = Object.keys(response).toString();
                                if (result === 'sukses') {        
                                    document.querySelector('.update-user').style.display = 'none';
                                    document.querySelector('.pesan').innerHTML = response.sukses;
                                    document.querySelector('.pesan').style.color = 'green';
                                    document.querySelector('.pesan').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan').style.display = 'none';        
                                    }, 1000);
                                    showDataUser();
                                } else {
                                    document.querySelector('.pesan').innerHTML = response.error;
                                    document.querySelector('.pesan').style.color = 'red';
                                    document.querySelector('.pesan').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan').style.display = 'none';        
                                    }, 1000);
                                    showDataUser();
                                }
                    });
                });
            }
        }
        // submit tambah user
        const submitSimpanUser = document.querySelector('.form-tambah-user');
        submitSimpanUser.addEventListener('submit', (e) => {
            e.preventDefault();
            tambahUser();
        });
        function tambahUser()
        {   
            const formData = document.querySelector('.form-tambah-user');
            const dataForm = new FormData(formData);
            dataForm.append('proses', 'tambah_user');
            let data = {};
            dataForm.forEach((value, index) => {
                data[index] = value;
            });
            fetch('core/proses.php', {
                method : 'POST',
                headers : {
                    'Content-Type' : 'application/json'
                },
                body : JSON.stringify(data)
            })
            .then(response => response.json())
            .then(response => {
                let result = Object.keys(response).toString();
                                if (result === 'sukses') {        
                                    document.querySelector('.update-user').style.display = 'none';
                                    document.querySelector('.pesan-tambah-user').innerHTML = response.sukses;
                                    document.querySelector('.pesan-tambah-user').style.color = 'green';
                                    document.querySelector('.pesan-tambah-user').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan-tambah-user').style.display = 'none';        
                                    }, 1000);
                                    document.querySelector('.form-tambah-user').reset();
                                    showDataUser();
                                } else {
                                    document.querySelector('.pesan-tambah-user').innerHTML = response.error;
                                    document.querySelector('.pesan-tambah-user').style.color = 'red';
                                    document.querySelector('.pesan-tambah-user').style.display = 'block';
                                    setTimeout(() => {
                                        document.querySelector('.pesan-tambah-user').style.display = 'none';        
                                    }, 1000);
                                    showDataUser();
                                }
            });
        }
        // update status user (select box)
        function updateStatusUser()
        {
            const statusUser = document.querySelectorAll('.status-user');
            for (let i = 0; i < statusUser.length; i++) {
                statusUser[i].addEventListener('change', (e) => {
                    const iduser = e.target.getAttribute('name');
                    const statusValue = e.target.value;
                    fetch('core/proses.php', {
                        method : 'POST',
                        headers : {
                            'Content-Type' : 'application/json'
                        },
                        body : JSON.stringify({
                            proses : 'update_status_user',
                            iduser : iduser,
                            status : statusValue
                        })
                    })
                    .then(response => response.json())
                    .then(response => alert(response.pesan))
                });
            }
        }

        /*----------------- USERGRUP -----------------*/
        // Menampilkan data usergrup

        dataUsergrup();

        function dataUsergrup()
        {
            fetch('core/proses.php', {
                method : 'POST',
                headers : {
                    'Content-Type' : 'application/json'
                },
                body : JSON.stringify({proses_usergrup : 'data_usergrup'})
            })
            .then(response => response.json())
            .then(response => {
                let data = '';
                let nomor =0;
                for (let i = 0; i < response.length; i++) {
                    nomor += 1;
                    data += `
                            <tr>
                                <td>${nomor}</td>
                                <td>${response[i].usergrup}</td>
                                <td>${response[i].akses}</td>
                                <td>Update</td>
                            </tr>
                        `;    
                }
                document.querySelector('.data-usergrup').innerHTML = data;
            });
        }

    }


    /* -------------- Halaman Gudang ------------------ */

    const halamanGudang = document.querySelector('.halaman-gudang');
    if (halamanGudang)
    {
        
        document.querySelector('.button-form-tambah-gudang').addEventListener('click', () => {
            const button = document.querySelector('#form-tambah-gudang');
            button.classList.toggle('form-toggle');
        });

    }

});