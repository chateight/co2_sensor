<?php
$ret = db_read();
foreach ($ret as $cnt => $value) {
 echo $value[2];
}
if($value[2] < 1000){
    }
    elseif($value[2] < 2000){
    }
    elseif($value[2] > 2000){
    }
//
function db_read()
{
define('DB_HOST', 'localhost');
define('DB_NAME', 'co2');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

//connect to the DB
try {
$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER, DB_PASSWORD, $options);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo 'success';
} catch (PDOException $e) {
echo $e->getMessage();
exit;
}
//DB read and return the data
$stmt = $dbh->prepare("SELECT * FROM `pythonco2` order by t desc limit 1");
$stmt->execute();
$results = $stmt->fetchAll();
// connection close
$dbh = null;
return $results;
}
?>