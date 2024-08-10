<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH . '/head.php');
?>

<h2 class="sr-only">메인 페이지</h2>
</div>

<!-- Container End -->
<section id="main-top" class="relative w-full flex items-center">
  <div class="container mx-auto sm: px-4 text-white relative" data-aos="zoom-out" data-aos-delay="100" data-aos-duration="1000">
    <h1 class="m-0 text-xl md:text-3xl font-extrabold leading-6 md:leading-8 "><?php echo $g5['title'] ?> <span class="text-indigo-500"> 도서정보검색</span></h1>
    <h2 class="mt-2 mr-0 mb-10 ml-0 text-lg md:text-xl font-base mb-0 md:mb-4">도서관리는 검색 정보 제공 사이트입니다.</h2>
    <div class="inline-flex items-center">
      <a href="./book" target="_blank" class="uppercase text-base md:text-lg tracking-wide inline-block py-1.5 px-3 rounded-md transition transition-duration-500 bg-indigo-500 hover:bg-indigo-400">시작하기</a>
      <a href="https://youtu.be/uQWPpCOAMDg" class="inline-flex items-center">
        <i class="bi bi-play-circle text-indigo-400 text-4xl transition transition-duration-300 leading-0 ml-8 hover:text-indigo-500"></i>
        <span class="text-base transition transition-duration-500 ml-2.5 text-gray-300 font-semibold hover:text-indigo-500">영상 보기</span>
      </a>
    </div>
  </div>
</section>

<?php
include_once(G5_THEME_PATH . '/tail.php');
