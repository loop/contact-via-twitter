<?php

/** this function stripslashes and encodes html entities for security purposes **/
function security($value) 
{
    if(is_array($value)) {
	  $value = array_map('security', $value);
	} else {
	  if(!get_magic_quotes_gpc()) {
	    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	  } else {
	    $value = htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8');
	  }
	  $value = str_replace("\\", "\\\\", $value);
	}
	return $value;
}

/** this function sends a tweet **/
function send_tweet($msg, $name)
{
    /** set globals **/
	global $twitter_use_DM, $twitter_username, $twitter_passwd;
	
	/** initiate curl **/
	$curl = curl_init();
	
	/** format tweet **/
	$tweet = urlencode(stripslashes(urldecode("@".$twitter_username." ".$msg." -".$name)));
	
	/** set host **/
	$twitterHost = 'http://twitter.com/statuses/update.xml';
	
	/** set params **/
	$params = "status=$tweet";
	
	/** decide on type **/
	if($twitter_use_DM === true)
	{
	    $twitterHost = 'http://twitter.com/direct_messages/new.xml';
	    $params = "text=$tweet&user=$twitter_username";
    }
	
	/** set curl params **/
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); 
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $twitterHost);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	curl_setopt($curl, CURLOPT_USERPWD, "$twitter_username:$twitter_passwd");
	
	/** result **/
	$result = curl_exec($curl);
	$re = curl_getinfo($curl);
	
	/** close curl connection **/
	curl_close($curl);
	
	/** get status **/
	if($re['http_code'] == 200){ return true; }else{ return false; }
}

?>