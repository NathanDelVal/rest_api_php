<?php

require_once __DIR__. '/controller/Controller.php';

use function Controller\createCarne;
use function Controller\findCarne;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['id'])) {
            if(findCarne($_GET['id'])) {
                echo findCarne($_GET['id']);
            } else {
                echo json_encode(["Status" => "Erro", "Mensagem" => "Não existe carnê com este id"]);
            }
        }
        break;
    case 'POST':
        $data = file_get_contents("php://input");
        $data_assoc = json_decode($data, true);
        try {
            $carne = createCarne($data_assoc['valor_total'], $data_assoc['qtd_parcelas'], $data_assoc['data-primeiro-vencimento'], $data_assoc['periodicidade']);
            echo json_encode(["Status" => "Sucesso", "Carnê" => $carne]);
        } catch (\Throwable $th) {
            echo json_encode(["Status" => "Erro", "Mensagem" => "Erro na criação do carnê"]);
        }
        break;
}
