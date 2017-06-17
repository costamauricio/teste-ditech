<?php

namespace App\Controllers;

use \App\Controllers\Controller;
use \App\Models\Reserva;

class ReservaController extends Controller
{
    public function detalhes($request, $response)
    {
        $reserva = Reserva::find($request->getParam("reserva"));

        return $response->withJson(array_merge($reserva->toArray(), [
            "usuario" => $reserva->usuario()->nome,
            "sala" => $reserva->sala()->descricao
        ]));
    }

    public function reservar($request, $response)
    {
        $validacao = Reserva::where("inicio", $request->getParam("horario"))
                            ->where("usuario_id", $this->container->auth->usuario()->id)
                            ->first();

        if ($validacao) {
            return $response->withJson(["erro" => true, "mensagem" => "Usuário já possui uma sala reservada neste horário."]);
        }

        $validacao = Reserva::where("sala_id", $request->getParam("sala"))
                            ->where("inicio", $request->getParam("horario"))
                            ->first();

        if ($validacao) {
            return $response->withJson(["erro" => true, "mensagem" => "Esta sala já esta reservada neste horário."]);
        }

        $reserva = new Reserva();

        $reserva->usuario_id = $this->container->auth->usuario()->id;
        $reserva->sala_id = $request->getParam("sala");
        $reserva->inicio = $request->getParam("horario");

        $reserva->save();

        return $response->withJson(array_merge($reserva->toArray(), ["erro" => false]));
    }

    public function remover($request, $response)
    {
        $reserva = Reserva::find($request->getParam("reserva"));

        if (!$reserva || $reserva->usuario_id != $this->container->auth->usuario()->id) {
            return $response->withJson(["erro" => true]);
        }

        $reserva->delete();

        return $response->withJson(["erro" => false]);
    }

    public function getReservas($request, $response)
    {
        $data_inicio = $request->getParam("data_inicio");
        $data_fim = $request->getParam("data_fim");

        $reservas = Reserva::where("inicio", ">=", $data_inicio . " 00:00")
                        ->where("inicio", "<=", $data_fim . " 23:59")
                        ->get();

        return $response->withJson($reservas->map(function($reserva) {
            return array_merge($reserva->toArray(), [
                "responsavel" => ($reserva->usuario_id == $this->container->auth->usuario()->id)
            ]);
        }));
    }
}
