$('#history-arrow-down').click(function() {
	$('#history-content').animate({
		scrollTop: $('#history-content')[0].scrollTop + 170
	}, 200);
});

$('#history-arrow-up').click(function() {
	$('#history-content').animate({
		scrollTop: $('#history-content')[0].scrollTop - 170
	}, 200);
});
