<?php
require_once('../util/config.php');
$itemId = $_POST['item_id'];
$itemName = $_POST['item_name'];
$itemPrice = $_POST['item_price'];
$itemStock = $_POST['item_stock'];

// $itemId > 0 adalah add event
// $event == 0 adalah delete event
if (isExist($itemId)) {
    updateItem($itemId, $itemName, $itemPrice, $itemStock);
} else {
    addItem($itemId, $itemName, $itemPrice, $itemStock);
}
header("Refresh:0; url=../home.php");

function addItem($itemId, $itemName, $itemPrice, $itemStock)
{
    try {
        $statement = getDbConnection()->prepare("INSERT INTO tbl_item (name, category_id, price, in_stock) VALUES (:itemName, 0, :itemPrice, :inStock)");
        $statement->execute([
            'itemName' => $itemName,
            'itemPrice' => $itemPrice,
            'inStock' => $itemStock
        ]);
    } catch (PDOException $exception) {
        return "Error!: " . $exception->getMessage();
        die();
    }
}

function updateItem($itemId, $itemName, $itemPrice, $itemStock) {
    try {
        $statement = getDbConnection()->prepare("UPDATE tbl_item SET name = :itemName, category_id = 0, price = :itemPrice, in_stock = :inStock WHERE id = :itemId");
        $statement->execute([
            'itemId' => $itemId,
            'itemName' => $itemName,
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
