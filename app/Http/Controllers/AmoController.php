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
        // Получаем токен
        $token = $request->token;
        // и саб домен
        $subdomain = $request->subdomain;

        /* dd($payload, $available, $token, $subdomain); */

        // Создаем новую сделку, компанию или контакт и возвращаем id
        $data = AmoHandler::sendRequest($payload, $available, $token, $subdomain);

        // Если есть какая либо ошибка, возвращаем ее
        if(isset($data['error'])) {
            return response()->json(['error' => $data['error']['detail'], 'status' => $data['error']['status']], $data['error']['status']);
        }

        // Получаем сущность по полученному ранее id
        $entity = AmoHandler::get($data['id'], $available, $token, $subdomain);

        // Возвращаем сущность
        // Изначально хотел разделить entitys по типу, но подумал что это лишнее
        return response()->json(['entity' => $entity, 'type' => $data['type']], 200);
    }

    public function getToken(Request $request)
    {
        //Формируем URL для запроса
        $link = 'https://test.gnzs.ru/oauth/get-token.php';

        /** Формируем заголовки */
        $headers = [
            'Access-Control-Allow-Origin: *',
            'X-Client-Id: ' . 29869519,
            'Content-Type: application/json'
        ];
        //Сохраняем дескриптор сеанса cURL
        $curl = curl_init();
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //Инициируем запрос к API и сохраняем ответ в переменную
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($error = curl_errno($curl)) {
            $error_message = curl_strerror($error);
            return response()->json(['error' => $error, 'message' => $error_message], 400);
        }

        curl_close($curl);

        return response()->json($out, $code);
    }

}
