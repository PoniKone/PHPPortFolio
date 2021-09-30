<?php session_start(); ?>
<?php require 'header.php'; ?>
<div>
<main>
<h2 class="fs-22">キャンセル結果</h2>
<hr>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=hoskybook;charset=utf8', 
	'staff', 'password');
// inputで受諾が送信された場合
if(isset($_REQUEST['yes'])){
//キャンセルが受諾された場合
	
	// parchaseテーブルのstatusを2に変更

		
$sql=$pdo->prepare('UPDATE purchase_detail set status=2 where purchase_id=?');
	$sql->execute([$_REQUEST['cancel_item']]);

	echo '<p>以下のキャンセルを受諾しました。</p>';

$sql=$pdo->prepare('SELECT * from purchase_detail,product where purchase_id=? and product.id = purchase_detail.product_id');	
	$sql->execute([$_REQUEST['cancel_item']]);



	//キャンセル処理をしたものを表示


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

}}
 else if(isset($_REQUEST['no'])){


	// キャンセル受諾されなかった場合
		// parchaseテーブルのstatusを3に変更


$sql=$pdo->prepare('update purchase_detail set status=3 where purchase_id=?');
	$sql->execute([$_REQUEST['cancel_item']]);

	echo '<p>以下のキャンセルが拒否しました。</p>';
$sql=$pdo->prepare('select * from purchase_detail,product where purchase_id=? and product.id = purchase_detail.product_id');	
	$sql->execute([$_REQUEST['cancel_item']]);



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
}
?>
<p class="cancel_btn"><a href="adminpage.php" class="btn-sub-3">管理者メニューに戻る</a></p>
<p class="cancel_btn"><a href="purchase-editadmin.php" class="btn-sub-3">購入履歴DBに戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>
