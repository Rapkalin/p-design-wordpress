$(function() {
	if ($('.home').length > 0) {
		var animationAboutScrolled = false;

		$(document).scroll(function() {
			if (!animationAboutScrolled) {
				var $scroll = $(window).scrollTop(),
					$homeAbout = $('#home-about').offset().top,
					$windowHeight = $(window).height();

				if ($scroll > ($homeAbout - $windowHeight / 2)) {
					$('#about-mask').addClass('active');
					animationAboutScrolled = true
				}
			}
		});
	}

})