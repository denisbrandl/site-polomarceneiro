<?php

    require '../vendor/autoload.php';
    use SuaMadeira\MarcaController;
    use SuaMadeira\LinhaController;
    use SuaMadeira\CorController;

    $arrMarcas = [];
    $arrMarcasId = [];

    $arrLinha = [];
    $arrLinhaId = [];    

    $arrCor = [];
    $arrCorId = [];
    $arrCorImagem = [];
    if (($handle = fopen($argv[1], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $marca = $data[0];
            $linha = $data[1];
            $cor = $data[2];

            $imagem_cor = $cor.'.jpg';
            $cor = str_replace(array('_', '-', 'site', 'download'), ' ', $cor);
            $cor = strtolower($cor);
            $cor = ucfirst($cor);
            $cor = preg_replace('/[0-9]+/', '', $cor);

            if (!in_array($marca, $arrMarcas)) {
                $id_marca = MarcaController::inserirMarca($marca);
                $arrMarcas[] = $marca;
                $arrMarcasId[$marca] = $id_marca;
            }

            if (!in_array($linha, $arrLinha)) {
                $id_linha = LinhaController::inserirLinha($linha, $arrMarcasId[$marca]);
                $arrLinha[] = $linha;
                $arrLinhaId[$linha] = $id_linha;
            }

            if (empty($cor)) {
                continue;
            }

            if (!in_array($cor, $arrCor)) {
                $id_cor = CorController::inserirCor($cor, $arrLinhaId[$linha], $imagem_cor);
                $arrCor[] = $cor;
                $arrCorId[$cor] = $id_cor;
                $arrCorImagem[] = array($id_cor, $imagem_cor);
            }

            $fp = fopen('imagens_id.csv', 'w');

            foreach ($arrCorImagem as $cor_imagem) {
                fputcsv($fp, $cor_imagem);
            }
            fclose($fp);
        }
        fclose($handle);
    }
?>