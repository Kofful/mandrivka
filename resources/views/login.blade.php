<!DOCTYPE html>
<html>
<head>
    <title>Вход Mandrivka</title>
</head>
<body>

<form action="api/login" method="post">
    @csrf
    <label for="email">E-mail</label>
    <input id="email" name="email">
    <label for="password">Пароль</label>
    <input id="password" type="password" name="password">
    <button>Войти</button>
</form>
</body>
</html>
