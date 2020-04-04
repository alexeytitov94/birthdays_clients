<?php

class Contact
{

    public function getContactDr($date_item, $auth)
    {
        return call("crm.contact.list", [
            'order' => ['BIRTHDATE' => 'DESK'],
            'filter' => [
                "!BIRTHDATE" => ''//$date_item->format('Y-m-d'),
            ],
            'select' => ["*"],
        ], $auth['DOMAIN'], $auth['ACCESS_TOKEN']);
    }

}