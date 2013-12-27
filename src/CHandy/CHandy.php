<?php

/**
* Main class for Handy, holds everything.
*
* @package HandyCore
*/
class CHandy implements ISingleton {

   /**
   * members
   *
   */
   
   private static $instance = null;
  
   public $config = array();
   public $request;
   public $data;
   public $db;
   public $views;
   public $session;
   public $user;
   public $timer = array();


   /**
    * Constructor
    */
   protected function __construct() {
   	  //time the page generation. added 11/11
   	   $this->timer['first'] = microtime(true); 
   	   
      // include the site specific config.php and create a ref to $ha to be used by site/config.php
      $ha = &$this;
      require(HANDY_SITE_PATH.'/config.php');
      
     //Start Session
      session_name($this->config['session_name']);
      session_start();
      $this->session = new CSession($this->config['session_key']);
      $this->session->PopulateFromSession();
      
      //Set default date/time-zone
      date_default_timezone_set($this->config['timezone']);     
     
     // create data base 
      if(isset($this->config['database'][0]['dsn'])) {
        $this->db = new CMDatabase($this->config['database'][0]['dsn']);
     }
     // Create a container for all views and theme data
     $this->views = new CViewContainer();
     // Create a object for the user
     $this->user = new CMUser($this);    
  }
   
//..............................................................................
   
   /**
    * Singleton pattern. Get the instance of the latest created object or create a new one.
    * @return CHandy The instance of this class.
    */
   public static function Instance() {
      if(self::$instance == null) {
         self::$instance = new CHandy();
      }
      return self::$instance;
   }
   
//..............................................................................
      
   /**
    * Frontcontroller, check url and route to controllers.
    */
  public function FrontControllerRoute() {  
    // Take current url and divide it in controller, method and parameters
    // part in fixing base_url mom05
    $this->request = new CRequest($this->config['url_type']);// mom06-3 Mos har med denna    
    $this->request->Init($this->config['base_url'], $this->config['routing']);//tillägg för  MOM07 part 03         
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;                       

    // Is the controller enabled in config.php?
    $controllerExists    = isset($this->config['controllers'][$controller]);
    $controllerEnabled    = false;
    $className             = false;
    $classExists           = false;

    if($controllerExists) {
      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
      $className            = $this->config['controllers'][$controller]['class'];
      $classExists          = class_exists($className);
    }  
    // Step 3
    // Check if there is a callable method in the controller class, if then call it
    // Anropa med PHP reflection
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      
      
      if($rc->implementsInterface('IController')) {
      	$formattedMethod = str_replace(array('_', '-'), '', $method);//test kommentera bort 9/11
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);        
          if($methodObj->isPublic()) {//9/11 la till denn a if-elsesats
            $methodObj->invokeArgs($controllerObj, $arguments);
          } else {
            die("404. " . get_class() . ' error: Controller method not public.');
          }
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    }
    else {
      die('404. Page is not found.');
    }

  }

//..............................................................................
   
    /**
    * ThemeEngineRender, renders the reply of the request.
    */
  public function ThemeEngineRender() {
  	//1.. Save to session before output anything
    $this->session->StoreInSession();
  	
    //2.. is theme enabled?
    if(!isset($this->config['theme'])) { return;} 

    //3..  Get the paths and settings for the theme
    //$themePath    = HANDY_INSTALL_PATH . "/themes/{$themeName}";
    //$themeUrl = $this->request->base_url . "themes/{$themeName}";
    // mom 07 -3
    $themePath = HANDY_INSTALL_PATH . '/'.$this->config['theme']['path'];
    $themeUrl = $this->request->base_url . $this->config['theme']['path'];
    //echo "themeUrl in CHandy::ThemeEngineReader: " . $themeUrl;
    //-- ev ta bort VERKAR INTE ANV I mom07
    //$themeName    = $this->config['theme']['name'];   
    
    //4..  Is there a parent theme? new mom07-3
    $parentPath = null;
    $parentUrl = null;
    if(isset($this->config['theme']['parent'])) {
      $parentPath = HANDY_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      $parentUrl        = $this->request->base_url . $this->config['theme']['parent'];
    }
    
    //5.. Add stylesheet path to the $ha->data array
     $this->data['stylesheet'] = $this->config['theme']['stylesheet'];
     //$this->data['stylesheet'] = "{$themeUrl}/" .$this->config['theme']['stylesheet'];
    
    //6.. nytt mom07-3. Make the theme urls available as part of $ha
    $this->themeUrl = $themeUrl;
    $this->themeParentUrl = $parentUrl;
    
    //7..  Map menu to region if defined
    if(is_array($this->config['theme']['menu_to_region'])) {
          foreach($this->config['theme']['menu_to_region'] as $key => $val) {
          	  $this->views->AddString($this->DrawMenu($key), null, $val);
          }
    }
    //echo "$this->data['stylesheet'] är: ". $this->data['stylesheet'];
    
    //8.. Include the global functions.php and the functions.php that are part of the theme
    $ha = &$this;
    //First the default Handy themes/functions.php
    include(HANDY_INSTALL_PATH . '/themes/functions.php');
    if($parentPath) {
      if(is_file("{$parentPath}/functions.php")) {
        include "{$parentPath}/functions.php";
      }
    } 
    //9.. And last the current theme functions.php
    if(is_file("{$themePath}/functions.php")) {
      include "{$themePath}/functions.php";
    }
   
    //10.. Extract $ha->data to own variables and handover to the template file
    extract($this->data);
    extract($this->views->GetData()); 
    //new mom07-3  
    if(isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }
    
    //11.. Execute the template file
    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';    
    //include("{$themePath}/{$templateFile}");  
    //nytt MOM07-3
    if(is_file("{$themePath}/{$templateFile}")) {
    	//echo "{$themePath}/{$templateFile}";
      include("{$themePath}/{$templateFile}");
    } else if(is_file("{$parentPath}/{$templateFile}")) {
    	include("{$parentPath}/{$templateFile}");
    } else {
      throw new Exception('No such template file.');
    }
  
  }
//-----------------------------------------------------------------------------
//from CObject in MOM07
  /**
    * Redirect to another url and store the session
    * called from CGuestbook->handler()
    */
    public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {//24/11 content:added arguments
    //$ha = CHandy::Instance(); bortkommenterad i mom07
    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }
    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }
    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
         $this->session->SetFlash('timer', $this->timer);
    }
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));// 24/11 content: added $arguments
  }
//-----------------------------------------------------------------------------
/**
  * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
  *
  * @param string method name the method, default is index method.
  */
   // changed in Content to take 2 arguments      
   public function RedirectToController($method=null, $arguments=null) {
   	   $this->RedirectTo($this->request->controller, $method, $arguments);
  }
//-----------------------------------------------------------------------------

        /**
         * Redirect to a controller and method. Uses RedirectTo().
         *
         * @param string controller name the controller or null for current controller.
         * @param string method name the method, default is current method.
         */
        public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
         $controller = is_null($controller) ? $this->request->controller : null;
         $method = is_null($method) ? $this->request->method : null;        
         $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
  }
 //-----------------------------------------------------------------------------
   /**
         * Save a message in the session. Uses $this->session->AddMessage()
         *
         * @param $type string the type of message, for example: notice, info, success, warning, error.
         * @param $message string the message.    
         * @param $alternative string the message if the $type is set to false, defaults to null.
         */
//new 12/11
  public function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }


 //-----------------------------------------------------------------------------
        
       /**
         * Create an url. Uses $this->request->CreateUrl()
         *
         * @param $urlOrController string the relative url or the controller
         * @param $method string the method to use, $url is then the controller or empty for current
         * @param $arguments string the extra arguments to send to the method
         */
      public function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
      	  $this->request->CreateUrl($urlOrController, $method, $arguments);
  }
// --------------------------------------------------------------------------------  
        /**
       * Draw HTML for a menu defined in $ly->config['menus'].
       *
       * @param $menu string then key to the menu in the config-array.
       * @returns string with the HTML representing the menu.
       */
      public function DrawMenu($menu) {
        $items = null;
        if(isset($this->config['menus'][$menu])) {
          foreach($this->config['menus'][$menu] as $val) {
            $selected = null;
            if($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) {
              $selected = " class='selected'";
            }
            $items .= "<li><a {$selected} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
          }
        } else {
          throw new Exception('No such menu.');
        }     
        return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
      }
}
