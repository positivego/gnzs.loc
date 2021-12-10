<?php

namespace App\Http\RequestHandler;

class AmoHandler
{

    public static function sendRequest($payload, $available, $token, $subdomain)
    {
        // Люблю коллекции в ларавеле
        $data = collect();

        //Формируем URL для запроса
        $link = 'https://' . $subdomain . '/api/v4/' . $available;

        /** Формируем заголовки */
        $headers = [
            'Authorization: Bearer ' . $token
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
            $data->put('error', ['detail' => $out->detail, 'status' => $code]);
        }

        return $data;
    }

    public static function get($id, $available, $token, $subdomain)
    {
        //Формируем URL для запроса
        $link = 'https://' . $subdomain . '/api/v4/' . $available . '/' . $id;

        /** Формируем заголовки */
        $headers = [
            'Authorization: Bearer ' . $token
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