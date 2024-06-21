<?php

require "connect.php";

$maxloss = trim($_POST['max_loss']);

$errors = array();

// バリデーション
if (empty($maxloss)) {
    // $errors[] = "最大損失額を入力してください";
}

if (empty($errors)) {
    $sql = "INSERT INTO config (max_loss) VALUES (:max_loss)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':max_loss', $max_loss);
} else {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>設定</title>
        <style>

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            .head_menu {
                background-color: #333;
                position: sticky;
                top: 0;
                display: flex;
                width: 100%;
            }

            .head_menu_list {
                color: #ffffff;
            }

            .head_menu_list ul {
                list-style-type: none;
                padding: 0;
                font-size: 0;
            }

            .head_menu_list li {
                float: left;
                margin: 0;
                padding: 0;
                font-size: 16px;
            }

            .head_menu_list a {
                cursor: pointer;
                color: #ffffff;
                text-decoration: none; /* リストの下線を削除 */
            }

            /* .head_menu_list > ul > li {
                position: relative;
            } */

            .head_menu_list span, .head_menu_list a	{
                display: block;
                padding: 5px 20px;
                color: white;
                background-color: #333;
                white-space: nowrap;
                line-height: 30px;
                cursor: pointer;
            }

            .head_menu_list span:hover, .head_menu_list a:hover, .head_menu_list .span_selected	{
                background-color: #00a0c6 !important;
            }

            .tb_custom_bd th {
                padding: 10px 40px;
                background-color: #e6e6fa;
                border: solid 1px #C0C0C0;
                color: #333333;
            }

            .tb_custom_bd td {
                background-color: #ffffff;
                border: solid 1px #C0C0C0;
                color: #333333;
            }

            .max_loss {
                width: 200px;
            }

            .bar1 {
                display: flex;
                /* float: left; */
                align-items: center;  /* 修正点 *//* 要素を垂直方向に中央揃え */
            }

            .bar1 h1 {
                width: 140px;
                height: 50px;
                /* display: table-cell; */
                display: flex; /* 修正点 */
                align-items: center; /* 修正点 */
                justify-content: center;    /* 修正点 */ 
                vertical-align: middle;
                /* text-align: center; */
                font-size: 16px;
                font-weight: normal;
                padding-left: 10px;
                background-color: #6a5acd;
                color: #fff;
            }

            .wrapper {
                padding: 10px 20px;
            }

            .button {
                width: 80px;
                height: 50px;
                font-size: 16px;
                /* vertical-align: middle; */
                text-align: center;
                color: #fff;
                background-color: #4b0082;
                cursor: pointer;
                /* border: 0; */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn_area {
                display: flex;
                align-items: center; /* 要素を垂直方向に中央揃え */
            }

        </style>
    </head>
    <body>
        <h1>収支管理</h1>
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
                <h1>設定</h1>
                <div class="btn_area">
                    <input type="button" class="button" value="保存"> <!-- onclickを追加する -->
                    <input type="button" class="button" value="戻る"> <!-- onclickを追加する -->
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <table width="100%" class="tb_custom_bd">
                        <tbody>
                            <tr>
                                <th width="200">最大損失額</th>
                                <td>
                                    <input type="text" class="max_loss" maxlength="20">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>





    </body>
</html>
