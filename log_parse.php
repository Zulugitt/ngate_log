<!DOCTYPE html>
<html>
<head>
<title>LogParser Ngate</title>
<meta charset="utf-8" />
</head>
<body>
<h2>Список пользователей</h2>
<?php
    require('dbconnect.php');
    
    $name = $_POST['name'];
    $datecon = $_POST['datecon'];

$sql_Tconnect = "INSERT INTO NG_connect (ID, DateConnect, session_id, Username) SELECT ID, ReceivedAt,SUBSTRING_INDEX(Message,' ', -1),SUBSTRING_INDEX(Message,'/0', 1) FROM SystemEvents WHERE (`Message` LIKE '%Create session - Su%') AND  (`Message` LIKE '%$name%') AND (`ReceivedAt` LIKE '%$datecon%')" ;
$sql_Tdisconnect = "INSERT INTO NG_Disconnect (ID, DateDisconnect, session_id, Username) SELECT ID, ReceivedAt, SUBSTRING_INDEX(Message,' ', -1), SUBSTRING_INDEX(Message,'/0', 1) FROM SystemEvents WHERE  (`Message` LIKE '%Deleting expired pa%' OR `Message` LIKE '%Abort %%') AND  (`Message` LIKE '%$name%') AND `ReceivedAt` LIKE '%$datecon%'" ;
$sql_NGLOG = "INSERT INTO NG_log (ConnectTime, DisconnectTime, Duration, Username, session_id) SELECT NG_connect.DateConnect, NG_Disconnect.DateDisconnect, (NG_Disconnect.DateDisconnect - NG_connect.DateConnect)/60, SUBSTRING_INDEX(NG_connect.username, ' ', -3 ), NG_connect.session_id FROM NG_connect JOIN NG_Disconnect ON NG_connect.session_id=NG_Disconnect.session_id ";
$sql_clearC = "DELETE FROM NG_connect";
$sql_clearD = "DELETE FROM NG_Disconnect";
$sql_clearL = "DELETE FROM NG_log";

$clear_tr = $conn->query($sql_clearC);
$clear_tr = $conn->query($sql_clearD);
$clear_tr = $conn->query($sql_clearL);

$eee = $conn->query($sql_Tconnect);
$eee1 = $conn->query($sql_Tdisconnect);
$eee2 = $conn->query($sql_NGLOG);


$sql_create = "SELECT ConnectTime,DisconnectTime,Duration,Username  FROM `NG_log`" ;

$sql_end = "SELECT ID,DeviceReportedTime,Message  FROM `SystemEvents` WHERE `Message` LIKE '%$name%'  AND (`Message` LIKE '%Deleting expired pa%' OR `Message` LIKE '%Abort %%') AND `ReceivedAt` LIKE '%$datecon%'  LIMIT 50";
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
