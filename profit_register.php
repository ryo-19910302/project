<?php
require "connect.php";

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームフィールドからデータを取得
    $date = $_POST['playYMD']; // 日付のフォーマットはYYYY-MM-DDと仮定
    $machineType = $_POST['machine_list'];
    $invest = $_POST['invest'];
    $retrieve = $_POST['retrieve'];
    $remarks = $_POST['remarks'];
    $profit = $_POST['retrieve'] - $_POST['invest'];
    $win = ($_POST['retrieve'] >= $_POST['invest']) ? 1 : 0;

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
        <title>収支登録</title>
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
                        <li><a href="http://localhost/project/machine_list.php">台一覧</a></li>
                        <li><a href="http://localhost/project/config.php">設定</a></li>
                    </ul>
                </div>
            </div>
            <div id="contents" style="height: auto !important;">
                <div id="sub">
                    <input type="date" style="font-family:Meiryo;"></input>
                </div>
                <div id="main">
                    <div id="NewRegist" style="vertical-align: top;">	
                        <div style="text-align:left;">
                            <span style="font-weight:bold;">稼動登録</span>
                            <span id="playYMD" name="playYMD" style="font-weight:bold;">2024-06-25</span>
                            (<span id="playWeek" style="font-weight:bold;">火</span>)
                        </div>

                        <table class="nomal_input_table" style="width:550px;">
                            <tbody>
                                <tr>
                                    <th style="width:30%;">台</th>
                                    <th style="width:35%;">投資金額</th>
                                    <th style="width:35%;">回収金額</th>
                                </tr>
                                <tr>
                                    <td class="DataColumnOrange" style="vertical-align: middle;white-space:nowrap;">
                                        <table style="margin-top:2px;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="machine_list" id="machine_list" style="width: 100%;">
                                                        <?php
                                                        // // マシンリストの配列
                                                        // $machine_list = [
                                                        //     ['id' => '0000', 'name' => '----'],
                                                        //     ['id' => '0001', 'name' => '北斗の拳'],
                                                        //     // 他のマシンも追加
                                                        // ];

                                                        // machine_list.phpから台情報をインクルード
                                                        // include('machine_list.php');

                                                        // machine_list.phpから台情報をインクルード
                                                        // require_once('machine_list.php');
                                                        // $machines = getMachineList($pdo);

                                                        // ループで選択肢を生成
                                                        // foreach ($machine_list as $machine) {
                                                        //     // echo '<option value="' . htmlspecialchars($machine['id']) . '">' . htmlspecialchars($machine['name']) . '</option>';
                                                        //     echo '<option value="' . htmlspecialchars($machine['name']) . '</option>';
                                                        // }
                                                        ?>
                                                            <option value="0000">----</option>
                                                            <option value="0001">北斗の拳</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                        <span style="font-weight:bold;">登録情報</span>
                        <span id="playYMD2" style="font-weight:bold;">2024/06/25</span>
                        (<span id="playWeek2" style="font-weight:bold;">火</span>)
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>