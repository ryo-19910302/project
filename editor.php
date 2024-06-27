<?php
require "connect.php";
require "machine_data.php";

// 台一覧を取得
$machine_list = getMachineList($pdo);

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームフィールドからデータを取得
    $date = $_POST['playYMD']; // 日付のフォーマットはYYYY-MM-DDと仮定
    $machineType = $_POST['machine_list'];
    $invest = $_POST['invest'];
    $retrieve = $_POST['retrieve'];
    $remarks = $_POST['remarks'];
    $profit = $retrieve - $invest;
    $win = ($retrieve >= $invest) ? 1 : 0;

    // デバッグ
    // var_dump($_POST);
    // echo '<pre>';
    // var_dump($machine_list);
    // echo '</pre>';

    if (empty($date) || empty($machineType) || empty($invest) || empty($retrieve)) {
        echo "<p style='color:red;'>すべての必須フィールドを入力してください。</p>";
    } else {
        try {
            // データベースにデータを挿入するSQL文を準備
            $sql = "INSERT INTO results (date, machine_type, invest, retrieve, remarks, profit, win) VALUES (:date, :machineType, :invest, :retrieve, :remarks, :profit, :win)";
            // PDOオブジェクトを使用してSQLを用意
            $stmt = $pdo->prepare($sql);
            // SQL文内のプレースホルダーに変数の値を挿入
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':machineType', $machineType);
            $stmt->bindParam(':invest', $invest);
            $stmt->bindParam(':retrieve', $retrieve);
            $stmt->bindParam(':remarks', $remarks);
            $stmt->bindParam(':profit', $profit);
            $stmt->bindParam(':win', $win);
            // SQLを実行
            if ($stmt->execute()) {
                echo "<p style='color:green;'>登録しました。</p>";
            } else {
                echo "<p style='color:red;'>データベースエラーが発生しました。</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>収支編集</title>
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
                <h1>収支の編集</h1>
                <div class="btn_area">
                </div>
            </div>
            <div id="contents" style="height: auto !important;">
                <div id="sub">
                </div>
                <div id="main">
                    <div id="EditRegist" style="vertical-align: top;">	
                        <div style="text-align:left;">
                            <!-- <span style="font-weight:bold;">日付</span> -->
                            <input type="date" id="playYMD" name="playYMD" style="font-weight:bold;" value="2024-06-25"></span>
                            
                            <input type="submit" name="submitDate" value="表示">
                        </div>

                        <table class="editor_input_table" style="width:550px;">
                                <tr>
                                    <th style="width:30%;">台</th>
                                    <th style="width:35%;">投資金額</th>
                                    <th style="width:35%;">回収金額</th>
                                </tr>
                            <tbody>
                                <tr>
                                    <td class="DataColumnEditor" style="vertical-align: middle;white-space:nowrap;">
                                        <select name="machine_list" id="machine_list" style="width: 100%;">
                                            <?php
                                            // ループで選択肢を生成
                                            foreach ($machine_list as $machine) {
                                                echo '<option value="' . $machine['number'] . '">' . htmlspecialchars($machine['name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td class="DataColumnEditor">
                                        ¥<input name="invest" type="text" value="0" maxlength="6" id="invest" style="width:70px;height:25px">
                                    </td>
                                    <td class="DataColumnEditor">
                                        ¥<input name="retrieve" type="text" value="0" maxlength="6" id="retrieve" style="width:70px;height:25px">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">備考</th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="DataColumnEditor" style="vertical-align:top;">
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
                        <!-- <span style="font-weight:bold;">登録情報</span>
                        <span id="playYMD2" style="font-weight:bold;">2024/06/25</span> -->
                    </div>

                    <!-- 登録情報表示 -->
                    <?php
                    // if (isset($_POST['submitDate'])) {
                    //     $selectedDate = $_POST['playYMD'];
                    //     // echo '<h2>登録情報 ' . htmlspecialchars($selectedDate) . '</h2>';
                    //     echo '<h2 class="fs_16" style="padding-top: 20px;">収支情報 ' . date('Y/m/d', strtotime($selectedDate)) . '</h2>';

                    //     // 登録情報の取得と表示
                    //     try {
                    //         $sql = "SELECT * FROM results WHERE date = :selectedDate";
                    //         $stmt = $pdo->prepare($sql);
                    //         $stmt->bindParam(':selectedDate', $selectedDate);
                    //         $stmt->execute();
                    //         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    //         if (count($results) > 0) {
                    //             echo '<table class="nomal_input_table fs_12" border="1">';
                    //             echo '<tr><th>機種</th><th>投資金額</th><th>回収金額</th><th>備考</th><th>編集</th><th>削除</th></tr>';
                    //             foreach ($results as $row) {
                    //                 echo '<tr>';
                    //                 echo '<td>' . htmlspecialchars($row['machine_type']) . '</td>';
                    //                 echo '<td>¥' . htmlspecialchars($row['invest']) . '</td>';
                    //                 echo '<td>¥' . htmlspecialchars($row['retrieve']) . '</td>';
                    //                 echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
                    //                 echo '<td><a href="edit.php?id=' . $row['id'] . '">編集</a></td>';
                    //                 echo '<td><a href="delete.php?id=' . $row['id'] . '" onclick="return confirm(\'本当に削除しますか？\')">削除</a></td>';
                    //                 echo '</tr>';
                    //             }
                    //             echo '</table>';
                    //         } else {
                    //             echo '<p>該当するデータはありません。</p>';
                    //         }
                    //     } catch (PDOException $e) {
                    //         echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
                    //     }
                    // }
                    ?>

                </div>
            </div>
        </form>
    </body>
</html>
