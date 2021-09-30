<?php session_start(); ?>
<?php require 'header.php'; ?>
<div>
<main>
<?php
if ($_SESSION['customer']['id'] == 1) {
	echo 'お疲れ様です。管理者様。';
	require 'adminpage.php';
} else {
	 header("location:index.php");//TOPに戻る
	}
?>
</main>
</div>
<?php require 'footer.php'; ?>
