<?php
/**
    * A blog controller to display a blog-like list of all content labelled as "post".
    *
    * @package HandyCore
    */
    class CCPage extends CObject implements IController {


      /**
       * Constructor
       */
      public function __construct() {
        parent::__construct();
      }

//-------------------------------------------------------------------------------
      /**
       * Display all content of the type "post".
       */
      public function Index() {
        $content = new CMContent();
        $this->views->SetTitle('Page')
                    ->AddInclude(__DIR__ . '/index.tpl.php', array(
                      'content'=>null));
      }
 //-------------------------------------------------------------------------------
     
      /**
       * Display a page.
       *
       * @param $id integer the id of the page.
       */
      public function View($id=null) {
        $content = new CMContent($id);
        $this->views->SetTitle('Page: '.htmlEnt($content['title']))
                    ->AddInclude(__DIR__ . '/index.tpl.php', array(
                      'content' => $content,
                    ));
      }
//-------------------------------------------------------------------------------
      
            /**
       * A list of all available controllers/methods
       *
       * @returns array list of controllers (key) and an array of methods
       */
      public function AvailableControllers() { 
        $controllers = array();
        foreach($this->config['controllers'] as $key => $val) {
          if($val['enabled']) {
            $rc = new ReflectionClass($val['class']);
            $controllers[$key] = array();
            $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach($methods as $method) {
              if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
                $methodName = mb_strtolower($method->name);
                $controllers[$key][] = $methodName;
              }
            }
            sort($controllers[$key], SORT_LOCALE_STRING);
          }
        }
        ksort($controllers, SORT_LOCALE_STRING);
        return $controllers;
      }
 //-------------------------------------------------------------------------------
     
            /**
       * Read and analyse all modules.
       *
       * @returns array with a entry for each module with the module name as the key.
       *                Returns boolean false if $src can not be opened.
       */
      public function ReadAndAnalyse() {
        $src = LYDIA_INSTALL_PATH.'/src';
        if(!$dir = dir($src)) throw new Exception('Could not open the directory.');
        $modules = array();
        while (($module = $dir->read()) !== false) {
          if(is_dir("$src/$module")) {
            if(class_exists($module)) {
              $rc = new ReflectionClass($module);
              $modules[$module]['name']          = $rc->name;
              $modules[$module]['interface']     = $rc->getInterfaceNames();
              $modules[$module]['isController']  = $rc->implementsInterface('IController');
              $modules[$module]['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
              $modules[$module]['hasSQL']        = $rc->implementsInterface('IHasSQL');
              $modules[$module]['isLydiaCore']   = in_array($rc->name, array('CLydia', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject'));
              $modules[$module]['isLydiaCMF']    = in_array($rc->name, array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier'));
            }
          }
        }
        $dir->close();
        ksort($modules, SORT_LOCALE_STRING);
        return $modules;
      }

    
}
    



      

