<?php
require_once('../util/config.php');
$itemId = $_POST['item_id'];
$itemName = $_POST['item_name'];
$itemPrice = $_POST['item_price'];
$itemStock = $_POST['item_stock'];
$categoryId = $_POST['selected_category'];

if (isExist($itemId)) {
    updateItem($itemId, $itemName, $itemPrice, $itemStock, $categoryId);
} else {
    addItem($itemName, $itemPrice, $itemStock, $categoryId);
}
header("Refresh:0; url=../home.php");

function addItem($itemName, $itemPrice, $itemStock, $categoryId)
{
    try {
        $statement = getDbConnection()->prepare("INSERT INTO tbl_item (name, category_id, price, in_stock) VALUES (:itemName, :categoryId, :itemPrice, :inStock)");
        $statement->execute([
            'itemName' => $itemName,
            'categoryId' => $categoryId,
            'itemPrice' => $itemPrice,
            'inStock' => $itemStock
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function updateItem($itemId, $itemName, $itemPrice, $itemStock, $categoryId) {
    try {
        $statement = getDbConnection()->prepare("UPDATE tbl_item SET name = :itemName, category_id = :categoryId, price = :itemPrice, in_stock = :inStock WHERE id = :itemId");
        $statement->execute([
            'itemId' => $itemId,
            'itemName' => $itemName,
            'categoryId' => $categoryId,
            'itemPrice' => $itemPrice,
            'inStock' => $itemStock
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function isExist($itemId)
{
    try {
        $statement = getDbConnection()->prepare("SELECT * FROM tbl_item WHERE id = :itemId");
        $statement->execute([
            'itemId' => $itemId
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
