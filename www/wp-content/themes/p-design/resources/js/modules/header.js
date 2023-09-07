var headerScrolled = false;

if($('.home').length > 0) {
	$(window).on('scroll', function () {
		if($(window).scrollTop() > 300 && headerScrolled == false) {
			headerScrolled = true;
			$('.header').addClass('scrolled')
		}
		if($(window).scrollTop() < 300 && headerScrolled == true) {
			headerScrolled = false;
			$('.header').removeClass('scrolled');
		}
	});
}


$('.header-menu-products-item').on('click', function (e) {
	e.preventDefault();
	var $this = $(this),
		$productsMenu = $('.header-products-nav');

	$this.toggleClass('active');
	$productsMenu.slideToggle();
});

$('.menu-mobile').on('click', function (e) {
	if($('.menu-content').hasClass('opened')) {
		$('.header-products-nav').slideUp();
	}
	$('.menu-content').toggleClass('opened');
});

$('.header-products-nav-close').on('click', function () {
	$('.header-products-nav').slideUp();
})