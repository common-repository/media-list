=== Medialist ===
Contributors: mauvedev
Donate Link: https://paypal.me/mauvedev?locale.x=en_GB
Tags: attachments, post list, file list, document list, media list
Requires at least: 4.2
Tested up to: 6.5
Requires PHP: 5.4
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Medialist will find page attachments (or) posts using criteria you provide in a shortcode and then display them elegantly styled anywhere on a page. Ideal for displaying policies, accompanying documents, newsletters, supporting documentation, media, Posts and more. 

== Description ==

Medialist plugin is designed to make displaying posts or attached page media, documents and more an easy process. Upload your content, attach it to a page, then place a medialist shortcode to display a neat list of items. You can customise the shortcode to only display a certain category or a certain number of items if you wish and also alter the style to suit your page.

== Features ==

**Note:** This plugin does not add a menu item to the wordpress dashboard, functionality and customisation is purely in the form of a shortcode.

* Incredibly lightweight
* List posts and posts by author
* List page attachments
* List posts and attachments by tag
* Define a category of items to display
* Define how many items to display
* Define the order in which items are displayed
* Toggle pagination on/off
* Multiple built-in styles
* Displays the item type with identifiable icons, with the items download size
* Place the shortcode anywhere on a page/post
* Ability to use the shortcode many times on a single page. You can split categories, **On the same page**, thats fun.
* Ability to toggle sticky posts on/off
* Ability to paginate the list of items after a defined number of items
* Ability to search for items in the list
* **NEW:** Compatible with RML Real Media Library Plugin.

== How to use the shortcode ==

Shortcodes are a simple way to add functionality to any wordpress page.
To use this plugins shortcode you need to write the shortcode into the page editor or guttenberg block where you want your item list to appear.

1. For a default list write **[medialist]** with the square brackets included (this will display all attached media in a list on the page and will display pagination at 10 or more items).
2. To customise the shortcode we add some keywords, for example **[medialist order= orderby= category= mediaitems= paginate= style=]**. After each equals(=) sign, we need to provide a customisation. All possible customisations are listed below.
**Example** 
**[medialist type=post order=DESC orderby=date category=recipes,cakes]**
The above example will display a list of most recent posts by date in descending order that have recipes and or cakes as the category.

== Customise the shortcode with additional keywords ==

**Note:** Some keywords can have multiple options, ensure they are seperated by a comma i.e [medialist mediatype=pdf,audio].

* **type=attachment** (or) **post**
* **mediatype=excel,pdf,doc,zip,ppt,text,audio,images,other** (You can use more than 1 here.)
* **order=ASC** (or) **DESC**
* **orderby**=none, ID, author, title, name, type, date, modified, parent, rand, comment_count
* **category=uncategorized,**(any defined category taxonomy assigned to media or post items, you can use more than 1 here.)
* **mediaitems=10** (Provide a number of items you wish to display in the list before pagination)
* **paginate=0** (Setting paginate to 0 will disable pagination, mediaitems number will be the max items displayed, default is 10)
* **style=ml-default** (Various built-in styles, write one of the style names listed below.)
* **author=author-username-here** (This will display posts uploaded by the specified author, case sensitive.)
* **search=1** (Setting search to 1 will enable a basic search facility.)
* **tags=** (any defined tag assigned to media or post items, you can use more than 1 here.)
* **rml_folder=(folder ID)** You can find the folder ID by selecting a folder, and click the three dots on the folder toolbar. A dialog opens and in the bottom right corner there is a text label with the ID.

**Note:** If using the RML Real Media Library Plugin, attach your files within RML folders to the desired page as normal or use the **globalitems=1** attribute.
**Other:** The mediatype (other) currently supports exe,sql & xml files.

Available styles

* ml-metro-light-green
* ml-metro-green
* ml-metro-magenta
* ml-metro-light-purple
* ml-mauve
* ml-taupe
* ml-sienna
* ml-white

== Override options for shortcode ==

For special use cases, you may want to override parts of the plugin using the following shortcode keywords.

* **sticky=0** (By default sticky posts will be ignored, setting this to 0 will pin sticky posts to the top of the list.)
* **max=200** (By default the plugin will only add 200 items to a list. You can override this by setting **max=** to a larger number. Or similarly a smaller number.)
* **globalitems=1** (By default attachments from the current page (or) post are able to be displayed. Setting this attribute to **1** will allow the list to display all items in the Media Library. It is **recommended** to set a category attribute first before using this override option.)

**Example** [medialist sticky=0 max=1000 mediaitems=10 type=post order=DESC orderby=date category=recipes]
The example will now pin sticky posts to the top and will also display up-to a thousand items and because mediaitems=10 there will be 10 items per page for a total of 100 possible pages.

== The defaults ==

A medialist will by default have the following features unless changed in your shortcode with keywords

* Pagination Enabled
* Display a maximum of 200 items
* Sticky posts will be ignored
* A total of 10 items will display per list and paginate for items over this number
* Generate a list of the mediaitems attached to the current page only and of any category
* Organise each list in ascending order by title

**Found a bug or incorrect translation?** Feel free to open a Support Topic.

If you have downloaded **Medialist** and are actively using it on your site, consider writing a review, let me know what you think.

**Thank You**

== Installation ==

1. Upload the **media-list** folder to the **/wp-content/plugins/** directory.
2. Activate the plugin through the **Plugins** menu in WordPress.

== Frequently Asked Questions ==

= What file types are supported? =

At the moment the following file types are supported - pdf,doc,ppt,xls,txt,csv,cal,mp3,wav,wma,mid,jpg,gif,png,bmp,tiff,icon,odt,odp,ods,exe,sql,xml


= How do I add a category to media items in WordPress? =

Starting with version 1.2.0 the feature to list by category is available for both attachments and posts. Assign a category from the Media library and on the Page or Post Settings as you normally would. 

= I'm not seeing the list update when using page builders? =

The lists will initially load once on page-load, when the shortcode is changed you won't always see the changes in the page builder automatically. To see all the changes when editing a list shortcode, its best to preview the page.

= I've added the shortcode to a page, but it isn't displaying my attachment? =

Make sure that the attachment you have uploaded into the **Media Library** has been attached to the page the shortcode has been placed on. In the case of Posts, check you have assigned a category and your shortcode syntax is correct.

== Screenshots ==

1. Medialist using the ml-white style
2. Medialist using the default style.
3. Medialist using the ml-taupe style
4. Medialist using the ml-metro-light-green style displaying zips only and pagination disabled
5. Medialist using the ml-metro-magenta style displaying zips only and pagination disabled
6. Medialist using the ml-sienna style displaying zips only and pagination disabled
7. Medialist using the ml-mauve style displaying a list of posts in ascending order by title
8. Medialist using the ml-metro-light-purple style displaying a list of posts in ascending order by title
9. Medialist using the ml-metro-green style displaying a list of posts in ascending order by title
10. Medialist using the default style with search enabled.
11. Shows example files attached to a page and the categories column when in within Media Library list view.

== Changelog ==
= 1.4.1 =
* Tested in Wordpress upto version 6.5 and PHP version 8.2.12
= 1.4.0 =
* A reported Cross Site Vulnerability detected, updated to fix. 
= 1.3.8 =
* Added suppot for .mid files
= 1.3.7 =
* Specific file types prevented from opening new tab, will now go direct to download.
* Some CSS optimisations
= 1.3.6 =
* CSS Accompanying icons update.
= 1.3.5 =
* Added support for exe, sql, xml.
= 1.3.4 =
* Request for compatibility for Real Media Library added. Thanks Matthias(RML dev).
= 1.3.3 =
* List will no longer display -1 when the file size isnt known.
* A resolution as low as 375x667 is now supported.
= 1.3.1 =
* Increased clickable space for list items.
* Increased whitespace around attachment size.
= 1.3.0 =
* Bumped up to version 1.3, media-list is now translatable via wordpress, yay.
* Fixed a minor bug caused when trying to save a page with an incorect mime in the shortcode.
