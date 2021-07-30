<?php
	session_start();
	$msg_titulo = 'Crie sua conta';
	$msg_subtitulo = 'Insira seus dados abaixo e crie sua conta <bold>gratuitamente</bold>';
	
	$cd_usuario_sessao = 0;
	if (isset($_SESSION['user_id'])) {
		$cd_usuario_sessao = $_SESSION['user_id'];
	}
	
	if (isset($_GET['usuario']) &&  $cd_usuario_sessao == $_GET['usuario']) {
		$msg_titulo = 'Edite seus dados';
		$msg_subtitulo = '';
	}
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

  <link href="./css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
  
  <title>Minha Conta</title>
  
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
	  <?php if ($cd_usuario_sessao > 0) { ?>
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Acessar painel</a>
      </li>
	  <?php } ?>
      <li>
        <a class="nav-link" href="../home.php" target="_blank">Ir para o site</a>
      </li>
      </ul>
    </div>
  </nav>


  <div class="container">
    <div class="py-5 text-center">
      <h2><?= $msg_titulo;?></h2>
      <p class="lead">
      <?= $msg_subtitulo;?>
      </p>
    </div>



    <div class="col-md-12 order-md-1">
      <form class="needs-validation" id="frmFormularioPrincipal" novalidate enctype="multipart/form-data">
        <h4 class="mb-3">Seus dados</h4>
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="cnpj">CNPJ</label>
            <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="" value="" required>
            <div class="invalid-feedback">
              Informe o CNPJ
            </div>
            <div class="invalid-feedback cnpj-invalido" style="display:none;">
              CNPJ Inválido
            </div>            
          </div>

          <div class="col-md-3 mb-3">
            <label for="inscricao_estadual">Inscrição Estadual</label>
            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" placeholder="" value="" required>
            <div class="invalid-feedback">
              Informe a inscrição estadual
            </div>
          </div>          
        </div>

        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="razao_social">Razão Social</label>
            <input type="text" class="form-control" id="razao_social" name="razao_social" placeholder="" value="" required>
            <div class="invalid-feedback">
              Preencha a razão social
            </div>
          </div>
          <div class="col-md-5 mb-3">
            <label for="nome_fantasia">Nome Fantasia</label>
            <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" placeholder="" value="" required>
            <div class="invalid-feedback">
              Preencha o nome fantasia
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="nome_completo">Nome completo</label>
            <input type="text" class="form-control" id="nome_completo" name="nome_completo" placeholder="" value="" required>
            <div class="invalid-feedback">
              Preencha seu nome completo
            </div>
          </div>

          <div class="col-md-5 mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            <div class="invalid-feedback">
              Preencha seu endereço de e-mail
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="senha">
              Senha
              <?php
              if (isset($_GET['usuario'])) {
                echo '<span style="font-weight:bold;font-size:12px">(Insira uma nova senha se deseja troca-la)</span>';
              }
            ?>
            </label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="" value="" required>
            <div class="invalid-feedback">
              Insira uma senha
            </div>
            <div class="invalid-feedback senhas-diferentes" style="display:none;">
              As duas senhas precisam ser iguais
            </div>
          </div>

          <div class="col-md-5 mb-3">
            <label for="senha2">Confirme a sua senha</label>
            <input type="password" class="form-control" name="senha2" id="senha2" required>
            <div class="invalid-feedback">
              Insira a confirmação da senha
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-5">
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="" value="" required pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}">
            <div class="invalid-feedback">
              Informe um número de telefone
            </div>
          </div>
        </div>
    
        <hr class="mb-4">
		<h4 class="mb-3">Foto para o seu perfil</h4>
		<div class="row py-4">
			<div class="col-lg-6">

				<!-- Upload image input-->
				<div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
					<input id="upload" type="file" onchange="readURL(this);" value="" class="form-control border-0" accept="image/x-png,image/gif,image/jpeg">
					<label id="upload-label" for="upload" class="font-weight-light text-muted">Escolha um arquivo</label>
					<div class="input-group-append">
						<label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Escolha um arquivo</small></label>
					</div>
				</div>

        </div>

			<div class="col-lg-6">
			<!-- Uploaded image area-->
			<div class="image-area mt-4"><img id="imageResult" src="#" alt="" class="img-fluid rounded shadow-sm mx-auto d-block"></div>

			</div>
		</div>		

        <hr class="mb-4">

        <h4 class="mb-3">Endereço</h4>


        <div class="row">
          <div class="col-md-3 col-sm-12">
            <label for="cep">CEP:</label>
            <input type="text" class="form-control" id="cep" name="cep" placeholder="" value="" required>
            <div class="invalid-feedback">
              Insira o seu CEP
            </div>
          </div>

          <div class="col-md-8 col-sm-12">
            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" id="endereco" name="endereco" placeholder="" value="" required>
            <div class="invalid-feedback">
              Insira um endereço válido
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-3 col-sm-12">
            <label for="endereco_numero">Número:</label>
            <input type="text" class="form-control" id="endereco_numero" name="endereco_numero" placeholder="" value="">
          </div>

          <div class="col-md-4 col-sm-12">
            <label for="complemento">Complemento:</label>
            <input type="text" class="form-control" id="complemento" name="complemento" placeholder="" value="">
          </div>

          <div class="col-md-4 col-sm-12">
            <label for="bairro">Bairro:</label>
            <input type="text" class="form-control" id="bairro" name="bairro" placeholder="" value="" required>
            <div class="invalid-feedback">
              Insira o bairro
            </div>
          </div>

        </div>

        <div class="row">


        <div class="col-md-5 col-sm-12">
            <label for="uf">Estado</label>
            <select class="custom-select d-block w-100" id="uf" name="uf" required>
				<option value="">Selecione</option>			
				<option value="AC">Acre</option>									
				<option value="AL">Alagoas</option>									
				<option value="AM">Amazonas</option>									
				<option value="AP">Amapá</option>									
				<option value="BA">Bahia</option>									
				<option value="CE">Ceará</option>									
				<option value="DF">Distrito Federal</option>									
				<option value="ES">Espírito Santo</option>									
				<option value="GO">Goiás</option>									
				<option value="MA">Maranhão</option>									
				<option value="MT">Mato Grosso</option>									
				<option value="MS">Mato Grosso do Sul</option>									
				<option value="MG">Minas Gerais</option>									
				<option value="PA">Pará</option>									
				<option value="PB">Paraíba</option>									
				<option value="PR">Paraná</option>									
				<option value="PE">Pernambuco</option>									
				<option value="PI">Piauí</option>									
				<option value="RJ">Rio de Janeiro</option>									
				<option value="RN">Rio Grande do Norte</option>									
				<option value="RO">Rondônia</option>									
				<option value="RS">Rio Grande do Sul</option>									
				<option value="RR">Roraima</option>									
				<option value="SC">Santa Catarina</option>									
				<option value="SE">Sergipe</option>									
				<option value="SP">São Paulo</option>									
				<option value="TO">Tocantins</option>				
            </select>
            <div class="invalid-feedback">
              Insira o estado (UF)
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <label for="cidade">Cidade:</label>
            <select class="custom-select d-block w-100" id="cidade" name="cidade" required></select>
            <div class="invalid-feedback">
              Insira a cidade
            </div>
          </div>

          

        </div>

        <hr class="mb-4">

        <button class="btn btn-primary btn-lg col-md-3" id="btnEnviarFormulario" type="button">Salvar</button>

      </form>
    </div>
  </div>
	
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>	
	

  <script src="./js/jquery.js"></script>
  <script src="./js/popper.js"></script>
  <script src="./js/bootstrap.js"></script>
  <script src="./js/fontawesome.js"></script>
  <script src="./js/jquery.validate.js"></script>
  <script src="./js/jquery.mask.js"></script>
  <script src="./js/form-validation.js"></script>
  <script src="./js/config-global.js"></script>

  <script>
    <?php
      $usuario = 0;
      if (isset($_GET['usuario']) && $cd_usuario_sessao == $_GET['usuario']) {
        $usuario = $_GET['usuario'];
      }
    ?>
    var usuario = '<?=$usuario;?>';
  </script>    

  <script src="./js/cadastro/cadastro_usuario.js"></script>

</body>

</html>
