<?php
require_once("package/Database.php");

class GetMoney
{

    function selectMoney($userName)
    {
        $db = new Database();
        $userName = $db->strSqlReplace($userName);
        $sql = "SELECT `bank_user_id`,`bank_user_money` ";
        $sql .= "FROM `bank_user` ";
        $sql .= "WHERE `bank_user_name` = '".$userName."'";
        // echo $sql;
        // exit;
        $row = $db->select($sql);
        // var_dump($row);

        return $row[0];
    }
}
