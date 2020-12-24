<!DOCTYPE html>
<html>
<head>
    <title>Регистрация Mandrivka</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
<script>
    function onRegister(event) {
        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let password_confirmation = document.getElementById("password_confirmation").value;
        let data = {
            name,
            email,
            password,
            password_confirmation
        };
        axios.post("api/register", data)
            .then(response => {
                if(response.data.code == 200) {
                    window.localStorage.setItem("pat", response.data.token);
                    window.location.replace("/");
                } else {
                    //print errors
                }
        });
    }
</script>
<label for="name">Имя</label>
<input id="name" name="name">
<label for="email">E-mail</label>
<input id="email" name="email">
<label for="password">Пароль</label>
<input id="password" type="password" name="password">
<label for="password_confirmation">Подтвердите пароль</label>
<input id="password_confirmation" type="password" name="password_confirmation">
<button onclick="onRegister()">Регистрация</button>

</body>
</html>
