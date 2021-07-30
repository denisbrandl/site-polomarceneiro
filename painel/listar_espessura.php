<?php
session_start();
if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) { } else {
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
  <!--<link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>-->
  <link rel="stylesheet" type="text/css" href="./css/dataTables.bootstrap4.css" />

  <link href='./css/font-robot.css' rel='stylesheet' type='text/css'>
  <link href='./css/font-raleway.css' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" type="text/css" href="./css/fontawesome/css/all.css">
  <link rel="stylesheet" type="text/css" href="./css/default.css">

  <title>Painel</title>

  <style>
    .overlay {
      display: none;
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 999;
      background: rgba(255, 255, 255, 0.8) center no-repeat;
    }

    body {
      text-align: center;
    }

    /* Turn off scrollbar when body element has the loading class */
    body.loading {
      overflow: hidden;
    }

    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay {
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
          <a class="nav-link" href="index.php">Painel</a>
        </li>
        <li>
          <a class="nav-link" href="../home.php" target="_blank">Ir para o site</a>
        </li>

        <li class="nav-link">
          &nbsp; Olá <?= $_SESSION['username']; ?> (<a href="" class="logout">Sair da conta)</a>
        </li>
      </ul>
    </div>
  </nav>


  <div class="container">
    <div class="py-5 text-center">
      <h2>Espessuras</h2>
    </div>

    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownAdicionarEspessura" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Adicionar
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownAdicionarEspessura">
        <button class="btn btn-block editarEspessura" role="link" type="button" name="op" value="0">Espessura</button>
      </div>
    </div>

    <table id="tableEspessura" class="table table-striped table-bordered display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Descrição</th>
          <th>Editar</th>
          <th>Excluir</th>
        </tr>
      </thead>
    </table>

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

  <div class="overlay">
    <h2>Aguarde ... Excluindo espessura</h2>
  </div>


  <div class="modal fade" id="modalEditarEspessura" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal">Cadastro de espessura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editarEspessura" novalidate>
            <div class="form-group">
              <label for="editar_valor" style="float:left;">Valor</label>
              <input type="number" id="editar_valor" name="editar_valor" class="form-control" required min="0" max="100" step="1">
            </div>

            <button class="btn btn-lg btn-primary btn-block" value="" type="button" id="btnSalvarItem">Salvar</button>
          </form>
          <div class="messages">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="./js/jquery.js"></script>
  <script src="./js/popper.js"></script>
  <script src="./js/bootstrap.js"></script>
  <script src="./js/jquery.validate.js"></script>
  <script src="./js/fontawesome.js"></script>
  <script src="./js/jquery.dataTables.js"></script>
  <script src="./js/dataTables.bootstrap4.js"></script>
  <script src="./js/dataTables.responsive.js"></script>
  <script src="./js/config-global.js"></script>
  <script src="./js/cadastro/listar_espessura.js"></script>
  <script src="./js/login/login.js"></script>

</body>

</html>