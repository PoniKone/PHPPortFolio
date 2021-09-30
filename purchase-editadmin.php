<?php session_start(); ?>
<?php require 'header.php'; ?>
<div id="editadmin">
<main>
<h2 class="fs-22">購入履歴データベース</h2>
<hr>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=hoskybook;charset=utf8', 
	'staff', 'password');

	if($_SESSION['customer']['id'] != 1){//管理者ではない場合
	 header("location:index.php");}//TOPに戻る	

if (isset($_REQUEST['command'])) {
	switch ($_REQUEST['command']) {
	// ここで追加できるのはやばいと思ったので隠しておきます(データ追加)
	// case 'insert':
	// //purchase及びpurchase_detailにデータ追加
	// 	$sql=$pdo->prepare('insert into purchase values(null,?)');
	// 	$sql->execute(
	// 		[$_REQUEST['customer_id']]);

	// 	$sql=$pdo->prepare('insert into purchase_detail values(?,?,?,?,?)');
	// 	$sql->execute(
	// 		[$_REQUEST['purchase_id'], $_REQUEST['product_id'], $_REQUEST['count'], $_REQUEST['day'], $_REQUEST['']]);
	// echo '<p>新規データを追加しました。</p>';
		// break;
	case 'update':
	//purchaseにデータ更新
		$sql=$pdo->prepare(
			'update purchase set customer_id=? where id=?');
		$sql->execute(
			[$_REQUEST['customer_id'], $_REQUEST['id']]);
	//purchase_detailにデータ更新
		$sql=$pdo->prepare(
			'update purchase_detail set product_id=?,count=?,day=? where purchase_id=?');
		$sql->execute(
			[$_REQUEST['product_id'], $_REQUEST['count'], $_REQUEST['day'], $_REQUEST['purchase_id']]);
	echo '<p>データを更新しました。</p>';

		break;
	case 'delete':

//purchase_detailテーブルから削除
		$pdo->query('SET foreign_key_checks = 0');
		$sql=$pdo->prepare('delete from purchase_detail where purchase_id=?');
		$sql->execute([$_REQUEST['purchase_id']]);
		
//purchaseテーブルから削除

		$sql=$pdo->prepare('delete from purchase where id=?');
		$sql->execute([$_REQUEST['id']]);
		$pdo->query('SET foreign_key_checks = 1');

	echo '<p>データを削除しました。</p>';

		break;
	}
}
//ここから画面表示用の記述
foreach ($pdo->query('select * from purchase,purchase_detail where purchase_id=id') as $row) {
	echo '<form class="ib" action="purchase-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="update">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<input type="hidden" name="purchase_id" value="', $row['purchase_id'], '">';
	echo '<table><tr><tr>
<th class="th0">購入ID</th>
<th class="th1">カスタマーID</th>
<th class="th1">商品ステータス</th>
<th class="th1">商品ID</th>
<th class="th1">個数</th>
<th class="th1">購入日時</th>
</tr>';
	echo '<td class="td0" rowspan="2">';
	echo $row['id'];
	echo '</td> ';
	echo '<td class="td">';
	echo '<input type="text" name="customer_id" value="', $row['customer_id'], '">';
	echo '</td> ';
	echo '<td class="td">';
	echo '<input readonly type="text" name="status" value="', $row['status'], '"><p class="fs-14">0:購入 1:申請 2:許可 3:拒否</p>';
	echo '</td> ';
	echo '<td class="td">';
	echo '<input type="text" name="product_id" value="', $row['product_id'], '">';
	echo '</td> ';
	echo '<td class="td">';
	echo '<input type="text" name="count" value="', $row['count'], '">';
	echo '</td> ';
	echo '<td class="td">';
	echo '<input type="text" name="day" value="', $row['day'], '">';
	echo '</td> ';

	echo '</tr></table>';
	echo '<p class="ta-r"><input type="submit" value="更新" class="btn-2"></p>';
	echo '</form> ';
	echo '<form class="ib" action="purchase-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="delete">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<input type="hidden" name="purchase_id" value="', $row['purchase_id'], '">';
	echo '<p class="ta-r"><input type="submit" value="削除" class="btn-sub-2"></p>';
	echo '</form>';
	//ステータス1～3の場合表示
	if ($row['status'] != 0&&4 ){
	echo '<form action="cancel-acpt-input.php" method="post">';
	echo '<input type="hidden" name="cancel_item" value="', $row['id'], '">';
	echo '<p class="ta-r"><input type="submit" value="キャンセル受諾画面へ" class="btn-2"></p>';
	echo '</form>';
		}
	echo "\n";
	echo '<hr>';
}
?>
<!-- ここから新規登録の画面表示用の記述 だが隠す -->

<!-- <table><tr>
<th class="th0">購入ID</th>
<th class="th1">カスタマーID</th>
<th class="th1">商品ステータス</th>
<th class="th1">商品ID</th>
<th class="th1">個数</th>
<th class="th1">購入日時</th>
</tr>
<tr>
<form action="purchase-editadmin.php" method="post">
<input type="hidden" name="command" value="insert">
<th class="td0">〇</th>
<th class="td1"><input type="text" name="customer_id"></th>
<th class="td1"><input type="text" name="status"></th>
<th class="td1"><input type="text" name="product_id"></th>
<th class="td1"><input type="text" name="count"></th>
<th class="td1"><input type="text" name="day"></th>
<th class="td2"><input type="submit" value="追加"></th><br>
</form>
</tr></table> -->
<p class="cancel_btn"><a href="adminpage-show.php" class="btn-sub-3">管理者メニューに戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>
