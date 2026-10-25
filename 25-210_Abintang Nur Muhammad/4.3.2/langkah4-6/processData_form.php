<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<style>
body { font-family: Verdana, sans-serif; font-size: 12px; }
fieldset { background: #ddf; width: 420px; }
.field { clear: both; text-align: right; line-height: 25px; }
label { float: left; width: 130px; padding-right: 10px; }
</style>
</head>
<body>
<h1>Register</h1>
<?php
$errors = array();
if (isset($_POST['surname']))
{
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    if ($errors)
    {
        echo '<h1>Invalid, correct the following errors:</h1>';
        foreach ($errors as $field => $error)
            echo "$field $error</br>";

        // tampilkan kembali form
        include 'form.inc';
    }
    else
    {
        echo 'Form submitted successfully with no errors';
    }
}
else
{
    // tampilkan kembali form
    include 'form.inc';
}
?>
</body>
</html>
