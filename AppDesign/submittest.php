<?php


if($_POST["message"]) {


mail("wenqixia99@gmail.com", "Here is the subject line",


$_POST["insert your message here"]. "From: wenqixia99@gmail.com");


}


?>





<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


<form method="post" action="submittest.php">


<textarea name="message"></textarea>


<input type="submit">


</form>
</body>
</html>

