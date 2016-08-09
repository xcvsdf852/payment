<?php
require_once("package/Database.php");

class GetMoney
{

    function selectMoney($user_name)
    {
        $db = new Database();
        $user_name = $db->strSqlReplace($user_name);
        $sql = "SELECT `bank_user_id`,`bank_user_money` ";
        $sql .= "FROM `bank_user` ";
        $sql .= "WHERE `bank_user_name` = '".$user_name."'";
        // echo $sql;
        // exit;
        $row = $db->select($sql);
        // var_dump($row);

        return $row[0];
    }
}
