<?php

// PDOを使用したデータベース接続の設定

$dsn = 'mysql:host=localhost;dbname=hiyoku';
$username = 'root';
$password = 'r-takahashi'; // MAMPのデフォルトパスワードは 'root'

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'データベース接続に失敗しました: ' . $e->getMessage();
}

?>