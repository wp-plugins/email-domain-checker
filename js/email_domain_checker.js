var SUBMITTABLE = false;
var SUBMITTING = false;

jQuery(document).ready(function() {
	jQuery(".make_purchase").click(function(){
		SUBMITTING = true;
		if(SUBMITTABLE){
			return true;
		}else{
			checkFunction();
			return false;
		}
	});
	
	var checkFunction = function() {
		jQuery('p.wpsc_email_address_p').append('<div id="wp_email"></div>');
		jQuery('#wp_email').html('<img src='+site+'/wp-content/plugins/email-domain-checker/Processing1.gif width="22px" height="22px"><br/><p style="color:black;"> Checking for valid domain!</p>');
		var full_email=jQuery('.wpsc_email_address input').val();
		jQuery.get( site+"/wp-admin/admin-ajax.php", {emailcheck:full_email,action:"email"},function (data){
			var json = jQuery.parseJSON(data);
			jQuery('#wp_email').html(json);
			if(jQuery("#wp_email span b").html() == "Email address is valid. Specified domain exists"){
				SUBMITTABLE = true;
				if (SUBMITTING){
					jQuery(".make_purchase").click();
				}
			}else{
				SUBMITTABLE = false;
				SUBMITTING = false;
			}			
		});
		return false;
	};

	function inputBlured (){
		SUBMITTING = false;
		checkFunction();
	}
	
	jQuery('.wpsc_email_address input').on('blur', inputBlured);
});
