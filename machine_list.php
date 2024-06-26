<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <title>台一覧</title>
        <link rel="stylesheet" href="base.css"> <!-- CSSファイル読込 -->
    </head>
    <body>
        <h1>収支管理</h1>
        <form method="POST" action="machine_list.php">
            <div class="head_menu">
                <div class="head_menu_list">
                    <ul>
                        <li><a href="http://localhost/project/home.html">ホーム</a></li>
                        <li><a href="http://localhost/project/profit_register.php">収支登録</a></li>
                        <li><a href="http://localhost/project/machine_list.php">台一覧</a></li>
                        <li><a href="http://localhost/project/config.php">設定</a></li>
                    </ul>
                </div>
            </div>

            <div class="bar1">
                <h1>台一覧</h1>
                <div class="btn_area">
                </div>
            </div>

            <div class="wrapper fs_12">

                <table width="100%" class="tb_custom_bd">
                    <tbody>
                        <tr>
                            <th width="140">台名</th>
                            <td>
                                <input type="text" id="name" name="name" maxlength="20" class="wd_200 full">&nbsp;
                                <input type="submit" id="regist" value="登録" class="btn_or btn_save">&nbsp;
                                <input type="button" id="cancel" value="キャンセル">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div id="machine_list" class="mt_10">
                    <?php
                    require "connect.php";

                    // 台情報を取得する関数
                    function getMachineList($pdo) {
                        try {
                            $sql = "SELECT name FROM machine_list";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            return $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            echo '<div class="frame fs_12">データベースエラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
                            return [];
                        }
                    }

                    // POSTリクエスト処理
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['name'])) {
                            $name = trim($_POST['name']);
                    
                            if (!empty($name)) {
                                try {
                                    // 台名をデータベースに登録するSQLクエリ
                                    $sql = "INSERT INTO machine_list (name) VALUES (:name)";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':name', $name);
                    
                                    // SQLを実行
                                    if ($stmt->execute()) {
                                        echo "<script>showModal('台名を登録しました。');</script>";
                                    } else {
                                        echo "<script>showModal('データベースエラーが発生しました。');</script>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<script>showModal('データベースエラーが発生しました: " . $e->getMessage() . "');</script>";
                                }
                            } else {
                                echo "<script>showModal('台名を入力してください。');</script>";
                            }
                        }
                    }

                    // 台名リストを取得して表示
                    $machines = getMachineList($pdo);
                    // try {
                        $sql = "SELECT name FROM machine_list";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $machines = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($machines) {
                            echo '<table width="100%" class="mv_5 tb_custom_bd fs_12">';
                            echo '<tbody>';
                            echo '<tr>';
                            echo '<th class="ta_l">台名</th>';
                            echo '</tr>';

                            foreach ($machines as $machine) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($machine['name'], ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<div class="frame fs_12">台は見つかりません。</div>';
                        }
                    
                    // } catch (PDOException $e) {
                    //     echo '<div class="frame fs_12">データベースエラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
                    // }
                    ?>
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
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        function showModal(message) {
            document.getElementById("modal-message").innerText = message;
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    </body>
</html>