<?php

require "connect.php";

$max_loss = $_POST['max_loss'];
$errors = array();

// データベースから最新のmax_lossを取得
try {
    $sql = "SELECT max_loss FROM config ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // printr($result);
    if ($result) {
        // 既存レコードがある場合はエラーを返す
        echo "<p style='color:red;'>既に登録されています。</p>";
        exit;
    }
} catch (PDOException $e) {
    // echo "<p style='color:red;'>設定が登録されていません: " . $e->getMessage() . "</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $max_loss = trim($_POST['max_loss']);


    // バリデーション
    if (empty($max_loss)) {
        $errors[] = "最大損失額を入力してください";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO config (max_loss) VALUES (:max_loss)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':max_loss', $max_loss);


            // SQLを実行
            if ($stmt->execute()) {
                echo "<p style='color:green;'>正常に登録されました。</p>";
            } 
        } catch (PDOException $e) {
            echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>設定</title>
        <link rel="stylesheet" href="base.css"> <!-- CSSファイル読込 -->
    </head>
    <body>
        <h1>収支管理</h1>
        <form action="" method="POST">
            <div class="head_menu">
                <div class="head_menu_list">
                    <ul>
                        <li><a href="http://localhost/project/home.html">ホーム</a></li>
                        <li><a href="http://localhost/project/profit_register.php">収支登録</a></li>
                        <li><a href="http://localhost/project/config.php">設定</a></li>
                    </ul>
                </div>
            </div>

            <div class="bar1">
                <h1>設定</h1>
                <div class="btn_area">
                    <input type="submit" class="button" value="保存">
                    <input type="button" class="button" value="戻る">
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <table width="100%" class="tb_custom_bd">
                        <tbody>
                            <tr>
                                <th width="200">最大損失額</th>
                                <td>
                                    <input type="text" class="max_loss" name="max_loss" maxlength="20" value="<?php echo htmlspecialchars($max_loss, ENT_QUOTES, 'UTF-8'); ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </body>
</html>
