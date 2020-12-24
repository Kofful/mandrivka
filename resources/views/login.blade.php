<!DOCTYPE html>
<html>
<head>
    <title>Вход Mandrivka</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
<script>
    function onLogin(event) {
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let data = {
            email,
            password
        };
        axios.post("api/login", data)
            .then(response => {
                if(response.data.code == 200) {
                    window.localStorage.setItem("pat", response.data.token);
                    window.location.replace("/");
                } else {
                    console.log(response.data.errors);
                    //print errors
                }
            });
    }
</script>
    <label for="email">E-mail</label>
    <input id="email" name="email">
    <label for="password">Пароль</label>
    <input id="password" type="password" name="password">
    <button onclick="onLogin()">Войти</button>
</body>
</html>
