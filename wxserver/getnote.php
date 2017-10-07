<?php
header("Content-type:text/html;charset=utf-8");
$conn = mysqli_connect("localhost","root","remainconfident","notes");
$str = "select * from usernotes where id='36'";
$query = mysqli_query($conn,$str);
$result = mysqli_fetch_array($query);
echo htmlspecialchars_decode($result['content']);
?>
