<?php
$sub_menu = "600100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

//book
if (!sql_query("select count(*) as cnt from g5_book_table",false)) { // attendance 테이블이 없다면 생성
    $sql_table = "create table g5_book_table (           
      id int(11) NOT NULL auto_increment,
	  mb_id int(11) NOT NULL DEFAULT '0',
      book_subject varchar(255) NOT NULL default '',
      book_day varchar(255) NOT NULL default '',
      book_site  varchar(255) NOT NULL default '',
      book_sponsor  varchar(255) NOT NULL default '',
      book_cagegory varchar(255) NOT NULL default '',
      book_sortted  varchar(255) NOT NULL default '',
      book_authors_name  varchar(255) NOT NULL default '',
      book_ing  varchar(255) NOT NULL default '',
      book_wr_1  varchar(255) NOT NULL default '',
      book_wr_2  varchar(255) NOT NULL default '',
      book_wr_3  varchar(255) NOT NULL default '',
      book_wr_4  varchar(255) NOT NULL default '',
      book_wr_5  varchar(255) NOT NULL default '',
      datetime datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (id),
      KEY id (mb_id,datetime)
        )";
    sql_query($sql_table, false);


	sql_query( "ALTER TABLE `g5_book_table` CHANGE mb_id mb_id int(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0'; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_subject book_subject VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_day book_day VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");	
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_site book_site VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_sponsor book_sponsor VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_cagegory book_cagegory VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_sortted book_sortted VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_authors_name book_authors_name VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL  DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE book_ing book_ing VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL  DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE `book_wr_1` `book_wr_1` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE `book_wr_2` `book_wr_2` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE `book_wr_3` `book_wr_3` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_table` CHANGE `book_wr_4` `book_wr_4` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");	
	sql_query( "ALTER TABLE `g5_book_table` CHANGE `book_wr_5` `book_wr_5` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");

}


$sql_common = " from g5_book_table ";

if ($stx) {
	$sql_search = " where (1) ";

    $sql_search .= " and ( ";
    switch ($sfl) {
        case "book_subject" :
            $sql_search .= " ($sfl like '%$stx%') ";
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

$g5['title'] = '도서 관리';
include_once('./admin.head.php');

$colspan = 10;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">생성된 도서목록수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($sfl, "mb_id", true); ?>>분류번호</option>
    <option value="book_subject"<?php echo get_selected($sfl, "book_subject", true); ?>>도서명</option>
    <option value=" book_day"<?php echo get_selected($sfl, " book_day"); ?>>구매일자</option>
    <option value="book_site"<?php echo get_selected($sfl, "book_site"); ?>>장소</option>
    <option value="book_sponsor"<?php echo get_selected($sfl, "book_sponsor"); ?>>후원</option>
    <option value="book_cagegory"<?php echo get_selected($sfl, "book_cagegory"); ?>>분류코드</option>
    <option value="book_sortted"<?php echo get_selected($sfl, "book_sortted"); ?>>도서분류</option>
    <option value="book_authors_name"<?php echo get_selected($sfl, "book_authors_name"); ?>>작가명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>


<form name="fboardlist" id="fboardlist" action="./book_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
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
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>분류번호</a></th>
        <th scope="col"><?php echo subject_sort_link('book_subject') ?>도서명</a></th>
        <th scope="col"><?php echo subject_sort_link('book_day') ?>구매일자</a></th>
        <th scope="col"><?php echo subject_sort_link('book_site', '', 'desc') ?>장소</a></th>
        <th scope="col"><?php echo subject_sort_link('book_sponsor', '', 'desc') ?>후원</a></th>
        <th scope="col"><?php echo subject_sort_link('book_cagegory', '', 'desc') ?>분류코드</a></th>
        <th scope="col"><?php echo subject_sort_link('book_sortted') ?>도서분류</a></th>
        <th scope="col"><?php echo subject_sort_link('book_authors_name') ?>작가명</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./book_form.php?w=u&amp;id='.$row['id'].'&amp;'.$qstr.'" class="btn btn_03">수정</a>';

		if(!$row['book_ing'])
        $one_update .= '<a href="./book_rental.php?book_book_number='.$row['mb_id'].'&amp;book_book_name='.$row['book_subject'].'" class="btn btn_02">대여</a>';
		else $one_update .= '<a href="./book_rental.php?sfl=book_book_number&stx='.$row['mb_id'] .'" class="btn btn_02">'.$row['book_ing'].'</a>';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['book_subjec']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			<input type="hidden" name="id[<?php echo $i ?>]" value="<?php echo $row['id'] ?>">
        </td>
        <td>
            <label for="book_subjec_<?php echo $i; ?>" class="sound_only">분류번호<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="mb_id[<?php echo $i ?>]" value="<?php echo get_text($row['mb_id']) ?>" id="mb_id<?php echo $i ?>" required class="required tbl_input book_subject full_input" size="3">
        </td>
        <td>
            <label for="book_subjec_<?php echo $i; ?>" class="sound_only">도서명 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_subject[<?php echo $i ?>]" value="<?php echo get_text($row['book_subject']) ?>" id="book_subjec_<?php echo $i ?>" required class="required tbl_input book_subject full_input" size="22">
        </td>
        <td>
            <label for="book_day_<?php echo $i; ?>" class="sound_only">구매일자 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_day[<?php echo $i ?>]" value="<?php echo get_text($row['book_day']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_day full_input" size="5">
        </td>
     <td>
            <label for="book_site_<?php echo $i; ?>" class="sound_only">장소 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_site[<?php echo $i ?>]" value="<?php echo get_text($row['book_site']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_site full_input" size="3">
        </td>
		<td>
            <label for="book_sponsor_<?php echo $i; ?>" class="sound_only">후원 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_sponsor[<?php echo $i ?>]" value="<?php echo get_text($row['book_sponsor']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_sponsor full_input" size="10">
        </td>
		<td>
            <label for="book_sponsor_<?php echo $i; ?>" class="sound_only">분류코드 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_cagegory[<?php echo $i ?>]" value="<?php echo get_text($row['book_cagegory']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_cagegory full_input" size="5">
        </td>
		     <td>
            <label for="book_sortted_<?php echo $i; ?>" class="sound_only">도서분류 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_sortted[<?php echo $i ?>]" value="<?php echo get_text($row['book_sortted']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_sortted full_input" size="10">
        </td>
        </td>
		     <td>
            <label for="book_authors_name_<?php echo $i; ?>" class="sound_only">작가명 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_authors_name[<?php echo $i ?>]" value="<?php echo get_text($row['book_authors_name']) ?>" id="book_authors_name_<?php echo $i ?>" class="tbl_input book_authors_name full_input" size="10">
        </td>

        </td>
        <td class="td_mng td_mng_m2">
            <?php echo $one_update ?>
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
    <?php if ($is_admin == 'super') { ?>
    <a href="./book_form.php" id="bo_add" class="btn_01 btn">도서 추가</a>
    <a href= "<?php echo G5_URL;?>/book/" id="bo_add" class="btn_01 btn">도서 리스트</a>
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
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
</script>

<?php
include_once('./admin.tail.php');