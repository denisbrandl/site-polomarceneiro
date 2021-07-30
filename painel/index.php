<?php
	session_start();
	if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
	} else {
		Header('Location: login.php');
		die;
	}
	
	$grupo = $_SESSION['grupo'] ?: 0;
?>
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

  <title>Painel</title>
  
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="col-md-6">
      <a class="navbar-brand" href="#">
        Polo Marceneiro
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a href="../home.php" target="_blank">Ir para o site</a> | 
        </li>
        <li class="nav-item">
			      &nbsp; Olá <?= $_SESSION['username']; ?> (<a href="" class="logout">Sair da conta)</a>
        </li>		
      </ul>
    </div>
  </nav>


  <div class="container">
    <div class="py-5 text-center">
      <h2>Escolha uma opção</h2>
    </div>

	<div class="row">
	  <div class="col-sm-6">
		<div class="card">
		  <div class="card-body">
			<h5 class="card-title"><i class="fas fa-industry"></i> Materiais</h5>
			<a href="listar_material.php" class="btn btn-primary">Acessar</a>
		  </div>
		</div>
	  </div>
	
	  <div class="col-sm-6">
		<div class="card">
		  <div class="card-body">
			<h5 class="card-title"><i class="fas fa-user"></i> Minha Conta</h5>
      <a href="cadastro_usuario.php?usuario=<?= $_SESSION['user_id']; ?>" class="btn btn-primary">Acessar</a>
      
		  </div>
		</div>
    </div>
    
	<?php if ($grupo == 2) { ?>
	<div class="col-sm-6 mt-3">
		<div class="card">
		  <div class="card-body">
			<h5 class="card-title"><i class="fab fa-accusoft"></i> Marca > Linha > Cor</h5>
      <a href="listar_marca_linha_cor.php" class="btn btn-primary">Acessar</a>
      
		  </div>
		</div>
    </div>
	
	<div class="col-sm-6 mt-3">
		<div class="card">
		  <div class="card-body">
			<h5 class="card-title"><i class="fas fa-project-diagram"></i> Categoria > Subcategoria</h5>
			<a href="listar_categoria_subcategoria.php" class="btn btn-primary">Acessar</a>
      
		  </div>
		</div>
    </div>	
	
	<div class="col-sm-6 mt-3">
		<div class="card">
		  <div class="card-body">
			<h5 class="card-title"><i class="fas fa-text-height"></i> Espessuras</h5>
			<a href="listar_espessura.php" class="btn btn-primary">Acessar</a>
      
		  </div>
		</div>
    </div>	
	
	<?php } ?>
        
	</div>


  </div>
	

  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="./js/jquery.js"></script>
  <script src="./js/popper.js"></script>
  <script src="./js/bootstrap.js"></script>
  <script src="./js/fontawesome.js"></script>
  <script src="./js/config-global.js"></script>
  <script src="./js/login/login.js"></script>


</body>

</html>
