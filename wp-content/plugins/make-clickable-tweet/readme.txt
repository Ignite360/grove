=== Plugin Name ===
Contributors: clarklab
Donate Link: http://clarklab.com/
Tags: twitter, tweet, make_clickable, regex
Requires at least: 2.7
Tested up to: 3.3.1
Stable tag: trunk

A plugin for converting @user mentions, #hashtags and URLs in a tweet into clickable links.

== Description ==

In a recent project, I had a bunch of tweet content saved and needed a way to convert all URLs, hashtags, and user mentions into clickable links. I found that WordPress has a built-in function called [make_clickable()](http://codex.wordpress.org/Function_Reference/make_clickable), but it just works with plain URLs, so I took it one more step to make_clickable_tweet(). It's basically a regular expression wrapped in a simple function, but I figured someone else out there might need it.

== Installation ==

Using the plugin is pretty dang simple, as the function only has one parameter: the content you wanted returned in a clickable form. To use the plugin, simply activate it then start using the function in your templates like so:

`<?php make_clickable_tweet($tweetcontent); ?>`

Of course, that above function will simply return the tweet content, if you'd like to display it in your templates you might want to toss an echo in front there.

If you want to be super-duper safe (and smart), you might want to wrap the whole thing

`<?php
if (function_exists('make_clickable_tweet'))
{
make_clickable_tweet($tweetcontent);
}
?>`

== Frequently Asked Questions ==

= Why did you make this? =

I had a bunch of tweet data that I needed to render like a real tweets.

== Screenshots ==

None yet (or probably ever)

== Changelog ==

= 0.2 =

Changed order of regular expressions.

= 0.1 =

Release

== Upgrade notice ==

None yet (or probably ever)