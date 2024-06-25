<?php
require "connect.php";

?>

<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <title>台選択</title>
        <link rel="stylesheet" href="base.css"> <!-- CSSファイル読込 -->
    </head>
    <body>
        <h1>台選択</h1>
        <form>
            <div class="head_menu">
                <div class="head_menu_list">
                    <ul>
                        <li>
                            <a href="http://localhost/project/home.html">ホーム</a>
                        </li>
                        <li>
                            <a href="http://localhost/project/profit_register.php">収支登録</a>
                        </li>
                        <li>
                            <a href="http://localhost/project/config.php">設定</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bar1">
                <h1>台選択</h1>
                <div class="btn_area">
                    <!-- <input type="button" class="button" value="保存"> onclickを追加する -->
                    <!-- <input type="submit" class="button" value="保存"> -->
                    <!-- <input type="button" class="button" value="戻る"> onclickを追加する -->
                </div>
            </div>

            <div class="wrapper">
                <div id="machine_list">
                    <table width="100%" id="sortable" class="mv_5 tb_custom_bd">
                        <tbody class="ui-sortable">
                            <tr>
                                <th width="40"></th>
                                <th class="ta_c">台名</th>
                                <th width="115">操作</th>
                            </tr>
                            <tr>
                                <th>
                                    <!-- <input type="hidden" name="number[0]" value="1"> 不要 -->
                                    <!-- <input type="hidden" name="row[]" value="0"> 不要 -->
                                </th>
                                <td>北斗の拳</td>
                                <td class="ta_c">
                                    <!-- <input type="button" value="編集" class="btn_or" onclick="startEdit(this, 1, '三菱電機クレジット 株式会社')">&nbsp; -->
                                    <input type="button" value="削除" class="btn_or" onclick="doSubmit('delete', 1)">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </body>
</html>