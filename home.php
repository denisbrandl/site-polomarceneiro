<?php
session_start();

require 'vendor/autoload.php';
use SuaMadeira\MaterialController;
use SuaMadeira\CategoriaController;
use SuaMadeira\MunicipioController;

$arrAnunciosRecentes = MaterialController::buscaAnuncioRecente();
$arrAnunciosRecentes = json_decode($arrAnunciosRecentes, true);

$arrCategorias = CategoriaController::buscaArvoreCategorias();
$arrCategorias = json_decode($arrCategorias, true);


$arrMunicipiosComAnuncios = MunicipioController::buscaMunicipiosComAnuncio();
$arrMunicipiosComAnuncios = json_decode($arrMunicipiosComAnuncios, true);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Polo Marceneiro</title>
	<link rel="shortcut icon" href="imagens/sistema/favicon.ico" type="image/x-icon" />
	
	<link href='./painel/css/font-robot.css' rel='stylesheet' type='text/css'>
	<link href='./painel/css/font-raleway.css' rel='stylesheet' type='text/css'>	
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="./painel/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="./painel/css/bootstrap-theme.css">

	<link rel="stylesheet" type="text/css" href="./painel/css/fontawesome/css/all.css">	

    <link rel="stylesheet" type="text/css" href="painel/css/default.css">
    <link rel="stylesheet" type="text/css" href="painel/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="painel/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="painel/css/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="painel/css/content/styles.css">


</head>
<body>

    <!--Start Header section-->
    <section id="header">
        <header>
            <div class="top-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 responsive-width-top">
                            <div class="social">
                                <div class="social-icon">
                                    <a href="home.php#"><i class="fa fas-facebook"></i></a>
                                    <a href="home.php#"><i class="fa fa-twitter"></i></a>
                                    <a href="home.php#"><i class="fa fa-linkedin"></i></a>
                                    <a href="home.php#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 responsive-width-top">
                            <div class="links text-right">
                                <a href="painel/cadastro_usuario.php">Criar uma conta</a>
                                
								<a href="painel/index.php">Entrar com minha conta</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end top bar-->

            <!--start menu-bar-->
            <div class="menu-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="logo">
                                <a href="home.php"><h4>Polo Marceneiro</h4></a>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <nav role="navigation" class="navbar navbar-expand-lg navbar-light">
                                <!-- Brand and toggle get grouped for better mobile display -->
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
								<span class="navbar-toggler-icon"></span>
								</button>
                                <!-- Collection of nav links and other content for toggling -->
                                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="home.php">Home</a>

                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre</a></li>
                                        <li class="nav-item"><a class="nav-link" href="categorias.php">Categorias</a></li>
                                                                               
 										<li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
                                    </ul>
                                </div>

                            </nav><!--end nav-->
                        </div>
                    </div>
                </div>
            </div><!--end menu-bar-->
        </header>
    </section><!--End Header section-->

    <!--start Layer slider-->
    <section id="slider">
        &nbsp;
    </section><!--end layer slider section-->

    <!--search bar-->
    <section id="searchBar">
        <div class="container">
            <div class="row">				
                <div class="col-md-12">
					<h3 class="text-light"> O que você procura? </h3>
					<form action="resultado-busca.php" method="GET">
						<div class="form-row">
							<div class="form-group col-md-2">
							  <label for="categoria">Categoria</label>
							  <select id="categoria" name="categoria" class="form-control">
								<option selected value="0">Todos</option>
								<?php
									if (is_array($arrCategorias)) {
										foreach ($arrCategorias as $categoria_pai) {
											echo sprintf('<option value="%s">%s</option>', $categoria_pai['id'] , $categoria_pai['nome']).chr(13).chr(10);
										}
									}
								?>
							  </select>
							</div>
							<div class="form-group col-md-2">
							  <label for="subcategoria">SubCategoria</label>
							  <select id="subcategoria" name="subcategoria" class="form-control">
								<option selected value="0">Todos</option>
							  </select>
							</div>
							<div class="form-group col-md-2">
							  <label for="marca">Marca</label>
							  <select id="marca" name="marca" class="form-control">
								<option value="0">Todos</option>
							  </select>
							</div>						
							<div class="form-group col-md-2">
							  <label for="linha">Linha</label>
							  <select id="linha" name="linha" class="form-control">
								<option selected value="0">Todos</option>
							  </select>
							</div>						
							<div class="form-group col-md-2">
							  <label for="cor">Cor</label>
							  <select id="cor" name="cor" class="form-control">
								<option selected value="0">Todos</option>
							  </select>
							</div>												
							<div class="form-group col-md-2">
							  <label for="cidade">Cidade</label>
							  <select id="cidade" name="cidade" class="form-control">
								<option selected value="0">Todos</option>
								<?php
									$estado_atual = null;
									if (is_array($arrMunicipiosComAnuncios)) {
										foreach ($arrMunicipiosComAnuncios as $estado => $municipios) {
											if ($estado_atual != $estado && $estado_atual != null) {
												echo '</optgroup>';
											}
											if ($estado_atual != $estado) {
												echo sprintf('<optgroup label="%s">', $estado);
											}
											foreach ($municipios as $codigo_ibge => $nome) {
												echo sprintf('<option value="%s">%s</option>', $codigo_ibge, $nome);
											}
											
											$estado_atual = $estado;
										}
										echo '</optgroup>';
									}
								?>
							  </select>
							  
								<label for="formControlRange">Limite de busca</label>
								<input type="range" name="limite_busca" class="form-control-range" id="formControlRange" min="0" max="500" value="0" step="10" oninput="this.nextElementSibling.value = this.value">
								<output>0</output> <span class="text-light">KM</span>
							</div>																		
						</div>
						  <div class="form-group">
							<input type="text" class="form-control" value="" name="palavra_chave" id="palavra_chave" placeholder="Estou buscando ...">
						  </div>			
							<button type="submit" class="btn btn-dark">Buscar</button>						  
					</form>
                </div>
            </div>
        </div>
    </section> <!--end search bar-->

    <!--Anúncios Recentes-->
    <section id="premium">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-heading text-center">
                        <h2>ANÚNCIOS RECENTES</h2>
                    </div>
                </div>
            </div>
            <div class="row">
				<?php 
					if (is_array($arrAnunciosRecentes)) {
						echo '<div class="owl-carousel">';
						foreach ($arrAnunciosRecentes as $anuncios) {
							if (!isset($anuncios['imagem']) || !file_exists('./imagens/'.$anuncios['imagem']['nome_arquivo'])) {
								$imagem = 'imagem-nao-encontrada.png';
							} else {
								$imagem = $anuncios['imagem']['nome_arquivo'];
							}
							echo sprintf(
								'<div class="item">
									<a href="anuncio.php?handle=%s"><img style="width:500px;height:500px;" src="%s" alt="carousel"></a>
									<div class="item-title"><a href="anuncio.php?handle=%1$s">%s</a></div>
									<a href="anuncio.php?handle=%1$s" class="item-hover"><span>%s</span></a>
								</div>',
								$anuncios['id'],
								'./imagens/'.$imagem,
								$anuncios['descricao_cor'],
								$anuncios['nome_fantasia']
							);
						}
						echo '</div>';
					}
				?>
				
            </div>
        </div>
    </section>
	<!-- FIM Anúncios Recentes-->

    <!--Category-->
    <section id="categories">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-heading text-center">
                        <h2>CATEGORIAS</h2>
                    </div>
                </div>
            </div>
            <div class="row">
				<?php
					if (is_array($arrCategorias)) {
						foreach ($arrCategorias as $categoria_pai) {
							// print_r($categoria_pai);exit;
							echo '<div class="col-md-3 col-sm-6">';
							echo '<div class="category">
									<div class="category-icon">
										<i class="fa fa-briefcase fa-2x"></i>
									</div>';							
							echo sprintf('<h4><a href="anuncios-categoria.php?categoria=%s">%s</a></h4>', $categoria_pai['id'] , $categoria_pai['nome']);
							
							if (is_array($categoria_pai['subcategorias'])) {
								foreach ($categoria_pai['subcategorias'] as $subcategoria) {
									echo sprintf('<p><a href="anuncios-categoria.php?categoria=%s">%s (%s)</a></p>', $subcategoria['id_categoria'] , $subcategoria['descricao'], $subcategoria['qtdAnuncio']);
								}
							}
							
							echo '</div>';
							echo '</div>';
						}
					}
				
				?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="advertise">
                        <a href="home.php#"><img src="imagens/banners/banner-publicitario.png" alt="ad"> </a>
                    </div>
                </div>
            </div>
        </div>
    </section><!--End Category-->

    <!--Footer-->
    <section id="footer">
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="widget">
                            <h4>SOBRE</h4>
                            <p>Lorem ipsum dolor sit amet sectetuer esl adipiscing elit sed diam nonummy nibhi euismod tincidunt ut <span class="col">laoreet dolore</span> amet magna aliquam erat volutpat. </p>
                            <p>Ut wisi enim ad minim veniam quis dest nostrud exerci tation ullamcorper norme
                                suscipit lobortis commodo consequat.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6">
                        <div class="widget">
                            <h4>NEWSLETTER</h4>
                            <p class="sign">Lorem ipsum dolor sit amet sectetuer in adipiscing elit sed diam...</p>
                            <p class="sign">Sign up for the newsletter !</p>
                            <form method="post">
                                <input type="email" name="email" placeholder="Seu endereço de e-mail...">
                                <input type="submit" name="submit" value="Assinar">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright text-center">
                            <p><?= '@'.date('Y');?> Todos os direitos reservados para Polo Marceneiro</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="home.php#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
        </footer>
    </section>


	<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	<script src="./painel/js/jquery.js"></script>
	<script src="./painel/js/popper.js"></script>
	<script src="./painel/js/bootstrap.js"></script>
	<script src="./painel/js/fontawesome.js"></script>
	<script src="./painel/js/owl.carousel.js"></script>
	<script src="./painel/js/jquery.autocomplete.js"></script>
	<script src="./painel/js/default.js"></script>
	<script src="./painel/js/config-global.js"></script>
  
	<script>
		$("#categoria").change(function() {
			consultaSubCategorias();
		});	
		
		$('#palavra_chave').autocomplete({
			minChars: 3,
			showNoSuggestionNotice: true,
			noSuggestionNotice: 'Nenhum registro encontrado',
			onSearchStart: function(params) {
				console.log('Iniciando a busca');
			},
			onSearchComplete: function(query, suggestions) {
				console.log('Busca finalizada');
			},
			groupBy: 'category',
			serviceUrl: url_global+'/api/BuscaMaterialAutoComplete/'
		});
		
		function consultaSubCategorias(subCategoriaSelecionar = '') {
			$('#subcategoria').find('option').remove().end().append('<option value="">Carregando informações...</option>');
			$.getJSON(url_global+"/api/BuscaSubCategorias/"+$('#categoria').val(), function(dados){
				$('#subcategoria').find('option').remove().end();
				$('#subcategoria').append('<option value="0">Selecione</option>');
				$.each(dados,function(key, value) 
				{	
					selected = '';
					if (value.id_categoria == subCategoriaSelecionar) {
						selected = 'selected';
					}			
					$('#subcategoria').append('<option ' + selected + ' value=' + value.id_categoria + '>' + value.descricao + '</option>');
				});
			});   
		}

		$.getJSON(url_global+"/api/BuscaMarcas", function(dados){    
			$.each(dados,function(key, value) 
			{
				$('#marca').append('<option value=' + value.id_marca + '>' + value.descricao + '</option>');
			});
			consultaLinhas();
		});

		$("#marca").change(function() {
			consultaLinhas();
		});
		function consultaLinhas() {
			if ($('#marca').val() == '0') {
				return false;
			}
			$('#linha').find('option').remove().end().append('<option value="">Carregando informações...</option>');
			$.getJSON(url_global+"/api/BuscaLinha/"+$('#marca').val(), function(dados){
				$('#linha').find('option').remove().end();
				$('#linha').append('<option value="0">Todos</option>');
				$.each(dados,function(key, value) 
				{			
					$('#linha').append('<option value=' + value.id_linha + '>' + value.descricao + '</option>');
				});    
				consultaCores();
			});
		}
		
		$("#linha").change(function() {
			consultaCores();
		});		
		function consultaCores(corSelecionar = '') {
			if ($('#linha').val() == '0') {
				return false;
			}			
			$('#cor').find('option').remove().end().append('<option value="">Carregando informações...</option>');
			$.getJSON(url_global+"/api/BuscaCor/"+$('#linha').val(), function(dados){
				$('#cor').find('option').remove().end();
				$('#cor').append('<option value="0">Todos</option>');
				$.each(dados,function(key, value) 
				{

					selected = '';
					if (value.id_cor == corSelecionar) {
						selected = 'selected';
					}
					$('#cor').append('<option ' + selected + ' value=' + value.id_cor + '>' + value.descricao + '</option>');
				});    
			});
		}		
	</script>


</body>
</html>