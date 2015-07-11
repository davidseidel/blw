<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TemplateParser');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_PageClassGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_DirectiveCodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagOpenCodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagCloseCodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_StaticContentCodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagNotBalancedException');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagEmptyCodeGenerator');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_RessourceLoader');

class BLW_CODEGEN_CodeCompiler {
	public function __construct() {
	}
	
	public function process($src) {
		$tag_stack = array('$view_root');
		$tag_name_stack = array();
		$tpl_parser = new BLW_CODEGEN_TemplateParser($src);
		$code_struct = $tpl_parser->parse();
		
		$page_container = new BLW_CODEGEN_PageClassGenerator();
		$i = 0;
		foreach($code_struct as $code_segment) {
			$code_generator = null;
			switch($code_segment['type']) {
				case BLW_CODEGEN_TemplateParser::DIRECTIVE : {
					$code_generator = new BLW_CODEGEN_DirectiveGenerator($page_container, $i, $code_segment['name']);
					break;
				}
				
				case BLW_CODEGEN_TemplateParser::TAG_OPEN : {
					$code_generator = new BLW_CODEGEN_TagOpenCodeGenerator($page_container, $i, $code_segment['name']);
					$code_generator->setParent($tag_stack[count($tag_stack) - 1]);
					array_push($tag_stack, $code_generator->getObjectName());
					array_push($tag_name_stack, $code_generator->getTagName());
					break;
				}
				
				case BLW_CODEGEN_TemplateParser::TAG_CLOSE : {
					if($tag_name_stack[count($tag_name_stack)  - 1] != $code_segment['name']) {
						throw new BLW_CODEGEN_TagNotBallancedException();
					}
					
					array_pop($tag_stack);
					
					$code_generator = new BLW_CODEGEN_TagCloseCodeGenerator($page_container, $i, $code_segment['name']);
					$code_generator->setParent($tag_stack[count($tag_stack)  - 1]);
					
					array_pop($tag_name_stack);
					
					break;
				}
				
				case BLW_CODEGEN_TemplateParser::TAG_EMPTY : {
					$code_generator = new BLW_CODEGEN_TagEmptyCodeGenerator($page_container, $i, $code_segment['name']);
					$code_generator->setParent($tag_stack[count($tag_stack) - 1]);
					break;
				}
				
				case BLW_CODEGEN_TemplateParser::STATIC_CONTENT : {
					$code_generator = new BLW_CODEGEN_StaticContentCodeGenerator($page_container, $i, 'static');
					$code_generator->setParent($tag_stack[count($tag_stack) - 1]);
					break;
				}
			}
			
			if(array_key_exists('attributes', $code_segment)) {
				foreach ($code_segment["attributes"] as $attribute) {
					$code_generator->addAttribute($attribute["name"], $attribute["value"]);
				}
			}
			
			$code_generator->setSrc($code_segment['source']);
			
			$code_generator->generateCode();
			$i++;
		}
		
		if(count($tag_stack) > 1) {
			throw new BLW_CODEGEN_TagNotBallancedException();
		}
		
		$class_tpl = BLW_CORE_RessourceLoader::load('lib/codegen/class.tpl');
		return $page_container->processClassTemplate($class_tpl);
	}
}

?>