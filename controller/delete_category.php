<?php
require_once('../util/config.php');
require_once('../util/config.php');
$categoryId = $_POST['category_id'];

try {
    if (isExist($categoryId)) {
        $statement = getDbConnection()->prepare("DELETE FROM tbl_category WHERE id = :categoryId");
        $statement->execute([
            'categoryId' => $categoryId
        ]);
    }

    header("Refresh:0; url=../home.php");
} catch (PDOException $exception) {
    return "Error!: " . $exception->getMessage();
    die();
}

function isExist($categoryId) {
    try {
        $statement = getDbConnection()->prepare("SELECT * FROM tbl_category WHERE id = :categoryId");
        $statement->execute([
            'categoryId' => $categoryId
        ]);
        $result = $statement->fetchAll();
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}
