<?php
require_once('../util/config.php');
$categoryId = $_POST['category_id'];
$categoryName = $_POST['category_name'];

// $categoryId > 0 adalah add event
// $event == 0 adalah delete event
if (isExist($categoryId)) {
    updatecategory($categoryId, $categoryName, $categoryPrice, $categoryStock);
} else {
    addcategory($categoryId, $categoryName, $categoryPrice, $categoryStock);
}
header("Refresh:0; url=../home.php");

function addcategory($categoryName) {
    try {
        $statement = getDbConnection()->prepare("INSERT INTO tbl_category (name) VALUES (:categoryName)");
        $statement->execute([
            'categoryName' => $categoryName
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function updatecategory($categoryId, $categoryName) {
    try {
        $statement = getDbConnection()->prepare("UPDATE tbl_category SET name = :categoryName WHERE id = :categoryId");
        $statement->execute([
            'categoryId' => $categoryId,
            'categoryName' => $categoryName
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
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
