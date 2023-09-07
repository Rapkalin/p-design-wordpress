/*--------------------------------------------------------------
# Slider Home
--------------------------------------------------------------*/

$('#slider-home').slick({
	fade: true,
	arrows: false
});

$('.slider-arrow-prev').on('click', function () {
	$('#slider-home').slick('slickPrev');
});

$('.slider-arrow-next').on('click', function () {
	$('#slider-home').slick('slickNext');
});

$('.slider-arrow-down').on('click', function () {
	$('html, body').animate({
		scrollTop: $('#home-quick-access').offset().top - 70
	}, 500)
});

/*--------------------------------------------------------------
# Slider Testimonials
--------------------------------------------------------------*/

$('#slider-testimonials').slick({
	infinite: true,
	slidesToShow: 1,
	arrows: false,
	dots: true
});

/*--------------------------------------------------------------
# Slider Clients
--------------------------------------------------------------*/

$('#slider-clients').slick({
	slidesToShow: 6,
	slidesToScroll: 1,
	autoplay: true,
	autoplaySpeed: 0,
	arrows: false,
	infinite: true,
	speed: 10000,
	pauseOnHover: true,
	pauseOnFocus: true,
	swipeToSlide: true,
	cssEase: 'linear',
	responsive: [
		{
		  breakpoint: 1280,
		  settings: { slidesToShow: 5 }
		},
		{
		  breakpoint: 1050,
		  settings: { slidesToShow: 4 }
		},
		{
		  breakpoint: 820,
		  settings: { slidesToShow: 3 }
		},
		{
		  breakpoint: 600,
		  settings: { slidesToShow: 2 }
		},
		{
		  breakpoint: 420,
		  settings: { slidesToShow: 1, speed: 5000 }
		}
	]
});

/*--------------------------------------------------------------
# Page Title Slider
--------------------------------------------------------------*/

$('.page-title-slider').slick({
	infinite: true,
	slidesToShow: 1,
	arrows: false,
	dots: true
});