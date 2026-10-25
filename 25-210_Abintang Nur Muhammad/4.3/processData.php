<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Process Data</title>
<style>
body { font-family: Verdana, sans-serif; font-size: 12px; }
</style>
</head>
<body>
<h3>Posted data: </h3>
<?php
foreach ($_POST as $key => $value)
    echo "($key) => ($value)<br/>";

$surname = $_POST['surname'];
?>
</body>
</html>
