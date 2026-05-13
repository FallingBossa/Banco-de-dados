<?php

include 'aha.php';

$stmt = $PDO->query ("SELECT name,email FROM usuarios");
while ($user = $stmt->fetch(PDO::FETCH_OBJ)){
    echo $user-> name . "-" . $user->email. "<br>";
}
?>