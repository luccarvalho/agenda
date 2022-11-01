<?php

session_start();

include_once("connection.php");
include_once("url.php");

$data = $_POST;

// Modificações no Banco
if (!empty($data)) {

    // Criar contato
    if ($data["type"] === "create") {
        $name = $data["name"];
        $phone = $data["phone"];
        $observation = $data["observation"];

        $query = "INSERT INTO contacts (name, phone, observation) VALUES (:name, :phone, :observation)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observation", $observation);

        try {
            $stmt->execute();
            $_SESSION["msg"] = "Contato criado com sucesso!";
        } catch (PDOException $e) {
            // erro na conexão
            $error = $e->getMessage();
            echo "Erro: $error";
        }
    }

    // Redirecionando para a home
    header("Location:" . $BASE_URL . "../index.php");

    // Seleção de Dados
} else {
    $id;

    if (!empty($_GET)) {
        $id = $_GET["id"];
    }

    if (!empty($id)) {
        $query = "SELECT * FROM contacts WHERE id= :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $contact = $stmt->fetch();
    } else {
        // Retorna todos os contatos
        $contacts = [];

        $query = "SELECT * FROM contacts";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $contacts = $stmt->fetchAll();
    }
}

// Fechando conexão
$conn = null;
