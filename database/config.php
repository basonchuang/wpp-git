<?php
  include_once "database.php";
  header("Content-Type:text/html; charset=utf-8");
  if(isset($_GET["method"])){
    switch ($_GET["method"]) {
      case "display":
        if(isset($_GET["class"])){
          $class=$_GET["class"];
          display($class);
          break;
        }
      case "spot":
        if(isset($_GET["name"])){
          $name=$_GET["name"];
          spot($name);
          break;
        }
      case "search":
        if(isset($_GET["keyword"])){
          search($_GET["keyword"]);
          break;
        }
    }
  }
  function pass($target){
    echo json_encode($target);
  }
  function display($class){
    $sql = "SELECT `name` FROM `spots` WHERE class='$class'";
    global $con;
    $result = mysqli_query($con,$sql);
    $return = array();
    $count = 0;

    while($x=mysqli_fetch_array($result)){
      $return[$count] = $x['name'];
      $count++;
    }
    echo json_encode($return);
  }
  function spot($name){
    $sql = "SELECT * FROM `spots` WHERE name='$name'";
    global $con;
    $result = mysqli_query($con,$sql);

    while($x=mysqli_fetch_array($result)){
      $class = $x['class'];
      $address = $x['address'];
      $phone = $x['phone'];
      $intro = $x['intro'];
    }
    $return = array($name,$class,$address,$phone,$intro);
    echo json_encode($return);
  }
  function search($keyword){
    $sql = "SELECT `name` FROM `spots` WHERE `name` LIKE '%$keyword%' OR `class` LIKE '%$keyword%' OR `address` LIKE '%$keyword%' OR `intro` LIKE '%$keyword%'";
    global $con;
    $result = mysqli_query($con,$sql);
    $return = array();
    $count = 0;

    while($x=mysqli_fetch_array($result)){
      $return[$count] = $x['name'];
      $count++;
    }
    if(!$return){
      $return = "0";
    }
    echo json_encode($return);
  }
?>