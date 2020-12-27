<!DOCTYPE html>
<html>
<head>
    <title>Управление Mandrivka</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
<h1>Admin</h1>
<script>
    axios.get('/oauth/tokens')
        .then(response => {
            console.log(response.data);
        });
</script>
</body>
</html>
