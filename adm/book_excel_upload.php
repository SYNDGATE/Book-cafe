<?php
$sub_menu = "600100";
include_once('./_common.php');

$g5['title'] = '도서 엑셀 업로드';
include_once('./admin.head.php');


set_time_limit ( 0 );
ini_set('memory_limit', '50M');


function only_number($n)
{
    return preg_replace('/[^0-9]/', '', $n);
}

if($_FILES['excelfile']['tmp_name']) {

	if($DBzero) {
		sql_query("TRUNCATE `g5_book_table`"); 
	}

	if(strlen($_POST['board_nm']) == 0){
		alert('테이블명 누락');
	}
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');


    $data->read($file);

  
    error_reporting(E_ALL ^ E_NOTICE);
		$succ_count = 0;
		$board_nm = $_POST['board_nm'];


	$encode = array('ASCII','UTF-8','EUC-KR');

    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

				$mb_id= addslashes($data->sheets[0]['cells'][$i][1]);
				$book_subject= addslashes($data->sheets[0]['cells'][$i][2]);
				$book_day= addslashes($data->sheets[0]['cells'][$i][3]);
				$book_site= addslashes($data->sheets[0]['cells'][$i][4]);
				$book_sponsor= addslashes($data->sheets[0]['cells'][$i][5]);
				$book_cagegory = addslashes($data->sheets[0]['cells'][$i][6]);
				$book_sortted= addslashes($data->sheets[0]['cells'][$i][7]);
				$book_authors_name= addslashes($data->sheets[0]['cells'][$i][8]);
		
		//도서목록 추가
			$sql = " insert into g5_book_table
			            set 
							mb_id           = '$mb_id',
							book_subject    = '$book_subject',
							book_day		= '$book_day',
							book_site		= '$book_site',
							book_sponsor	= '$book_sponsor',
							book_cagegory= '$book_cagegory',
							book_sortted	= '$book_sortted',
							book_authors_name= '$book_authors_name'
						";
			 sql_query($sql);

        $succ_count++;

    }
		alert(number_format($succ_count)."개 insert 성공","book_excel_upload.php"); 
}
?>
<div style="border:3px solid #000;padding:20px;max-width:700px;margin:50px auto 0 auto;">
	<h1 style="margin-bottom:40px;">엑셀데이터 데이터 INSERT!! < 번호 | 도서명 | 구매일자 | 장소 | 후원 | 분류코드 | 도서분류 | 작가명 >  <a href="book\book_table.xls" _blank>엑셀 샘플 파일</a> </h1>
		<form name="frm" id="frm" method="post" enctype="multipart/form-data">
			<input type="text" id="board_nm" name="board_nm" value="g5_book_table" class="frm_input" style='width:120px;' placeholder="만들어질 테이블명">
			<label for="file">엑셀파일:</label> 
			<input type="file" name="excelfile" id="excelfile" />
			  DB초기화 
			  <select name="DBzero">
                <option value="0">사용안함</option>
				<option value="1">DB초기화</option>
            </select>
			<p style="float:right;">
			<input type="submit" name="submit" value="DB업로드실행" class="btn_02 btn"/>
		</form>
</div>
<?php
include_once('./admin.tail.php');