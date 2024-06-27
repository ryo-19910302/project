<?php
require "connect.php";

if (isset($_GET['N'])) {
    $number = $_GET['N'];

    try {
        $sql = "DELETE FROM results WHERE number = :number";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>削除しました。</p>";
        } else {
            echo "<p style='color:red;'>削除に失敗しました。</p>";
        }
        header("Location: profit_register.php"); // 収支登録画面にリダイレクト
        exit();
    } catch (PDOException $e) {
        echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
    }
}