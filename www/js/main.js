$(function(){
	// FORM PROTECTION
	$('input[data-phone]').each(function () {
		$(this).val($(this).data('phone')).hide();
	});
});
