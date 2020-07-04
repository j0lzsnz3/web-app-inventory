<?php
session_start();
require_once('../util/config.php');

try {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $validateEmail = findEmail($email);

        if ($validateEmail > 0) {
            if (password_verify($password, findPasswordByEmail($email))) {
                $_SESSION['user_id'] = $email;
                header("Location: ../home.html");
            } else {
                header("Location: ../login.html");
            }
        } else {
            header("Location: ../login.php");
        }
    }
} catch (Exception $exception) {
    return "Error!: " . $exception->getMessage();
    die();
}

// eksekusi untuk membuat default user
function createInitUser()
{
    try {
        $statement = getDbConnection()->prepare("INSERT INTO tbl_user(id, email, password) VALUES(0, :email, :password)");
        $statement->execute([
            'email' => 'imam.kurniansyah@go-jek.com',
            'password' => encrypt('imamganteng')
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function findEmail($email): int
{
    try {
        $statement = getDbConnection()->prepare("SELECT * FROM tbl_user WHERE email = :email LIMIT 1");
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->rowCount();
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function findPasswordByEmail($email): string
{
    try {
        $statement = getDbConnection()->prepare("SELECT password FROM tbl_user WHERE email = :email LIMIT 1");
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchColumn();

        if (!empty($result)) {
            return $result;
        } else {
            return "gagal bin kosong";
        }
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function encrypt($pure_string): string
{
    return password_hash($pure_string, PASSWORD_DEFAULT);
}
