jQuery(function ($) {
	$("span label[for^='et_pb_contact_datenschutzhinweis']").each(function () {
		$(this).append(" Dies machen wir gemäß unserer <a href='/datenschutzerklaerung' target='blank'>Datenschutzerklärung</a>.");
	});
});
