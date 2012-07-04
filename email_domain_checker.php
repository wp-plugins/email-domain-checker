<?php 
/*
Plugin Name: Email Domain Checker for WP e-Commerce
Plugin URI: http://www.tychesoftwares.com/store/free-plugin/email-domain-checker/
Description: This plugin checks for a valid domain name in the email address entered during checkout.
Author: Ashok Rane
Version: 1.0
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/

//add the js file to front side script.
function wp_frontside_scripts() {
	$path=get_bloginfo('url');
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/email-domain-checker/js/jquery.js'></script>";
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/email-domain-checker/js/email_domain_checker.js'></script>";
	print "<script type='text/javascript'> var site='$path'; </script>";
}
add_action ('wp_enqueue_scripts','wp_frontside_scripts');

add_action('admin_head', 'wp_admin_script');
function wp_admin_script() {
	//Get the url of blog.
	$path=get_bloginfo('url');
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/email-domain-checker/js/jquery.js'></script>";
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/email-domain-checker/js/email_domain_checker.js'></script>";
	//print "<script type='text/javascript' src='".$path."/wp-content/plugins/email-domain-checker/js/discount.js'></script>";
}
add_action('wp_ajax_email','email_check_callback');
add_action('wp_ajax_nopriv_email','email_check_callback');

function email_check_callback(){
	$full_email=$_GET['emailcheck'];
	$email_parts=explode("@",$full_email);
	//get the domain part of the email.
	$domain=$email_parts[1];
	//check the domain is valid or not and returns true or false.
	if(isset($domain)){
		$domain_check=checkdnsrr($domain,ANY);
	}
	//get the mx deatils of the given domain.
	$mx_details=@dns_get_record($domain,DNS_MX);
	if($full_email != ""){
		if($domain_check){ 
			$check_domain="<span style='color:black;'><b>Email address is valid. Specified domain exists</b><span>";
		}else{
			//check that the email you enter conatain @ sign or not.
			if (strpbrk($full_email, '@') != FALSE) { 
				$check_domain="<span style='color:red;'><b>Please enter a valid email address.<br/> Specified domain does not exist.<span>";
			}else{
				$check_domain="<span style='color:red;'><b>Please enter a valid email address.<br/> Specified domain does not exist.<span>";
			}
		}
	}
	else{
		$check_domain="<span style='color:red;'>Email Address is Required !<span>";
	}
	//get back the message to ajax call function to display it.
	print json_encode($check_domain);
	exit;
}

?>
