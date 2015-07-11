<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagLibInfo');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagInfo');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_RessourceLoader');
class BLW_CODEGEN_TagLibParser {
	protected $taglib_objects = null;
	public function __construct() {
		$this->taglib_objects = new ArrayObject();
	}
	
	public function getTagLib($uri) {
		if(!$this->taglib_objects->offsetExists($uri)) {
			
			$tld_xml = BLW_CORE_RessourceLoader::load($uri);
			
			// parse the tag-lib
			$tld_xml_object = simplexml_load_string($tld_xml);
			
			// Build the TagLibInfo-Object
			$tld = new BLW_CODEGEN_TagLibInfo((string) $tld_xml_object->shortname, (string) $tld_xml_object->version);
			
			$i = 0;
			// add the tags to the tab-library-info			
			foreach($tld_xml_object->tag as $tag_defition) {
				$tag[++$i] = new BLW_CODEGEN_TagInfo((string) $tag_defition->name, (string) $tag_defition->tagclass, $tld);
				foreach($tag_defition->attribute as $attribute) {
					$tag[$i]->addAttribute((string) $attribute->name, (string) $attribute->required );
				}
			}
			
			// store the tag-libraryinfo
			$this->taglib_objects->offsetSet($uri, $tld);
		}
		return $this->taglib_objects->offsetGet($uri);
	}
}


?>