<?php
// Variáveis do formulário de registro
$nome = $_POST['nome_completo'];
$telefone = $_POST['telefone'];
$nascimento = $_POST['data_nascimento'];
$email = $_POST['email_registo'];
$pass = $_POST['senha_registo'];

if (isset($nome) && isset($telefone) && isset($nascimento) && isset($email) && isset($pass)) {
    $liga = mysqli_connect('localhost', 'root', 'root', 'RTL');
    $sql = "INSERT INTO utilizadores (id, email, nome, telefone, data_nascimento, pass) VALUES (NULL, '$email','$nome','$telefone','$nascimento','$pass')";
    echo $sql;
    if ($liga->query($sql) === TRUE) {
        // Redirecionar para a página de perfil
        header("Location: HTML.RTL.Perfil.php");
        exit;
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: HTML.RTL.Registo.html");
    exit;
}
?>
