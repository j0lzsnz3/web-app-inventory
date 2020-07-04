<?php
require_once('../util/config.php');
require_once('../util/config.php');
$itemId = $_POST['item_id'];

try {
    if (isExist($itemId)) {
        $statement = getDbConnection()->prepare("DELETE FROM tbl_item WHERE id = :itemId");
        $statement->execute([
            'itemId' => $itemId
        ]);
    }

    header("Refresh:0; url=../home.php");
} catch (PDOException $exception) {
    return "Error!: " . $exception->getMessage();
    die();
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
