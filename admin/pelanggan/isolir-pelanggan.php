<?php
if(isset($_GET['kode'])){
            include "mikrotik/function.php";
            $mt->updatePaket($_GET['kode'],"p-ISOLIR"); 
            $sql_isolir = "UPDATE `tb_pelanggan` SET `id_paket` = NULL WHERE `tb_pelanggan`.`id_pelanggan` = '".$_GET['kode']."'";
            $query_isolir = mysqli_query($koneksi, $sql_isolir);

            if ($query_isolir) {
                echo "<script>
                Swal.fire({title: 'isolir Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=data-pelanggan';
                    }
                })</script>";
                }else{
                echo "<script>
                Swal.fire({title: 'isolir Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=data-pelanggan';
                    }
                })</script>";
            }
        }

