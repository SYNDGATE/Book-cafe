<?php
$sub_menu = "600300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');


//대여관리   아이디, 이름, 연락처,  집주소, 책번호, 대여일, 반납일, 메모
//   book_mb_id   book_name  book_hp book_addr book_book_number book_book_name book_outbook  book_inbook book_memo
if (!sql_query("select count(*) as cnt from g5_book_rental",false)) { // 회원관리 테이블이 없다면 생성
    $sql_table = "create table g5_book_rental (           
      id int(11) NOT NULL auto_increment,
      book_mb_id varchar(50) NOT NULL default '',
      book_id varchar(50) NOT NULL default '',
      book_name varchar(255) NOT NULL default '',
      book_hp varchar(255) NOT NULL default '',
      book_addr  varchar(255) NOT NULL default '',
      book_book_number  varchar(255) NOT NULL default '',
      book_book_name  varchar(255) NOT NULL default '',
      book_outbook  varchar(255) NOT NULL default '',
      book_inbook  varchar(255) NOT NULL default '',
      book_ing  varchar(255) NOT NULL default '',
      book_memo text NOT NULL,
      book_wr_1  varchar(255) NOT NULL default '',
      book_wr_2  varchar(255) NOT NULL default '',
      book_wr_3  varchar(255) NOT NULL default '',
      book_wr_4  varchar(255) NOT NULL default '',
      book_wr_5  varchar(255) NOT NULL default '',
      datetime datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (id),
      KEY id (book_mb_id,datetime)
        )";
    sql_query($sql_table, false);
}


$sql_common = " from g5_book_rental ";

if ($stx) {
	$sql_search = " where (1) ";

    $sql_search .= " and ( ";
    switch ($sfl) {
        case "book_subject" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.book_site" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '도서 대여 관리';
include_once('./admin.head.php');

$colspan = 9;

$fr_date = isset($_REQUEST['fr_date']) ? $_REQUEST['fr_date'] : '';
$to_date = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '';

if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+30d" });
});
</script>
  <style>
    .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 520px;
            background-color: white;
            border: 1px solid black;
            padding: 10px;
            display: none;
        }
        /* CSS 코드 */
        .frame {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 비율에 맞게 설정 */
            height: 0;
        }

        .frame > * {position: absolute;top: 0;left: 0;width: 100%;height: 100%;}

        .frame > iframe {
            border: none; /* 기본적으로 iframe에 적용되는 테두리 제거 */
        }
		
		#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Adjust the opacity (last value) as needed */
    display: none; /* Initially hidden */
	}
	
	.fullclose {margin: 40px 0 0 0;width:98%; height:30px;}
    </style>
	
<section>
    <h2 class="h2_frm">도서 대여</h2>

    
	<form name="fbooklist" id="fbooklist" method="post" action="./book_rental_update.php" onsubmit="return fmenulist_submit(this);">

    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="book_mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="book_mb_id" value="<?php echo $book_mb_id ?>" id="book_mb_id" class="required frm_input" required>
			<button class="btn btn_02" onclick="return openPopup()">회원검색</button>
			
            <th scope="row"><label for="book_book_number">책번호/책이름<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="book_book_number" value="<?php echo $book_book_number ?>" id="book_book_number" required class="required frm_input"> / <input type="text" name="book_book_name" id="book_book_name" value="<?php echo $book_book_name;?>" required class="frm_input"> 			
			<button class="btn btn_02" onclick="return openPopup2()">도서 검색</button>
			</td>
        </tr>
        <tr>
            <th scope="row"><label for="book_outbook">대여일<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="book_outbook" value="<?php echo date("Y-m-d");?>" id="fr_date" required class="required frm_input"></td>
            <th scope="row"><label for="book_inbook">반납일<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="book_inbook" value="<?php echo date("Y-m-d");?>" id="to_date" required class="required frm_input"></td>
        </tr>
        <tr>
            <th scope="row"><label for="po_point">상태<strong class="sound_only">필수</strong></label></th>
            <td>
				<select name="book_ing" id="sfl">
 					 <option value="대여중"  selected="selected">대여중</option>
					 <option value="반납">반납</option>
					 <option value="분실">분실</option>
					 <option value="파손">파손</option>
				</select></td>
            <th scope="row"><label for="book_hp">이름/연락처<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="book_name" id="book_name" required class="required frm_input">/<input type="text" name="book_hp" id="book_hp" required class="required frm_input"></td>
        </tr>
        </tbody>
        </table>
    </div>
	<div id="overlay"></div>
    <div id="popup" class="popup" onclick="closePopup()">
        <h2>회원 관리</h2>
        <div class="frame" data-src="book_member_pop.php"></div>
		<button onclick="closePopup()" class="btn btn_02 fullclose">닫기</button>		
    </div>

    <div id="popup2" class="popup" onclick="closePopup2()">
        <h2>도서 관리</h2>
        <div class="frame" data-src="book_book_pop.php"></div>
		<button onclick="closePopup2()" class="btn btn_02 fullclose">닫기</button>		
    </div>
	
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="도서 대여 등록" class="btn_submit btn">
    </div>
    </form>
</section>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">대여 리스트</span><span class="ov_num"> <?php echo number_format($total_count) ?>건</span></span>
</div>
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="book_name"<?php echo get_selected($sfl, "book_name ", true); ?>>회원이름</option>
    <option value="book_hp "<?php echo get_selected($sfl, " book_hp "); ?>>H.P</option>
	<option value="book_book_name"<?php echo get_selected($sfl, "book_book_name", true); ?>>책이름</option>
    <option value="book_book_number"<?php echo get_selected($sfl, "book_book_name", true); ?>>책번호</option>
    <option value="book_mb_id"<?php echo get_selected($sfl, "book_mb_id", true); ?>>아이디</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>


<form name="fboardlist" id="fboardlist" action="./book_rental_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="w" value="a">
<input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">게시판 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
        <th scope="col"><?php echo subject_sort_link('book_name') ?>회원이름</a></th>
        <th scope="col">연락처</th>
        <th scope="col">책번호</th>
        <th scope="col">책이름</th>
        <th scope="col">대여일</th>
        <th scope="col">반납일</th>
        <th scope="col"><?php echo subject_sort_link('book_ing') ?>상태</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['book_subjec']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			<input type="hidden" name="id[<?php echo $i ?>]" value="<?php echo $row['id'] ?>">
			<input type="hidden" name="book_mb_id[<?php echo $i ?>]" value="<?php echo $row['book_mb_id'] ?>">
			<input type="hidden" name="book_book_number[<?php echo $i ?>]" value="<?php echo $row['book_book_number'] ?>">
        </td>
        <td>
            <label for="book_subjec_<?php echo $i; ?>" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
			<input type="text" name="book_mb_ids[<?php echo $i ?>]" value="<?php echo get_text($row['book_mb_id']) ?>" id="mb_id<?php echo $i ?>" disabled class="tbl_input book_subject full_input" size="10">
        </td>
        <td>
            <label for="book_subjec_<?php echo $i; ?>" class="sound_only">회원이름 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_names[<?php echo $i ?>]" value="<?php echo get_text($row['book_name']) ?>" id="book_name<?php echo $i ?>" disabled class="tbl_input book_name full_input" size="10">
        </td>
        <td>
            <label for="book_day_<?php echo $i; ?>" class="sound_only">연락처 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_hp[<?php echo $i ?>]" value="<?php echo get_text($row['book_hp']) ?>" id="book_hp<?php echo $i ?>" class="tbl_input book_hp full_input" size="10">
        </td>
     <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">책번호 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_book_numbers[<?php echo $i ?>]" value="<?php echo get_text($row['book_book_number']) ?>" disabled id="book_book_number<?php echo $i ?>" class="tbl_input book_hp full_input" size="10">
        </td>

     <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">책이름 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_book_names[<?php echo $i ?>]" value="<?php echo get_text($row['book_book_name']) ?>" disabled id="book_book_name<?php echo $i ?>" class="tbl_input book_hp full_input" size="10">
        </td>

     <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">대여일 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_outbook[<?php echo $i ?>]" value="<?php echo get_text($row['book_outbook']) ?>" required id="book_outbook<?php echo $i ?>" class="tbl_input book_hp full_input" size="10">
        </td>

     <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">반납일 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_inbook[<?php echo $i ?>]" value="<?php echo get_text($row['book_inbook']) ?>" required id="book_inbook<?php echo $i ?>" class="tbl_input book_hp full_input" size="10">
        </td>
		 <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">상태 <strong class="sound_only"> 필수</strong></label>
			<select name="book_ing[<?php echo $i ?>]" id="sfl">
 			 <option value="대여중" <?php if($row['book_ing'] == "대여중") echo ' selected="selected" ';?>>대여중</option>
			 <option value="반납" <?php if($row['book_ing'] == "반납") echo ' selected="selected" ';?>>반납</option>
			 <option value="분실" <?php if($row['book_ing'] == "분실") echo ' selected="selected" ';?>>분실</option>
			 <option value="파손" <?php if($row['book_ing'] == "파손") echo ' selected="selected" ';?>>파손</option>
			</select>
        </td>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
		function add_book_book_number()
		{
			var names = document.fbooklist.book_book_number.value;
		    var url = "./book_book_pop.php?stx="+names+"&sfl=mb_id";
		    window.open(url, "new_member", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
		    return false;
		}

		function fboardlist_submit(f)
		{
		    if (!is_checked("chk[]")) {
		        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		        return false;
		    }

		    if(document.pressed == "선택삭제") {
		        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
		            return false;
		        }
		    }

		    return true;
		}

		function openPopup() {
				    document.getElementById('popup').style.display = 'block';
				    document.getElementById('overlay').style.display = 'block';
				    return false;
		}

		function closePopup() {
		  var popup = document.getElementById("popup");
		  window.parent.document.getElementById('overlay').style.display = 'none';
		  popup.style.display = "none";
		}
		
		function openPopup2() {
				    document.getElementById('popup2').style.display = 'block';
				    document.getElementById('overlay').style.display = 'block';
				    return false;
		}

		function closePopup2() {
		  var popup2 = document.getElementById("popup2");
		  window.parent.document.getElementById('overlay').style.display = 'none';
		  popup2.style.display = "none";
		}
		
        document.addEventListener("DOMContentLoaded", function () {
            const frames = document.querySelectorAll(".frame");
            frames.forEach((frame) => {
                const src = frame.dataset.src;
                const iframe = document.createElement("iframe");
                iframe.src = src;
                frame.appendChild(iframe);
            });
        });
</script>

<?php
include_once('./admin.tail.php');