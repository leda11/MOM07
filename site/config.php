<?php
/**
* Site configuration, this file is changed by user per site.
*
*/

/**
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);

// handle outgoing links (06)
/**
* What type of urls should be used? 
*
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$ha->config['url_type'] = 1;

/**
* Set a base_url to use another than the default calculated
*/
$ha->config['base_url'] = null;

/**
* Define session name
*/
//$ha->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
$ha->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);

$ha->config['session_key']  = 'handy';

/**
 * Create user ok
 */
$ha->config['create_new_users']= true;;

/**
* Set database(s).
*/
$ha->config['database'][0]['dsn'] = 'sqlite:' . HANDY_SITE_PATH . '/data/.ht.sqlite';

/**
* How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
*/
// choose between md5 plain, md5, md5salt, sha1, sha1salt
$ha->config['hashing_algorithm'] = 'sha1salt';
    
/**
* Define server timezone
*/
$ha->config['timezone'] = 'Europe/Stockholm';

/**
* Define internal character encoding
*/
$ha->config['character_encoding'] = 'UTF-8';

/**
* Define language
*/
$ha->config['language'] = 'en';


/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
*/
    $ha->config['debug']['handy'] = false;
    $ha->config['debug']['db-num-queries'] = true;
    $ha->config['debug']['db-queries'] = true;
    $ha->config['debug']['session'] = false;
    $ha->config['debug']['timer'] = true;
    
    //$ha->config['debug']= 0;

/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example:
* the url 'developer/dump' would instantiate the controller with the key "developer", that is
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $ha->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
$ha->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true, 'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true, 'class' => 'CCGuestbook'),
  'user'	  => array('enabled' => true, 'class' => 'CCUser' ),
  'acp'       => array('enabled' => true, 'class' => 'CCAdminControlPanel' ), 
  'content'   => array('enabled' => true, 'class' => 'CCContent' ), 
  'blog'   => array('enabled' => true, 'class' => 'CCBlog' ), 
  'page'   => array('enabled' => true, 'class' => 'CCPage' ), 
  'theme'   => array('enabled' => true, 'class' => 'CCTheme' ), 
  'module'   => array('enabled' => true, 'class' => 'CCModules' ),
  'my' => array('enabled' => true,'class' => 'CCMycontroller'),
);

/**
* Define a routing table for urls.( updateCHandy::FrontController
*
* Route custom urls to a defined controller/method/arguments
*/
    $ha->config['routing'] = array(
      'home' => array('enabled' => true, 'url' => 'index/index'),
      
    );

   /**
    * Define menus.
    *
    * Create hardcoded menus and map them to a theme region through $ha->config['theme'].
    */
    $ha->config['menus'] = array(
      'navbar' => array(
        'home'      => array('label' =>'Home','url'=>'home'),
        'modules'   => array('label' =>'Modules','url'=>'module'),
        'content'   => array('label' =>'Content','url'=>'content'),
        'guestbook' => array('label' =>'Guestbook','url'=>'guestbook'),
        'blog'      => array('label' =>'Blog','url'=>'blog'),
      ),
      'my-navbar' => array(
      'home' => array('label'=>'About Me', 'url'=>'my'),
      'blog' => array('label'=>'My Blog', 'url'=>'my/blog'),
      'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
  ),
 );    
 
    

/**
* Settings for the theme.	
*/
$ha->config['theme'] = array(
  // The name of the theme in the theme directory
  'path'       => 'site/themes/mytheme',
  //'path'    => 'themes/grid',
   'parent'    => 'themes/grid',
  //'name'    => 'core',
   //'name'     => 'grid',
   //'stylesheet'  => 'style.php',   // Main stylesheet to include in template files when prooduction piont to style.css
   'stylesheet' => 'style.css',
   'template_file'   => 'index.tpl.php',   // Default template file, else use default.tpl.php
   
   // A list of valid theme regions
   'regions' => array('navbar','flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
    ),
   
   'menu_to_region' => array('navbar'=>'navbar'), 
   // Add static entries for use in the template file.
     'data' => array(
        'header' => 'Handy',
        'slogan' => 'A PHP-based MVC-inspired CMF',
        'favicon' => 'logo_80x80.png',
        'logo' => 'logo_80x80.png',
        'logo_width'  => 80,
        'logo_height' => 80,
        'footer' => '<p>Handy &copy; by Lena Dackhammar</p>',
      ),
);

    /**
    * Settings for the theme. The theme may have a parent theme.
    *
    * When a parent theme is used the parent's functions.php will be included before the current
    * theme's functions.php. The parent stylesheet can be included in the current stylesheet
    * by an @import clause. See site/themes/mytheme for an example of a child/parent theme.
    * Template files can reside in the parent or current theme, the CHandy::ThemeEngineRender()
    * looks for the template-file in the current theme first, then it looks in the parent theme.
    *
    * There are two useful theme helpers defined in themes/functions.php.
    *  theme_url($url): Prepends the current theme url to $url to make an absolute url.
    *  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
    *
    * path: Path to current theme, relativly HANDY_INSTALL_PATH, for example themes/grid or site/themes/mytheme.
    * parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
    * stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
    * template_file: Set the default template file, defaults to default.tpl.php.
    * regions: Array with all regions that the theme supports.
    * data: Array with data that is made available to the template file as variables.
    *
    * The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
    * available to the template files.
    */
/*    $ha->config['theme'] = array(
      'path'            => 'site/themes/mytheme',
      'parent'          => 'themes/grid',
      'stylesheet'      => 'style.css',
      'template_file'   => 'index.tpl.php',
      'regions' => array('flash','featured-first','featured-middle','featured-last',
        'primary','sidebar','triptych-first','triptych-middle','triptych-last',
        'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
        'footer',
      ),
      'data' => array(
        'header' => 'Lydia',
        'slogan' => 'A PHP-based MVC-inspired CMF',
        'favicon' => 'logo_80x80.png',
        'logo' => 'logo_80x80.png',
        'logo_width'  => 80,
        'logo_height' => 80,
        'footer' => '<p>Lydia &copy; by Mikael Roos (mos@dbwebb.se)</p>',
      ),
    );
*/

