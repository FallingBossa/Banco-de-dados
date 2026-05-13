<?php 

include 'aha.php';

$stmt = $PDO->query ("SELECT id, name FROM usuarios");
while ($row = $stmt->fetch (PDO::FETCH_ASSOC)){
    echo $row ['name']. "<br>";
}

?>