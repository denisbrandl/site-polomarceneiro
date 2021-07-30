<?php

require '../vendor/autoload.php';
use SuaMadeira\CorController;

if (($handle = fopen('./categoria_imagem.csv', "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (!file_exists("../imagens/categorias/" . $data[2])) {
            print_r($data);
            continue;
        }
        rename("../imagens/categorias/" . $data[2], "../imagens/categorias/cat-".$data[1].'-0.jpg' );
        CorController::atualizarImagem($data[1]);
    }
    fclose($handle);
}