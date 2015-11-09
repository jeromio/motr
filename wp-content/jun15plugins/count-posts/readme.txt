=== Count Posts ===

Contributors: hami
Tags: post, page, posts, pages, count, number, category
Donate link: http://code.andrewhamilton.net/donate/
Requires at least: 2.1
Tested up to: 2.6.2
Stable tag: 1.0

Count Posts is a simple plugin that provides a template function to count posts, as well as filtering by category and over time in days. The function can either return or display the result.

== Description ==

*Count Posts* is a simple plugin that provides a template function to count posts, as well as filtering by category and over time in days. The function can either return or display the result.

_Based off Clint Howarth's `count_posts`_


== Installation ==

This section describes how to install the plugin and get it working.

1. Download the archive and expand it.
2. Upload the *count-posts* folder into your *wp-content/plugins/* directory
3. In your *WordPress Administration Area*, go to the *Plugins* page and click *Activate* for *Count Posts*

Once you have *Count Posts* installed and activated you can call the function in your WordPress template.

== Changes ==

*1.0*

1. Initial release.

== Count Posts Function ==

The Count Posts function can be called anywhere in a WordPress template and has three variables:

*`count_posts($category, $daysago, $display)`*

*$category*

The `$category` variable is used to count the number of posts in a category (or Term in WordPress 2.5 or greater). Simply put the name of your category in the function call e.g.

*`count_posts('mycategory')`*

If it is left blank, which is the default, the function will count post in all categories.

*$daysago*

The `$daysago` variable is used to count the number of posts from a specified number of days ago e.g.

*`count_posts('mycategory', '8')`*

If it is left blank or set to `0`, the default, the function will count all posts.

*$display*

The `$display` variable is used to either output the count number or to return it.

*`count_posts('mycategory', '8', true)`*

If it is left blank or set to `true`, the default, the function will output the number of posts to the page. If it is set to `false`, the function will return the number.

== Known Issues ==

No known issues at this time. 

If you find any bugs or want to request some additional features for future releases, please log them the [projects tracker page](http://tracker.andrewhamilton.net/projects/show/count-posts)
