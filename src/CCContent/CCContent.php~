<?php
    /**
    * A user controller to manage  content
    *
    * @package HandyCore
    */
    class CCContent extends CObject implements IController  {

      
      /**
       * Constructor
       */
      public function __construct() {
        parent::__construct();
      }

//-----------------------------------------------------------------------------
      /**
       * List all content
       */
       public function Index() {
        $content = new CMContent();
        $this->views->SetTitle('Content Controller')
        			->AddInclude(__DIR__ . '/index.tpl.php', array(
        				'content' =>$content->ListAll(),
        				));
      }
 
 //-----------------------------------------------------------------------------     
         /**
       * Init the content database.
       */
      public function Init() {
        $content = new CMContent();
        $content->Init();
        $this->RedirectToController();
      }
 //-----------------------------------------------------------------------------     
       /**
       * Edit a selected content, or prepare to create new content if argument is missing.
       *
       * @param id integer the id of the content.
       */
      public function Edit($id=null) {
        $content = new CMContent($id);
        $form = new CFormContent($content);
        $status = $form->Check();
        if($status === false) {
          //test
          //echo "Status:är false <br/>";
          $this->AddMessage('notice', 'The form could not be processed.');
          $this->RedirectToController('edit', $id);
        } else if($status === true) {
        	//test
        	//echo "Status:är true <br/>";
          $this->RedirectToController('edit', $content['id']);
        }
        //test
        //echo "Id: " . $id; 
        $title = isset($id) ? 'Edit' : 'Create';
        $this->views->SetTitle("$title content: $id")
                    ->AddInclude(__DIR__ . '/edit.tpl.php', array(
                      'user'=>$this->user,
                      'content'=>$content,
                      'form'=>$form,
                    ));
      }
 //-----------------------------------------------------------------------------     
         /**
       * Create new content.
       */
      public function Create() {
        $this->Edit();
      }
  }
