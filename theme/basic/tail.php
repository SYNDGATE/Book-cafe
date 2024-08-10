<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
  include_once(G5_THEME_MOBILE_PATH . '/tail.php');
  return;
}
?>

<!-- 	</div> -->
<?php
/*
	<div id="aside">
        <?php echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
        <?php echo poll('theme/basic'); // 설문조사, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
    </div>
    */
?>

</div>
<!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
<div id="footer" class="w-full bg-indigo-500 text-white">
  <div class="xl:px-40 pb-12 lg:px-20 md:px-10 sm:px-5 px-10">
    <div class="w-full pt-12 flex flex-col sm:flex-row space-y-2  justify-start">
      <div class="w-full sm:w-2/5 pr-6 flex flex-col space-y-4">
	  <?php
	    $logo_img = G5_DATA_PATH."/common/logo_img2.jpg";
		if (file_exists($logo_img))
		 { ?>
		 	<img src="<?php echo G5_DATA_URL; ?>/common/logo_img2.jpg" alt="<?php echo $config['cf_title']; ?>" class="w-1/3"></a>
		<?php }  else {
			echo $config['cf_title'];
		}?>
        <p class="opacity-60"><?php echo $config['cf_addre'];?></p>
      </div>
  
    <div class="opacity-60 pt-2">
      <p>© 도서관리 시스템</p>
    </div>
  </div>
</div>

<a href="#" class="back-to-top flex items-center justify-center bg-gray-700 hover:bg-gray-800 fixed invisible opacity-0 right-4 bottom-4 z-50 w-10 h-10 rounded-md transition duration-400"><i class="bi bi-arrow-up-short text-3xl text-white leading-0"></i></a>

<script>
  $(function() {
    $(".back-to-top").on("click", function() {
      $("html, body").animate({
        scrollTop: 0
      }, '500');
      return false;
    });
  });
</script>

<script>
  const btn = document.querySelector("#theme-toggle");
  const currentTheme = localStorage.getItem("theme");

  if (currentTheme == "dark") {
    document.body.classList.add("dark");
    btn.classList.remove('bi-moon')
    btn.classList.add('bi-sun')
  } else {
    btn.classList.remove('bi-sun')
    btn.classList.add('bi-moon')
  }

  btn.addEventListener("click", function() {
    document.body.classList.toggle("dark");

    let theme = "light";
    if (document.body.classList.contains("dark")) {
      theme = "dark";
      btn.classList.remove('bi-moon')
      btn.classList.add('bi-sun')
    } else {
      btn.classList.remove('bi-sun')
      btn.classList.add('bi-moon')
    }
    localStorage.setItem("theme", theme);
  });
</script>

<?php
if (G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
  echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="<?php echo G5_THEME_URL ?>/assets/swiper/swiper-bundle.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="<?php echo G5_THEME_URL ?>/assets/theme.js"></script>

<!-- Initialize AOS -->
<script>
  AOS.init();
</script>

<!-- Initialize Slick -->
<script>
  $(".slider").slick({
    autoplay: true,
    dots: true,
    arrows: true,
    fade: true,
  });
</script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".testimonials-slider", {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });
</script>

<script>
  $(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
  });
</script>

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");
