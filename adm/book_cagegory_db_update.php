<?php
$sub_menu = "600500";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');


$sql = " select id,book_cagegory,book_sortted,book_day from g5_book_table ";
$result = sql_query($sql);


//카테고리  mb_id          book_sorted    ==> g5_book_cagegory
// 도서     book_cagegory  book_sortted    ==> book_talbe 


for ($i=0; $row=sql_fetch_array($result); $i++) {

	// 분류코드 없다면 입력
	if(!$row['book_cagegory'] && $row['book_sortted'])  {

		$sqlpo = " select mb_id,book_sortted from g5_book_cagegory where book_sortted = '{$row['book_sortted']}' ";
		$rchk = sql_fetch($sqlpo);
        $sqlpo = " update g5_book_table set	book_cagegory = '{$rchk['mb_id']}' where id = '{$row['id']}' ";
        sql_query($sqlpo);
	}


	if(!$row['book_sortted'] && $row['book_cagegory'])  {

		$sqlpok = " select mb_id,book_cagegory from g5_book_cagegory where mb_id = '{$row['book_cagegory']}' ";
		$rchkk = sql_fetch($sqlpok);
  	
        $sqlp2 = " update g5_book_table set book_sortted = '{$rchkk['book_sortted']}' where id = '{$row['id']}' ";
        sql_query($sqlp2);
	}


	//날자 05/01/2012 일때 변경

	$strs = explode("/",$row['book_day']);

	if($strs[2])
	{
		$book_day = $strs[2]."년 ".	$strs[1]."월 ".$strs[0]."일";
        $sqlp22 = " update g5_book_table set book_day = '{$book_day}' where id = '{$row['id']}' ";
        sql_query($sqlp22);
	}


}

goto_url('./book_cagegory.php?'.$qstr);