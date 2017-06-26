<?php
$query = "  6/      ";
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
      $newarray[]=$arquery[$i];
    }
}
echo var_dump($newarray);//array only with string


// echo createsql($newarray,$site['database']['product']);

$result = array();
while($result == NULL){
  if(count($newarray)==0){
    break;
  }else{
    $sql =createsql($newarray,$site['database']['product']);
    $resultid = sql_exec($sql);
    $result =array();
      while($rid = mysqli_fetch_assoc($resultid)){
      $result[]=$rid;
      }
      array_pop($newarray);
  }
}

if($result == NULL){
  echo "no result";
}
else{
  echo "result is".count($result);
}
}

function createsql($array,$database){
  $sql = "SELECT * FROM".$database." where ";
  for ($i=0;$i<count($array);$i++){
      $array[$i] = "productTitle LIKE '%".$array[$i]."%'";
  }
  $sql = $sql.implode(" and ", $array);
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
