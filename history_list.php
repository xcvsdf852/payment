<?php
require_once("BankSystem.php");

$BankSystem = new BankSystem;
$arrayReturn =  $BankSystem->get_history_list($_POST['user_name']);
// var_dump($arrayReturn);
$length = count($arrayReturn) -1 ;

// echo $length ;
for ($i = 0; $i <= $length; $i++ ) {
    if ($arrayReturn[$i]['bank_log_do'] == 1) {
        $arrayReturn[$i]['bank_log_do'] = '存款';
    } else {
        $arrayReturn[$i]['bank_log_do'] = '取款';
    }
}

// var_dump($arrayReturn);
// exit;
$html = ""; 
for ($i = 0; $i <= $length; $i++ ) {
     $html .= "<tr>
                    <td id ='pa_id_".$arrayReturn[$i]['bank_log_id']."'>".$arrayReturn[$i]['bank_log_time']."</td>
                    <td>".$arrayReturn[$i]['bank_log_do']."</td>
                    <td>".$arrayReturn[$i]['bank_log_money']."</td>
                    <td>".$arrayReturn[$i]['bank_log_balance']."</td>
    　         </tr>";
}


?>


<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易銀行系統-明細</title>
</head>
<body>
    <table border = '1'>
        <thead>
            <tr>
                <th>時間</th>
                <th>存款/取款</th>
                <th>存/取 金額</th>
                <th>餘額</th>
    　      </tr>
        </thead>
        <tbody>
        <?php echo  $html;?>
　      
　      </tbody>
    </table>
    <button><a href = 'index.php'>回首頁</a></button>
</body>
</html>