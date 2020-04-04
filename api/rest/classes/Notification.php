<?php

class Notification
{

    public function task($type, $data, $auth)
    {

        if($type == 'ASSIGNED') {

            $find_year = substr(date('d.m.Y H:i:s', strtotime(substr($data['BIRTHDATE'], 0, 10) . " +11 hours")), 6, 4);

            $result_date = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($data['BIRTHDATE'], 0, 10) . " +11 hours")));


            return call("tasks.task.add", [
                'fields' => [
                    "TITLE" => "Поздравить с днем рождения ".$data['NAME']." ".$data['SECOND_NAME']." ".$data['LAST_NAME'],
                    "DESCRIPTION" => 'День рожение у контакта '."[URL=".$auth['DOMAIN']."/crm/contact/details/".$data['ID']."/]".$data['NAME']." ".$data['SECOND_NAME']." ".$data['LAST_NAME']."[/URL] - ".substr($data['BIRTHDATE'], 0, 10),
                    'RESPONSIBLE_ID' => $data['ASSIGNED_BY_ID'],
                    'DEADLINE' => $result_date,
                ],
            ], $auth['DOMAIN'], $auth['ACCESS_TOKEN'])['result'];

        } else {

            $description = '';

            foreach ($data as $contac) {
                $description = $description.'<br>День рожение у контакта '."[URL=".$auth['DOMAIN']."/crm/contact/details/".$contac['ID']."/]".$contac['NAME']." ".$contac['SECOND_NAME']." ".$contac['LAST_NAME']."[/URL] - <b>".substr($contac['BIRTHDATE'], 0, 10)."</b>";
            }

            $find_year = substr(date('d.m.Y H:i:s', strtotime(substr($contac['BIRTHDATE'], 0, 10) . " +11 hours")), 6, 4);

            $result_date = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($contac['BIRTHDATE'], 0, 10) . " +11 hours")));


            return call("tasks.task.add", [
                'fields' => [
                    "TITLE" => "Поздравить с днем рождения",
                    "DESCRIPTION" => $description,
                    'RESPONSIBLE_ID' => $type,
                    'DEADLINE' => $result_date,
                ],
            ], $auth['DOMAIN'], $auth['ACCESS_TOKEN'])['result'];


        }
    }

    public function delo($type, $data, $auth)
    {

        if($type == 'ASSIGNED') {

            $phone = call("crm.contact.get", [
                'id' => $data['ID']
            ], $auth['DOMAIN'], $auth['ACCESS_TOKEN'])['result']['PHONE'][0]['VALUE'];

            $find_year = substr(date('d.m.Y H:i:s', strtotime(substr($data['BIRTHDATE'], 0, 10) . " +11 hours")), 6, 4);

            $result_start = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($data['BIRTHDATE'], 0, 10) . " +11 hours")));
            $result_end = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($data['BIRTHDATE'], 0, 10) . " +16 hours")));



            return call("crm.activity.add", [
                'fields' => [
                    "OWNER_TYPE_ID" => 3,
                    "OWNER_ID" => $data['ID'],
                    "TYPE_ID" => 2,
                    "COMMUNICATIONS" => [
                        [
                            'VALUE' => $phone,
                            'ENTITY_ID' => $data['ID'],
                            'ENTITY_TYPE_ID' => 3,
                        ]
                    ],
                    "SUBJECT" => 'Поздравить с днем рождения',
                    "START_TIME" => $result_start,
                    "END_TIME" => $result_end,
                    "COMPLETED" => "N",
                    "RESPONSIBLE_ID" => $data['ASSIGNED_BY_ID'],
                ]
            ], $auth['DOMAIN'], $auth['ACCESS_TOKEN']);


        } else {

            $x = [];

            foreach ($data as $contac) {

                $phone = call("crm.contact.get", [
                    'id' => $contac['ID']
                ], $auth['DOMAIN'], $auth['ACCESS_TOKEN'])['result']['PHONE'][0]['VALUE'];

                $find_year = substr(date('d.m.Y H:i:s', strtotime(substr($contac['BIRTHDATE'], 0, 10) . " +11 hours")), 6, 4);

                $result_start = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($contac['BIRTHDATE'], 0, 10) . " +11 hours")));
                $result_end = str_replace('.'.$find_year.' ', '.2020 ', date('d.m.Y H:i:s', strtotime(substr($contac['BIRTHDATE'], 0, 10) . " +16 hours")));

                $x[] = call("crm.activity.add", [
                    'fields' => [
                        "OWNER_TYPE_ID" => 3,
                        "OWNER_ID" => $contac['ID'],
                        "TYPE_ID" => 2,
                        "COMMUNICATIONS" => [
                            [
                                'VALUE' => $phone,
                                'ENTITY_ID' => $contac['ID'],
                                'ENTITY_TYPE_ID' => 3,
                            ]
                        ],
                        "SUBJECT" => 'Поздравить с днем рождения',
                        "START_TIME" => $result_start,
                        "END_TIME" => $result_end,
                        "COMPLETED" => "N",
                        "RESPONSIBLE_ID" => $type,
                    ]
                ], $auth['DOMAIN'], $auth['ACCESS_TOKEN']);
            }

            return $x;

        }

        return true;
    }



    public function chat($type, $data, $auth)
    {
        if($type == 'ASSIGNED') {

        }   else {
            return call("crm.activity.add", [
                'OWNER_TYPE_ID' => [

                ],

            ], $auth['DOMAIN'], $auth['ACCESS_TOKEN'])['result'];
        }
    }

}