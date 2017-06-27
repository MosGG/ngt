<?php
$query = "   box bag dsadsa dsadas ds box";
if($query == Null || ctype_space($query)){
  echo "plz enter actual character";
}
else{
$arquery = explode(" ",$query);
$newarray =array();
for ($i=0;$i<count($arquery);$i++){
  if($arquery[$i] == Null || ctype_space($arquery[$i])){
      Continue;
    }else{
      $newarray[]="%".$arquery[$i]."%";
    }
}
 //echo var_dump($newarray);//array only with string
// echo createsql($newarray,$site['database']['product']);

$result = array();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "newg_hosting";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

while($result == NULL){
  if(count($newarray)==0){
    break;
  }else{
    $sql =createsql($newarray,"`newg_product`");
    // echo $sql;
    $n = count($newarray);
    $param_type = str_repeat("s", $n);
    $tmp = array();
    $tmp []= &$param_type;
    for($i=0 ; $i<$n; $i++){
      $tmp[] = &$newarray[$i];
    }

    $stmt = $conn->prepare($sql);
    call_user_func_array(array($stmt, 'bind_param'), $tmp);
    $stmt->execute();
    $res = $stmt->get_result();
    while($res2 = $res->fetch_assoc()){
      $result[]=$res2;
    }
    // echo var_dump($result);
    $stmt->free_result();

    $stmt->close();

    array_pop($newarray);
  }
}

if($result == NULL){
  echo "no result";
}
else{
  echo var_dump($result);
  echo "result is".count($result);
}
}

function createsql($array,$database){
  $sql = "SELECT * FROM ".$database." WHERE ";
  $tmparray =array();
  for ($i=0;$i<count($array);$i++){
      array_push($tmparray,"`productTitle` LIKE ?") ;
  }
  $sql = $sql.implode(" and ", $tmparray);
  return $sql;
}


?>
<style type="text/css">
        @import "include/search.css";
</style>

<div id="main_container">
  <table style="width:100%">
    <?php
      // echo var_dump($result);
      echo "<tr>";
      for($i=1;$i<=count($result);$i++){
        echo "<td>";
        echo " <div class='product'>";
        echo " <div class='img'></div>";
        echo " <div>".$result[$i]['productPart']." (".$result[$i]['productStock']." AVAILABLE)</div>";
        echo " <div>$".$result[$i]['productPrice1']." ea</div>";
        echo " <div>MIN ORDER QTY: ".$result[$i]['productInner']."</div>";
        echo " <div>CARTON QTY: ".$result[$i]['productCarton']."</div>";
        echo "</td>";

        if($i%4==0){
          echo "</tr>";
          echo "<tr>";
        }
      }
      echo "</tr>";

    ?>
  </table>
</div>
