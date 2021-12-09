<?php

namespace App\Http\RequestHandler;

class AmoHandler
{
    private static $subdomain = 'https://d6757be6f1c94.amocrm.ru';
    private static $access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJkMTNlNTU2MGNjZDVjMWU4M2E0OTg5ZDAzNDRmZGY4NzZhMmIwMGEwYjRhYWU5ZWM2Y2MyM2JlYzExYTg2NGJlOWY1ZmVmNjVlMDUwYWI3In0.eyJhdWQiOiI3NmYyNTBlZi0yNDJkLTQwZWMtOTJiYy1iYzVhY2RlODQ0ODMiLCJqdGkiOiIyZDEzZTU1NjBjY2Q1YzFlODNhNDk4OWQwMzQ0ZmRmODc2YTJiMDBhMGI0YWFlOWVjNmNjMjNiZWMxMWE4NjRiZTlmNWZlZjY1ZTA1MGFiNyIsImlhdCI6MTYzOTA0NDgwNCwibmJmIjoxNjM5MDQ0ODA0LCJleHAiOjE2MzkxMzEyMDQsInN1YiI6IjY3NzEyNzQiLCJhY2NvdW50X2lkIjoyOTg2OTUxOSwic2NvcGVzIjpbImNybSJdfQ.Yrda_70byLISGfTjhktnIRiOCcbSPMs42Q-w000lyElJU_DyVrLHVwtn0wkfYSs-8XHQ37vyDLe1A2M8DRQcSgUO4SzaTTBC9WGgd830y12M-fTJF5ge0-GGZpshpmsQIc-vclf6I5IH_zXRZikw6iFRYLxzDpFk5LBDxmqcjzslWSmjgKwvoBknqkgrpYpJLBoWvry70idmvuGRb8vRO6S2JYRHAVO5EePV1kfZeypgwWsL5yH9aFh9q89Y-g1WRFhHTukQqmJnpDt4Teag_qBZggH0krpcdtUpWBFpCOqs6Ip6ZBU2Wbcx1RN6bNCjhBoDp5Rwf7meDC7F0-2G8g';

    public static function sendRequest($payload, $available)
    {
        // Люблю коллекции в ларавеле
        $data = collect();

        //Формируем URL для запроса
        $link = self::$subdomain . '/api/v4/' . $available;

        /** Формируем заголовки */
        $headers = [
            'Authorization: Bearer ' . self::$access_token
        ];
        
        $payload = json_encode(array($payload));

        //Сохраняем дескриптор сеанса cURL
        $curl = curl_init();
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        //Инициируем запрос к API и сохраняем ответ в переменную
        $out = (object)json_decode(curl_exec($curl), true);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code === 200) {
            // Вносим данные в коллекцию
            // Для начала нужно внести type, что бы я мог далее динамически получить id leads, contacts и companyes
            $data->put('type', array_key_first($out->_embedded));
            // Собсвенное получаем id
            $data->put('id', $out->_embedded[$data['type']][0]['id']);
        } else {
            $data->put('error', ['deteil' => $out->detail, 'status' => $code]);
        }

        return $data;
    }

    public static function get($id, $available)
    {
        //Формируем URL для запроса
        $link = self::$subdomain . '/api/v4/' . $available . '/' . $id;

        /** Формируем заголовки */
        $headers = [
            'Authorization: Bearer ' . self::$access_token
        ];
        //Сохраняем дескриптор сеанса cURL
        $curl = curl_init();
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        //Инициируем запрос к API и сохраняем ответ в переменную
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $out;
    }
}