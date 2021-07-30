<?php
session_start();

require 'vendor/autoload.php';

use SuaMadeira\UsuarioController;

if (!isset($_GET['hash']) || empty($_GET['hash'])) {
    die('Acesso negado');
}

$_SESSION['hash'] = $_GET['hash'];

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
            </div>
            <!--end top bar-->

            <!--start menu-bar-->
            <div class="menu-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="logo">
                                <a href="home.php">
                                    <h4>Polo Marceneiro</h4>
                                </a>
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

                            </nav>
                            <!--end nav-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end menu-bar-->
        </header>
    </section>
    <!--End Header section-->

    <!--start Layer slider-->
    <section id="slider">
        &nbsp;
    </section>
    <!--end layer slider section-->

    <section id="page-head" class="main-heading text-center">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="page-heading">
                        <h1>Recuperação de senha</h1>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="detail">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <!--form submission-->
                    <div id="form2">
                        <form method="post" id="frmFormularioPrincipal">

                            <div class="input-group mb-3">
                                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite a nova senha" aria-label="Digite a nova senha" aria-describedby="basic-addon1">
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="senha2" id="senha2" class="form-control" placeholder="Repita a nova senha" aria-label="Repita a nova senha" aria-describedby="basic-addon2">
                            </div>

                            <button type="button" id="recuperar" class="btn btn-dark">Recuperar Senha</button>
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
        </div>
        </div>
    </section>

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
                            <p><?= '@' . date('Y'); ?> Todos os direitos reservados para Polo Marceneiro</p>
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
    <script src="./painel/js/recuperar_senha.js"></script>

    <script>
    </script>


</body>

</html>