<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="./css/bootstrap.css">

	<link href='./css/font-robot.css' rel='stylesheet' type='text/css'>
	<link href='./css/font-raleway.css' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" type="text/css" href="./css/fontawesome/css/all.css">
	<link href="./css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<title>Login</title>
</head>

<body>
	<div class="sidenav">
		<div class="login-main-text">
			<h2>Polo Marceneiro</h2>
			<p>Faça seu login ou crie sua conta</p>
		</div>
	</div>

	<div class="main">
		<div class="col-md-6 col-sm-12">
			<div class="login-form">
				<form id="frmFormularioPrincipal" novalidate>

					<div class="form-group">
						<label for="email" class="sr-only">Endereço de e-mail</label>
						<input type="email" id="email" name="email" class="form-control" placeholder="Endereço de e-mail" required autofocus>
					</div>
					<div class="form-group">
						<label for="senha" class="sr-only">Senha</label>
						<input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
						<!-- <a href="#" class="stretched-link">Esqueci minha senha!</a> -->
					</div>

					<button class="btn btn-lg btn-primary btn-block" type="button" id="btnEnviarFormulario">Acessar minha conta</button>

					<p><a class="stretched-link recuperarSenha" href="#">Esqueci minha senha</a></p>
					<p>Ainda não é cadastrado? <a href="cadastro_usuario.php" class="stretched-link">Crie sua conta</a></p>

				</form>

				<div class="modal fade" id="modalMessages" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="tituloModal"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="messages">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="modalRecuperarSenha" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="tituloModal">Insira os dados abaixo</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="frmFormularioRecuperarSenha" novalidate>
									<div class="form-group">
										<label for="email" class="sr-only">Endereço de e-mail</label>
										<input type="email" id="email_recuperar" name="email_recuperar" class="form-control" placeholder="Endereço de e-mail" required>
									</div>
									<button class="btn btn-lg btn-primary btn-block" type="button" id="btnEnviarFormularioRecuperarSenha">Recuperar minha senha</button>
								</form>
								<div class="messagesRecuperarSenha">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	<script src="./js/jquery.js"></script>
	<script src="./js/popper.js"></script>
	<script src="./js/bootstrap.js"></script>
	<script src="./js/fontawesome.js"></script>
	<script src="./js/jquery.validate.js"></script>
	<script src="./js/config-global.js"></script>
	<script src="./js/login/login.js"></script>
</body>

</html>