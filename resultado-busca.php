<?php
session_start();

require 'vendor/autoload.php';
use SuaMadeira\MaterialController;

$arrFiltrosSelecionados = array();
$arrFiltrosPossiveis = array('categoria', 'subcategoria', 'marca', 'linha', 'cor', 'cidade', 'limite_busca', 'palavra_chave');
foreach ($_GET as $key => $filtro_busca) {

    if (!in_array($key, $arrFiltrosPossiveis)) {
		continue;
    }
	
	if ($key !== 'palavra_chave') {
		if ($filtro_busca == '0') {
			continue;
		}
	} else {
		if (empty($filtro_busca)) {
			continue;
		}			
	}
	
    $arrFiltrosSelecionados[$key] = $filtro_busca;
}
$arrAnunciosCategoria = MaterialController::buscaAnunciosComFiltro($arrFiltrosSelecionados);
$arrAnunciosCategoria = json_decode($arrAnunciosCategoria, true);

$quantidade_anuncios_categoria = count($arrAnunciosCategoria);

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

    <!--main sub page heading-->
    <section id="page-head" class="main-heading text-center">
        <div class="container">
			
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-heading">
                        <h1>Resultado da busca</h1>
                    </div>
                </div>
            </div>			
			
        </div>
    </section><!--end main page heading-->

    <!--Detail -->
    <section id="detail">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
					<?php
						if ($quantidade_anuncios_categoria > 0) {
					?>
                    <!--Related Ads-->
                    <div id="relatedAds">
                        <h4 class="inner-heading"><?= str_pad($quantidade_anuncios_categoria, 2, '0', STR_PAD_LEFT);;?> <?php echo ($quantidade_anuncios_categoria == 1) ? 'ANÚNCIO' : 'ANÚNCIOS'; ?> </h4>
                        <div class="row">
                            <div class="col-md-12 content content1">
                                <div class="row">
									<?php
										if (is_array($arrAnunciosCategoria)) {
											foreach ($arrAnunciosCategoria as $anuncio) {
                                                if (!isset($anuncio['imagem']) || !file_exists('./imagens/'.$anuncio['imagem']['nome_arquivo'])) {
													if (!isset($anuncio['cor_imagem']) || !file_exists('./imagens/categorias/'.$anuncio['cor_imagem'])) {														
														$imagem = 'imagem-nao-encontrada.png';
													} else {
														$imagem = 'categorias/'.$anuncio['cor_imagem'];
													}
                                                } else {
													$imagem = $anuncio['imagem']['nome_arquivo'];
												}

												echo '<div class="col-md-4 col-sm-4 adp">';
												echo '<div class="ads">';
												echo sprintf('<a href="anuncio.php?handle='.$anuncio['id'].'"><img style="width:200px; height:200px;" src="%s"></a>', './imagens/'.$imagem);
												echo sprintf('<div class="ads-title_"><p><a href="#">%s</a></p></div>', $anuncio['titulo']);
												echo '<a href="anuncio.php?handle='.$anuncio['id'].'" class="ads-hover">';
													echo '<span>'.$anuncio['nome_fantasia'].'</span>';
													echo '<i class="fa fas fa-archive fa-2x"></i>';
												echo '</a>';
												echo '</div>';
												echo '</div>';
											}
										}
									?>
                                </div>
                            </div>
						</div>
                    </div><!--end related ads-->
                        <div class="row">
                            <div class="pagi">
                                <ul class="pagination">
                                    <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                                    <li class="page-item active"><span class="page-link">1</span></li>
                                    <li class="page-item"><a class="page-link" href="subcategory.html#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="subcategory.html#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="subcategory.html#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="subcategory.html#">5</a></li>
                                    <li class="page-item"><a class="page-link" href="subcategory.html#">Próximo</a></li>
                                </ul>
                            </div>
                        </div>
						<?php } else { ?>
							<h4> Nenhum anúncio encontrado para a busca selecionada :-( </h4>
						<?php } ?>
                    </div>
                <div class="col-md-4">
                    <div class="sidebar">


                        <!--advertisement-->
                        <div class="side-widget">
                            <h4 class="inner-heading">ANÚNCIOS</h4>
                            <div class="side-widget-adv">
                                <a href="subcategory.html#"><img src="imagens/banners/crie-sua-conta.png" alt="google ads"></a>
                            </div>
                        </div><!--end advertisement widget-->

                    </div>
                </div>
            </div>
        </div>
    </section><!--end details-->

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
	<script src="./painel/js/default.js"></script>
	<script src="./painel/js/config-global.js"></script>
  
	<script>
		$("#categoria").change(function() {
			consultaSubCategorias();
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