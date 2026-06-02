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

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_editar'])) {
        $id      = $_POST['id_editar'];
        $modelo  = $_POST['edit_modelo'];
        $tamanho = $_POST['edit_tamanho'];
        $preco   = $_POST['edit_preco'];

        $sql = "UPDATE camisetas SET modelo = :m, tamanho = :t, preco = :p WHERE id = :id";
        $stmt = $PDO->prepare($sql);
        $stmt->execute([':m' => $modelo, ':t' => $tamanho, ':p' => $preco, ':id' => $id]);

        header("Location: aha.php");
        exit;
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
        echo "<td class='txt-modelo'>" . $row['modelo'] . "</td>";
        echo "<td class='txt-tamanho'>" . $row['tamanho'] . "</td>";
        echo "<td class='txt-preco' data-valor='" . $row['preco'] . "'>R$ " . $row['preco'] . "</td>";
       
        echo "<td>
                <button class='btn-editar' data-id='" . $row['id'] . "'>Editar</button>
                <button class='excluir' data-id='" . $row['id'] . "'>Excluir</button>
              </td>";
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

    <style>
        .modal { position: fixed; top: 0; left: 0; z-index: 1055; display: none; width: 100%; height: 100%; overflow-x: hidden; overflow-y: auto; outline: 0; background: rgba(0,0,0,0.5); }
        .modal.show { display: block; }
        .modal-dialog { position: relative; width: auto; margin: 1.75rem auto; max-width: 500px; pointer-events: none; }
        .modal-content { position: relative; display: flex; flex-direction: column; width: 100%; pointer-events: auto; background-color: #fff; border: 1px solid rgba(0,0,0,.2); border-radius: .3rem; outline: 0; font-family: sans-serif; }
        .modal-header { display: flex; flex-shrink: 0; align-items: center; justify-content: space-between; padding: 1rem 1rem; border-bottom: 1px solid #dee2e6; }
        .modal-title { margin-bottom: 0; line-height: 1.5; font-size: 1.25rem; font-weight: 500; }
        .btn-close { box-sizing: content-box; width: 1em; height: 1em; padding: .25rem .25rem; color: #000; border: 0; border-radius: .25rem; opacity: .5; cursor: pointer; }
        .modal-body { position: relative; flex: 1 1 auto; padding: 1rem; }
        .modal-footer { display: flex; flex-wrap: wrap; flex-shrink: 0; align-items: center; justify-content: flex-end; padding: .75rem; border-top: 1px solid #dee2e6; }
        .mb-3 { margin-bottom: 1rem; }
        .form-label { display: inline-block; margin-bottom: .5rem; font-family: sans-serif; font-size: 14px; }
        .form-control { display: block; width: 93%; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #212529; background-color: #fff; border: 1px solid #ced4da; border-radius: .25rem; }
        .btn-novo { display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; cursor: pointer; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; border: 1px solid transparent; }
        .btn-azul { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
        .btn-cinza { color: #fff; background-color: #6c757d; border-color: #6c757d; }
        .btn-verde { color: #fff; background-color: #198754; border-color: #198754; }
    </style>
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
            var id_camiseta = $(this).attr("data-id"); 
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

        
        $('.btn-editar').click(function() {
            var id = $(this).attr("data-id");
            var linha = $('#linha-' + id);
            
           
            var modelo = linha.find('.txt-modelo').text();
            var tamanho = linha.find('.txt-tamanho').text();
            var preco = linha.find('.txt-preco').attr('data-valor');

       
            $('#edit_id').val(id);
            $('#edit_modelo').val(modelo);
            $('#edit_tamanho').val(tamanho);
            $('#edit_preco').val(preco);

          
            $('#modalEditar').fadeIn(150).addClass('show');
        });

        $('#btnFecharEditar, #btnFecharXEditar').click(function() {
            $('#modalEditar').fadeOut(150).removeClass('show');
        });

        
        $('#btnAbrirModal').click(function() {
            $('#modalTelaNova').fadeIn(150).addClass('show');
        });
        $('#btnFecharModal, #btnFecharX').click(function() {
            $('#modalTelaNova').fadeOut(150).removeClass('show');
        });
    });
    </script>

    <div style="margin-top: 20px;">
        <button type="button" class="btn-novo btn-azul" id="btnAbrirModal">
            Enviar
        </button>
    </div>

    <div class="modal" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#222; font-family: sans-serif;">Editar Camiseta</h5>
                    <button type="button" class="btn-close" id="btnFecharXEditar">X</button>
                </div>
                <form method="POST" action="aha.php">
                    <div class="modal-body" style="color:#222; text-align: left;">
                        <input type="hidden" name="id_editar" id="edit_id">

                        <div class="mb-3">
                            <label class="form-label">Modelo:</label>
                            <input type="text" class="form-control" name="edit_modelo" id="edit_modelo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tamanho:</label>
                            <select class="form-control" name="edit_tamanho" id="edit_tamanho">
                                <option value="PP">PP</option>
                                <option value="P">P</option>
                                <option value="M">M</option>
                                <option value="G">G</option>
                                <option value="GG">GG</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preço:</label>
                            <input type="number" step="0.01" class="form-control" name="edit_preco" id="edit_preco" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-novo btn-cinza" id="btnFecharEditar">Fechar</button>
                        <button type="submit" class="btn-novo btn-verde">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="modalTelaNova">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#222; font-family: sans-serif;">Formulário Novo</h5>
                    <button type="button" class="btn-close" id="btnFecharX">X</button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body" style="color:#222; text-align: left;">
                        <div class="mb-3">
                            <label class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="campo_nome" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail:</label>
                            <input type="email" class="form-control" name="campo_email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-novo btn-cinza" id="btnFecharModal">Fechar</button>
                        <button type="submit" class="btn-novo btn-verde">Enviar Dados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
