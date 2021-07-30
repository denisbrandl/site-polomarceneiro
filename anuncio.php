<?php
session_start();

require 'vendor/autoload.php';
use SuaMadeira\MaterialController;

if (!isset($_GET['handle'])) {
	die('Acesso não autorizado');
}

$handle = $_GET['handle'];

$arrAnuncio = MaterialController::buscaAnuncio($handle);
$arrAnuncio = json_decode($arrAnuncio, true);

// var_dump($arrAnuncio);

$arrAnuncioAnunciante = MaterialController::buscaAnuncioPorAnunciante($arrAnuncio['handle_usuario']);
$arrAnuncioAnunciante = json_decode($arrAnuncioAnunciante, true);

// print_r($arrAnuncioAnunciante);
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
                                    <a href="home.php#"><i class="fab fa-facebook-f"></i></a>
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
                        <h1><?= $arrAnuncio['titulo']; ?></h1>
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
                    <div class="ads-detail">
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div id="carouselAnuncio" class="carousel slide" data-interval="false" data-ride="carousel">
                                        <!-- Carousel items -->
                                        <div class="carousel-inner" role="listbox">
											<?php
												if (is_array($arrAnuncio['imagem']) && count($arrAnuncio['imagem']) > 0) {
													$aux = 0;
													foreach ($arrAnuncio['imagem'] as $imagem) {
														$classe_active = '';
														if ($aux == 0) {
															$classe_active = 'active';
                                                        }
                                                        
                                                        $imagem_ = $imagem['nome_arquivo'];
                                                        if (!isset($imagem['nome_arquivo']) || !file_exists('./imagens/'.$imagem['nome_arquivo'])) {
                                                            $imagem_ = 'imagem-nao-encontrada.png';
                                                        }                                                        
														echo sprintf(
															'
															<div class="%s carousel-item">
																<img class="d-block w-100" src="./imagens/%s" alt="carousel">
															</div>',
															$classe_active,
															$imagem_
														);
														$aux++;
													}
												} else {
                                                    echo '
                                                    <div class="active carousel-item">
                                                        <img class="d-block w-100" src="./imagens/imagem-nao-encontrada.png" alt="carousel">
                                                    </div>';                                          
                                                }
											?>
                                        </div>
                                        <!-- Carousel nav -->
                                        <a class="carousel-control-prev" href="#carouselAnuncio" role="button" data-slide="prev">
											<span class="carousel-control-prev-icon" aria-hidden="true"></span>
											<span class="sr-only">Anterior</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselAnuncio" role="button" data-slide="next">
											<span class="carousel-control-next-icon" aria-hidden="true"></span>
											<span class="sr-only">Próximo</span>
										</a>				
                                    </div><!--end carousel-->
                            </div>
                        </div>
						
                        <div class="ad-detail">
                            <div class="ad-detail-head">
                                <i class="fa fa-info fa-2x"></i>
                                <h4 class="ad-cat">Detalhes do anúncio</h4>
							</div>
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Marca:</p>
                                <p class="pull-right light"><?= $arrAnuncio['marca'];?></p>
                            </div>							
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Linha</p>
                                <p class="pull-right light"><?= $arrAnuncio['linha'];?></p>
							</div>
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Cor</p>
                                <p class="pull-right light"><?= $arrAnuncio['cor'];?></p>
							</div>			
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Categoria</p>
                                <p class="pull-right light"><?= $arrAnuncio['categoria'];?></p>
							</div>			
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Subcategoria</p>
                                <p class="pull-right light"><?= $arrAnuncio['subcategoria'];?></p>
                            </div>																		
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Quantidade disponível</p>
                                <p class="pull-right light"><?= $arrAnuncio['quantidade_venda'];?></p>
                            </div>
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Dimensões (em mm)</p>
                                <p class="pull-right light"><?= $arrAnuncio['largura'];?> X <?= $arrAnuncio['altura'];?></p>
                            </div>
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Espessura (em mm)</p>
                                <p class="pull-right light"><?= $arrAnuncio['espessura'];?> mm</p>
                            </div>							
                            <div class="ad-detail-info clearfix">
                                <p class="pull-left">Vende somente estoque inteiro?</p>
                                <p class="pull-right light"><?= $arrAnuncio['descricao_situacao_venda'];?></p>
                            </div>
                            <div class="ad-detail-info clearfix">
                                <p>Descrição:</p>
                                <p class="light line"><?= !empty($arrAnuncio['descricao']) ? $arrAnuncio['descricao'] : '<i>Não informado</i>';?></p>
                            </div>

                        </div><!--end ad-detail-->
						
                        <div class="author-detail">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="author-avatar">
                                        <img style="width:120px;height:120px;" src="imagens/<?= $arrAnuncio['handle_usuario'];?>.png" alt="author avatar">
                                    </div>
                                    <div class="author-name">
                                        <p><?= $arrAnuncio['nome_fantasia'];?></p>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="author-detail-right">
                                        <div class="author-info">
                                            <i class="fa fa-map-marker"></i>
                                            <p><?= $arrAnuncio['cidade'];?>/<?= $arrAnuncio['uf'];?></p>
                                        </div>
                                        <div class="author-info">
                                            <i class="fa fa-phone"></i>
                                            <p>Telefone: <a class="telefoneAnunciante" href="tel:+55<?= preg_replace( '/[^0-9]/', '', $arrAnuncio['telefone'] );?>"><?= $arrAnuncio['telefone'];?></a></p>
                                            <br>
											<button type="button" data-whats="+55<?= preg_replace( '/[^0-9]/', '', $arrAnuncio['telefone'] );?>" class="msgWhatsapp btn btn-primary btn-success">Conversar no whatsapp</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

						<!--form submission-->
						<div id="form2">
							<h4 class="inner-heading">ENTRE EM CONTATO COM O ANUNCIANTE</h4>
							<form method="post" id="frmFormularioPrincipal">

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Seu nome" aria-label="Seu nome" aria-describedby="basic-addon1">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" aria-label="E-mail" aria-describedby="basic-addon2">
                                </div>   

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" aria-label="Telefone" aria-describedby="basic-addon3">
                                </div>                                 

                                <div class="form-group">
                                    <label for="mensagem">Mensagem</label>
                                    <textarea class="form-control" id="mensagem" name="mensagem" rows="3"></textarea>
                                </div>

                                <button type="button" id="enviarMensagem" class="btn btn-dark">Enviar</button>
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
									<div class="modal-footer">
									  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
									</div>
								  </div>
								</div>
							  </div>							
						</div>
						<!--end form submission-->						
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="sidebar">

                        <div class="side-widget clearfix">
                            <h4 class="inner-heading">Outros anúncios deste anunciante</h4>

                            <?php
                                if (is_array($arrAnuncioAnunciante) && count($arrAnuncioAnunciante) > 0) {
                                    foreach ($arrAnuncioAnunciante as $anuncio_anunciante) {
                                        if (!isset($anuncio_anunciante['imagem']) || !file_exists('./imagens/'.$anuncio_anunciante['imagem']['nome_arquivo'])) {
                                            $imagem = 'imagem-nao-encontrada.png';
                                        } else {
											$imagem = $anuncio_anunciante['imagem']['nome_arquivo'];
										}

                                        echo sprintf(
                                            '<div class="sidebar-latest-ad">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                    <a href="anuncio.php?handle=%s" class="col" style="color:#000;text-decoration:none;"><img style="width:80px;height:80px;" src="imagens/%s" alt="side ads"></a>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <p><a href="anuncio.php?handle=%1$s" class="col" style="color:#000;text-decoration:none;font-weight:bold;padding:0px;">
                                                        %s</a></p>
                                                    </div>
                                                </div>
                                            </div>',
                                            $anuncio_anunciante['id'],
                                            $imagem,
                                            $anuncio_anunciante['titulo']
                                        );
                                    }
                                } else {
                                    echo '<p>Infelizmente não há mais anúncios deste anunciante :-(</p>';
                                }
                            ?>
                        </div><!--end latest ad widget-->
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
	<script>
		var anuncio = '<?= $_GET["handle"];?>';
	</script>
	<script src="./painel/js/jquery.js"></script>
	<script src="./painel/js/popper.js"></script>
	<script src="./painel/js/bootstrap.js"></script>
	<script src="./painel/js/fontawesome.js"></script>
	<script src="./painel/js/owl.carousel.js"></script>
	<script src="./painel/js/default.js"></script>
	<script src="./painel/js/jquery.validate.js"></script>
	<script src="./painel/js/enviar_mensagem.js"></script>
	<script src="./painel/js/config-global.js"></script>
  
	<script>		
	</script>


</body>
</html>