<?php

require('api/main.php');

//Получаю всех клиентов
$clients = Request::getAllClients();

foreach ($clients as $item) {

    //Дата
    $date = new DateTime('+'.$item['DATA_NOTIFY'].' days');


    //Получение авторизации
    $auth = Auth::getAccessToken($item);

    //Перезаписываю REFRESH
    Request::updateRefresh($item['PORTAL'], $auth['REFRESH_TOKEN']);

    //Обращаюсь к контакту и ищу всех контактов с датой рождения от и до
    $contacts = Contact::getContactDr($date, $auth)['result'];

    $res_contacts = [];


    foreach ($contacts as $contact) {
        if(substr(date($contact['BIRTHDATE']), 5, 5) == $date->format('m-d')){
            $res_contacts[] = $contact;
        }
    }

    $contacts = $res_contacts;


    writeToLog($contacts);

    //Отправляю уведомление
    if(count($contacts) > 0) {
        if ($item['ASSIGNED'] == 'ASSIGNED') {

            foreach ($contacts as $contac) {

                switch ($item['TYPE_NOTIFY']) {
                    case 'TASK':
                        $x = Notification::task($item['ASSIGNED'], $contac, $auth);
                        break;
                    case 'DELO':
                        $x = Notification::delo($item['ASSIGNED'], $contac, $auth);
                        break;
                }

                writeToLog($x);

            }

        } else {

            switch ($item['TYPE_NOTIFY']) {
                case 'TASK':
                    $x = Notification::task($item['ASSIGNED'], $contacts, $auth);
                    break;
                case 'DELO':
                    $x = Notification::delo($item['ASSIGNED'], $contacts, $auth);
                    break;
            }

            writeToLog($x);

        }
    }


}

//http://b24apps.ru/local/b24apps/our_app/birthday/request.php