<?php
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from g5_book_table ";

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

$g5['title'] = '도서 관리';
include_once(G5_PATH.'/head.sub.php');
$colspan = 4;
?>


<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="book_subject"<?php echo get_selected($sfl, "book_subject ", true); ?>>도서명</option>
    <option value="book_authors_name "<?php echo get_selected($sfl, "book_authors_name"); ?>>작가명</option>
    <option value="book_site "<?php echo get_selected($sfl, "book_site"); ?>>장소</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>


<form name="fboardlist" id="fboardlist" action="#" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">


<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>분류번호</a></th>
        <th scope="col"><?php echo subject_sort_link('book_name') ?>도서명</a></th>
        <th scope="col">장소</th>
        <th scope="col">선택</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td><input type="hidden" name="id[<?php echo $i;?>]" value="<?php echo $row['id']?>">
            <label for="book_mb_id_<?php echo $i; ?>" class="sound_only">분류코드<strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['mb_id']) ?>
        </td>

        <td>
            <label for="book_name_<?php echo $i; ?>" class="sound_only">도서이름 <strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['book_subject']) ?>
        </td>
        <td>
            <label for="book_hp_<?php echo $i; ?>" class="sound_only">장소 <strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['book_site']) ?>
        </td>
        </td>
        <td class="td_mng td_mng_m">
           <button type="button" class="add_select btn btn_03" onclick="closePopup('<?php echo $row['mb_id'];?>','<?php echo $row['book_subject'];?>')">선택</button>
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
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
        function closePopup(book_book_number,book_book_name) {			
            window.parent.document.querySelector('input[name="book_book_number"]').value = book_book_number;
            window.parent.document.querySelector('input[name="book_book_name"]').value = book_book_name;
            window.parent.document.getElementById('popup2').style.display = 'none';
			window.parent.document.getElementById('overlay').style.display = 'none';
			window.close();
        }
</script>
<?php
include_once(G5_PATH.'/tail.sub.php');