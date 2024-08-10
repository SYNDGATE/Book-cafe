<?php
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from g5_book_member ";

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

$g5['title'] = '도서 회원 관리';
include_once(G5_PATH.'/head.sub.php');
$colspan = 4;
?>
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="book_mb_id"<?php echo get_selected($sfl, "book_mb_id", true); ?>>아이디</option>
    <option value="book_name"<?php echo get_selected($sfl, "book_name ", true); ?>>성명</option>
    <option value="book_hp "<?php echo get_selected($sfl, " book_hp "); ?>>H.P</option>
    <option value="book_email"<?php echo get_selected($sfl, "book_email"); ?>>이메일</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>
<form name="fboardlist" id="fboardlist" onsubmit="return fboardlist_submit(this);" method="post">
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
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
        <th scope="col"><?php echo subject_sort_link('book_name') ?>회원이름</a></th>
        <th scope="col">연락처</th>
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
            <label for="book_mb_id_<?php echo $i; ?>" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['book_mb_id']) ?>
        </td>

        <td>
            <label for="book_name_<?php echo $i; ?>" class="sound_only">회원이름 <strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['book_name']) ?>
        </td>
        <td>
            <label for="book_hp_<?php echo $i; ?>" class="sound_only">연락처 <strong class="sound_only"> 필수</strong></label>
            <?php echo get_text($row['book_hp']) ?>
        </td>
        </td>
        <td class="td_mng td_mng_m">
           <button type="button" class="add_select btn btn_03" onclick="closePopup('<?php echo $row['book_mb_id'];?>','<?php echo $row['book_name'];?>','<?php echo $row['book_hp'];?>')">선택</button>
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
        function closePopup(book_mb_id,book_name,book_hp) {			
            window.parent.document.querySelector('input[name="book_mb_id"]').value = book_mb_id;
            window.parent.document.querySelector('input[name="book_name"]').value = book_name;
            window.parent.document.querySelector('input[name="book_hp"]').value = book_hp;
            window.parent.document.getElementById('popup').style.display = 'none';
			window.parent.document.getElementById('overlay').style.display = 'none';
        }
</script>
<?php
include_once(G5_PATH.'/tail.sub.php');