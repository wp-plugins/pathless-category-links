﻿=== Pathless Category Links ===
Contributors: anothercoder
Donate link: http://www.anothercoder.com/donate
Tags: category, permalinks, 301 redirect, get_category, get_category_link, category link, top level category links, seo, search engine optimization
Requires at least: 2.0.2
Tested up to: 2.7
Stable tag: trunk

A WordPress plugin to remove the base category, e.g. the /category/, folder from your category links. 301 Redirects are added for old links.

== Description ==

This plugin will remove the */category/* base directory that WordPress adds to category links. It’ll also go beyond that and automatically 301 redirect the old */category/* links to the new, pathless category link.

Once installed, the plugin will **automatically** change all your category links on the website. You do not need to modify any of your WordPress themes or code.

There is also an admin panel that will let you enable/disable 301 redirects, as well as change the text to remove from the category link.

*Change Log*

**Version 1.1  (Dec-31-2008)** - Added the `Update category links with pages` setting to modify category links that contain */page/* to become */?paged*
Version 1.0 (Dec-30-2008) - Initial release

== Installation ==

1. Upload `pathless-category-links.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the `Settings > Pathless Category Links` section to modify plugin settings

== Frequently Asked Questions ==

= What if other links on my site contain /category/? =

You can disable the automatic 301 redirect feature via the settings. If it is enabled, then ANY link that contains the base category directory (by default */category/*) will be redirected with the base category directory removed.

= It's not working? =

The first thing to check is your `permalink settings`. Is your permalink structure `/%postname%`? If so, the plugin will not work because WordPress will not be able to tell the difference between a category page and a post page without the `/category/` in the URL. I would recommend the permalink settings: `/%category%/%postname%`, which is what I use.

If your permalink settings are okay, then follow these steps.

1. Disable the plugin
1. Go to your category link, something like `http://www.anothercoder.com/category/news`.
* Is that working? If not, check your other plugins and WordPress installation as something is wrong there.
1. Manually remove the `/category/` from your link and go to that URL, something like `http://www.anothercoder.com/news`
* Is that working? If not, check your other plugins and WordPress installation
1. Once you can get the URL without `/category/` working manually, re-enable the plugin and you'll be all set.

You can also report bugs and get advice on the [Pathless Category Links](http://www.anothercoder.com/wordpress/pathless-category-permalinks-plugin "Pathless Category Links homepage") page on blog.

= Why do my category links contain `/?paged=[0-9]`? =

WordPress does not allow the following link `http://www.anothercoder.com/news/page/2`, it only works with `http://www.anothercoder.com/category/news/page/2`. So, this is a work around to turn ONLY category links that have pages to be rewritten as `http://www.anothercoder.com/news/?paged=2` instead.

The `Update category links with pages` setting in the `Settings > Pathless Category Links` section allows you to disable this work around. But, if it is disabled, then your links will not have the base category directory removed, nor will a 301 redirect be performed.

So, it's up to you, do you want: `http://www.anothercoder.com/category/news/page/2` or do you want `http://www.anothercoder.com/news/?paged=2`

== Screenshots ==

1. The settings admin for the plugin. You can easily disable 301 redirects or change the base category directory, which is */category/* by default.

== Primary Benefits ==

* Remove category base directories, */category/* by default, from all category links on the website. So, `http://www.anothercoder.com/category/news` will automatically become `http://www.anothercoder.com/news`
* Pathless category permalinks provide improved SEO
* Automatically 301 redirect old links, e.g. `http://www.anothercoder.com/category/news`, to the new pathless category permalink, e.g. `http://www.anothercoder.com/news`

Post comments and get help on the [Pathless Category Links](http://www.anothercoder.com/wordpress/pathless-category-permalinks-plugin "Pathless Category Links homepage") page of my blog.