<?php
	/**
	* class  that filter user input text before shown to visitorn.
	* @package HandyCore
    */
    
    class CTextFilter {
    	
    	public static $instance = null;
    	
    /**
   * Constructor
   */
   public function __construct($text) {
    parent::__construct(); 
  }
     public static function Filter($text, $filter) {
    	switch($filter) {
        	//case 'php': $text = nl2br(make_clickable(eval('?>'.$text))); break;
        	case 'html': $text = nl2br(make_clickable($text)); break;
        	case 'htmlpurify': $text = nl2br(self::purify($text)); break;
        	case 'make_clickable': $text = make_clickable(htmlEnt($text)); break;
        	case 'bbcode': $text = nl2br(bbcode2html(htmlEnt($text))); break;
        		//case 'markdown': $text = self::markdownextra($text,'Markdown_Parser'); break;
        		//case 'markdownextra': $text = self::markdownextra($text,'MarkdownExtra_Parser'); break;
        		//case 'smartypants': $text = nl2br(make_clickable(self::smartypants(htmlEnt($text),'SmartyPants_Parser'))); break;
        		//case 'typographer': $text = nl2br(make_clickable(self::smartypants(htmlEnt($text),'SmartyPantsTypographer_Parser'))); break;
        	case 'plain':
        		default: $text = nl2br(make_clickable(htmlEnt($text))); break;
        }
        return $text;
    }
    
    //--------------------------------------------------------------------------
    /**
       * Purify it. Create an instance of HTMLPurifier if it does not exists.
       *
       * @param $text string the dirty HTML.
       * @returns string as the clean HTML.
       */
       public static function Purify($text) {   
        if(!self::$instance) {
          require_once(__DIR__.'/htmlpurifier-4.4.0-standalone/HTMLPurifier.standalone.php');
          $config = HTMLPurifier_Config::createDefault();
          $config->set('Cache.DefinitionImpl', null);
          self::$instance = new HTMLPurifier($config);
        }
        return self::$instance->purify($text);
      }
      
      //--------------------------------------------------------------------------------

/**
* Helper, make clickable links from URLs in text.
*/
function makeClickable($text) {
  return preg_replace_callback(
    '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
    create_function(
      '$matches',
      'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
    ),
    $text
  );
}
//--------------------------------------------------------------------------------
    /**
    * Helper, BBCode formatting converting to HTML.
    *
    * @param string text The text to be converted.
    * @returns string the formatted text.
    */
    function bbcode2html($text) {
      $search = array(
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[img\](https?.*?)\[\/img\]/is',
        '/\[url\](https?.*?)\[\/url\]/is',
        '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        );   
      $replace = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<u>$1</u>',
        '<img src="$1" />',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>'
        );     
      return preg_replace($search, $replace, $text);
    }
    
     //--------------------------------------------------------------------------
   
    /**
* Format text according to Markdown och Markdown extra syntax.
*
* @param string $text the text that should be formatted.
* @param string $parser the parser method, can be Markdown_Parser och MarkdownExtra_Parser.
* @return string as the formatted html-text.
*/
/*private static function markdownextra($text,$parser) {
    require_once(__DIR__ . '/php-markdown-extra/markdown.php');
    return Markdown($text,$parser);
}*/
    //--------------------------------------------------------------------------


/**
* Format text according to Smartypants syntax.
*
* @param string $text the text that should be formatted.
* @param string $parser the parser method can be SmartyPants_Parser or SmartyPantsTypographer_Parser.
* @return string as the formatted html-text.
*/
/*private static function smartypants($text,$parser) {
    require_once(__DIR__ . '/typographer/smartypants.php');
    return SmartyPants($text,$parser);
}
*/

}
    	
