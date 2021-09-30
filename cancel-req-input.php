<?php session_start(); ?>
<?php require 'header.php'; ?>
<div id="cancel">
	<main>
	<h2 class="fs-20">キャンセル申請・確認画面</h2>
	<hr>

<?php
$pdo=new PDO('mysql:host=localhost;dbname=hoskybook;charset=utf8', 'staff', 'password');

// ログインの有無を確認する
if (!isset($_SESSION['customer'])) {
	echo 'キャンセル手続きを行うにはログインしてください。';
}

// キャンセルしたいアイテムの情報（DBの purchase_id に相当する $_REQUEST['cancel_item'] ）が送信されたか確認する
elseif (isset($_REQUEST['cancel_item'])) {

	//キャンセルしたいアイテムの情報を変数に代入
	$cancel_item = $_REQUEST['cancel_item'];

	//キャンセル申請の表示 ここから
	echo '<p class="cancel_txt">以下の商品のキャンセル申請で間違いありませんか？</p>';
	echo '<table>';
	echo '<thead><tr><th>商品画像</th><th>商品名</th><th>価格</th><th>個数</th><th>小計</th></tr></thead>';
	echo '<tbody>';

	// 商品詳細のDBを配列として取得し、該当する列を抽出する ここから
	$sql=$pdo->prepare('SELECT * FROM purchase_detail, product WHERE purchase_id=? AND product.id = purchase_detail.product_id');
	$sql->execute([$_REQUEST['cancel_item']]);
	$total=0;
	foreach ($sql as $row) {
		// 列のデータを取り出す。
		echo '<tr>';
		echo '<tr><td class="cancel_image"><p><img src="image/', $row['name'], '.jpg"></p></td>';//商品画像
		echo '<td>',$row['name'],'</td>';//商品名
		$price = number_format($row['price']);//関数で数字の表記を3桁のカンマ区切りに
		echo '<td>', $price, '<span class="fs-14">円</span></td>';//価格
		echo '<td>', $row['count'], '</td>';//個数
		$subtotal=$row['price']*$row['count'];
		$total+=$subtotal;
		$subtotal = number_format($subtotal);//関数で数字の表記を3桁のカンマ区切りに
		echo '<td>', $subtotal, '<span class="fs-14">円</span></td>';
		echo '</tr>';
	}
	// 商品詳細のDBを配列として取得し、該当する列を抽出する ここまで
	echo '<tr><th></th></tr>';

	echo '</tbody>';
	echo '</table>';

	echo '<table>';
	echo '<thead><tr><th>注文合計</th><th>注文時刻</th><th>注文状況</th></tr></thead>';
	echo '<tbody>';
	echo '<tr>';
	// $row['purchase_id'] 注文番号
	$total = number_format($total);//関数で数字の表記を3桁のカンマ区切りに
	echo '<td>',$total,'<span class="fs-14">円</span></td>';//合計
	echo '<td>',$row['day'],'</td>';// 購入日時
	echo '<td>';
	// 0：仮購入、1：キャンセル申請中、2：申請通る、3：申請通らない
	if ($row['status'] == 0){
		echo 'キャンセル申請前';
	} elseif ($row['status'] == 1) {
		echo 'キャンセル申請中';
	} elseif ($row['status'] == 2) {
		echo '申請通る';
	} elseif ($row['status'] == 3) {
		echo '申請通らない';
	}
	echo '</td></tr>';
	echo '</tbody>';
	echo '</table>';
	//キャンセル申請の表示 ここまで

	//キャンセル申請の情報を送信 ここから
	echo '<form action="cancel-req-output.php" method="post" class="cancel_btn">';
	echo '<input type="hidden" name="cancel_item" value="', $_REQUEST['cancel_item'], '">';
	echo '<input type="submit" value="キャンセル申請を確定" class="btn-3">';
	echo '</form><hr>';
	//キャンセル申請の情報を送信 ここまで
} else {
	echo '<p>キャンセルしたい注文の情報がありません。</p>';
}

?>
<p class="cancel_btn"><a href="history.php" class="btn-sub-3">購入履歴に戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>
