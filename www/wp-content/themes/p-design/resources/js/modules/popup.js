/*--------------------------------------------------------------
# Popup
--------------------------------------------------------------*/

$('.popup-link').on('click', function (e) {
	$popup = $(this).attr('data-popup');

	e.preventDefault();

	$('#' + $popup).fadeIn();

	if ($popup == 'popup-search') {
		$('.popup input').focus();
	}
});

$('.popup-close').on('click', function () {
	$(this).closest('.popup').fadeOut();
});