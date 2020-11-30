<!DOCTYPE html>
<html>
<head>
    <title>Регистрация Mandrivka</title>
</head>
<body>
    <form action="api/register" method="post">
        @csrf
        <label for="name">Имя</label>
        <input id="name" name="name">
        <label for="email">E-mail</label>
        <input id="email" name="email">
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password">
        <label for="password_confirmation">Подтвердите пароль</label>
        <input id="password_confirmation" type="password" name="password_confirmation">
        <button>Регистрация</button>
    </form>
</body>
</html>
