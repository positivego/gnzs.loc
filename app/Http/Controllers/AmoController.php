<?php

namespace App\Http\Controllers;

use App\Http\RequestHandler\AmoHandler;
use Illuminate\Http\Request;

class AmoController extends Controller
{
    public function formingRequest(Request $request)
    {
        // Получаем наименование сделки, контакта, компании
        $payload = $request->payload;
        // Получаем тип
        $available = $request->available;

        // Создаем новую сделку, компанию или контакт и возвращаем id
        $data = AmoHandler::sendRequest($payload, $available);

        // Если есть какая либо ошибка, возвращаем ее
        if(isset($data['error'])) {
            return response()->json(['error' => $data['error']['deteil'], 'status' => $data['error']['status']], $data['error']['status']);
        }

        // Получаем сущность по полученному ранее id
        $entity = AmoHandler::get($data['id'], $available);

        // Возвращаем сущность
        // Изначально хотел разделить entitys по типу, но подумал что это лишнее
        return response()->json(['entity' => $entity, 'type' => $data['type']], 200);
    }

}
