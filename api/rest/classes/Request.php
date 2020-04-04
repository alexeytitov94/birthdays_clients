<?php

class Request
{

    public function getPortal($domain)
    {

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "SELECT * FROM portal WHERE PORTAL='$domain'";

        $arResult = [];

        if ($result = $mysql->query($query)) {

            while ($row = $result->fetch_assoc()) {
                $arResult[] = $row;
            }

            $result->close();
        }

        return $arResult[0];
    }

    public function addPortal($request)
    {

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "INSERT INTO portal (PORTAL, REFRESH_TOKEN, MEMBER_ID, TYPE_NOTIFY, ID, ASSIGNED, DATA_NOTIFY) VALUES ('$request->DOMAIN', '$request->REFRESH_ID', '$request->member_id', 'CHAT', '', 'ASSIGNED', '1')";

        $mysql->query($query);

        return true;
    }

    public function updateFieldPortal($portal, $field, $value) {

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "UPDATE portal SET $field='$value' WHERE `PORTAL` = '$portal';";

        $mysql->query($query);

        return true;
    }

    public function getAllClients()
    {
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "SELECT * FROM portal";

        $arResult = [];

        if ($result = $mysql->query($query)) {

            while ($row = $result->fetch_assoc()) {
                $arResult[] = $row;
            }

            $result->close();
        }

        return $arResult;
    }

    public function updateRefresh($portal, $refresh) {

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "UPDATE portal SET REFRESH_TOKEN='$refresh' WHERE `PORTAL` = '$portal';";

        $mysql->query($query);

        return true;
    }



}