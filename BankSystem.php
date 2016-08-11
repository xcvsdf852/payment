<?php
require_once("package/Database.php");

class BankSystem
{
    function deposit($money, $type, $id)
    {
        $db = new Database();
        $ip = $db->getIp();
        $money = $db->strSqlReplace($money);
        $type = $db->strSqlReplace($type);
        $id = $db->strSqlReplace($id);

        try {
            $db->getConnection()->beginTransaction();

            $sql = "UPDATE `bank_user` SET `bank_user_flag` = '0' ";
            $sql .="WHERE `bank_user_flag` = '1' AND `bank_user_id` = '$id'";

            $row = $db->update($sql);

            if ($row == 0) {
                throw new Exception("目前有人使用，請稍後嘗試!");
            }
            $sqlSelect = "SELECT `bank_user_money` ";
            $sqlSelect .= "FROM `bank_user` ";
            $sqlSelect .= "WHERE `bank_user_id` = '".$id."' ";

            $rowSelect = $db->select($sqlSelect);

            $balance = $rowSelect[0]['bank_user_money'];

            $sql = "UPDATE `bank_user` ";
            $sql .= "SET `bank_user_money` = `bank_user_money` + $money ";
            $sql .= "WHERE `bank_user_id` = '$id' ";

            $row = $db->update($sql);

            if (!$row) {
                throw new Exception("取款失敗，系統錯誤!");
            }

            $balance = $balance + $money;
            $sqlRegist .= "INSERT INTO `bank_log`";
            $sqlRegist .= "(`bank_log_do`, `bank_log_money`, `bank_log_balance`, ";
            $sqlRegist .= "`bank_log_suer`, `bank_log_time`, `bank_log_ip`) ";
            $sqlRegist .= "VALUES ('$type','$money','$balance','$id',NOW(),'$ip')";

            $rowRegist = $db->insert($sqlRegist);

            if (!$rowRegist) {
                throw new Exception("取款失敗，系統錯誤!");
            }

            $sqlFlag = "UPDATE `bank_user` ";
            $sqlFlag .= "SET `bank_user_flag` = 1 ";
            $sqlFlag .= "WHERE `bank_user_id` = '$id' ";

            $rowFlag = $db->update($sqlFlag);

            if (!$rowFlag) {
                throw new Exception("取款失敗，系統錯誤!");
            }

            $db->getConnection()->commit();

        } catch (Exception $err) {
            $db->getConnection()->rollback();
            $arryResult["mesg"] = $err->getMessage();
            $arryResult["isTrue"] = false;
            $arryResult["errorCod"] = 6;

            return $arryResult;
        }
        $arryResult["mesg"] = "存款成功!";
        $arryResult["isTrue"] = true;
        $arryResult["errorCod"] = 1;

        return $arryResult;
    }

    function withdrawals($money, $type, $id)
    {
        $db = new Database();
        $ip = $db->getIp();
        $money = $db->strSqlReplace($money);
        $type = $db->strSqlReplace($type);
        $id = $db->strSqlReplace($id);

        try {
            $db->getConnection()->beginTransaction();

            $sql = "UPDATE `bank_user` SET `bank_user_flag` = '0' ";
            $sql .="WHERE `bank_user_flag` = '1' AND `bank_user_id` = '$id'";

            $row = $db->update($sql);

            if ($row == 0) {
                throw new Exception("目前有人使用，請稍後嘗試!");
            }

            $sqlSelect = "SELECT `bank_user_money` ";
            $sqlSelect .= "FROM `bank_user` ";
            $sqlSelect .= "WHERE `bank_user_id` = '".$id."' ";

            $rowSelect = $db->select($sqlSelect);

            $balance = $rowSelect[0]['bank_user_money'];
            if ($balance >= $money) {

                $sql = "UPDATE `bank_user` ";
                $sql .= "SET `bank_user_money` = `bank_user_money` - $money ";
                $sql .= "WHERE `bank_user_id` = '$id'";

                $row = $db->update($sql);

                if (!$row) {
                    throw new Exception("取款失敗，系統錯誤!");
                }

                $balance = $balance - $money;
                $sqlRegist = "INSERT INTO `bank_log` ";
                $sqlRegist .= "(`bank_log_do`, `bank_log_money`, ";
                $sqlRegist .= "`bank_log_balance`, `bank_log_suer`, `bank_log_time`, `bank_log_ip`)";
                $sqlRegist .= "VALUES ('$type','$money','$balance','$id',NOW(),'$ip')";

                $rowRegist = $db->update($sqlRegist);

                if (!$rowRegist) {
                    throw new Exception("取款失敗，系統錯誤!");
                }

                $sqlFlag = "UPDATE `bank_user` ";
                $sqlFlag .= "SET `bank_user_flag` = 1 ";
                $sqlFlag .= "WHERE `bank_user_id` = '$id' ";

                $rowFlag = $db->update($sqlFlag);

                if (!$rowFlag) {
                    throw new Exception("取款失敗，系統錯誤!");
                }

                $db->getConnection()->commit();

                $arryResult["mesg"] = "取款成功!";
                $arryResult["isTrue"] = true;
                $arryResult["errorCod"] = 1;

                return $arryResult;
            } else {
                throw new Exception("取款失敗，餘額不足!");
            }

        } catch (Exception $err) {
            $db->getConnection()->rollback();
            $arryResult["mesg"] = $err->getMessage();
            $arryResult["isTrue"] = false;
            $arryResult["errorCod"] = 6;

            return $arryResult;
        }
    }

    function getHistoryList($userName){
        $db = new Database();
        $userName = $db->strSqlReplace($userName);
        $sql = "SELECT `bank_user_id` ";
        $sql .= "FROM `bank_user` ";
        $sql .= "WHERE `bank_user_name` = '".$userName."'";

        $row = $db->select($sql);

        $sqlList ="SELECT `bank_log_id`, `bank_log_do`, `bank_log_money`, ";
        $sqlList .= "`bank_log_balance`, `bank_log_time` ";
        $sqlList .= "FROM `bank_log` ";
        $sqlList .= "WHERE `bank_log_suer` = '".$row[0]['bank_user_id']."'";

        $rowList = $db->select($sqlList);

        return $rowList;
    }
}
