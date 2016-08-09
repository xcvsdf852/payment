<?php
require_once("package/Database.php");

class BankSystem
{
    function deposit($money, $type, $id)
    {   
        $db = new Database();
        $IP = $db->getIp();
        $money = $db->strSqlReplace($money);
        $type = $db->strSqlReplace($type);
        $id = $db->strSqlReplace($id);
        
        $sql = "SELECT `bank_user_money` 
                FROM `bank_user` 
                WHERE `bank_user_id` = '".$id."' FOR UPDATE";
        // echo $sql;
        // exit;
        $row = $db->select($sql);
        // sleep(5);
        // echo $row[0]['bank_user_money'];
        // exit;
        $balance = $row[0]['bank_user_money'];
        
        $sql ="UPDATE `bank_user` 
               SET `bank_user_money` = `bank_user_money` + $money
               WHERE `bank_user_id` = '$id'";
        // echo $sql;
        // exit;
        $row = $db->update($sql);
        // var_dump($row);
        // exit;
        if (!$row) {
            if ($type==1) {
                $arry_result["mesg"] = "存款失敗，系統錯誤!";
            } else {
                $arry_result["mesg"] ="取款失敗，系統錯誤!";
            }
            $arry_result["isTrue"] = false;
            $arry_result["errorCod"] = 2;
            return $arry_result;
        }
        
        $balance = $balance + $money;
        $sql_Regist = "INSERT INTO `bank_log`(`bank_log_do`, `bank_log_money`, `bank_log_balance`, `bank_log_suer`, `bank_log_time`, `bank_log_ip`)
                       VALUES ('$type','$money','$balance','$id',NOW(),'$IP')";
        //echo $sql_Regist;
        //exit;
        $row__Regist = $db->update($sql_Regist);
        // var_dump($row__Regist);
        if (!$row__Regist) {
            if ($type==1) {
                $arry_result["mesg"] = "存款失敗，系統錯誤!";
            } else {
                $arry_result["mesg"] = "取款失敗，系統錯誤!";
            }
            $arry_result["isTrue"] = false;
            $arry_result["errorCod"] = 3;
            return $arry_result;
        }
        if ($type==1) {
            $arry_result["mesg"] = "存款成功!";
        } else {
            $arry_result["mesg"] = "取款成功!";
        }
        $arry_result["isTrue"] = true;
        $arry_result["errorCod"] = 1;
        return $arry_result;
    }
    
    function withdrawals($money, $type, $id)
    {
        $db = new Database();
        $IP = $db->getIp();
        $money = $db->strSqlReplace($money);
        $type = $db->strSqlReplace($type);
        $id = $db->strSqlReplace($id);
        
        try
        {
        
            $db->get_connection()->beginTransaction();
            $sql = "SELECT `bank_user_money` 
                    FROM `bank_user` 
                    WHERE `bank_user_id` = '".$id."' FOR UPDATE";
            // echo $sql;
            // exit;
            $row = $db->select($sql);
            #取得目前餘額，看是否可以扣除
            // sleep(5);
            // echo $row[0]['bank_user_money'];
            // exit;
            $balance = $row[0]['bank_user_money'];
            if ($balance >= $money) {
                $sql ="UPDATE `bank_user` 
                       SET `bank_user_money` = `bank_user_money` - $money
                       WHERE `bank_user_id` = '$id'";
                // echo $sql;
                // exit;
                $row = $db->update($sql);
                // var_dump($row);
                // exit;
                $balance = $balance - $money;
                $sql_Regist = "INSERT INTO `bank_log`(`bank_log_do`, `bank_log_money`, `bank_log_balance`, `bank_log_suer`, `bank_log_time`, `bank_log_ip`)
                               VALUES ('$type','$money','$balance','$id',NOW(),'$IP')";
            //   echo $sql_Regist;
            //   exit;
                $row__Regist = $db->update($sql_Regist);
              
              
                $db->get_connection()->commit();
                if (!$row) {
                    if ($type==1) {
                        $arry_result["mesg"] = "存款失敗，系統錯誤!";
                    } else {
                        $arry_result["mesg"] ="取款失敗，系統錯誤!";
                    }
                    $arry_result["isTrue"] = false;
                    $arry_result["errorCod"] = 4;
                    return $arry_result;
                }
                // var_dump($row__Regist);
                if (!$row__Regist) {
                    if ($type==1) {
                        $arry_result["mesg"] = "存款失敗，系統錯誤!";
                    } else {
                        $arry_result["mesg"] = "取款失敗，系統錯誤!";
                    }
                    $arry_result["isTrue"] = false;
                    $arry_result["errorCod"] = 5;
                    return $arry_result;
                }
                if ($type==1) {
                    $arry_result["mesg"] = "存款成功!";
                } else {
                    $arry_result["mesg"] = "取款成功!";
                }
                $arry_result["isTrue"] = true;
                $arry_result["errorCod"] = 1;
                return $arry_result;
              
            } else {
                throw new Exception($error);
            }
        
        } catch (Exception $err) {
            $db->get_connection()->rollback();
            if ($type==1) {
                $arry_result["mesg"] = "存款失敗，系統錯誤!";
            } else {
                $arry_result["mesg"] = "取款失敗，系統錯誤!";
            }
            $arry_result["isTrue"] = false;
            $arry_result["errorCod"] = 6;
            return $arry_result;
        }
    }
    
    function get_history_list($user_name){
        $db = new Database();
        $user_name = $db->strSqlReplace($user_name);
        $sql = "SELECT `bank_user_id`
                FROM `bank_user` 
                WHERE `bank_user_name` = '".$user_name."'";
        // echo $sql;
        // exit;
        $row = $db->select($sql);
        // var_dump($row);
        // exit;
        $sql_list ="SELECT `bank_log_id`, `bank_log_do`, `bank_log_money`, `bank_log_balance`, `bank_log_time`
               FROM `bank_log`
               WHERE `bank_log_suer` = '".$row[0]['bank_user_id']."'";
        // echo $sql_list;
        $row_list = $db->select($sql_list);
        // var_dump($row_list);
        return $row_list;
    }
}
?>