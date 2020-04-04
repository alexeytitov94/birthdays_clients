<?php

require('../api/main.php');

$data = json_decode($_POST['DATA']);


//Запрос к базе, выясняю есть такой портал
$portal = Request::getPortal($data->DOMAIN);

if (count($portal) == 0) {
    //Добавляем новый портал
    Request::addPortal($data);
    echo 'new';

} else {
    //Полученные значения возвращаем
    echo json_encode($portal);
}







