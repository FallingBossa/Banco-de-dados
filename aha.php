<?php

$host = 'localhost';
$db   = 'db_loja';
$user = 'root';
$pass = 'usbw';
$port = '3307';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

try {
    $PDO = new PDO($dsn, $user, $pass);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modelo'])) {
        $modelo  = $_POST['modelo'];
        $tamanho = $_POST['tamanho'];
        $preco   = $_POST['preco'];

        $sql = "INSERT INTO camisetas (modelo, tamanho, preco) VALUES (:m, :t, :p)";
        $stmt = $PDO->prepare($sql);
        $stmt->execute([':m' => $modelo, ':t' => $tamanho, ':p' => $preco]);

        echo "<p style='color: green;'>Camiseta salva com sucesso!</p>";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_excluir'])) {
    try {
        $id = $_POST['id_excluir'];
        $sql = "DELETE FROM camisetas WHERE id = :id";
        $stmt = $PDO->prepare($sql);
        $stmt->execute([':id' => $id]);
        echo "Sucesso"; 2
    } catch(Exception $e) {
        echo "Erro ao excluir";
    }
    exit; 
}


function consultar() {
    global $PDO; 
    $stmt = $PDO->query("SELECT id, modelo, tamanho, preco FROM camisetas");
    
    echo "<table border='1' style='margin-top: 20px; border-collapse: collapse; width: 100%; max-width: 500px;'>";
    echo "<tr><th>Modelo</th><th>Tam</th><th>Preço</th><th>Ação</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
        echo "<tr id='linha-" . $row['id'] . "'>";
        echo "<td>" . $row['modelo'] . "</td>";
        echo "<td>" . $row['tamanho'] . "</td>";
        echo "<td>R$ " . $row['preco'] . "</td>";
        echo "<td><button class='excluir' data-id='" . $row['id'] . "'>Excluir</button></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Camisetas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Cadastrar Sua Nova Camiseta</h2>
    <form method="POST">
        <label>Modelo</label><br>
        <input type="text" name="modelo" required><br><br>
        
        <label>Tamanho</label><br>
        <select name="tamanho">
            <option value="PP">PP</option>
            <option value="P">P</option>
            <option value="M">M</option>
            <option value="G">G</option>
            <option value="GG">GG</option>
        </select><br><br>

        <label>Preço</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <button type="submit">Solicitar Camiseta</button>
    </form>

    <hr>
    <h3>Camisetas Cadastradas</h3>
    <?php 
    
    consultar();
    ?>

    
    <script>
    $(document).ready(function() {
        $('.excluir').click(function() {
            var id_camiseta = $(this).attr("data-id"); // Pega o id do botão
            
            if(confirm("Deseja realmente excluir esta camiseta?")) {
                $.ajax({
                    url: "aha.php", 
                    type: "POST",
                    data: { id_excluir: id_camiseta },
                    success: function(resposta) {
                        if(resposta.trim() == "Sucesso") {
            
                            $('#linha-' + id_camiseta).remove();
                        } else {
                            alert("Erro ao excluir do banco de dados.");
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>             