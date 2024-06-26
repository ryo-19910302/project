<?php
require "connect.php";

// 台一覧を取得する関数
function getMachineList($pdo) {
    $sql = "SELECT number, name FROM machine_list";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>