<?php

require "connect.php";

$max_loss = '';
$errors = array();

// モーダルメッセージを初期化
$modalMessage = '';

// データベースから最新のmax_lossを取得する関数
function getMaxLoss($pdo) {
    try {
        $sql = "SELECT max_loss FROM config ORDER BY number DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['max_loss'];
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
    }
    return '';
}

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $max_loss = trim($_POST['max_loss']);

    // バリデーション
    if (empty($max_loss)) {
        // $errors[] = "最大損失額を入力してください";
        echo "<script>showModal('最大損失額を入力してください');</script>";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO config (max_loss) VALUES (:max_loss)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':max_loss', $max_loss);

            // SQLを実行
            // if ($stmt->execute()) {
            //     echo "<script>showModal('正常に登録されました。');</script>";
            // } 

            $stmt->execute();

            // モーダルメッセージを設定
            $modalMessage = '登録しました。';

        } catch (PDOException $e) {
            echo "<script>showModal('データベースエラーが発生しました');</script>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
            // echo "<script>showModal($error);</script>";
        }
    }
    $max_loss = getMaxLoss($pdo);   // 登録後に最新のmax_lossを表示
} else {
    $max_loss = getMaxLoss($pdo);   // 初回表示時に最新のmax_lossを表示
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
                        <li><a href="home.php">ホーム</a></li>
                        <li><a href="profit_register.php">収支登録</a></li>
                        <li><a href="machine_list.php">台一覧</a></li>
                        <li><a href="config.php">設定</a></li>
                    </ul>
                </div>
            </div>

            <div class="bar1">
                <h1>設定</h1>
                <div class="btn_area">
                    <input type="submit" class="button" value="保存">
                    <!-- <input type="button" class="button" value="戻る"> -->
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <table width="100%" class="tb_custom_bd">
                        <tbody>
                            <tr>
                                <th width="200" class="fs_12">最大損失額</th>
                                <td>
                                    <input type="text" class="max_loss" name="max_loss" maxlength="20" value="<?php echo htmlspecialchars($max_loss, ENT_QUOTES, 'UTF-8'); ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <!-- モーダルダイアログ -->
        <div id="myModal" class="modal">
            <div class="modal-content fs_12">
                <span class="close">&times;</span>
                <p id="modal-message"></p>
            </div>
        </div>

        <script>
            // モーダル表示用のスクリプト
            // var modal = document.getElementById("configModal");
            // var span = document.getElementsByClassName("close")[0];

            // function showModal(message) {
            //     document.getElementById("modal-message").innerText = message;
            //     modal.style.display = "block";
            // }

            // span.onclick = function() {
            //     modal.style.display = "none";
            // }

            // window.onclick = function(event) {
            //     if (event.target == modal) {
            //         modal.style.display = "none";
            //     }
            // }

            window.onload = function() {
                var modalMessage = <?php echo json_encode($modalMessage); ?>;
                if (modalMessage) {
                    alert(modalMessage);
                }
            };
        </script>
    </body>
</html>
