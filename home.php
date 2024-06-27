<?php
require "connect.php"; // データベース接続の設定ファイル

// 直近の過去8日間の profit を取得する SQL
$sql = "SELECT period, day, profit 
        FROM dairy_results 
        ORDER BY STR_TO_DATE(CONCAT(period, '-', day), '%Y-%m-%d')
        ASC LIMIT 8";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 収支合計の初期化
$totalProfit = 0;

// 勝敗数を初期化
$playCnt = 0;
$winCnt = 0;
$loseCnt = 0;
$drawCnt = 0;

// profit の配列を準備
$dailyProfitData = [];

foreach ($results as $result) {
    // 収支データを配列に追加する
    $dailyProfitData[] = (int)$result['profit'];
    // 勝敗数をカウント
    if ($result['profit'] > 0) {
        $winCnt++;
    } elseif ($result['profit'] < 0) {
        $loseCnt++;
    } else {
        $drawCnt++;
    }
    $playCnt++;
    // 収支合計を加算
    $totalProfit += $result['profit'];
}
echo "<pre>";
print_r($dailyProfitData);
echo "</pre>";

// PHPからJavaScriptにデータを渡す
echo '<script>';
echo 'var dailyProfitData = ' . json_encode($dailyProfitData) . ';'; // JSON形式でデータをJavaScriptに渡す

echo 'var playCnt = ' . $playCnt . ';';
echo 'var winCnt = ' . $winCnt . ';';
echo 'var loseCnt = ' . $loseCnt . ';';
echo 'var drawCnt = ' . $drawCnt . ';';
echo '</script>';


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>収支管理</title>
    <link rel="stylesheet" href="base.css"> <!-- CSSファイルを読み込む -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.jsをインクルード -->
</head>
<body>
    <h1>収支管理</h1>
    <form method="post">
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
            <div class="wrapper">
                <div class="result" style="font-weight: bold;">
                    <span style="padding-left: 20px;">直近の収支</span>
                    <div style="text-align: left;">
                        <table style="width:450px;">
                            <tbody>
                                <tr>
                                    <th>
                                        <div style="font-weight: bold; text-align: left; padding-left: 20px;">
                                            <span id="playCnt"><?php echo $playCnt; ?></span>戦
                                            <span id="winCnt" style="color:Blue;"><?php echo $winCnt; ?></span>勝
                                            <span id="loseCnt" style="color:Red;"><?php echo $loseCnt; ?></span>負
                                            <span id="drawCnt" style="color:Green;"><?php echo $drawCnt; ?></span>分
                                        </div>
                                    </th>
                                    <th style="width: 40px;">収支</th>
                                    <td style="width: 95px;">¥
                                        <span id="profit" class="txtcolorRed" style="font-weight:bold;"><?php echo number_format($totalProfit); ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- グラフを表示するためのcanvasタグ -->
                    <canvas id="myChart" style="height:300px;width:800px; padding-left: 20px;"></canvas>
                </div>
            </div>
    </form>

    <script>
        // 収支データを取得
        var playCnt = document.getElementById('playCnt').innerText;
        var winCnt = document.getElementById('winCnt').innerText;
        var loseCnt = document.getElementById('loseCnt').innerText;
        var drawCnt = document.getElementById('drawCnt').innerText;
        var profit = document.getElementById('profit').innerText.replace('¥', '').replace(',', ''); // ¥を除去し、カンマを削除

        // 収支データ (日ごと)
        var dailyProfitData = <?php echo json_encode($dailyProfitData); ?>

        // 累積収支データを計算する関数
        function calculateCumulativeProfit(data) {
            var cumulativeData = [];
            var sum = 0;
            for (var i=0; i<data.length; i++) {
                sum += data[i];
                cumulativeData.push(sum);
            }
            return cumulativeData;
        }

        // 日ごとの収支データから累積収支データを生成
        var cumulativeProfitData = calculateCumulativeProfit(dailyProfitData);

        // Chart.jsの設定
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line', // 折れ線
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8'], // X軸のラベル
                datasets: [{
                    label: '累計収支', // グラフのラベル
                    data: cumulativeProfitData, // 日ごとの収支データ
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false, // 塗りつぶしを無効
                    tension: 0.1 // 線の滑らかさ
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Y軸のスケールをゼロから始める
                    }
                }
            }
        });
    </script>

</body>
</html>