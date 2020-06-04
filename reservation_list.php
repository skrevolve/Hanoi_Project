<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>예약 목록</title>
<link rel="stylesheet" type="text/css" href="./css/reservation.css">
<style>
#reservation_box{
	height:600px;
}

</style>
</head>
<body> 
<header>
    <?php include "header.php";?>
</header>  
<section>
   	<div id="reservation_box">
	    <h3>
	    	예약하기 > 예약확인
		</h3>
	    <ul id="reservation_list">
				<li>
					<span class="col1" style="width:80px;">번호</span>
					<span class="col2"style="width:300px;">인원 / 메뉴</span>
					<span class="col3"style="width:150px;">예약명 / 아이디</span>
					<span class="col4"style="width:150px;">날짜</span>
					<span class="col5"style="width:80px;">시간</span>
					
				</li>
<?php
	if (isset($_GET["page"]))
		$page = $_GET["page"];
	else
		$page = 1;

	$word = $_POST['word'];
	$category = $_POST['category'];
	
	if ($word != '') {
		$where = "where $category like '%$word%'";
	}
	

	$con = mysqli_connect("localhost", "sukyu0919", "SKlim478359!", "sukyu0919");
	$sql = "select * from reservation $where order by num desc";
	
	$result = mysqli_query($con, $sql);
	$total_record = mysqli_num_rows($result); // 전체 글 수

	$scale = 10;

	// 전체 페이지 수($total_page) 계산 
	if ($total_record % $scale == 0)     
		$total_page = floor($total_record/$scale);      
	else
		$total_page = floor($total_record/$scale) + 1; 
 
	// 표시할 페이지($page)에 따라 $start 계산  
	$start = ($page - 1) * $scale;      

	$number = $total_record - $start;

   for ($i=$start; $i<$start+$scale && $i < $total_record; $i++)
   {
      mysqli_data_seek($result, $i);
      // 가져올 레코드로 위치(포인터) 이동
      $row = mysqli_fetch_array($result);
      // 하나의 레코드 가져오기
	  $num         = $row["num"];
	  $id          = $row["id"];
	  $name        = $row["name"];
	  $subject     = $row["subject"]; // 인원메뉴
	  $date     = $row["date"];
	  $time     = $row["time"];

?>
				<li>
					<span class="col1"style="width:80px;"><?=$number?></span>
					<span class="col2"style="width:300px;"><a href="reservation_view.php?num=<?=$num?>&page=<?=$page?>"><?=$subject?></a></span>
					<span class="col3"style="width:150px;"><?=$name?> / <?=$id?></span>
					<span class="col4"style="width:150px;"><?=$date?></span>
					<span class="col5"style="width:80px;"><?=$time?></span>
					
				</li>	
<?php
   	   $number--;
   }
   mysqli_close($con);

?>
	    	</ul>
			<ul id="page_num"> 	
<?php
	if ($total_page>=2 && $page >= 2)	
	{
		$new_page = $page-1;
		echo "<li><a href='reservation_list.php?page=$new_page'>◀ 이전</a> </li>";
	}		
	else 
		echo "<li>&nbsp;</li>";

   	// 게시판 목록 하단에 페이지 링크 번호 출력
   	for ($i=1; $i<=$total_page; $i++)
   	{
		if ($page == $i)     // 현재 페이지 번호 링크 안함
		{
			echo "<li><b> $i </b></li>";
		}
		else
		{
			echo "<li><a href='reservation_list.php?page=$i'> $i </a><li>";
		}
   	}
   	if ($total_page>=2 && $page != $total_page)		
   	{
		$new_page = $page+1;	
		echo "<li> <a href='reservation_list.php?page=$new_page'>다음 ▶</a> </li>";
	}
	else 
		echo "<li>&nbsp;</li>";
?>
			</ul> <!-- page -->
			<form method=post action="reservation_list.php"><!---검색입력폼--->
			<tr>
			<td text-align="center">
			<select name="category">
			<option value="name">이름</option>
			<option value="subject">제목</option>
			<option value="content">내용</option>
			</select>
			<input type="text" name="word" size="30">
			<input type="submit" value="검색">
			</td>
			</tr>
			</form><!---검색입력폼끝--->
			<ul class="buttons">
				<li><button onclick="location.href='reservation_list.php'">목록</button></li>
				<li>
<?php 
    if($userid) {
?>
					<button onclick="location.href='reservation_form.php'">글쓰기</button>
<?php
	} else {
?>
					<a href="javascript:alert('로그인 후 이용해 주세요!')"><button>글쓰기</button></a>
<?php
	}
?>
				</li>
			</ul>
	</div> <!-- reservation_box -->
</section> 
<footer>
    <?php include "footer.php";?>
</footer>
</body>
</html>
