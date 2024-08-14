<html>
<head>
 <title>Запрос логов соединений</title>
</head>
<body>
<h1>
Введите фамилию
</h1>
<h4>
Если поле пустое, идёт поиск по всем данным
</h4>
 <form method="POST" action="log_parse.php">
  <input name="name" type="text" placeholder="Фамилия"/>
  <input name="datecon" type="text" placeholder="ГГГГ-ММ-ДД"/>
  <input type="submit" value="Отправить"/>
 </form>
</body>
</html>
