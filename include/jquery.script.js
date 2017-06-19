jQuery.noConflict();

jQuery(document).ready(function() {
	jQuery(".inputfield").keyup(function() {
		// COUNT REMAINING CHARACTERS
		var maxChar = jQuery(this).attr("maxlength");
		var curChar = jQuery(this).val().length;

		var remChar = maxChar-curChar;

		jQuery(".remain").text(remChar + "/160 characters remaining");
	});
});
