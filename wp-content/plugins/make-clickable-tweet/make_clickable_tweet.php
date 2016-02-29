<?php
/*
Plugin Name: Make Clickable Tweet
Description: A plugin for converting @user metions, #hashtags and URLs in a tweet into clickable links.
Version: 0.2
Author: ClarkLab
Author URI: http://clarklab.com
*/

function make_clickable_tweet($tweetcontent) {
	$tweetcontent = preg_replace("/((http)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweetcontent );
	$tweetcontent = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $tweetcontent );
	$tweetcontent = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $tweetcontent );
	return $tweetcontent;
}