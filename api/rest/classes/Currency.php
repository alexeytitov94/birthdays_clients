<?php

class Currency
{
    public function getCurrencyPortal($data)
    {
        return call('crm.currency.list', "", $data['DOMAIN'], $data['ACCESS_TOKEN'])['result'];
    }

    public function getField($data)
    {
        return call('crm.currency.fields', "", $data['DOMAIN'], $data['ACCESS_TOKEN'])['result'];
    }

    public function updateCurrencePortal($data,$currency)
    {
        $arParam = array(
            'id' => $currency['CURRENCY'],
            'fields' => array(
                'AMOUNT_CNT' => $currency['NOMINAL'],
                'AMOUNT' => $currency['VALUE']
            )
        );

        return call('crm.currency.update', $arParam, $data['DOMAIN'], $data['ACCESS_TOKEN']);
    }

}