<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- dev only (FOR REAL DONT FORGET IT AGAIN, ITS MY HOT LOADING soo haaawt) -->
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <link rel="stylesheet" href="http://localhost:5173/scss/main.scss">
</head>
<body>

<?php // quick and dirty loading for now / this project since stay simple
require_once "standaloneLogics/nav.php";
require_once "standaloneLogics/scanPorts.php"; ?>

<?php scanPorts();?>



</body>
</html>