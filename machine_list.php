<?php
require "connect.php";
require "machine_lists.php";

/*------------------------------------------------------------------------------
	DB
------------------------------------------------------------------------------*/
$machine_list = new MachineLists(_DB_HOST, _DB_USER, _DB_PASSWORD, _DB_DATABASE);s


// POSTリクエストを処理
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
                    echo "<p style='color:green;'>台名を登録しました。</p>";
                } else {
                    echo "<p style='color:red;'>データベースエラーが発生しました。</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color:red;'>データベースエラーが発生しました: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color:red;'>台名を入力してください。</p>";
        }
    }
}
?>

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
                        <li><a href="http://localhost/project/config.php">設定</a></li>
                    </ul>
                </div>
            </div>

            <div class="bar1">
                <h1>台一覧</h1>
                <div class="btn_area">
                </div>
            </div>

            <div class="wrapper">

            <table width="100%" class="tb_custom_bd">
                <tbody>
                    <tr>
                        <th width="140">台名</th>
                        <td>
                            <input type="text" id="name" name="name" maxlength="20" class="wd_200 full">&nbsp;
                            <input type="submit" id="regist" value="登録" class="btn_or btn_save">&nbsp;
                            <input type="button" id="cancel" value="キャンセル" class="btn_or btn_clear hide" onclick="cancelEdit()">
                        </td>
                    </tr>
                </tbody>
            </table>

                <div id="machine_list" class="mt_10">
                    <?php
                    $machine_buf = $machine_list->searchList();


                    if ($machine_buf) {
                        ?>
                        <table width="100%" id="sortable" class="mv_5 tb_custom_bd">
                            <tbody class="ui-sortable">
                                <?php
                                    foreach ($machine_buf as $i =>  $buf) {
                                        echo "<tr>\n";
                                        echo "<th class=\"move\">";
                                        echo "<input type=\"hidden\" name=\"number[".$i."]\" value=\"".$buf["number"]."\">";
                                        echo "<input type=\"hidden\" name=\"row[]\" value=\"".$i."\">";
                                        echo "</th>\n";
                                        echo "<td>".$buf["name"]."</td>\n";
                                        echo "<td class=\"ta_c\">";
                                        echo "<input type=\"button\" value=\"削除\" class=\"btn_or\" onclick=\"doSubmit('delete', ".$buf["number"].")\"";
                                    }
                                    echo "</tbody>\n";
                                    echo "</table>\n";
                    } else {
                        echo "<div class=\"frame fs_12\">台は見つかりません。</div>\n";
                    }
                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </body>
</html>