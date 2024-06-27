<?php
require "connect.php";
require "machine_data.php";

// 台一覧を取得
$machine_list = getMachineList($pdo);

// 編集対象のデータnumberを取得
$number = isset($_GET['N']) ? $_GET['N'] : null;
// echo $number;

// 編集対象データを取得
if ($number) {
    try {
        $sql = "SELECT * FROM results WHERE number = :number";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $date = $result['date'];
            $machineType = $result['machine_type'];
            $invest = $result['invest'];
            $retrieve = $result['retrieve'];
            $remarks = $result['remarks'];
        } else {
            echo "<p style='color:red;'>該当するデータが見つかりません。</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
    }
}
// echo "<pre>";
// print_r($result);
// echo "</pre>";


// POSTリクエストを処理
if ($_POST) {
    // フォームフィールドからデータを取得
    $date = $_POST['playYMD']; // 日付のフォーマットはYYYY-MM-DDと仮定
    $machineType = $_POST['machine_list'];
    $invest = $_POST['invest'];
    $retrieve = $_POST['retrieve'];
    $remarks = $_POST['remarks'];
    $profit = $retrieve - $invest;
    $win = ($retrieve >= $invest) ? 1 : 0;

    if (empty($date) || empty($machineType) || empty($invest) || empty($retrieve)) {
        echo "<p style='color:red;'>すべての必須フィールドを入力してください。</p>";
    } else {
        try {
            // データベース更新するSQL文を準備
            $sql = "UPDATE results SET date = :date, machine_type = :machineType, invest = :invest, retrieve = :retrieve, remarks = :remarks, profit = :profit, win = :win WHERE number = :number";
            // PDOオブジェクトを使用してSQLを用意
            $stmt = $pdo->prepare($sql);
            // SQL文内のプレースホルダーに変数の値を挿入
            $stmt->bindParam(':number', $number);
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
                echo "<p style='color:red;'>データベースエラー1が発生しました。</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>データベースエラー2が発生しました: " . $e->getMessage() . "</p>";
        }
        // echo "<pre>";
        // print_r($stmt);
        // echo "</pre>";
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
                            <label style="font-weight:bold;"><?php echo date('Y/m/d', strtotime($date))?></label>
                            <!-- フォーム送信時には隠しフィールドとして送信する為 -->
                            <input type="hidden" name="playYMD" value="<?php echo htmlspecialchars($date); ?>">
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
                                                $selected = ($machine['number'] == $machineType) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($machine['number']) . '" ' . $selected . '>' . htmlspecialchars($machine['name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td class="DataColumnEditor">
                                        ¥<input name="invest" type="text" maxlength="6" id="invest" style="width:70px;height:25px" value="<?php echo htmlspecialchars($invest); ?>">
                                    </td>
                                    <td class="DataColumnEditor">
                                        ¥<input name="retrieve" type="text" maxlength="6" id="retrieve" style="width:70px;height:25px" value="<?php echo htmlspecialchars($retrieve); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">備考</th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="DataColumnEditor" style="vertical-align:top;">
                                        <textarea name="remarks" rows="3" id="remarks" style="font-family:メイリオ;font-size:Small;width:99%;"><?php echo htmlspecialchars($remarks); ?></textarea>
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
                </div>
            </div>
        </form>
    </body>
</html>
