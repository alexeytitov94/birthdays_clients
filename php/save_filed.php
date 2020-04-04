<?php

require('../api/main.php');


//Обновляю поля
Request::updateFieldPortal($_POST['portal'], $_POST['field'], $_POST['value']);

echo true;

