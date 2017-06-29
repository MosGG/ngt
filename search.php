<script type="text/javascript">
  function submitform()
  {
    document.forms["forget-search"].submit();
  }

  function openproduct(productid, imageid, productTitle){ //Product Window
    productwin=dhtmlwindow.open('productwin', 'ajax', '<?php echo $site['url']['full']."product_display.php?id="; ?>'+productid+'&image='+imageid, productTitle,'width=auto,height=auto,resize=1,scrolling=1');
    putMiddle();
  }

  function putMiddle(){
    var windowWidth = 822;
    var screenWidth = document.body.clientWidth;
    console.log(windowWidth + "/" + screenWidth);
    var left = 0.5 * (screenWidth - windowWidth);
    var windowHeight = 562;
    var screenHeight = document.body.clientHeight;
    var top = 0.5 * (screenHeight - windowHeight);
    console.log(windowHeight + "/" + screenHeight);
    document.getElementById("productwin").style.top = top + "px";
    document.getElementById("productwin").style.left = left + "px";
  };
</script>
<?php
include 'include/product.tpl.php';
if (!$_SESSION['membership'] && !$_SESSION['member']) {
  echo "<p class='notification-p'>Please <a class='hvr-underline-from-left-blue' href='".$site['url']['full'].$_SESSION['cache']['layout']['287']['pageUrl']."'>Login</a> to search product.</p>";
  echo "<p class='notification-p'>Don't have a login? <a class='hvr-underline-from-left-blue' href='".$site['url']['full']."become-a-member'>Register</a> now.</p>";
  exit();
}
if($_POST['query']!==NULL){
  $queryStr = $_POST['query'];
}elseif($_GET['query']!= NULL){
  $queryStr = $_GET['query'];
}
if($queryStr == Null || ctype_space($queryStr)){
  noresult($queryStr);
}
else{
  $arquery = explode(" ",$queryStr);
  $newarray =array();
  for ($i=0;$i<count($arquery);$i++){
    if($arquery[$i] == Null || ctype_space($arquery[$i])){
      Continue;
    }else{
      $newarray[]="%".$arquery[$i]."%";
    }
  }

  $result = array();
  global $db;
  while(!empty($newarray)){
    $searchColumn = array("productTitle","productPart","productDescription");
    foreach($searchColumn as $column){
      $sql =createsql($newarray,"`newg_product`", $column);
      $n = count($newarray);
      $param_type = str_repeat("s", $n);
      $tmp = array();
      $tmp []= &$param_type;
      for($i=0 ; $i<$n; $i++){
        $tmp[] = &$newarray[$i];
      }
      $stmt = $db->prepare($sql);
      call_user_func_array(array($stmt, 'bind_param'), $tmp);
      $stmt->execute();
      $res = $stmt->get_result();
      while($res2 = $res->fetch_assoc()){
        if (($res2['productCategory'] !== 'In Stock' || $res2['productStock'] > '0') && $res2['productFlag'] == '') {
          $result[]=$res2;
        }
      }
      $stmt->free_result();
      $stmt->close();
    }
    array_pop($newarray);
  }
  $resultUnique = array();
  foreach ($result as $rlt){
    $flag = false;
    foreach ($resultUnique as $unique){
      if ($unique['productId'] == $rlt['productId']){
        $flag = true;
        break;
      }
    }
    if (!$flag) {
      $resultUnique[] = $rlt;
    }
  }
  $prodList = $resultUnique;
  if($prodList == NULL){
    noresult($queryStr);
  }
  else{
    if (count($prodList) > 0) {
      $itemsPerPage = 8;
      $offset = 0;
      $allPages = (count($prodList)%$itemsPerPage == 0)?(int)Floor(count($prodList) / $itemsPerPage):(int)Floor(count($prodList) / $itemsPerPage) + 1;
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
          prev: "<img src='/images/new/prev.png'>", 
            next: "<img src='/images/new/next.png'>",
            first: '1',
            last: <?php echo $allPages; ?>,
          pages: <?php echo $allPages; ?>, 
          curr: function(){ 
            var page = location.search.match(/page=(\d+)/);
            return page ? page[1] : 1;
          }(),
          jump: function(e, first){ 
              if(!first){ 
                location.href = '?page='+e.curr+'&query=<?php echo $queryStr;?>';
              }
            }
          });

        laypage({
          cont: 'layPage2',
          skin: '#4A4A4A',
          groups: 5,
          prev: "<img src='/images/new/prev.png'>", 
            next: "<img src='/images/new/next.png'>",
            first: '1',
            last: <?php echo $allPages; ?>,
          pages: <?php echo $allPages; ?>, 
          curr: function(){ 
            var page = location.search.match(/page=(\d+)/);
            return page ? page[1] : 1;
          }(),
          jump: function(e, first){ 
              if(!first){ 
                location.href = '?page='+e.curr+'&query=<?php echo $queryStr;?>';
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

function createsql($array,$database,$column){
  $sql = "SELECT * FROM ".$database." WHERE ";
  $tmparray =array();
  for ($i=0;$i<count($array);$i++){
    array_push($tmparray,"`$column` LIKE ?") ;
  }
  $sql = $sql.implode(" and ", $tmparray);
  return $sql;
}

function noresult($queryStr){
  echo "<div style='width:592px;margin: 0 auto;'>";
  echo "<div id ='we_are_sorry'>We're sorry, no products were found for your search: ".$queryStr."</div>";
  echo "<hr>";
  echo "<div id='search_tip_content'>Search Tips</div>";
  echo "<div id='search_tip_content'><ul><li>Double check your spelling.</li><li>Be less specific in your wording or try a similar term.</li><li>Limit the search to one or two words.</li></ul></div>";
  echo "<div id='bottom_stuff'>";
  echo "<hr>";
  echo "<div><div id = 'try_new_search' style='float:left'>Try a new search</div><div><form action='/searchresult' method='post' id ='forget-search'><input type='text' name='query' placeholder='Search...'/></form></div></div>";
  echo "<a id='box' href='javascript: submitform()'><img id='search-icon2' src='/images/new/search.png' type='submit'/></a>";
  echo "</div>";
  echo "<hr style='position: relative; bottom: -120px;'>";
  echo "</div>";
}
?>
