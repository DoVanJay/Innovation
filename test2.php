<?php
require './possess/PasswordHash.php';
require('./possess/mysql.php');
header('Content-type: text/plain');


$userID = "1507020326";
$passwd = "dwj";
$sql_tch = "select passwd from tch where tchID='$userID';";
$tch_result = mysqli_query($con, $sql_tch);
$tch_correct_hash = mysqli_fetch_array($tch_result);


$ok = 0;

$t_hasher = new PasswordHash(8, FALSE);

$check=$t_hasher->CheckPassword($passwd , "$tch_correct_hash[0]");

echo $t_hasher->HashPassword("dwj");