<style>
	#pdf-div{
		text-align: center;
		position: relative;
	}
	#pdf-tb{
		margin:0 auto 40px auto;
	}
	#pdf-tb td{
		width:160px;
		padding:10px 20px;
		text-align: left;
	}
	#pdf-tb td a{
		font-family: Montserrat-Light;
		font-size: 14px;
		color:#4A4A4A;
		text-decoration: none;
		text-transform: uppercase;
	}
	#pdf-tb td a:hover{
		color:#4ABDAC;
	}
	#pdf-hand{
		position: absolute;
		top:-153px;
		right:0px;
	}
</style>
<div id="pdf-div">
	<img id="pdf-hand" src="images/new/pdf-hand.png">
	<table id="pdf-tb">
		<tr>
			<?php
			$sql = "SELECT * FROM ".$site['database']['pdf'];
			$r = sql_exec($sql);
			$pdf = mysqli_fetch_all($r,MYSQLI_ASSOC);
			foreach ($pdf as $key => $value){
				echo "<td><a href='".$site['url']['full']."download.php?type=pdf&amp;file=".$value['pdfId']."'>".$value['pdfHyperlink']."</a></td>";
				if ($key%3 == 2) {
					echo "</tr><tr>";
				}
			}
			?>
		</tr>
	</table>
	<img id='pdf-book' src="images/new/pdf-book.png">
</div>