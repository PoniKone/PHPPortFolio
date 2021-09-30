<?php session_start(); ?>
<?php require 'header.php'; ?>
<div id="editadmin">
<main>
<h2 class="fs-22">商品の追加・変更</h2>
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
		if (empty($_REQUEST['name']) || 
			!preg_match('/[0-9]+/', $_REQUEST['price'])) break;
		$sql=$pdo->prepare('insert into product values(null,?,?,?,?,?,?,?,?)');
		$sql->execute(
			[htmlspecialchars($_REQUEST['name']), $_REQUEST['price'], $_REQUEST['intro'], $_REQUEST['genre1'], $_REQUEST['genre2'], $_REQUEST['genre3'], $_REQUEST['genre4'], $_REQUEST['author']]);
		echo '<p>商品を追加しました。</p>';
		break;
	case 'update':
			//productテーブルへの更新処理
		if (empty($_REQUEST['name']) || 
			!preg_match('/[0-9]+/', $_REQUEST['price'])) break;
		$sql=$pdo->prepare(
			'update product set name=?, price=?, author=?, genre1=?, genre2=?, genre3=?, genre4=?, intro=? where id=?');
		$sql->execute([
			htmlspecialchars($_REQUEST['name']), $_REQUEST['price'], $_REQUEST['author'], $_REQUEST['genre1'], $_REQUEST['genre2'], $_REQUEST['genre3'], $_REQUEST['genre4'], $_REQUEST['intro'], $_REQUEST['id']
		]);
		echo '<p>データを更新しました。</p>';
		break;
	case 'delete':
		$sql=$pdo->prepare('delete from product where id=?');
		$sql->execute([$_REQUEST['id']]);
		echo '<p>データを削除しました。</p>';
		break;
	}
}
//ここから画面表示用の記述
foreach ($pdo->query('select * from product') as $row) {
	//動作処理後にこのページに戻ってくるように設定
	echo '<form class="ib" action="product-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="update">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<table>';
	echo '<tr>
	<th class="th0">商品番号</th>
	<th class="th1" colspan="2">商品名</th>
	<th class="th1">商品価格</th>
	<th class="th1">著者</th>';
	echo '</tr><tr>';
	//id番号
	echo '<td class="td0" rowspan="6">';
	echo $row['id'];
	echo '</td> ';
	//商品名
	echo '<td class="td" colspan="2">';
	echo '<input type="text" name="name" value="', $row['name'], '">';
	echo '</td> ';
	//価格
	echo '<td class="td">';
	echo '<input type="text" name="price" value="', $row['price'], '">';
	echo '</td> ';
	//著者
	echo '<td class="td">';
	echo '<input type="text" name="author" value="', $row['author'], '">';
	echo '</td>';
	echo '</tr><tr>';
	echo '<th class="th1">ジャンル1</th><th class="th1">ジャンル2</th><th class="th1">ジャンル3</th><th class="th1">ジャンル4</th></tr><tr>';
	//ジャンル1
	echo '<td class="td">';
	echo '<input type="text" name="genre1" value="', $row['genre1'], '"><p class="fs-14">0:単行本 1:全巻セット</p>';
	echo '</td> ';
	//ジャンル2
	echo '<td class="td">';
	echo '<input type="text" name="genre2" value="', $row['genre2'], '"><p class="fs-14">0:少年 1:青年 2:少女 3:女性</p>';
	echo '</td> ';
	//ジャンル3
	echo '<td class="td">';
	echo '<input type="text" name="genre3" value="', $row['genre3'], '"><p class="fs-14">0:アクション 1:ミステリー<br>2:恋愛 3:ギャグ</p>';
	echo '</td> ';
	//ジャンル4
	echo '<td class="td">';
	echo '<input type="text" name="genre4" value="', $row['genre4'], '"><p class="fs-14">0:講談社 1:集英社<br>2:KADOKAWA 3:小学館</p>';
	echo '</td></tr><tr>';
	//あらすじ
	echo '<th class="th1" colspan="4">あらすじ</th></tr><tr>';
	echo '<td class="intro" colspan="4">';
	echo '<textarea  name="intro" rows="4">', $row['intro'], '</textarea>';
	echo '</td> ';
	echo '</tr></table>';
	//更新ボタン
	echo '<p class="ta-r"><input type="submit" value="更新" class="btn-2"></p>';
	echo '</form> ';
	//削除ボタン、動作処理後にこのページに戻ってくるように設定
	echo '<form class="ib" action="product-editadmin.php" method="post">';
	echo '<input type="hidden" name="command" value="delete">';
	echo '<input type="hidden" name="id" value="', $row['id'], '">';
	echo '<p class="ta-r"><input type="submit" value="削除" class="btn-sub-2"></p>';
	echo '</form><hr>';
	echo "\n";
}
?>
<!-- ここから新規登録の画面表示用の記述 -->
<h2 class="fs-20">新規登録</h2>
<form action="product-editadmin.php" method="post">
<table>
	<tr>
		<th class="th0">商品番号</th>
		<th class="th1" colspan="2">商品名</th>
		<th class="th1">商品価格</th>
		<th class="th1">著者</th>
	</tr>
	<tr>
		<td class="td0" rowspan="6"></td>
		<td class="td" colspan="2"><input type="text" name="name"></td>
		<td class="td"><input type="text" name="price"></td>
		<td class="td"><input type="text" name="author"></td>
	</tr>
	<tr>
		<th class="th1">ジャンル1</th>
		<th class="th1">ジャンル2</th>
		<th class="th1">ジャンル3</th>
		<th class="th1">ジャンル4</th>
	</tr>
	<tr>
		<td class="td"><input type="text" name="genre1"><p class="fs-14">0:単行本 1:全巻セット</p></td>
		<td class="td"><input type="text" name="genre2"><p class="fs-14">0:少年 1:青年 2:少女 3:女性</p></td>
		<td class="td"><input type="text" name="genre3"><p class="fs-14">0:アクション 1:ミステリー<br>2:恋愛 3:ギャグ</p></td>
		<td class="td"><input type="text" name="genre4"><p class="fs-14">0:講談社 1:集英社<br>2:KADOKAWA 3:小学館</p></td>
	</tr>
	<tr>
		<th class="th1" colspan="4">あらすじ</th>
	</tr>
	<tr>
		<td class="intro" colspan="4"><textarea  name="intro" rows="4"></textarea></td>
	</tr>
</table>
<input type="hidden" name="command" value="insert">
<p class="ta-r"><input type="submit" value="追加" class="btn-2"></p>
</form>
<hr>
<p class="ta-r"><a href="adminpage-show.php" class="btn-sub-2">管理者メニューに戻る</a></p>
</main>
</div>
<?php require 'footer.php'; ?>
