<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
* Plugin Name: Media List
* Plugin URI: https://en-gb.wordpress.org/plugins/media-list/
* Description: Adds the ability to quickly list posts or media attached to a page with pagination via [medialist] shortcode.
* Version: 1.4.1
* Author: D. Relton
* Author URI: https://profiles.wordpress.org/mauvedev/
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: media-list
**/
function media_list_load_plugin_textdomain() {
load_plugin_textdomain( 'media-list', false, dirname( plugin_basename(__FILE__)) . '/languages/');
}
add_action( 'plugins_loaded', 'media_list_load_plugin_textdomain' );
if ( !class_exists( 'medialistpluginclass' ) ) { //check if class is already taken.
class medialistpluginclass {
//constructor
function __construct() {
	add_action('init', array( $this, 'medialistmainplugininit' )); //initialise shortcodes
	add_action('the_posts', array( $this, 'checkformedialistshortcode' )); //perform the check when the_posts() function is called
	add_action( 'init' , array( $this, 'medialist_add_taxonomies' ));
}
function medialist_add_taxonomies() {
    register_taxonomy_for_object_type( 'category', 'attachment' );
	register_taxonomy_for_object_type( 'category', 'page' );
	register_taxonomy_for_object_type( 'post_tag', 'page' );
	register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
function checkformedialistshortcode($posts) {
    if ( empty($posts) )
        return $posts;
    //false because we have to search through the posts first
    $found = false;
    //search through each post
    foreach ($posts as $post) {
        //check the post content for the short code
        if ( stripos($post->post_content, '[medialist') )
            //we have found a post with the short code
            $found = true;
            //stop the search
            break;
        }
    if ($found){
        //$medialistdirurl contains the path to the plugin folder
        $medialistdirurl = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'media-list',$medialistdirurl.'styles/styles.css' );
		wp_register_script( 'media-list',$medialistdirurl.'js/medialistpaging.js', array('jquery') );
		wp_enqueue_script( 'media-list' );
		//Localize the script with new data
		wp_localize_script(
		'media-list',
		'passtojq',
		array(
			'vpages' => __('Pages','media-list' ),
			'voffsep' => __('of','media-list' ),
			'vprev' => __('Prev','media-list'),
			'vnext' => __('Next','media-list'),
			)
		);
    }
    return $posts;
}
function medialistgeturlfilesize($medialistquery, $medialistformatsize = true){
	$file_url = wp_get_attachment_url( $medialistquery->ID );
    $head = array_change_key_case(get_headers($file_url, 1));
    //content-length of download (in bytes), read from Content-Length: field
    $clen = isset($head['content-length']) ? $head['content-length'] : 0;
    //cannot retrieve file size, return "-1"
    if (!$clen) {
        return;
    }
    if (!$medialistformatsize) {
        return $clen; 
		//return size in bytes
    }
    $size = $clen;
    switch ($clen) {
        case $clen < 1024:
            $size = $clen .' B'; break;
        case $clen < 1048576:
            $size = round($clen / 1024,1) .' KB'; break;
        case $clen < 1073741824:
            $size = round($clen / 1048576,1) . ' MB'; break;
        case $clen < 1099511627776:
            $size = round($clen / 1073741824,1) . ' GB'; break;
    }
    return $size; 
	//return formatted size
}
function medialiststitchmimes($medialistaddstitch){
		$mimetype = explode(",", $medialistaddstitch);
		$mimeappend = "";
		$i = 0;
		foreach ($mimetype as $mediatype) {
		//add comma so we can concatenate mime types when multiple types are defined in the shortcode
		if ($i > 0){
			$mimeappend	.= ",";
		}
		  switch ($mediatype) {
		  case "pdf":
			$mimeappend .= "application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,text/x-pdf";
			break;
		  case "xls":
		  case "excel":
			$mimeappend .= "application/vnd.ms-excel,application/vnd.oasis.opendocument.spreadsheet,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";  
			break;
		  case "doc":
			$mimeappend .= "application/doc,application/vnd.msword,application/vnd.ms-word,application/winword,application/word,application/x-msw6,application/x-msword,application/msword,application/vnd.oasis.opendocument.text,application/vnd.openxmlformats-officedocument.wordprocessingml.document";
			break;
		  case "ppt":
			$mimeappend .= "application/mspowerpnt,application/vnd-mspowerpoint,application/powerpoint,application/x-powerpoint,application/vnd.ms-powerpoint,application/mspowerpoint,application/ms-powerpoint,application/vnd.oasis.opendocument.presentation,application/vnd.openxmlformats-officedocument.presentationml.presentation";
			break;      
		  case "zip":
			$mimeappend .= "application/zip,application/x-zip,application/x-zip-compressed,application/x-compress,application/x-compressed,multipart/x-zip,application/rar,application/x-tar,application/x-7z-compressed";
			break;
		  case "text":
			$mimeappend .= "text/plain,text/csv,text/tab-separated-values,text/calendar,text/richtext,text/css,text/html";
			break;
		  case "audio":
			$mimeappend .= "audio/mpeg,audio/wav,audio/x-ms-wma,audio/midi";
			break;
		  case "images":
			$mimeappend .= "image/jpeg,image/gif,image/png,image/bmp,image/tiff,image/x-icon";
			break;
		  case "other":
			$mimeappend .= "application/sql,application/x-sql,text/sql,text/x-sql,application/octet-stream,application/sql,application/x-sql,text/sql,text/x-sql,application/xml,application/x-xml,text/xml,text/x-xml,application/x-msdownload";
			break;
		  default:
		    $mimeappend .= "image/x-icon";
			break;
			}
			$i++;
		}
		return $mimeappend;
}
function medialistgetthemimetype($medialistmediatype) {
	//check type from shortcode
	switch ($medialistmediatype) {
		case 'pdf':
			$mediatype = 'application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,text/x-pdf';
			break;
		case 'doc':
			$mediatype = 'application/doc,application/vnd.msword,application/vnd.ms-word,application/winword,application/word,application/x-msw6,application/x-msword,application/msword,application/vnd.oasis.opendocument.text,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
			break;
		case 'excel':
			$mediatype = 'application/vnd.ms-excel,application/vnd.oasis.opendocument.spreadsheet,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			break;
		case 'ppt':
			$mediatype = 'application/mspowerpnt,application/vnd-mspowerpoint,application/powerpoint,application/x-powerpoint,application/vnd.ms-powerpoint,application/mspowerpoint,application/ms-powerpoint,application/vnd.oasis.opendocument.presentation,application/vnd.openxmlformats-officedocument.presentationml.presentation';
			break;
		case 'zip':
			$mediatype = 'application/zip,application/x-zip,application/x-zip-compressed,application/x-compress,application/x-compressed,multipart/x-zip,application/rar,application/x-tar,application/x-7z-compressed';
			break;
		case 'text':
			$mediatype = 'text/plain,text/csv,text/tab-separated-values,text/calendar,text/richtext,text/css,text/html';
			break;
		case 'audio':
			$mediatype = 'audio/mpeg,audio/wav,audio/x-ms-wma,audio/midi';
			break;
	    case 'images':
			$mediatype = 'image/jpeg,image/gif,image/png,image/bmp,image/tiff,image/x-icon';
			break;
		case 'other':
			$mediatype = 'application/sql,application/x-sql,text/sql,text/x-sql,application/octet-stream,application/sql,application/x-sql,text/sql,text/x-sql,application/xml,application/x-xml,text/xml,text/x-xml,application/x-msdownload';
			break;
		default:
		    $mediatype = 'image/x-icon';
			break;
	}
}
function medialistumbrellamimetype($medialistquery) {
		//pulls the mime and match it to display the umbrella type.
		$iconbymime = get_post_mime_type();
		$type="";
		switch ($iconbymime) {
		  case "application/pdf":
		  case "application/x-pdf": 
		  case "application/acrobat": 
		  case "applications/vnd.pdf":
		  case "text/pdf":
		  case "text/x-pdf":
			$type = "pdf";
			break;
		  case "application/vnd.ms-excel":
		  case "application/vnd.oasis.opendocument.spreadsheet":
		  case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
			$type = "xls";
			break;
		  case "application/doc":
		  case "application/vnd.msword": 
		  case "application/vnd.ms-word":
		  case "application/winword":
		  case "application/word":
		  case "application/x-msw6":
		  case "application/x-msword":
		  case "application/msword":
		  case "application/vnd.oasis.opendocument.text":
		  case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			$type = "doc";
			break;
		  case "application/mspowerpnt":
		  case "application/vnd-mspowerpoint":
		  case "application/powerpoint":
		  case "application/x-powerpoint":
		  case "application/vnd.ms-powerpoint":
		  case "application/mspowerpoint":
		  case "application/ms-powerpoint":
		  case "application/vnd.oasis.opendocument.presentation":
		  case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
			$type = "ppt";
			break;      
		  case "application/zip":
		  case "application/x-zip":
		  case "application/x-zip-compressed":
		  case "application/x-compress":
		  case "application/x-compressed":
		  case "multipart/x-zip":
		  case "application/rar":
		  case "application/x-tar":
		  case "application/x-7z-compressed":
			$type = "zip";
			break;	
		  case "text/plain":
		  case "text/csv":
		  case "text/tab-separated-values":
		  case "text/calendar":
		  case "text/richtext":
		  case "text/css":
		  case "text/html":
			$type = "text";
			break;
		  case "audio/mpeg":
			$type = "mp3";
			break;
		  case "audio/wav":
			 $type = "wav";
			 break;
		  case "audio/x-ms-wma":
			$type = "wma";
			break;
			case "audio/midi":
			$type = "mid";
			break;
		  case "image/jpeg":
		    $type = "jpg";
			break;
		  case "image/gif":
		    $type = "gif";
			break;
		  case "image/png":
		    $type = "png";
			break;
		  case "image/bmp":
		    $type = "bmp";
			break;
		  case "image/tiff":
		    $type = "tiff";
			break;
		  case "image/x-icon":
			$type = "icon";
			break;
		  case "application/sql":
		  case "application/x-sql":
		  case "text/sql":
		  case "text/x-sql":
		  case "application/octet-stream":
			$type = "sql";
			break;
		  case "application/xml":
		  case "application/x-xml,":
		  case "text/xml":
		  case "text/x-xml":
			$type = "xml";
			break;
		  case "application/x-msdownload":
		  //case "application/octet-stream":
			$type = "exe";
			break;
		}
		if($type != "")
		{
		  return $type;
		}  else {
			$type = "Posted";
		  return $type;
		}	 
}
function medialistmainplugin($medialistatts = [], $content = null ) {
$medialistatts = array_change_key_case((array)$medialistatts, CASE_LOWER);
//unique ID ready per instance
$squid['instance'] = uniqid();
global $post; //post data
$mlout = ''; //output markup
$maxloop = 1; //set ready to count loop iterations.
$listofmimes='';
	//extract shortcode attributes.
    $attributes = shortcode_atts([
               'type' => 'attachment',
			   'mediatype' => 'excel,pdf,doc,zip,ppt,text,audio,images',
			   'order' => 'ASC',
			   'orderby' => 'title',
			   'category' => '',
			   'mediaitems' => 10,
			   'paginate' => 1,
			   'sticky' => 1,
			   'style' => 'ml-default',
			   'max' => 200,
			   'globalitems' => 0,
			   'author' => 'notset',
			   'search' => 0,
			   'tags' => '',
			   'rml_folder' => null // Real Media Library compatibility 
    ], $medialistatts);
        wp_enqueue_script('media-list');	
		//if post type changed alter steps
		if ( $attributes['type'] == 'attachment' ) { 
		$listofmimes = $this->medialiststitchmimes($attributes['mediatype']);
		$this->medialistgetthemimetype($attributes['mediatype']);
		}
	//build arguments for wp_query
	$args = array(
		'post_parent' => $post->ID,
		'post_type' => $attributes['type'],
		'author_name' => $attributes['author'],
        'post_status' => 'inherit',
        'post_mime_type' => $listofmimes,
		'posts_per_page' => -1,
		'order' => $attributes['order'],
		'orderby' => $attributes['orderby'],
		'category_name' => $attributes['category'],
		'ignore_sticky_posts' => $attributes['sticky'],
		'post_parent__not_in' => array(0),
		'tag' => $attributes['tags'],
		'rml_folder' => $attributes['rml_folder'] // Real Media Library compatibility
    );
	//check shortcode format & apply defaults if necessary
	foreach($attributes as $arraykey => $number) 
    {
        switch($arraykey ) 
        {
            case 'paginate':
				if(preg_match("/^[^0-9]*$/", $number)){
				$attributes['paginate'] = 1;
				}
                break;
            case 'mediaitems':
				if(preg_match("/^[^0-9]*$/", $number)){
				$attributes['mediaitems'] = 10;
				}
                break;
            case 'sticky':
				if(preg_match("/^[^0-9]*$/", $number)){
				$attributes['sticky'] = 1;
				}
                break;
			case 'max';
				if(preg_match("/^[^0-9]*$/", $number)){
				$attributes['max'] = 200;
				}
				break;
        }
    }
	//update args as needed
	if ($attributes['paginate'] == 0 ){
		$attributes['max'] = $attributes['mediaitems'];//when pagination is disabled we set the max value to the mediaitems value, so max looped items is max items displayed.
	} 
	if ($attributes['author'] == 'notset'){
		unset ($args['author_name']);
	}
	if (($attributes['globalitems'] == 1 && $attributes['type'] == 'attachment')){
		$args['post_status'] = 'any';
		unset ($args['post_parent__not_in']);
		unset ($args['post_parent']);
		unset ($args['ignore_sticky_posts']);
	} elseif ($attributes['type'] == 'attachment' ) {
		unset ($args['ignore_sticky_posts']);
	} elseif ($attributes['type'] == 'post') {
		unset ($args['post_parent__not_in']);
		unset ($args['post_parent']);
		unset ($args['post_mime_type']);
		$args['post_status'] = 'publish';
	}
	//instantiate new query instance.
    $medialistquery = new WP_Query( $args );
    //check that we have query results.
    if ( $medialistquery->have_posts() ) {
        //begin generating markup.
        $mlout .= '<section class="medialist-embedded-section">';
	    $mlout .= '<div mediajqref="medialist-construct" id="mlid-' . esc_attr($squid['instance']) . '"' . 'class="medialist ' . esc_attr($attributes['style']) . '" data-instance="' . esc_attr($squid['instance']) . '" data-token="' . esc_attr($attributes['mediaitems']) . '" data-paging="' . esc_attr($attributes['paginate']) . '">';
		if ($attributes['search'] == 1){
		$mlout .= '<div class="medialist-search-' . esc_attr($attributes['style']) . '"><input type="text" class="medialist-search ml-search-' . esc_attr($squid['instance']) . ' ml-search-' . esc_attr($attributes['style']) .'"><a class="medialist-gosearch">'. __('Search','media-list') .'</a></div>';
		}
		$mlout .= '<ul class="ml-ul-' . esc_attr($attributes['style']) . '" style="list-style-type:none;">';
        //start looping over the query results and stop when maxloop iterations reaches max set in array.
        while ( $medialistquery->have_posts() && $maxloop <= $attributes['max'] ) {
			  $medialistquery->the_post();
				$mlout .= '<li class="ml-li-' . esc_attr($attributes['style']) . '">';
				$mlout .= '<a class="ml-item-' . esc_attr($attributes['style']) . ' medialist-'. esc_attr($attributes['style']) .' ' . $this->medialistumbrellamimetype($medialistquery) . '"';
				$mlout .= 'href="';
				if ( $attributes['type'] == 'attachment' ) {
					
					$mlout .= wp_get_attachment_url ( $medialistquery->ID );
				} else
				{
					$mlout .= get_permalink();
				}
					if (in_array($this->medialistumbrellamimetype($medialistquery), array("sql","xml","exe"))){
						//change switch to allow certain files to be downloaded properly with the correct extension.
						$mlout .= '"download="' . get_the_title() . '.' . $this->medialistumbrellamimetype($medialistquery) . '">';
					}
					else {
						$mlout .= '"target="_blank">';
					}
					$mlout .= get_the_title();
					$mlout .= ' <span class="ml-details-' . esc_attr($attributes['style']) . '">';
					$mlout .= $this->medialistumbrellamimetype($medialistquery) .' ';
				if ( $attributes['type'] == 'attachment' ) { //if post type changed alter steps
					$mlout .= $this->medialistgeturlfilesize($medialistquery);
					$mlout .= '</span></a></li>'; 
				} else {
					$mlout .= get_the_date($medialistquery->ID);
					$mlout .= '</span></a></li>'; 
				}
				$maxloop++; //iterate counter
        }
			//close elements
			$mlout .= '</ul></div>';
			$mlout .= '</section>'; 
    } else {
        //output message to let user know that no posts were found.
        $mlout = '<section class="medialist-embedded-section">';
		$mlout .= '<div class="medialist-alert" style="background-color:#2196F3;">';
		$mlout .= '<span class="medialist-closebtn" ';
		$mlout .= 'onclick="';
		$mlout .= "this.parentElement.style.display='none';";
		$mlout .= '"';
		$mlout .= '">&times;</span><strong>Info! </strong>'. __('No posts or attachments to display.','media-list') .'</div>';
        $mlout .= '</section>';
		//end generating markup.
    }
	wp_reset_postdata();
	return $mlout;
}
function medialistmainplugininit() {
	add_shortcode( 'medialist', array( $this,'medialistmainplugin' ));
}
}//end class
//create object
new medialistpluginclass();
}
?>