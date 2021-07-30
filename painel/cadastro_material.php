<?php
	session_start();
	if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
	} else {
		Header('Location: login.php');
		die;
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
  
  <style>
	label {
		float: left;
	}
  </style>
  
  <title>Cadastro de Material</title>

  <style>
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) center no-repeat;
    }
    body{
        text-align: center;
    }
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }  
  </style>

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
          <a class="nav-link" href="index.php">Painel <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="listar_material.php">Lista de material</a>
        </li>		
      </ul>
    </div>
  </nav>


  <div class="container">
    <div class="py-5 text-center">
      <h2>Cadastro de material</h2>
    </div>



    <div class="col-md-12 order-md-1">
      <form class="needs-validation" id="frmFormularioPrincipal" novalidate enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="" value="" required>
          </div>
        </div>
		
        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="descricao">Descrição do anúncio</label>
            <textarea class="form-control" id="descricao" name="descricao" placeholder="Insira informações detalhadas referente ao material que será anunciado" rows="3"></textarea>
          </div>
        </div>		

        <div class="row">
          <div class="col-md-6 col-sm-12">
            <label for="categoria">Categoria</label>
            <select class="custom-select d-block w-100" id="id_categoria" name="id_categoria" required></select>
          </div>

          <div class="col-md-6 col-sm-12">
            <label for="sub_categoria">Subcategoria</label>
            <select class="custom-select d-block w-100" id="id_subcategoria" name="id_subcategoria" required></select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-12">
            <label for="marca">Marca</label>
            <select class="custom-select d-block w-100" id="id_marca" name="id_marca">
            </select>
          </div>

          <div class="col-md-3 col-sm-12">
            <label for="linha">Linha</label>
            <select class="custom-select d-block w-100" id="id_linha" name="id_linha">
            </select>
          </div>

          <div class="col-md-3 col-sm-12">
            <label for="cor">Cor</label>
            <select class="custom-select d-block w-100" id="id_cor" name="id_cor">
            </select>
          </div>
		  
          <div class="col-md-3 col-sm-12 exibirImagemCategoria" style="display:none;">
            Pré visualização do material
            <div class="preVisualizacaoImagemCategoria">
			</div>
          </div>		  
		  
        </div>

        <hr class="mb-4">

        <h4 class="mb-3">Medidas</h4>

        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="largura">Largura (em mm)</label>
            <input type="number" class="form-control" id="largura" name="largura" placeholder="" value="">
          </div>

          <div class="col-md-3 mb-3">
            <label for="altura">Altura (em mm)</label>
            <input type="number" class="form-control" id="altura" name="altura" placeholder="" value="">
          </div>
		  
          <div class="col-md-3 mb-3">
            <label for="id_espessura">Espessura (em mm)</label>
            <select class="custom-select d-block w-100" id="id_espessura" name="id_espessura" required>
			<option value=""></option>
			</select>
          </div>

          <div class="col-md-3 mb-3">
            <label for="profundidade">M²</label>
            <input type="number" disabled class="form-control" id="profundidade" name="profundidade" placeholder="" value="">
          </div>
        </div>



        <hr class="mb-4">
        <h4 class="mb-3">Adicione imagens do material</h4>
        <p class="alert alert-warning text-left">
          Tamanho máximo do arquivo: 2MB <br>
          Dimensão maxima da imagem sugerido: 500x500px <br>
          Tipos suportados: png, jpg, gif <br>
		  Máximo de <strong>2</strong> imagens por anúncio
        </p>

        <div class="row alert alert-danger alertaArquivoInvalido" style="display:none;">
        </div>        

        <div class="row py-4 uploadImagens">
          <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
            <input id="upload" name="files[]" type="file" value="" class="form-control border-0" accept="image/x-png,image/gif,image/jpeg" multiple>
            <label id="upload-label" for="upload" class="font-weight-light text-muted">Escolha um arquivo</label>
            <div class="input-group-append">
              <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Escolha um arquivo</small></label>
            </div>
          </div>
        </div>
		
		<p class="alert alert-danger text-left uploadImagensDesabilitado" style="display:none;"> O número máximo de imagens foi esgotado! </p>


        <div class="row text-center text-lg-left" id="imageResult"></div>



        <hr class="mb-4">
		
		<h4 class="text-left"> Informações para anunciar o material </h4>
		<div class="row">		 
		
			<div class="col-md-3 mb-3">
				<label for="quantidade">Quantidade em estoque</label>
				<div class="input-group">
				  <input type="number" class="form-control col-md-6" id="quantidade" name="quantidade" value="0">
				  <select class="custom-select d-block w-100 col-md-6" id="unidade_medida" name="unidade_medida">
					<option value="1" selected>Unidades</option>
				  </select>
				</div>
			</div>		
		
			<div class="col-md-3 mb-3">
				<label for="quantidade_venda">Quantidade a venda</label>
				<div class="input-group">
					<input type="number" class="form-control col-md-6" id="quantidade_venda" name="quantidade_venda" placeholder="" value="0">
				</div>
			</div>
			
			<div class="col-md-6 mb-6">
				<label for="tipo_venda">Venda completo/parcial ?</label>
				<div class="input-group">
				  <select class="custom-select d-block w-100 col-md-6" id="tipo_venda" name="tipo_venda">
					<option value="1">Total</option>
					<option value="2">Parcial</option>
					<option value="3">Ambos</option>
				  </select>
				 <small class="form-text text-muted">
				   Informe se você fará a venda somente da <strong>quantidade inteira</strong> ou vende de forma <strong>parcial</strong>.
				 </small>				  
				</div>
			</div>
		</div>
		
		<div class="row panelDisponivelVenda" style="display:none;">
			<div class="col-md-3 mb-3">
				<label for="situacao_anuncio">Produto disponível para venda?</label>
				<div class="input-group">
				  <select class="custom-select d-block w-100 col-md-6" id="situacao_anuncio" name="situacao_anuncio">
					<option value="0">Não</option>
					<option value="1">Sim</option>
				  </select>

				</div>
			</div>
		</div>
		
		
        <hr class="mb-4">
		

        <button class="btn btn-primary btn-lg col-md-3" id="btnEnviarFormulario" type="button">Salvar</button>
        <a class="btn btn-secondary btn-lg col-md-3" href="listar_material.php" role="button">Voltar</a>

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

  <div class="overlay"><h2>Aguarde ... Carregando dados</h2></div>

  <script src="./js/jquery.js"></script>
  <script src="./js/popper.js"></script>
  <script src="./js/bootstrap.js"></script>
  <script src="./js/fontawesome.js"></script>
  <script src="./js/jquery.validate.js"></script>
  <script src="./js/jquery.mask.js"></script>
  <script src="./js/config-global.js"></script>

  <script>
    <?php
      $material = 0;
      if (isset($_GET['material'])) {
        $material = $_GET['material'];
	    echo '$("body").addClass("loading");';
      }
    ?>
    var material = '<?=$material;?>';

  </script>  
  
  <script src="./js/cadastro/cadastro_material.js"></script>
</body>

</html>