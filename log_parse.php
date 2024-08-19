<!DOCTYPE html>
<html>
<head>
<title>LogParser MegaMix</title>
<meta charset="utf-8" />
</head>
<body>
<h2>Список пользователей</h2>
<?php
    require('dbconnect.php');
    
    $name = $_POST['name'];
    $datecon = $_POST['datecon'];

$sql_Tconnect = "INSERT INTO NG_connect (ID, DateConnect, session_id, Username) SELECT ID, ReceivedAt,SUBSTRING_INDEX(Message,' ', -1),SUBSTRING_INDEX(Message,'/0', 1) FROM SystemEvents WHERE (`Message` LIKE '%Create session - Su%') AND  (`Message` LIKE '%$name%') AND (`ReceivedAt` LIKE '%$datecon%')" ;
$sql_Tdisconnect = "INSERT INTO NG_Disconnect (ID, DateDisconnect, session_id, Username) SELECT ID, ReceivedAt, SUBSTRING_INDEX(Message,' ', -1), SUBSTRING_INDEX(Message,'/0', 1) FROM SystemEvents WHERE ((`Message` LIKE '%Deleting expired pa%'  OR `Message` LIKE '% Abort %') OR `Message` LIKE '%Deleting expired aborted session%')   AND  (`Message` LIKE '%$name%') AND `ReceivedAt` LIKE '%$datecon%'" ;
$sql_NGTMP = "INSERT INTO NG_tmp (ConnectTime, DisconnectTime, Duration, Username, session_id) SELECT NG_connect.DateConnect, NG_Disconnect.DateDisconnect, TIMESTAMPDIFF(MINUTE,NG_connect.DateConnect,NG_Disconnect.DateDisconnect), SUBSTRING_INDEX(NG_connect.username, ' ', -3 ), NG_connect.session_id FROM NG_connect JOIN NG_Disconnect ON NG_connect.session_id=NG_Disconnect.session_id ";
$sql_NGLOG = "INSERT INTO NG_log (ConnectTime, DisconnectTime, Duration, Username, session_id) SELECT ConnectTime,DisconnectTime,Duration,Username,session_id from (SELECT Username,ConnectTime,DisconnectTime,Duration,session_id, ROW_NUMBER() OVER(PARTITION BY session_id ORDER BY session_id) AS selcol FROM NG_tmp ) AS kikk where selcol = 1 ";
$sql_clearC = "DELETE FROM NG_connect";
$sql_clearD = "DELETE FROM NG_Disconnect";
$sql_clearT = "DELETE FROM NG_tmp";
$sql_clearL = "DELETE FROM NG_log";

$clear_tr = $conn->query($sql_clearC);
$clear_tr = $conn->query($sql_clearD);
$clear_tr = $conn->query($sql_clearL);
$clear_tr = $conn->query($sql_clearT);

$eee = $conn->query($sql_Tconnect);
$eee1 = $conn->query($sql_Tdisconnect);
$eee2 = $conn->query($sql_NGTMP);
$eee3 = $conn->query($sql_NGLOG);


$sql_create = "SELECT ConnectTime,DisconnectTime,Duration,Username  FROM `NG_log`" ;
if($result = $conn->query($sql_create)){
    echo "<table border='1'><tr><th>Пользователь</th><th>Время подключения</th><th>Время отключения</th><th>Длительность, мин</th></tr>";
    foreach($result as $row){
        echo "<tr>";
            echo "<td>" . $row["Username"] . "</td>";
            echo "<td style='text-align:center;'>" . $row["ConnectTime"] . "</td>";
            echo "<td style='text-align:center;'>" . $row["DisconnectTime"] . "</td>";
            echo "<td style='text-align:center;'>" . $row["Duration"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    $result->closeCursor();
} else{
    echo "Ошибка: " . $conn->error;
}


if(isset($_SERVER['HTTP_REFERER'])) {
	$urlback = htmlspecialchars($_SERVER['HTTP_REFERER']);
	echo "<a href='$urlback' class='history-back'>Вернуться назад</a>"; 
  }


$conn->close();
?>
</body>
</html>
