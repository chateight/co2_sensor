<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="center"><h1>『密』センサー</h1></div>
<div class="center"><h2> 現在のco2濃度は、</h2></div>

<?php
$ret = db_read();
foreach ($ret as $cnt => $value) {
 echo "<div class=\"center\"><h2>$value[2]"." ppmです</h2></div>";
}
if($value[2] < 1000){
    echo "<div class=\"center\"><h3>換気状態は良好です</h3></div>";
    }
    elseif($value[2] < 2000){
        echo "<div class=\"center1\"><h3>換気が必要かもしれません</h3></div>";
    }
    elseif($value[2] > 2000){
        echo "<div class=\"center2\"><h3>すぐに換気してください</h3></div>";
    }

//
function db_read()
{
define('DB_HOST', 'localhost');
define('DB_NAME', 'co2');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

// 文字化け対策
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

// データベースの接続
try {
$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER, DB_PASSWORD, $options);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo 'success';
} catch (PDOException $e) {
echo $e->getMessage();
exit;
}
// DBからメールアドレス読み出して返却
$stmt = $dbh->prepare("SELECT * FROM `pythonco2` order by t desc limit 1");
$stmt->execute();
$results = $stmt->fetchAll();
// connection close
$dbh = null;
return $results;
}
?>
</body>
</html>
