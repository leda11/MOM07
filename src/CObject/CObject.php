<?php
/**
* Holding a instance of CHandy to enable use of $this in subclasses.
*
* @package HandyCore
*/
class CObject {

   public $config;
   public $request;
   public $data;
   
   public $db;
   public $views;
   public $session;
   public $user;//mom07:3
   
   protected $ha;
   
   /**
    * Constructor
    */
   protected function __construct($ha=null) {
   	if(!$ha) {
   	   $ha = CHandy::Instance();
   	}
   	$this->ha 		= &$ha;//mom 07 -3
    $this->config   = &$ha->config;
    $this->request  = &$ha->request;
    $this->data     = &$ha->data;
    $this->db 		= &$ha->db;
    $this->views 	= &$ha->views;
    $this->session	= &$ha->session;
    $this->user     = &$ha->user;
  }
//-----------------------------------------------------------------------------  
/**
  * Wrapper for same method in CHandy. See there for documentation.
  */
    protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {//24/11 content:added arguments
    	$this->ha->RedirectTo($urlOrController, $method, $arguments);
    }
   
//-----------------------------------------------------------------------------
/**
  * Wrapper for same method in CHandy. See there for documentation.
  */     
   protected function RedirectToController($method=null, $arguments=null) {
   	   $this->ha->RedirectToController($method, $arguments);
  }
//-----------------------------------------------------------------------------
/**
  * Wrapper for same method in CHandy. See there for documentation.
  */ 
    protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
       $this->ha->RedirectToControllerMethod($controller, $method, $arguments );
  }
 //-----------------------------------------------------------------------------
/**
  * Wrapper for same method in CHandy. See there for documentation.
  */ 
  protected function AddMessage($type, $message, $alternative=null) {
  	  return $this->ha->AddMessage($type, $message, $alternative); //var kommer alternative från 22/12	  
  }

 //-----------------------------------------------------------------------------
/**
  * Wrapper for same method in CHandy. See there for documentation.
  */ 
   protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
      return $this->ha->CreateUrl($urlOrController, $method, $arguments);
  }
}
