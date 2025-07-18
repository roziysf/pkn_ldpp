<?php
include "inc/koneksi.php";
   
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Aplikasi PKN-LDP</title>
	<link rel="icon" href="dist/img/logo1.png">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">


</head>

<body class="text-center"  style="background-image: url('dist/img/bg.jpg');">
	<div class="login-box" >
		<div class="login-logo">

		</div>
		<!-- /.login-logo -->
		<div class="login-box-body" style= "background: #ADD8E6;border-radius: 20px">
			<center>
				<img src="dist/img/logo.png" width=170px />
				<marquee><h4>
					<b>
						APLIKASI TAGIHAN INTERNET</b> | <i>Pastikan menggunakan user dan password dengan benar!</i>
					</marquee>
				</h4>
				<BR>
			</center>
			<form action="#" method="post">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" style= "border-radius: 10px" name="email" placeholder="E-Mail" required>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" style= "border-radius: 10px" name="password" placeholder="Password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">

					</div>
					<!-- /.col -->
					<div class="box-footer" style= "background: #ADD8E6">
						<button type="submit" class="btn btn-primary btn-block btn-flat" style= "border-radius: 10px" name="btnLogin" title="Masuk Sistem">
							<b>M A S U K</b>
						</button>
						<br>
				<div class="row">
					<!-- <div class="col-xs-8"> -->

					</div>
					<!-- /alternatif menggunakan tombol lupa password -->
					<!-- <div class="box-footer" style= "background: #DCDCDC">
						<a href="https://api.whatsapp.com/send?phone=6289652885753&text=*Gunakan%20Nomor%20yang%20terdaftar%20di%20BJ-NET*%0A_BJ-NET%20tidak%20menerima%20perubahan%20atau%20permintaan%20password%20tanpa%20menggunakan%20nomor%20WA%20yang%20terdaftar%20di%20kami._%0A-------------------%0APermisi%20admin%20BJ-NET,%0AMohon%20info%20Password%20atas%20nama%20pelanggan:%20.......... %0A%0ATerima%20Kasih." class="btn btn-gray btn-block btn-flat">
							<b>Lupa Password? | <img src="dist/img/wa3.png"></b></a>
					</div> -->	
						<br>
						
						<a href="login.php" title="Masuk Halaman Adminintrator" class="btn btn-default btn-sm">
						<i class="glyphicon glyphicon-user"> Admin</i>
						</a>
					</div></div>
					<!-- /.col -->
				</div>
			</form>
			<!-- /.social-auth-links -->

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 2.2.3 -->
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<!-- sweet alert -->

</body>

</html>


<?php 
		if (isset($_POST['btnLogin'])) {  

		$email=mysqli_real_escape_string($koneksi,$_POST['email']);
		$password=mysqli_real_escape_string($koneksi,$_POST['password']);


		$sql_login = "SELECT * FROM tb_pelanggan WHERE BINARY email='$email' AND password='$password'";
		$query_login = mysqli_query($koneksi, $sql_login);
		$data_login = mysqli_fetch_array($query_login,MYSQLI_BOTH);
		$jumlah_login = mysqli_num_rows($query_login);
        

            if ($jumlah_login == 1 ){
              session_start();
			  $_SESSION["ses_id"]=$data_login["id_pelanggan"];
			  $_SESSION["ses_email"]=$data_login["email"];
              $_SESSION["ses_nama"]=$data_login["nama"];
			  $_SESSION["ses_password"]=$data_login["password"];
			  $_SESSION["ses_level"]=$data_login["level"];
                
              echo "<script>
                    Swal.fire({title: 'Login Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.value) {
                            window.location = 'aplication.php';
                        }
                    })</script>";
              }else{
              echo "<script>
                    Swal.fire({title: 'Login Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.value) {
                            window.location = 'member.php';
                        }
                    })</script>";
                }
			  }
