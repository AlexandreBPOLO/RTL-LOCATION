<?php
$email = $_POST['email'];
$senha = $_POST['senha'];

if (isset($email) && isset($senha)) {
    $liga = mysqli_connect('localhost', 'root', 'root', 'RTL');
    $verifica = mysqli_query($liga, "SELECT * FROM utilizadores WHERE email='$email'");
    if (mysqli_num_rows($verifica) > 0) {
        $verifica2 = mysqli_query($liga, "SELECT * FROM utilizadores WHERE email='$email' and pass='$senha'");
        if (mysqli_num_rows($verifica2) > 0) {
            setcookie("RTL", $email, time() + 3600); /* expire in 1 hour */
            $verifica3 = mysqli_query($liga, "UPDATE utilizadores set ultimo=concat(CURRENT_DATE(),' ',CURRENT_TIME())  WHERE email='$email'");
            // Redirecionar para a página de perfil
            header("Location: HTML.RTL.Perfil.php");
            exit;
        } else {
            echo "Password Incorreta!!!";
        }
    } else {
        echo "Utilizador não Existente";
    }
} else {
    header("Location: HTML.RTL.Entrar.html");
    exit;
}
?>

