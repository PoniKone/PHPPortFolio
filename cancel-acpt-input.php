<?php require 'header.php'; ?>
<div>
<main>
<h2 class="fs-22">キャンセル申請受諾・拒否</h2>
<hr>
<br>

<?php
$pdo=new PDO('mysql:host=localhost;dbname=hoskybook;charset=utf8', 'staff', 'password');


// キャンセルしたいアイテムの情報（DBの purchase_id に相当する $_REQUEST['cancel_item'] ）が送信されたか確認する
if (isset($_REQUEST['cancel_item'])) {

	//キャンセルしたいアイテムの情報を変数に代入
	$cancel_item = $_REQUEST['cancel_item'];

	//キャンセル申請の表示 ここから
	echo '<p>以下の商品のキャンセル申請を許可しますか。</p>';

	// 確認用。削除予定の記述 ここから
	// 商品詳細のDBを配列として取得し、1行ずつ処理する ここから
	// foreach ($pdo->query('SELECT * FROM purchase_detail') as $row) {
	// 	// 列のデータを取り出す。
	// 	echo '<tr><td>';
	// 	echo $row['purchase_id'];// 購入番号
	// 	echo '</td><td>';
	// 	echo $row['product_id'];// 購入アイテム
	// 	echo '</td><td>';
	// 	echo $row['count'];// 購入数
	// 	echo '</td><td>';
	// 	echo $row['day'];// 購入日時
	// 	echo '</td><td>';
	// 	echo '（仮：注文状況）';
	// 	echo '</td></tr>';
	// 	// 「商品詳細のDBの product_id 」と「キャンセルしたいアイテムの情報 $cancel_item」が合致するなら
	// 	if ($row['product_id'] == $cancel_item){
	// 	}
	// }
	// 商品詳細のDBを配列として取得し、1行ずつ処理する ここまで
	// 確認用。削除予定の記述 ここまで

	// 実装予定の記述 ここから
	// 商品詳細のDBを配列として取得し、該当する列を抽出する ここから



$sql=$pdo->prepare('SELECT * from purchase_detail,product where purchase_id=? and product.id = purchase_detail.product_id');	
	$sql->execute([$_REQUEST['cancel_item']]);
	
	//処理する商品を表示


	echo '<br>';

	echo '<table>';
	echo '<tr><th></th>';
	echo '<th>','商品名','</th>';
	echo '<th>','冊数','</th>';
	echo '<th>','購入日時','</th></tr>';

foreach ($sql as $row) {
	echo '<tr><td><p><img src="image/', $row['name'], '.jpg"></p></td>';
	echo '<td>',$row['name'],'</td>';
	echo '<td>',$row['count'],'</td>'; 
	echo '<td>',$row['day'],'</td></tr>';

	echo '</table>';

	echo '<br>';
}



	// 商品詳細のDBを配列として取得し、該当する列を抽出する ここまで
	// 実装予定の記述 ここまで

	echo '</table>';
	//キャンセル申請の表示 ここまで
	echo '<form action="cancel-acpt-output.php" method="post" name="yes">';
	echo '<p><input type="submit" name="yes" value="キャンセル申請を受諾" class="btn-3"></p>';
	echo '<input type="hidden" name="cancel_item" value="', $_REQUEST['cancel_item'], '">';
	echo '</form>';

	echo '<form action="cancel-acpt-output.php" method="post" name="no">';
	echo '<p><input type="submit" name="no" value="キャンセル申請を拒否" class="btn-3"></p>';
	echo '<input type="hidden" name="cancel_item" value="', $_REQUEST['cancel_item'], '">';
	echo '</form>';

	//キャンセル申請の情報を送信 ここまで
} else {
	echo 'キャンセルしたい注文の情報がありません。';
}




?>
<p class="cancel_btn"><a href="adminpage.php" class="btn-sub-3">管理者メニューに戻る</a></p>
<p class="cancel_btn"><a href="purchase-editadmin.php" class="btn-sub-3">購入履歴DBに戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>
