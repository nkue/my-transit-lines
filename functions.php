<?php
/**
 * My Transit Lines functions and definitions
 *
 * @package My Transit Lines
 */
 
/* created by Johannes Bouchain, 2014-09-06 */
 
/* ### PLEASE NOTE ###
 * 1) all basic and general functions are included in this file. Functions for special My Transit Lines modules are (and for future stuff: should be) put into /modules/nameofmodule/filename.php|js|css|etc.
 * 2) all custom functions for this theme should start with the theme name abbreviation mtl: e.g. mtl-custom-function()
 * 3) please enqueue all scripts and styles for *all* modules using the respective section below
 * 4) please include all strings needed for l10n in the theme and theme module js files into the mtl_localize_script() funtion below
 */

 /**
 * include module functions files
 */
include( get_template_directory() . '/modules/mtl-login-register/mtl-login-register.php'); // Login/Register module
include( get_template_directory() . '/modules/mtl-admin-menu/mtl-admin-menu.php'); // dashboard admin section module
include( get_template_directory() . '/modules/mtl-proposal-form/mtl-proposal-form.php'); // new proposal form
include( get_template_directory() . '/modules/mtl-single-proposal/mtl-single-proposal.php'); // single proposal custom display
include( get_template_directory() . '/modules/mtl-tile-list/mtl-tile-list.php'); // proposal tile list with small maps
include( get_template_directory() . '/modules/mtl-custom-posttypes/mtl-custom-posttypes.php'); // create the custom posttypes necessary for the theme
include( get_template_directory() . '/modules/mtl-comment-notification/mtl-comment-notification.php'); // custom comment notification, to be extended
//include( get_template_directory() . '/modules/mtl-comment-editing/mtl-comment-editing.php'); // add comment editing functionality
include( get_template_directory() . '/modules/mtl-metaboxes/mtl-metaboxes.php'); // proposal meta boxes for dashboard post edit view
include( get_template_directory() . '/modules/mtl-flextiles/mtl-flextiles.php'); // flexible tiles e.g. for menues
include( get_template_directory() . '/modules/mtl-star-rating/mtl-star-rating.php'); // star rating functioanlity
include( get_template_directory() . '/modules/mtl-download-geojson/mtl-download-geojson.php'); // download geojson functioanlity
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'my_transit_lines_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function my_transit_lines_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on My Transit Lines, use a find and replace
	 * to change 'my-transit-lines' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'my-transit-lines', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'my_transit_lines_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // my_transit_lines_setup
add_action( 'after_setup_theme', 'my_transit_lines_setup' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/* END basic functions from underscores.me package generator */

/* BEGIN My Transit Lines functions */

/**
 * Enqueue scripts and styles for the My Transit Line theme.
 */
function my_transit_lines_scripts() {

	wp_enqueue_style( 'my-transit-lines-style', get_stylesheet_uri() );

	// get the style for the Openlayers Editor
	wp_enqueue_style('ole-style',get_template_directory_uri() .'/ole/theme/geosilk/geosilk.css',array());
	
	// enable jQuery
	wp_enqueue_script( 'jquery');
	
	// enable WP suggest script
	wp_enqueue_script( 'suggest' );
	
	// AJAX form plugin
	wp_enqueue_script( 'jquery-form', get_template_directory_uri() . '/js/jquery.form.min.js', array());

	wp_enqueue_script( 'my-transit-lines-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'my-transit-lines-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	// enqueue script for the MTL all proposals tile list
	wp_enqueue_script( 'my-transit-lines-tilelist', get_template_directory_uri() . '/modules/mtl-tile-list/mtl-tile-list.js', array());


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'my_transit_lines_scripts' );

/**
 * register all nav menus needed for the theme
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'my-transit-lines' ),
	'secondary' => __( 'Secondary Menu', 'my-transit-lines' ),
) );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function mtl_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'my-transit-lines' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Top Menu', 'my-transit-lines' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'mtl_widgets_init' );

/**
 * start session - needed for frontend form captcha and for other things.
 */
function mtl_session_start() {
  if( !session_id() ) { 
     session_cache_limiter ('private, must-revalidate');
     session_start();
  }
}
add_action( 'init', 'mtl_session_start' );

/**
 * save contents from get_template_part() to variable - needed e.g. for returning shortcode content.
 */
function mtl_load_template_part($template_name, $part_name=null) {
    ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

/**
 * save contents of wp_editor() to variable - needed e.g. for returning shortcode content.
 */
function mtl_load_wp_editor($content, $editor_id, $settings) {
    ob_start();
    wp_editor($content, $editor_id, $settings);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

/**
 * create domain output for automatically created e-mail addresses like noreply@...
 */
function mtl_maildomain() {
	$maildomain = str_replace('http://','',get_bloginfo('siteurl'));
	$maildomain = str_replace('www.','',$maildomain);
	$maildomain = explode('/',$maildomain);
	$maildomain = $maildomain[0];
	return $maildomain;
}

/**
 * change comment form defaults. More default stuff might be added here later
 */
function mtl_commentform_defaults($defaults) {
	$defaults['title_reply'] = __('Leave a comment for this post','my-transit-lines');
	return $defaults;
}
add_filter('comment_form_defaults', 'mtl_commentform_defaults');

/**
 * redirect all possible domains to the main domain, needed e.g. for correct webfont display. Is there a better solution?
 */
function mtl_main_domain_redirect() {
	$checkurl='http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$other_domains = array(); // put all possible domains that can be used except the site_url to this array, without http:// and www.
	$blog_domain = str_replace('http://www.','',get_bloginfo('siteurl'));
	foreach($other_domains as $other_domain) {
		if(str_replace($other_domain,$blog_domain,$checkurl) != $checkurl) {
			$new_url = str_replace($other_domain,$blog_domain,$checkurl);
			header("Location: $new_url");
			exit();
		}
	}
}

/**
 * l10n of all scripts needed in js files
 */
function mtl_localize_script($getVar = false) {
	$translatedStrings = array(
		'titleOPNV'=>__('Colored public transit map','my-transit-lines'),
		'attributionOPNV'=>__('Map data <a href="http://www.openstreetmap.org">Openstreetmap</a> (© OpenStreetMap contributors), Map: CC-BY-SA license (© by <a href="http://memomaps.de/">MeMoMaps</a>).','my-transit-lines'),
		'titleTransport'=>__('Transport map','my-transit-lines'),
		'attributionTransport'=>__('&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles courtesy of <a href="http://www.opencyclemap.org">Andy Allan</a>','my-transit-lines'),
		'titleOSM'=>__('Openstreetmap (Mapnik)','my-transit-lines'),
		'vectorLayerTitle'=>__('Line proposal (vector data)','my-transit-lines'),
		'fitToMap'=>__('Fit proposition to map','my-transit-lines'),
		'buildLine'=>__('Build line','my-transit-lines'),
		'buildStations'=>__('Build stations','my-transit-lines'),
		'editObjects'=>__('Edit line/stations','my-transit-lines'),
		'selectObjects'=>__('Select line/stations','my-transit-lines'),
		'moveObjects'=>__('Move selected line/stations','my-transit-lines'),
		'deleteObjects'=>__('Delete selected line/stations','my-transit-lines'),
		'baselayersTitle'=>__('Base layers','my-transit-lines'),
		'overlaysTitle'=>__('Overlays','my-transit-lines'),
		'changeToSubmit'=>__('Please select another tool on the map to submit your proposal.','my-transit-lines')
	);
	$localizeScript = '<script type="text/javascript">'."\r\n".'/* <![CDATA[ */'."\r\n".'var objectL10n = {';
	$countValues = 0;
	foreach($translatedStrings as $key => $value) {
		$localizeScript .= '"'.$key.'":"'.addslashes($value).'"';
		if($countValues<sizeof($translatedStrings)-1) $localizeScript .= ',';
		$countValues++;
	}
	$localizeScript .= '};'."\r\n".'/* ]]> */'."\r\n".'</script>'."\r\n";
	if($getVar) return $localizeScript;
	else echo $localizeScript;
}

/**
 * add custom post type mtlproposal. If new custom post types are created within the theme, add them to the array
 */
if (!function_exists('my_theme_filter')) {
    function my_theme_filter( $query ){

    if ( $query->is_main_query() )
        if ( $query->get( 'tag' ) OR is_search() )
            $query->set( 'post_type', array( 'mtlproposal' ) );

    return $query;
}}
add_filter( 'pre_get_posts', 'my_theme_filter' );

/**
 * custom post meta display
 */
function mtl_posted_on2() {
	global $post;
	$time_string = get_the_date( 'd.m.Y' );
	unset($author);
	if(get_post_meta($post->ID,'author-name',true)) $author = get_post_meta($post->ID,'author-name',true);
	else $author = esc_html( get_the_author() );
	printf( __( '<span class="posted-on">%1$s</span>, <span class="byline"> by %2$s</span>', 'my-transit-lines' ),
		$time_string, 
		$author
		
	);
}

/**
 * make all users selectable for user dropdown in admin edit proposal page
 */
/* DEACTIVATED BECAUSE OF FATAL PROBLEMS ON BULK EDITING OF POSTS!!! */
/*add_filter('wp_dropdown_users', 'mtl_switch_user');
function mtl_switch_user()
{
    global $post; // remove if not needed
    //global $post is available here, hence you can check for the post type here
    $users = get_users();

    echo'<select id="post_author_override" name="post_author_override" class="">';
    echo '<option></option>';
    foreach($users as $user)
    {
        echo '<option value="'.$user->ID.'"';

        if ($post->post_author == $user->ID){ echo 'selected="selected"'; }

        echo'>';
        echo $user->user_login.'</option>';     
    }
    echo'</select>';

}*/

/**
 * add tinyMCE editor to comment form
 */
function gk_comment_form( $fields ) {
    ob_start();
	$settings = array( 'media_buttons' => false,  'textarea_name' => 'comment','teeny'=>true,'tinymce' => array( 'height' => 200 ));
    wp_editor( '', 'comment', $settings);
    $fields['comment_field'] = ob_get_clean();
    return $fields;
}
add_filter( 'comment_form_defaults', 'gk_comment_form' );

// wp_editor doesn't work when clicking reply. Here is the fix.
add_action( 'wp_enqueue_scripts', '__THEME_PREFIX__scripts' );
function __THEME_PREFIX__scripts() {
    wp_enqueue_script('jquery');
}
add_filter( 'comment_reply_link', '__THEME_PREFIX__comment_reply_link' );
function __THEME_PREFIX__comment_reply_link($link) {
    return str_replace( 'onclick=', 'data-onclick=', $link );
}
add_action( 'wp_head', '__THEME_PREFIX__wp_head' );
function __THEME_PREFIX__wp_head() {
?>
<script type="text/javascript">
  jQuery(function($){
    $('.comment-reply-link, .comment-edit-link').click(function(e){
      e.preventDefault();
      var args = $(this).data('onclick');
      args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
      args = args.split(',');
      tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
      addComment.moveForm.apply( addComment, args );
      tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
    });
  });
</script>
<?php
}

/* fallback: emtpy the needed content hook for tile list if rating not activated */
if(!function_exists('mtl_tiles_rating_output')) {
	function mtl_tiles_empty_content($content) {
		global $post;
		if(get_post_type($post->ID)=='mtlproposal') return;
		else return $content;
	}
	add_action('the_content','mtl_tiles_empty_content');
}

// create realization horizon custom taxonomy
//hook into the init action and create_implementation_horizon_taxonomy when it fires
add_action( 'init', 'create_implementation_horizon_taxonomy', 0 );

//create a custom taxonomy name it topics for your posts
function create_implementation_horizon_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Implementation Horizon', 'taxonomy general name','my-transit-lines' ),
    'singular_name' => _x( 'Implementation Horizon', 'taxonomy singular name','my-transit-lines' ),
    'search_items' =>  __( 'Search items','my-transit-lines' ),
    'all_items' => __( 'All items','my-transit-lines' ),
    'parent_item' => __( 'Parent item','my-transit-lines' ),
    'parent_item_colon' => __( 'Parent item:','my-transit-lines' ),
    'edit_item' => __( 'Edit Implementation Horizon','my-transit-lines' ), 
    'update_item' => __( 'Update Implementation Horizon','my-transit-lines' ),
    'add_new_item' => __( 'Add New Implementation Horizon','my-transit-lines' ),
    'new_item_name' => __( 'New Implementation Horizon Name','my-transit-lines' ),
    'menu_name' => __( 'Implementation Horizons' ),
  ); 	

// Now register the taxonomy
  register_taxonomy('horizon',array('mtlproposal'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => false,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'horizon' ),
  ));
}

// current page url
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
