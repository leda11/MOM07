<?php



class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule{ 
 	
 	 /**
 	 * Properties to handle arrays
 	 */
 	 public $data;
 	 //public $userId ;
   
 	 /**
   * Constructor
   */
  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();//Tom men färdig att fyllas med data
    } 
  }
// ----------------------------------------------------------------------------------  

  /**
* Implementing ArrayAccess for $this->profile
*/
  public function offsetSet($offset, $value) { 
  	if (is_null($offset)) { $this->data[] = $value; 
  	} else { 
  		$this->data[$offset] = $value; 
  	}
  }
  public function offsetExists($offset) { 
  	return isset($this->data[$offset]); 
  }
  public function offsetUnset($offset) { 
  	unset($this->data[$offset]); 
  }
  public function offsetGet($offset) { 
  	return isset($this->data[$offset]) ? $this->data[$offset] : null; 
  }
// ----------------------------------------------------------------------------------  
      /**
       * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
       * added grouptables and questions
       * @param string $key the string that is the key of the wanted SQL-entry in the array.
       */
      public static function SQL($key=null, $args=null) {
      	// new technique
      	$order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
      	$order_by = isset($args['order-by']) ? $args['order-by'] : 'id';      	
        $queries = array(   
        	'drop table content' 	=> "DROP TABLE IF EXISTS Content;",
        	'create table content'  => "CREATE TABLE IF NOT EXISTS Content( id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, idUser INT, filter TEXT, created DATETIME default (datetime('now')),updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
        	'insert content'  	    => 'INSERT INTO Content (key, type, title, data, idUser, filter) VALUES (?,?,?,?,?, ?) ;',       								    
        	'get all content by type'=> "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",       	
        	'get content by id'		=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
        	'get content by key'	=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE key=?;',

        	'update content'  		=> "UPDATE  Content SET  key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') where id=? ;",      	
         	'get all content'		=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
        	'update content as deleted'			=> "UPDATE Content SET deleted=datetime('now') WHERE id=?;"
        	 
        	
        	);
      if(!isset($queries[$key])) {
          throw new Exception("No such SQL query, key '$key' was not found.");
        }                                                                                                       
        return $queries[$key];
      } 
      
// ----------------------------------------------------------------------------------  
  public function Init() {
		try {
		      $this->db->ExecuteQuery(self::SQL('drop table content'));
              $this->db->ExecuteQuery(self::SQL('create table content'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nLydia is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", $this->user['id'], 'bbcode'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", $this->user['id'], 'htmlpurify'));
			
			
       /*   $this->db->ExecuteQuery(self::SQL('drop table content'));
          $this->db->ExecuteQuery(self::SQL('create table content'));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('Hejsan!', 'post', 'Testing', 'En testtest för att se hur det fungerar', $this->user['id'], 'plain' ));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('Programmering!', 'post', 'vilka språk är roligast?', 'Demo post: Det finns väldigt många språk inom programmering.', $this->user['id'], 'plain' ));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('Vinter!', 'post', 'Vintern är kall.', 'Dememo page:Vad kan man göra på semestern. Ja det finns hur mycket som helst att välja på.', $this->user['id'], 'plain' ));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('Politik!', 'post', 'Politik är det kul?', 'Demo post:Iidag så är det väldigt få undommar som är intresserade av politik.', $this->user['id'], 'plain' ));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', 'This is a demo page, Demo page:Tthis could be your personal home-page.', $this->user['id'], 'plain'));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', 'This is a demo page, this could be your personal about-page.', $this->user['id'], 'plain'));
       */    
          $this->session->AddMessage('success', 'Successfully created the database tables and created a testing post in the blog.<br/>');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
      }
    
 // ----------------------------------------------------------------------------------       
            /**
       * Implementing interface IModule. Manage install/update/deinstall and equal actions.
       */
      public function Manage($action=null) {
        switch($action) {
          case 'install':
            try {
              $this->db->ExecuteQuery(self::SQL('drop table content'));
              $this->db->ExecuteQuery(self::SQL('create table content'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nLydia is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", $this->user['id'], 'plain'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", $this->user['id'], 'bbcode'));
              $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", $this->user['id'], 'htmlpurify'));
              return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
            } catch(Exception$e) {
              die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
            }
          break;
         
          default:
            throw new Exception('Unsupported action for this module.');
          break;
        }
      }
 // ----------------------------------------------------------------------------------  
     /**
     * If there is an id save otherwiser create a new post
     * 
     * @returns boolean true if success else false.
     */
    public function Save() {
    $msg = null;
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this->user['id'],  $this['filter']));
      $this['id'] = $this->db->LastInsertId();
      $msg = 'created';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', "Successfully {$msg} content '" . htmlEnt($this['key']) . "'.");
    } else {
      $this->AddMessage('error', "Failed to {$msg} content '" . htmlEnt($this['key']) . "'.");
    }
    return $rowcount === 1;
  }
  // ----------------------------------------------------------------------------------  
//MOM07 
   /**
     * Mark post as deleted
     * 
     * @returns boolean true if success else false.
     */
      public function Delete() {
     	  
      	 if($this['id']) {
      	 	 $this->db->ExecuteQuery(self::SQL('update content as deleted') , array($this['id']));
      	 } 
      	 $rowcount = $this->db->RowCount();
      	 if($rowcount) {
      	 	 $this->AddMessage('success', "Successfully set content '" . htmlEnt($this['key']) . "' as deleted.");
      	 } else {
      	 	 $this->AddMessage('error', "Failed to set content '" . htmlEnt($this['key']) . "' as deleted.");
      	 }
      	 return $rowcount === 1;
      }
// ----------------------------------------------------------------------------------  
      /**
   * List all content.
   *
   * @returns array with listing or null if empty.
   */
      public function ListAll($args=null){
      	  try{
      	  	  if (isset($args) && isset($args['type'])){      	  	  	  
      	  	  	  return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get all content by type', $args), array($args['type'] ));
      	  	  }else{
      	  	  	  return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get all content', $args ));     	  	  	  
      	  	  }	  	  	  
      	  } catch(Exception$e) {
      	  	  echo $e;
      	  	  return null;    	  	 
      	  }
      }
 // ----------------------------------------------------------------------------------  
      /**
      * Load content by id.
      *
      * @param id integer the id of the content.
      * @returns boolean true if success else false.
      */
      public function LoadById($id){
      	  // save the result
      	  $res= $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get content by id'), array($id ));
      	  //check if resultset is empty -> message otherwise put in $data variable
      	  if(empty($res)) {
      	  	  $this->AddMessage('error', "Failed to load content with id '$id'.");
      	  	  return false;
      	  } else {
      	  	  $this->data = $res[0];
      	  }
      	  return true;
      }
 //-----------------------------------------------------------------------------     
  /**
* Filter content according to a filter.
*
* @param $data string of text to filter and format according its filter settings.
* @returns string with the filtered data.
* Added HTML Purifier
*/
  public static function Filter($data, $filter) {
    switch($filter) {
      /*case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;*/
      /*case 'html':   $data = nl2br(makeClickable($data)); break;*/
  	  case 'htmlpurify': $data = nl2br(CHTMLPurifier::Purify($data)); break;      
      case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;
      case 'plain':
      default:       $data = nl2br(makeClickable(htmlEnt($data))); break;
    }
    return $data;
  }
  
  
  /**
* Get the filtered content.
*
* @returns string with the filtered data.
*/
  public function GetFilteredData() {
    return $this->Filter($this['data'], $this['filter']);
  }
  
  
}
