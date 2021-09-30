<?php session_start(); ?>
<?php require 'header.php'; ?>
<div id="editadmin">
<main>
<h2 class="fs-22">ユーザーの追加・変更</h2>
<hr>

<?php
$pdo=new PDO('mysql:host=localhost;dbname=hoskybook;charset=utf8', 
	'staff', 'password');

	if($_SESSION['customer']['id'] != 1){//管理者ではない場合
	 header("location:index.php");}//TOPに戻る	

if (isset($_REQUEST['command'])) {
	switch ($_REQUEST['command']) {
	case 'insert':
		
		//productテーブルへの追加処理
$sql=$pdo->prepare('insert into customer values(null,?,?,?,?,?)');
	
		$sql->execute(
			[htmlspecialchars($_REQUEST['name']), $_REQUEST['address'], $_REQUEST['login'], $_REQUEST['password'], $_REQUEST['mail']]);
	break;

	case 'update':
			//productテーブルへの更新処理
$sql=$pdo->prepare('update customer set name=?, address=?, login=?, password=?, mail=? where id=?');
		$sql->execute(
			[htmlspecialchars($_REQUEST['name']), $_REQUEST['address'], $_REQUEST['login'], $_REQUEST['password'], $_REQUEST['mail'],$_REQUEST['id']]);
		break;
		
	case 'delete':

		$sql=$pdo->prepare('delete from favorite where customer_id=? ');
		$sql->execute([$_REQUEST['id']]);

		$pdo->query('SET foreign_key_checks = 0');
		$sql=$pdo->prepare('delete from customer where id=? ');
		$sql->execute([$_REQUEST['id']]);
		$pdo->query('SET foreign_key_checks = 1');
		break;
	
}}
//ここから画面表示用の記述
foreach ($pdo->query('select * from customer') as $row) {
		echo '<table><tr>
	<th class="th0">顧客番号</th>
	<th class="th1">顧客名</th>
	<th class="th1">住所</th>
	<th class="th1">ログイン名</th>
	<th class="th1">パスワード</th>
	<th class="th1">メールアドレス</th>
	</tr><tr>';
	//動作処理後にこのページに戻ってくるように設定
	echo '<form class="ib" action="customer-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="update">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<th class="th0">';
	echo $row['id'];
	echo '</th> ';
	echo '<th class="th1">';
	echo '<input type="text" name="name" value="', $row['name'], '">';
	echo '</th> ';
	echo '<th class="th1">';
	echo '<input type="text" name="address" value="', $row['address'], '">';
	echo '</th> ';
	echo '<th class="th1">';
	echo '<input type="text" name="login" value="', $row['login'], '">';
	echo '</th> ';
	echo '<th class="th1">';
	echo '<input type="text" name="password" value="', $row['password'], '">';
	echo '</th> ';
	echo '<th class="th1">';
	echo '<input type="text" name="mail" value="', $row['mail'], '">';
	echo '</th> ';

	echo '</tr></table>';
	//更新ボタン
	echo '<p class="ta-r"><input type="submit" value="更新" class="btn-2"></p>';
	echo '</form> ';

	echo '<form class="ib" action="customer-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="delete">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<p class="ta-r"><input type="submit" value="削除" class="btn-sub-2"></p>';
	echo '</form><hr>';
	echo "\n";
}
?>

<!-- ここから新規登録の画面表示用の記述 -->

<table><tr>
	<th class="th0">顧客番号</th>
	<th class="th1">顧客名</th>
	<th class="th1">住所</th>
	<th class="th1">ログイン名</th>
	<th class="th1">パスワード</th>
	<th class="th1">メールアドレス</th>
	</tr><tr>
<form action="customer-editadmin.php" method="post">
<input type="hidden" name="command" value="insert">
<th class="th0"></th>
<th class="th1"><input type="name" name="name"></th>
<th class="th1"><input type="text" name="address"></th>
<th class="th1"><input type="text" name="login"></th>
<th class="th1"><input type="text" name="password"></th>
<th class="th1"><input type="text" name="mail"></th>

</tr><tr>
<th></th>
<th class="th2"><input type="submit" value="追加"></th><br>
</form>
</tr></table>
<p class="cancel_btn"><a href="adminpage-show.php" class="btn-sub-3">管理者メニューに戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>

