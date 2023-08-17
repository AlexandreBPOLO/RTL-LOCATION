<?php
$email = $_COOKIE['RTL'];

if (isset($email)) {
  $liga = mysqli_connect('localhost', 'root', 'root', 'RTL');
  $verifica = mysqli_query($liga, "SELECT * FROM utilizadores WHERE email='$email'");
  $linha = mysqli_fetch_array($verifica);
} else {
  header("Location: HTML.RTL.Entrar.html");
  exit(); // Adicionando um 'exit()' para garantir que o código seja interrompido após o redirecionamento.
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Perfil</title>
  <style>
    /* Estilos gerais do corpo da página */
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f8f8;
      color: #333333;
      margin: 0;
      padding: 0;
    }

    /* Estilos do cabeçalho do perfil */
    .profile-header {
      background-color: #0099cc;
      padding: 40px;
      text-align: center;
      color: #ffffff;
    }

    .profile-header h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .profile-header p {
      font-size: 18px;
      color: #ffffff;
      margin-top: -10px;
    }

    /* Estilos do conteúdo do perfil */
    .profile-content {
      margin: 40px;
      background-color: #ffffff;
      padding: 40px;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .profile-content p {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .profile-content .label {
      font-weight: bold;
    }

    .profile-content .data {
      color: #333333;
    }

    /* Estilos do rodapé do perfil */
    .profile-footer {
      text-align: center;
      margin-top: 30px;
    }

    .profile-footer a {
      display: inline-block;
      background-color: #0099cc;
      color: rgb(255, 255, 255);
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }

    /* Estilos do logotipo do perfil */
    .profile-logo {
      text-align: center;
      padding: 10px;
    }

    .profile-logo img {
      max-width: 150px;
      height: auto;
      border-radius: 50%;
      border: none;
    }

    /* Estilos do avatar do perfil */
    .profile-avatar {
      position: relative;
      width: 120px;
      height: 120px;
      margin: 0 auto;
      border-radius: 50%;
      overflow: hidden;
    }

    .profile-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Estilos do campo de senha */
    .password-field {
      position: relative;
    }

    .password-field input[type="password"] {
      padding-right: 30px;
      border-radius: 5%;
    }

    .password-field .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }

    .password-field .toggle-password:hover {
      color: #333;
    }

    /* Estilos do campo de upload de avatar */
    .profile-avatar input[type="file"] {
      display: none;
    }

    .profile-avatar label {
      position: relative;
      display: inline-block;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }

    .profile-avatar label::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      opacity: 0;
      transition: opacity 0.3s;
    }

    .profile-avatar label:hover::before {
      opacity: 1;
    }

    .profile-avatar label img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    /* Media queries para telas menores */
    @media (max-width: 950px) {
      .profile-avatar {
        position: static;
        margin-bottom: 20px;
        width: 150px;
        height: 150px;
      }

      .profile-avatar img {
        width: 150px;
        height: 150px;
      }

      .profile-header h1 {
        font-size: 48px;
      }

      .profile-header p {
        font-size: 24px;
      }

      .profile-content p {
        font-size: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="profile-header">
    <div class="profile-avatar">
      <label for="avatar-upload">
        <img src="placeholder.png" alt="Avatar">
      </label>
      <input type="file" id="avatar-upload">
    </div>
    <div class="profile-logo">
      <img src="RTL.png" alt="Logo">
    </div>
    <h1>Perfil</h1>
    <p>Bem-vindo(a) de volta!</p>
  </div>
  <div class="profile-content">
    <p><span class="label">Nome:</span> <span class="data" id="nome-usuario"><?php echo $linha['nome']; ?></span></p>
    <p><span class="label">Email:</span> <span class="data" id="email-usuario"><?php echo $linha['email']; ?></span></p>
    <p><span class="label">Telefone:</span> <span class="data" id="telefone-usuario"><?php echo $linha['telefone']; ?></span></p>
    <p><span class="label">Data de Nascimento:</span> <span class="data" id="data-nascimento-usuario"><?php echo $linha['data_nascimento']; ?></span></p>
    <p><span class="label">Último Login:</span> <span class="data" id="ultimo-login-usuario"><?php echo $linha['ultimo']; ?></span></p>
    <div class="password-field">
      <label for="password">Senha:</label>
      <input type="password" id="password" value="<?php echo $linha['pass']; ?>">
      <span class="toggle-password">Mostrar</span>
    </div>
  </div>
  <div class="profile-footer">
    <a href="GPSPRIN.html">Início</a>
    <a href="HTML.RTL.Historico de localização.html">Histórico</a>
  </div>

  <script>
    // Alternar visibilidade da senha
    var passwordInput = document.getElementById("password");
    var togglePassword = document.querySelector(".toggle-password");

    togglePassword.addEventListener("click", function () {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.textContent = "Ocultar";
      } else {
        passwordInput.type = "password";
        togglePassword.textContent = "Mostrar";
      }
    });
  </script>
</body>

</html>




