<?php
class ContatoModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function inserirContato($nome, $email, $telefone, $mensagem) {
        $sql = "INSERT INTO contatos (nome, email, telefone, mensagem) VALUES ('$nome', '$email', '$telefone', '$mensagem')";
        return $this->conn->query($sql);
    }

    public function getAllContatos() {
        $sql = "SELECT id, nome, email, telefone, mensagem FROM contatos";
        $result = $this->conn->query($sql);

        $contatos = array();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $contatos[] = $row;
            }
        }

        return $contatos;
    }
}
?>
