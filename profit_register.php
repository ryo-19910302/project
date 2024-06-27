<?php
require "connect.php";
require "machine_data.php";

// 台一覧を取得
$machine_list = getMachineList($pdo);

// 現在日を取得
$currentDate = date('Y-m-d');

// モーダルメッセージを初期化
$modalMessage = '';

// POSTリクエストを処理
$selectedDate = $currentDate; // 初期値を現在日に設定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedDate = $_POST['playYMD']; // POSTデータから日付を取得（選択日付の保持）
    // フォームフィールドからデータを取得
    $machineType = $_POST['machine_list'];
    $invest = $_POST['invest'];
    $retrieve = $_POST['retrieve'];
    $remarks = $_POST['remarks'];
    $profit = $retrieve - $invest;
    $win = ($retrieve >= $invest) ? 1 : 0;

    $period = date('Y-m', strtotime($selectedDate)); // YYYY-MM
    $day = date('d', strtotime($selectedDate)); // DD

    if (empty($selectedDate) || empty($machineType) || empty($invest) || empty($retrieve)) {
        echo "<p style='color:red;'>すべての必須フィールドを入力してください。</p>";
    } else {
        try {
            // 既存の日単位の合計投資額・回収額を取得するSQL
            $sql_existing_totals = "SELECT SUM(invest) AS total_invest, SUM(retrieve) AS total_retrieve FROM results WHERE date = :date";
            // $sql_total_retrieve = "SELECT SUM(retrieve) AS total_retrieve FROM results WHERE date = :date";

            $stmt_existing_totals = $pdo->prepare($sql_existing_totals);
            // $stmt_total_retrieve = $pdo->prepare($sql_total_retrieve);

            $stmt_existing_totals->bindParam(':date', $selectedDate);
            // $stmt_total_invest->bindParam(':date', $selectedDate);
            // $stmt_total_retrieve->bindParam(':date', $selectedDate);
            
            $stmt_existing_totals->execute();
            // $stmt_total_invest->execute();
            // $stmt_total_retrieve->execute();

            $existing_totals = $stmt_existing_totals->fetch(PDO::FETCH_ASSOC);
            // $total_invest_result = $stmt_total_invest->fetch(PDO::FETCH_ASSOC);
            // $total_retrieve_result = $stmt_total_retrieve->fetch(PDO::FETCH_ASSOC);

            // 新規に登録する合計投資額と回収額を計算
            $invest_amount = $existing_totals['total_invest'] + $invest;
            $retrieve_amount = $existing_totals['total_retrieve'] + $retrieve;
            // $invest_amount = $total_invest_result['total_invest'] + $invest;
            // $retrieve_amount = $total_retrieve_result['total_retrieve'] + $retrieve;
            $profit_amount = $retrieve_amount - $invest_amount;

            // データベースにデータを挿入するSQL文を準備
            $sql_insert_results  = "INSERT INTO results (date, machine_type, invest, retrieve, remarks, profit, win) VALUES (:date, :machineType, :invest, :retrieve, :remarks, :profit, :win)";
            $sql_insert_daily_results  = "INSERT INTO dairy_results (period, day, invest_amount, retrieve_amount, profit) VALUES (:period, :day, :invest_amount, :retrieve_amount, :profit)";

            // PDOオブジェクトを使用してSQLを用意
            $stmt_insert_results = $pdo->prepare($sql_insert_results);
            $stmt_insert_daily_results = $pdo->prepare($sql_insert_daily_results );
            // SQL文内のプレースホルダーに変数の値を挿入
            $stmt_insert_results->bindParam(':date', $selectedDate);
            $stmt_insert_results->bindParam(':machineType', $machineType);
            $stmt_insert_results->bindParam(':invest', $invest);
            $stmt_insert_results->bindParam(':retrieve', $retrieve);
            $stmt_insert_results->bindParam(':remarks', $remarks);
            $stmt_insert_results->bindParam(':profit', $profit);
            $stmt_insert_results->bindParam(':win', $win);

            $stmt_insert_daily_results->bindParam(':period', $period);
            $stmt_insert_daily_results->bindParam(':day', $day);
            $stmt_insert_daily_results->bindParam(':invest_amount', $invest_amount);
            $stmt_insert_daily_results->bindParam(':retrieve_amount', $retrieve_amount);
            $stmt_insert_daily_results->bindParam(':profit', $profit_amount);

            // トランザクション開始
            $pdo->beginTransaction();
            
            // SQLを実行
            $stmt_insert_results->execute();
            $stmt_insert_daily_results->execute();

            // コミット
            $pdo->commit();

            // モーダルメッセージを設定
            $modalMessage = '登録しました。';

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>収支登録</title>
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
                <h1>収支登録</h1>
                <div class="btn_area">
                </div>
            </div>
            <div id="contents" style="height: auto !important;">
                <div id="sub">
                </div>
                <div id="main">
                    <div id="NewRegist" style="vertical-align: top;">	
                        <div style="text-align:left;">
                            <span style="font-weight:bold;">日付</span>
                            <input type="date" id="playYMD" name="playYMD" style="font-weight:bold;" value="<?php echo htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8'); ?>"></span>
                            
                            <input type="submit" name="submitDate" value="表示">
                        </div>

                        <table class="nomal_input_table" style="width:550px;">
                                <tr>
                                    <th style="width:30%;">台</th>
                                    <th style="width:35%;">投資金額</th>
                                    <th style="width:35%;">回収金額</th>
                                </tr>
                            <tbody>
                                <tr>
                                    <td class="DataColumnOrange" style="vertical-align: middle;white-space:nowrap;">
                                        <select name="machine_list" id="machine_list" style="width: 100%;">
                                            <?php
                                            // ループで選択肢を生成
                                            foreach ($machine_list as $machine) {
                                                echo '<option value="' . $machine['number'] . '">' . htmlspecialchars($machine['name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td class="DataColumnOrange">
                                        ¥<input name="invest" type="text" value="0" maxlength="6" id="invest" style="width:70px;height:25px">
                                    </td>
                                    <td class="DataColumnOrange">
                                        ¥<input name="retrieve" type="text" value="0" maxlength="6" id="retrieve" style="width:70px;height:25px">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">備考</th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="DataColumnOrange" style="vertical-align:top;">
                                        <textarea name="remarks" rows="3" id="remarks" style="font-family:メイリオ;font-size:Small;width:99%;"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width:650px;">
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="submit" name="ctl00$ContentPageHeader$btnRegist" value="登録" id="ContentPageHeader_btnRegist">
                                        <input type="submit" name="ctl00$ContentPageHeader$btnClear" value="クリア" id="ContentPageHeader_btnClear">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                    </div>
                    <div style="text-align:left;">

                    </div>

                    <!-- 登録情報表示 -->
                    <?php
                    if (isset($_POST['submitDate'])) {
                        $selectedDate = $_POST['playYMD'];
                        echo '<h2 class="fs_16" style="padding-top: 20px;">収支情報 ' . date('Y/m/d', strtotime($selectedDate)) . '</h2>';

                        // 登録情報の取得と表示
                        try {
                            $sql = "SELECT r.*,
                                           m.name
                                    FROM results r
                                    join machine_list m on m.number = r.machine_type
                                    WHERE r.date = :selectedDate";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':selectedDate', $selectedDate);
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $invest_amount = 0;
                            $retrieve_amount = 0;
                            if (count($results) > 0) {
                                echo '<table class="nomal_input_table fs_12" border="1">';
                                echo '<tr><th>台</th><th>投資金額</th><th>回収金額</th><th>備考</th><th>編集</th><th>削除</th></tr>';
                                foreach ($results as $row) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td>¥' . htmlspecialchars($row['invest']) . '</td>';
                                    echo '<td>¥' . htmlspecialchars($row['retrieve']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
                                    echo '<td><a href="editor.php?N=' . $row['number'] . '">編集</a></td>';
                                    echo '<td><a href="delete.php?N=' . $row['number'] . '" onclick="return confirm(\'本当に削除しますか？\')">削除</a></td>';
                                    echo '</tr>';
                                    $invest_amount += $row['invest'];
                                    $retrieve_amount += $row['retrieve'];
                                }
                                // echo "<pre>";
                                // print_r($row);
                                // echo "</pre>";
                                // echo '</table>';
                            } else {
                                echo '<p>該当するデータはありません。</p>';
                            }
                        } catch (PDOException $e) {
                            echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
                        }
                    }
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
