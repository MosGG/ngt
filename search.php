<script type="text/javascript">
function submitform()
  {
      document.forms["forget-search"].submit();
  }
</script>
<?php
include 'include/product.tpl.php';
if (!$_SESSION['membership'] && !$_SESSION['member']) {
  echo "<p class='notification-p'>Please <a class='hvr-underline-from-left-blue' href='".$site['url']['full'].$_SESSION['cache']['layout']['287']['pageUrl']."'>Login</a> to search product.</p>";
  echo "<p class='notification-p'>Don't have a login? <a class='hvr-underline-from-left-blue' href='".$site['url']['full']."become-a-member'>Register</a> now.</p>";
  exit();
}
if($_POST['query']!==NULL){
$query = $_POST['query'];
}elseif($_GET['query']!= NULL){
$query = $_GET['query'];
}

if($query == Null || ctype_space($query)){
  echo "<div style='width:592px;margin: 0 auto;'>";
  echo "<div id ='we_are_sorry'>We're sorry, no products were found for your search: ".$query."</div>";
  echo "<br>";
  echo "<hr>";
  echo "<br>";
  echo "<div id='search_tip_content'>Search Tips</div>";
  echo "<br>";
  echo "<div id='search_tip_content'><ul><li>Double check your spelling.</li><li>Be less specific in your wording or try a similar term.</li><li>Limit the search to one or two words.</li></ul></div>";
  echo "<br>";
  echo "<div id='bottom_stuff'>";
  echo "<hr>";
  echo "<div><div id = 'try_new_search' style='float:left'>Try a new search</div><div style='float:left'><form action='/searchresult' method='post' id ='forget-search'><input type='text' name='query' placeholder='Search...'/></form></div></div>";
  echo "<a id='box' href='javascript: submitform()'><img id='search-icon2' src='/images/new/search.png' type='submit'/></a>";
  echo "</br>";
  echo "</div>";
  echo "<hr style='position: relative; bottom: -110px;'>";
  echo "</div>";
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
      if ($res2['productCategory'] !== 'In Stock' || $res2['productStock'] > '0') {
      $result[]=$res2;
    }
    }
    // echo var_dump($result);
    $stmt->free_result();

    $stmt->close();

    array_pop($newarray);
  }
}
$prodList = $result;
// echo var_dump($prodList);
if($prodList == NULL){
  echo "<div style='width:592px;margin: 0 auto;'>";
  echo "<div id ='we_are_sorry'>We're sorry, no products were found for your search: ".$query."</div>";
  echo "<br>";
  echo "<hr>";
  echo "<br>";
  echo "<div id='search_tip_content'>Search Tips</div>";
  echo "<br>";
  echo "<div id='search_tip_content'><ul><li>Double check your spelling.</li><li>Be less specific in your wording or try a similar term.</li><li>Limit the search to one or two words.</li></ul></div>";
  echo "<br>";
  echo "<div id='bottom_stuff'>";
  echo "<hr>";
  echo "<div><div id = 'try_new_search' style='float:left'>Try a new search</div><div style='float:left'><form action='/searchresult' method='post' id ='forget-search'><input type='text' name='query' placeholder='Search...'/></form></div></div>";
  echo "<a id='box' href='javascript: submitform()'><img id='search-icon2' src='/images/new/search.png' type='submit'/></a>";
  echo "</br>";
  echo "</div>";
  echo "<hr style='position: relative; bottom: -110px;'>";
  echo "</div>";
}
else{

  // echo "result is".count($result);
  if (count($prodList) > 0) {
    //logic and html for slice page
    $itemsPerPage = 8;
    $offset = 0;
    $allPages = (int)Floor(count($prodList) / $itemsPerPage) + 1;
    $num = 1;

    if (!empty($_SERVER['QUERY_STRING'])){
      $query = substr($_SERVER['QUERY_STRING'], 0, 5);
      if ($query == 'page=') {
        $num = substr($_SERVER['QUERY_STRING'], 5);
        if ((int)($num) < 1){
          $num = 1;
        }
      }
    }
    $prod = array_slice($prodList, ($num - 1) * $itemsPerPage, $itemsPerPage);
    // echo var_dump($prod);

    echo "<div id='product'>";
    if ($allPages > 1) {
      echo "<div id='layPage' class='laypage'></div>";
    }
    //echo product list

    echo "<form action='".$site['url']['actual']."' method='post'>";
    foreach($prod as $line) {
      $image_array = array();
      $sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$line['productId']."' ORDER BY `productImageOrder`, `productImageId`, `productImageTitle`";
      $resultimage = sql_exec($sql);
      while ($image = $resultimage->fetch_assoc()) {
        $image_array[] = $image;
      }

      if ($site['database']['product-pdf']) {
        $pdf_array = array();
        $sql  = "SELECT * FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$line['productId']."' ORDER BY `productPdfOrder`, `productPdfDescription`";
        $resultpdf = sql_exec($sql);
        while ($pdf = $resultpdf->fetch_assoc()) {
          $pdf_array[] = $pdf;
        }
        product_template($line, $image_array, $pdf_array);
      }

      if (!$site['database']['product-pdf']) {
        product_template($line, $image_array);
      }

    } # (while-$line)
    echo "</form>";
    if ($allPages > 1) {
      echo "<div id='layPage2' class='laypage'></div>";
      ?>
      <script type="text/javascript" src="/include/laypage/laypage.js"></script>
      <script type="text/javascript">
        laypage({
            cont: 'layPage',
          skin: '#4A4A4A',
          groups: 5,
          prev: "<img src='/images/new/prev.png'>", //若不显示，设置false即可
            next: "<img src='/images/new/next.png'>",//若不显示，设置false即可
            first: '1',
          last: <?php echo $allPages; ?>,
          pages: <?php echo $allPages; ?>, //可以叫服务端把总页数放在某一个隐藏域，再获取。假设我们获取到的是18
          curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
            var page = location.search.match(/page=(\d+)/);
              return page ? page[1] : 1;
            }(),
          jump: function(e, first){ //触发分页后的回调
              if(!first){ //一定要加此判断，否则初始时会无限刷新
                location.href = '?page='+e.curr+'&query=box';
              }
          }
        });

        laypage({
            cont: 'layPage2',
          skin: '#4A4A4A',
          groups: 5,
          prev: "<img src='/images/new/prev.png'>", //若不显示，设置false即可
            next: "<img src='/images/new/next.png'>",//若不显示，设置false即可
            first: '1',
          last: <?php echo $allPages; ?>,
          pages: <?php echo $allPages; ?>, //可以叫服务端把总页数放在某一个隐藏域，再获取。假设我们获取到的是18
          curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
            var page = location.search.match(/page=(\d+)/);
              return page ? page[1] : 1;
            }(),
          jump: function(e, first){ //触发分页后的回调
              if(!first){ //一定要加此判断，否则初始时会无限刷新
                location.href = '?page='+e.curr+'&query=box';
              }
          }
        });
      </script>
      <?php
    }
    echo "</div> <!-- product -->";
  }
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
<!--<style type="text/css">
        @import "include/search.css";
</style>

<div id="main_container">
  <table style="width:100%">
    <?php
      // echo var_dump($result);
      // echo "<tr>";
      // for($i=1;$i<=count($result);$i++){
      //   echo "<td>";
      //   echo " <div class='product'>";
      //   echo " <div class='img'></div>";
      //   echo " <div>".$result[$i]['productPart']." (".$result[$i]['productStock']." AVAILABLE)</div>";
      //   echo " <div>$".$result[$i]['productPrice1']." ea</div>";
      //   echo " <div>MIN ORDER QTY: ".$result[$i]['productInner']."</div>";
      //   echo " <div>CARTON QTY: ".$result[$i]['productCarton']."</div>";
      //   echo "</td>";
      //
      //   if($i%4==0){
      //     echo "</tr>";
      //     echo "<tr>";
      //   }
      // }
      // echo "</tr>";

    ?>
  </table>
</div>
