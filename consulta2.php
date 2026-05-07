<?php

include 'aha.php'

$stmt = $PDO->query ("SELECT name,email FROM usuarios");
qhile ($user = $stmt->fetch object()){
    echo $user name . "-" . $user->email. "<br>";
}
?>