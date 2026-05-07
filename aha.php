<?php

$host = 'localhost';
$db   = 'db_loja';
$user = 'root';
$pass = 'usbw';
$port = '3306';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $modelo  = $_POST['modelo'];
        $tamanho = $_POST['tamanho'];
        $preco   = $_POST['preco'];

        $sql = "INSERT INTO camisetas (modelo, tamanho, preco) VALUES (:m, :t, :p)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':m' => $modelo, ':t' => $tamanho, ':p' => $preco]);

        echo "<p style='color: green;'>Camiseta salva com sucesso!</p>";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Camisetas</title>
</head>
<body>
<h2>Cadastrar Sua Nova Camiseta</h2>
<form method="POST">
<label>Modelo</Label><br>
<input type="text" name="modelo" required><br><br>
<Select name="tamanho">
<option value="PP">PP</option>
<option value="P">P</option>
<option value="M">M</option>
<option value="G">G</option>
<option value="GG">GG</option>
</Select>

<label>Preço</label><br>
<input type="number" step="0.01" name="preco" required><br><br>

<button type="submit"> Solicitar Camiseta</button>
    </form>
</body>
</html>