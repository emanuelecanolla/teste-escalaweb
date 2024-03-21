<?php
require '/vendor/autoload.php';
require 'ContatoModel.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
// Conexão com o banco de dados (ajuste com suas credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_cadastro";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Cria uma instância do modelo
$contatoModel = new ContatoModel($conn);

// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$mensagem = $_POST['mensagem'];

// Insere os dados no banco de dados
if ($contatoModel->inserirContato($nome, $email, $telefone, $mensagem)) {
    // Dados inseridos no banco de dados com sucesso

    // Envio do e-mail com PHPMailer
    $mail = new PHPMailer(true);

 // Obtém todos os contatos do modelo
$contatos = $contatoModel->getAllContatos();

// Ação de enviar contato
if ($action == 'enviar_contato') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $mensagem = $_POST['mensagem'];

// Exibe os contatos em uma tabela HTML
if (!empty($contatos)) {
    echo "<h2>Lista de Contatos</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Mensagem</th></tr>";
    foreach ($contatos as $contato) {
        echo "<tr><td>".$contato["id"]."</td><td>".$contato["nome"]."</td><td>".$contato["email"]."</td><td>".$contato["telefone"]."</td><td>".$contato["mensagem"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Nenhum contato encontrado.";
}   

    try {
        // Configurações do servidor SMTP (ajuste com suas configurações)
        $mail->isSMTP();
        $mail->Host = 'smtp.escalaweb.com.br';
        $mail->SMTPAuth = true;
        $mail->Username = 'teste@escalaweb.com.br';
        $mail->Password = 'yf8ah#qe4 ';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinatário
        $mail->setFrom('teste@escalaweb.com.br', 'Escala web');
        $mail->addAddress('teste@escalaweb.com.br');

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Novo Contato';
        $mail->Body = "Nome: $nome<br>Email: $email<br>Telefone: $telefone<br>Mensagem: $mensagem";

        // Envia o e-mail
        $mail->send();
        echo 'Mensagem enviada com sucesso!';
    } catch (Exception $e) {
        echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
    }
} else {
    echo "Erro ao enviar mensagem: " . $conn->error;
}
}

// Fecha a conexão com o banco de dados
$conn->close();
?>