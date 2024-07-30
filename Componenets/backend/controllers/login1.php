<?php
require_once('dbconfig.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = "SELECT * FROM userlogin WHERE username='$username' and password = '$password'";
  $result = mysqli_query($conn, $sql);
  if($result){
  $row = mysqli_num_rows($result);
  
  if($row >0){
header("location: index.php");
  }else{
    echo "Invalid login credentials";
    header("refresh:1;url=login.html");
  }
}

}
?>