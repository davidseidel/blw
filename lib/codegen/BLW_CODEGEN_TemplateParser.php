<?php
/**
 * Class to parse PSP-Templates.
 *  
 * @author David Seidel
 */
class BLW_CODEGEN_TemplateParser {
	private $source = null;
	const DIRECTIVE = 0;
	const TAG_OPEN = 1;
	const TAG_CLOSE = 2;
	const TAG_EMPTY = 4;
	const STATIC_CONTENT = 8;
	
	public function __construct($source) {
		$this->source = $source;
	}
	
	public function parse() {
		
		// regular expression for parser
		// instructions (f.e. taglib-import-statements, ... )
		$patterns[self::DIRECTIVE] = '<%@[ ]+[a-zA-Z]+[ ]+.* %>';
		// open tag like <foo:bar attr1="value">
		$patterns[self::TAG_OPEN] = '<[a-zA-Z0-9]+:[a-zA-Z0-9]+(([ ]+[a-zA-Z0-9]+="[^"]*")*)[ ]*>';
		// close tags like </foo:bar>
		$patterns[self::TAG_CLOSE] = '<\/[a-zA-Z0-9]+:[a-zA-Z0-9]+>';
		// empty tags like <foo:bar />
		$patterns[self::TAG_EMPTY] = '<[a-zA-Z0-9]+:[a-zA-Z0-9]+(([ ]+[a-zA-Z0-9]+="[^"]*")*)[ ]*\/>';
		
		// connect all expression to one string
		$pattern = '/'.implode('|', $patterns).'/sU';
		
		// storage for the matches
		$matches = array();
		
		// parse the template
		preg_match_all($pattern, $this->source, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
		
		// now the matches will be stored with their type and the static-content will be added 
		// storage for the parsed content
		$content = new ArrayObject();
		
		// set the offset to the start off the source
		$offsetOfLastMatch = 0;
		
		// iterate every match
		foreach($matches as $match) {
			// iterate every the off match
			foreach(array_keys($patterns) as $pattern_name) {
				$foundMatch = $match[0][0];
				if(preg_match('/'.$patterns[$pattern_name].'/sU', trim($foundMatch))) {
					// Because we found a match, store the static content(means: no PSP-Tags inside) between the last match and the current one
					$offsetOfCurrentMatch = $match[0][1];
					if($offsetOfLastMatch != $offsetOfCurrentMatch) {
						$content_entry = array('type' => self::STATIC_CONTENT, 'source' => substr($this->source, $offsetOfLastMatch, $offsetOfCurrentMatch - $offsetOfLastMatch));
						$content->append($content_entry);
						$offsetOfLastMatch = $offsetOfCurrentMatch;
					}
					
					// init storage for attributes and $tag_name
					$attributes = array();
					$tag_name = null;
					
					// preprocess elements
					switch($pattern_name) {
						case self::DIRECTIVE : {
							// lookup tag-name
							$regex = '=<%@ ([a-zA-Z]+) (.*) %>=sU';
							preg_match($regex, $foundMatch, $sub_patterns);
							//store tag-name
							$tag_name = $sub_patterns[1];
							
							// extract attributes
							$attribute_string = $sub_patterns[2];
							// extract every single attribute and its value
							$regex = '=([a-zA-Z]+)\="(.*)"=sU';			
							preg_match_all($regex, $attribute_string, $sub_patterns, PREG_SET_ORDER);
							$attributes = array();
							// store every single attribute and its value
							foreach($sub_patterns as $sub_pattern) {
								array_push($attributes, array('name' => $sub_pattern[1], 'value' => $sub_pattern[2]));
							}
							break;
						}
						
						case self::TAG_OPEN : {
							// lookup tag-name
							$regex = '=<([a-zA-Z]+:[a-zA-Z]+) (.*)>=sU';
							preg_match($regex, $foundMatch, $sub_patterns);
							$tag_struct = explode(':', $sub_patterns[1]);
							
							//store tag-name
							$tag_name = $sub_patterns[1];
							
							// extract attributes
							$attribute_string = $sub_patterns[2];
							// extract every single attribute and its value
							$regex = '=([a-zA-Z]+)\="(.*)"=sU';
							preg_match_all($regex, $attribute_string, $sub_patterns, PREG_SET_ORDER);
							$attributes = array();
							// store every single attribute and its value
							foreach($sub_patterns as $sub_pattern) {
								array_push($attributes, array('name' => $sub_pattern[1], 'value' => $sub_pattern[2]));
							}
							break;
						}
						
						case self::TAG_CLOSE : {
							// lookup tag-name
							$regex = '=</([a-zA-Z]+:[a-zA-Z]+)>=sU';
							preg_match($regex, $foundMatch, $sub_patterns);
							$tag_name = $sub_patterns[1];
							break;
						}
						
						case self::TAG_EMPTY : {
							// lookup tag-name
							$regex = '=<([a-zA-Z]+:[a-zA-Z]+) (.*)/>=sU';
							preg_match($regex, $foundMatch, $sub_patterns);
							$tag_name = $sub_patterns[1];
							
							// extract attributes
							$attribute_string = $sub_patterns[2];
							// extract every single attribute and its value
							$regex = '=([a-zA-Z]+)\="(.*)"=sU';
							preg_match_all($regex, $attribute_string, $sub_patterns, PREG_SET_ORDER);
							$attributes = array();
							// store every single attribute and its value
							foreach($sub_patterns as $sub_pattern) {
								array_push($attributes, array('name' => $sub_pattern[1], 'value' => $sub_pattern[2]));
							}
							break;
						}
						
						default : {
							
						}
					}
					
					// append the tag to the content-array
					$content_entry = array(
										'type' => $pattern_name, 
										'source' => $foundMatch,
										'name' => $tag_name,
										'attributes' => $attributes);
					$content->append($content_entry);
					
					// set the new offset to the end of the match
					$offsetOfLastMatch = $offsetOfCurrentMatch + strlen($foundMatch);
				}
			}
		}
		
		// store the static-content between the last match and the end of the source
		if($offsetOfLastMatch < strlen($this->source)) {
			$content_entry = array('type' => self::STATIC_CONTENT, 'source' => substr($this->source, $offsetOfLastMatch, strlen($this->source) - $offsetOfLastMatch));
			$content->append($content_entry);
		}
		
		return $content;
	}
	
}

?>