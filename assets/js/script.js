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

    // halaman login
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
                } else {
                    window.location.replace('index.php');
                }
            });
        }
    }

    // halaman user
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

        // Menampilkan data user
        getDataUser();

        async function getDataUser()
        {   
            let sendData = {
                proses : 'data_user'
            };
            let data = await fetch('core/proses.php', {
                method : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                    //'Content-Type': 'application/x-www-form-urlencoded'
                },
                body : JSON.stringify(sendData)
            })
            .then(response => response.text())
            .then(response => document.querySelector('.data-user').innerHTML = response)
            .then(() => deleteUser())
            .then(() => editUser());
        }

        function editUser() {
            const buttonEditUser = document.querySelectorAll('.button-edit-user');
            for (let i = 0; i < buttonEditUser.length; i++) {
                buttonEditUser[i].addEventListener('click', () => {
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
                    .then(response => console.log(response));
                });   
            }
        }
        
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
                    .then(() => getDataUser());
                });   
            }
        }

        

        const submitSimpanUser = document.querySelector('.form-tambah-user');
        submitSimpanUser.addEventListener('submit', async function (e) {
            e.preventDefault();          
            submitForm('.form-tambah-user', 'core/proses.php', {proses:'tambah_user'})
            .then(response => console.log(response))
            .then(() => getDataUser());
        });

        async function submitForm(form=null, url, array=null)
        {   
            const formData = document.querySelector(form);
            const dataForm = new FormData(formData);
            if (array !== null) {
                for(index in array) {
                    dataForm.append(index, array[index]);
                }
            }
            let data = await fetch(url, {
                method : 'POST',
                body : dataForm
            });
            return await data.text();
        }

        

    }

});