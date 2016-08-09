<?php
require_once("BankSystem.php");
# 1 是存款
# 2 是取款
$BankSystem = new BankSystem;

if ($_POST['type']==1) {
    $arrayReturn = $BankSystem->deposit($_POST['money'], $_POST['type'], $_POST['id']); #存款
} else {
    $arrayReturn = $BankSystem->withdrawals($_POST['money'], $_POST['type'], $_POST['id']); #取款
}

// var_dump($arrayReturn);
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易銀行系統-執行結果</title>
</head>
<body>
    <form action="into_db.php" method="post">
        操作結果 : <?php echo $arrayReturn['mesg']?>
        <br>
        <button><a href = 'index.php'>回首頁</a></button>
    </form>
</body>
</html>
