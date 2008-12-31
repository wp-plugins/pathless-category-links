=== Pathless Category Links ===
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

== Installation ==

1. Upload `pathless-category-links.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the `Settings > Pathless Category Links` section to modify plugin settings

== Frequently Asked Questions ==

= What if other links on my site contain /category/? =

You can disable the automatic 301 redirect feature via the settings. If it is enabled, then ANY link that contains the base category directory (by default */category/*) will be redirected with the base category directory removed.

== Screenshots ==

1. The settings admin for the plugin. You can easily disable 301 redirects or change the base category directory, which is */category/* by default.

== A brief Markdown Example ==

* Remove category base directories, */category/* by default, from all category links on the website. So, `http://www.anothercoder.com/category/news` will automatically become `http://www.anothercoder.com/news`
* Pathless category permalinks provide improved SEO
* Automatically 301 redirect old links, e.g. `http://www.anothercoder.com/category/news`, to the new pathless category permalink, e.g. `http://www.anothercoder.com/news`

Post comments and get help on the [Pathless Category Links](http://www.anothercoder.com/wordpress/pathless-category-permalinks-plugin "Pathless Category Links homepage") page of my blog.