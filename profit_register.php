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

    if (empty($date) || empty($machineType) || empty($invest) || empty($retrieve)) {
        echo "<p style='color:red;'>すべての必須フィールドを入力してください。</p>";
    } else {
        try {
            // データベースにデータを挿入するSQL文を準備
            // $sql = "INSERT INTO results (date, machine_type, invest, retrieve, remarks) VALUES (:date, :machineType, :invest, :retrieve, :remarks)";
            $sql = "INSERT INTO results (date, machine_type, invest, retrieve, remarks) VALUES (2024-01-01, 台A, 1000, 10000, 特になし)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':machineType', $machineType);
            $stmt->bindParam(':invest', $invest);
            $stmt->bindParam(':retrieve', $retrieve);
            $stmt->bindParam(':remarks', $remarks);
            // SQLクエリを実行
            if ($stmt->execute()) {
                echo "<p style='color:green;'>データが正常に登録されました。</p>";
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
            <div id="contents" style="height: auto !important;">
                <div id="sub">
                    <!-- <table id="Calendar1" cellspacing="0" cellpadding="0" rules="all" title="カレンダー" style="width:100%;height:200px;font-size:11pt;font-family:Meiryo;color:#663399;border-width:1px;border-style:solid;border-color:#F3B55B;background-color:LemonChiffon;border-collapse:collapse;">
                        <tbody>
                            <tr>
                                <td colspan="7" style="background-color:#990000;">
                                    <table cellspacing="0" style="color:LemonChiffon;font-family:Meiryo;font-size:12pt;font-weight:bold;width:100%;border-collapse:collapse;">
                                        <tbody>
                                            <tr>
                                                <td style="color:LemonChiffon;font-size:12pt;font-weight:bold;width:15%;">
                                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','V8887')" style="color:LemonChiffon" title="先月へ移動">&lt;</a>
                                                </td>
                                                <td align="center" style="width:70%;">2024年6月</td>
                                                <td align="right" style="color:LemonChiffon;font-size:12pt;font-weight:bold;width:15%;">
                                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','V8948')" style="color:LemonChiffon" title="来月へ移動">&gt;</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th align="center" abbr="日曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">日</th>
                                <th align="center" abbr="月曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">月</th>
                                <th align="center" abbr="火曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">火</th>
                                <th align="center" abbr="水曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">水</th>
                                <th align="center" abbr="木曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">木</th>
                                <th align="center" abbr="金曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">金</th>
                                <th align="center" abbr="土曜日" scope="col" style="background-color:#F3B55B;font-weight:bold;height:1px;">土</th>
                            </tr>
                            <tr>
                                <td align="center" style="color:#CC9966;width:14%;">
                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8912')" style="color:#CC9966" title="5月26日">26</a>
                                </td>
                                <td align="center" style="color:#CC9966;width:14%;">
                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8913')" style="color:#CC9966" title="5月27日">27</a>
                                </td>
                                <td align="center" style="color:#CC9966;width:14%;">
                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8914')" style="color:#CC9966" title="5月28日">28</a>
                                </td>
                                <td align="center" style="color:#CC9966;width:14%;">
                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8915')" style="color:#CC9966" title="5月29日">29</a>
                                </td>
                                <td align="center" style="color:#CC9966;width:14%;">
                                    <a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8916')" style="color:#CC9966" title="5月30日">30</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8917')" style="color:#CC9966" title="5月31日">31</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8918')" style="color:#663399" title="6月1日">1</a></td></tr><tr><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8919')" style="color:#663399" title="6月2日">2</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8920')" style="color:#663399" title="6月3日">3</a></td><td align="center" style="background-color:Salmon;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8921')" style="color:#663399" title="6月4日">4</a></td><td align="center" style="background-color:Salmon;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8922')" style="color:#663399" title="6月5日">5</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8923')" style="color:#663399" title="6月6日">6</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8924')" style="color:#663399" title="6月7日">7</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8925')" style="color:#663399" title="6月8日">8</a></td></tr><tr><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8926')" style="color:#663399" title="6月9日">9</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8927')" style="color:#663399" title="6月10日">10</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8928')" style="color:#663399" title="6月11日">11</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8929')" style="color:#663399" title="6月12日">12</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8930')" style="color:#663399" title="6月13日">13</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8931')" style="color:#663399" title="6月14日">14</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8932')" style="color:#663399" title="6月15日">15</a></td></tr><tr><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8933')" style="color:#663399" title="6月16日">16</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8934')" style="color:#663399" title="6月17日">17</a></td><td align="center" style="background-color:Salmon;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8935')" style="color:#663399" title="6月18日">18</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8936')" style="color:#663399" title="6月19日">19</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8937')" style="color:#663399" title="6月20日">20</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8938')" style="color:#663399" title="6月21日">21</a></td><td align="center" style="color:Red;background-color:Salmon;font-weight:bold;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8939')" style="color:Red" title="6月22日">22</a></td></tr><tr><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8940')" style="color:#663399" title="6月23日">23</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8941')" style="color:#663399" title="6月24日">24</a></td><td align="center" style="color:White;background-color:#F3B55B;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8942')" style="color:White" title="6月25日">25</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8943')" style="color:#663399" title="6月26日">26</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8944')" style="color:#663399" title="6月27日">27</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8945')" style="color:#663399" title="6月28日">28</a></td><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8946')" style="color:#663399" title="6月29日">29</a></td></tr><tr><td align="center" style="width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8947')" style="color:#663399" title="6月30日">30</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8948')" style="color:#CC9966" title="7月1日">1</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8949')" style="color:#CC9966" title="7月2日">2</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8950')" style="color:#CC9966" title="7月3日">3</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8951')" style="color:#CC9966" title="7月4日">4</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8952')" style="color:#CC9966" title="7月5日">5</a></td><td align="center" style="color:#CC9966;width:14%;"><a href="javascript:__doPostBack('ctl00$ContentPageHeader$Calendar1','8953')" style="color:#CC9966" title="7月6日">6</a></td></tr>
                        </tbody>
                    </table> -->
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
                                        ¥<input name="retrieve" type="text" value="0" maxlength="6" id="return" style="width:70px;height:25px">
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