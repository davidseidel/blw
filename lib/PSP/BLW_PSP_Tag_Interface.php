<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2006 by David Seidel. All rights reserved.
 *
 * To contact the author write to {@link mailto:seidel.david@googlemail.com David Seidel}
 * The latest version of BlueWonder can be obtained from: {@link http://www.bluewonder-framework.de/}
 *
 * @author David Seidel <seidel.david@googlemail.com>
 * @package blw.lib.PSP
 */

/**
 * Interface for all tags
 */
interface BLW_PSP_Tag_Interface {
	/**
	 * called after processing the body of the tag during the build tree state
	 *
	 */
	public function doAfterBody();
          
	/**
	 * called if find a closing tag
	 *
	 */
	public function doEndTag();
       
	/**
	 * called while starting the tag
	 *
	 */
	public function doStartTag();

	/**
	 * searches recursive in all parents of the tag for the first instance of a given class name
	 *
	 * @param string $class_name name of the class which should be found
	 * @return BLW_PSP_Tag|null null=no instance of the specified class name was found 
	 */
	public function findAncestorWithClass($class_name);

	/**
	 * returns the id of the tag
	 *
	 * @return string id of the tag
	 */   
	public function	getId();
  
	/**
	 * returns the parent tag
	 *
	 * @return BLW_PSP_Tag|null
	 */
	public function getParent();

	/**
	 * returns the value of an attribute by its name
	 *
	 * @param string $attribute_name
	 * @return string|null
	 */
	public function getValue($attribute_name);
  
	/**
	 * returns the values of all attributes of the tag
	 *
	 * @return ArrayObject
	 */   
	public function getValues();
    
	/**
	 * removes a tag attribute
	 *
	 * @param string $attribute_name
	 * @return string|null
	 */
	public function removeValue($attribute_name);
	
	/**
	 * sets the id of the tag
	 *
	 * @param string $id
	 */
	public function setId($id);

	/**
	 * sets the page context of the tag
	 *
	 * @param BLW_PSP_PageContext $pageContext
	 */
	public function setPageContext(BLW_PSP_PageContext $pageContext);
	
	/**
	 * sets the parent of the tag and adds the tag as a child of the parent tag
	 *
	 * @param BLW_PSP_Tag $tag
	 */
	public function setParent(BLW_PSP_Tag $tag = null);

	/**
	 * sets the value for an attribute of the tag
	 *
	 * @param string $attribute_name
	 * @param string $value
	 * @return bool
	 */
	public function setValue($attribute_name, $value);
	
	/**
	 * renders the output of the tag
	 *
	 */
	public function getOutput();
}

?>