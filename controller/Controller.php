<?php

namespace Controller;

use stdClass;

function createCarne(float $total, int $parcelas, string $primeiro_vencimento, string $periodicidade, float $entrada = 0)
{
    $data = file_get_contents('carnes.json');
    $id = count(json_decode($data, true)) + 1;

    $carnes = json_decode($data);

    $file = fopen('carnes.json', 'w');

    $parcela_arr = [];

    $total_carne = $total - $entrada;

    $prox_vencimento = $periodicidade == 'semanal' ? 'week' : 'month';

    for ($x = 1; $x <= $parcelas; $x++) {
        array_push($parcela_arr, [
            'data_vencimento' =>  date('Y-m-d', strtotime("+{$x} {$prox_vencimento}", strtotime($primeiro_vencimento))),
            'valor' => $total_carne / $parcelas,
            'numero' => $x,
            'entrada' => false
        ]);
    }

    if ($entrada != 0) {
        array_unshift($parcela_arr, [
            'valor' => $entrada,
            'entrada' => true
        ]);
    }

    $carne = [
        'total' => $total,
        'valor_entrada' => $entrada,
        'parcelas' => $parcela_arr
    ];

    $carnes->$id = $carne;
    fwrite($file, json_encode($carnes));
    fclose($file);

    $response = new stdClass();
    $response->$id = $carne;
    return $response;
}

function findCarne($id) {
    $data = file_get_contents('carnes.json');
    $carnes = json_decode($data);

    foreach ($carnes as $key => $value) {
        if($key == $id) {
            return json_encode([$id => $value]);
        }
    }
}
