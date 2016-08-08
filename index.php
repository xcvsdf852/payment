<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易銀行系統-首頁</title>
</head>
<body>
    <form  action="deposit.php" method="post">
        <input type = "text" name = "user_name" id = "user_name" placeholder="請輸入名稱">
        <br>
        <button type = "submit" >存款</button>
    </form>
    <hr>
    <form  action="withdrawals.php" method="post">
        <input type = "text" name = "user_name" id = "user_name" placeholder="請輸入名稱">
        <br>
        <button type = "submit">取款</button>
    </form>
    <hr>
    <form  action="history_list.php" method="post">
        <input type = "text" name = "user_name" id = "user_name" placeholder="請輸入名稱">
        <br>
        <button type = "submit">明細</button>
    </form>
</body>
</html>