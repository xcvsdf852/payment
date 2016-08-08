<?php
require_once("GetMoney.php");
$getMmoney = new GetMoney;
$row = $getMmoney->selectMoney($_POST['user_name']);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易銀行系統-存款</title>
</head>
<body>
    <form action="into_db.php" method="post">
        帳戶餘額 : <?php echo $row['bank_user_money']?>
        <br>
        存款金額 : <input type = "number" name = "money" id = "money">
        <br>
        <input type = "hidden" value = '1' name ="type">
        <input type = "hidden" value = '<?php echo $row['bank_user_id']?>' name ="id">
        <button>存款</button>
        <button><a href = 'index.php'>回首頁</a></button>
    </form>
</body>
</html>