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
        echo "Sucesso"; 
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
    <div style="margin-top: 30px;">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mymodal">
            Enviar
        </button>
    </div>

    <div class="modal fade" id="mymodal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel" style="color: #333;">Formulário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="">
                    <div class="modal-body" style="color: #333; text-align: left;">
                        
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="campo_nome" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">E-mail de Contato</label>
                            <input type="email" class="form-control" name="campo_email" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success">Enviar Dados</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>             
