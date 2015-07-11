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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag_Interface');

/**
 * base class for all tags and faces
 */
abstract class BLW_PSP_Tag implements BLW_PSP_Tag_Interface {
	/**
	 * contains the parent tag
	 *
	 * @var BLW_PSP_Tag
	 */
	protected $parent = null;
	/**
	 * unique id of the tage
	 *
	 * @var string
	 */
	protected $id = null;
	/**
	 * contains the attributes of the tag
	 *
	 * @var ArrayObject
	 */
	protected $attributes = null;
	/**
	 * contains the page context
	 *
	 * @var BLW_PSP_PageContext
	 */
	protected $pageContext = null;

	/**
	 * contains the children of the tag
	 *
	 * @var ArrayObject
	 */
	protected $children = null;
	/**
	 * regular expression for tag ids
	 *
	 */
	const ID_REGEX = '=^[_A-Za-z]{1}[_0-9A-Za-z]*$=';
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->attributes = new ArrayObject();
		$this->children = new ArrayObject();
		$this->onInit();
	}
	
	/**
	 * called by the constructor of the tag. Place here some speficed instruction during the initialisation of the tag.
	 *
	 */
	protected function onInit() { }
	
	/**
	 * adds a child tag
	 *
	 * @param BLW_PSP_Tag $tag
	 */
	public function addChild(BLW_PSP_Tag $tag) {
		$this->children->append($tag);
	}
	
	/**
	 * returns all children of the tag
	 *
	 * @return ArrayObject|null
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * searches recursive in all parents of the tag for the first instance of a given class name
	 *
	 * @param string $class_name name of the class which should be found
	 * @return BLW_PSP_Tag|null null=no instance of the specified class name was found 
	 */
	public function findAncestorWithClass($class_name) {
		if(!is_null($this->parent)) {
			$parent_refl = new ReflectionClass(get_class($this->parent));	
			
			if (get_class($this->getParent()) == $class_name) {
				return $this->parent;
			}	
			return $this->parent->findAncestorWithClass($class_name);
		}
		return null;
	}
          
	/**
	 * returns the id of the tag
	 *
	 * @return string id of the tag
	 */
	public function	getId() {
		return $this->id;
	}
  
	/**
	 * returns the parent tag
	 *
	 * @return BLW_PSP_Tag|null
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * returns the value of an attribute by its name
	 *
	 * @param string $attribute_name
	 * @return string|null
	 */
	public function getValue($attribute_name) {
		$this->checkPageContext();
		return $this->pageContext->getTagAttribute($this, $attribute_name);
	}
	
	/**
	 * sets the value for an attribute of the tag
	 *
	 * @param string $attribute_name
	 * @param string $value
	 * @return bool
	 */
	public function setValue($attribute_name, $value) {
		$this->checkPageContext();
		return $this->pageContext->setTagAttribute($this, $attribute_name, $value);
	}
  
	/**
	 * returns the values of all attributes of the tag
	 *
	 * @return ArrayObject
	 */
	public function getValues() {
		$this->checkPageContext();
		return $this->pageContext->getTagAttributes($this);
	}
         
	/**
	 * removes a tag attribute
	 *
	 * @param string $attribute_name
	 * @return string|null
	 */
	public function removeValue($attribute_name) {
		$this->checkPageContext();
		return $this->pageContext->removeTagAttribute($this, $attribute_name);
	}
	
	/**
	 * checks if the page context was set
	 *
	 * @return bool true=page context found
	 */
	protected function checkPageContext() {
		if($this->pageContext instanceof BLW_PSP_PageContext) {
			return true;
		}
		throw new Exception('No PageContext found!!!');
	}
	
	/**
	 * sets the id of the tag
	 *
	 * @param string $id
	 */
	public function setId($id) {
		if(preg_match(self::ID_REGEX, $id)) {
			$this->id = $id;
			$view_root = $this->findAncestorWithClass("BLW_PSP_ViewRoot");
			if($view_root != null) {
				$view_root->registerTag($this->getId(), $this);
			} else {
				throw new Exception('No view root found in '.get_class($this).' !!!');
			}
		} else {
			throw new Exception('Invalid value for id "'.$id.'"');
		}
	}
	
	/**
	 * returns a tag by given id
	 *
	 * @param string $id
	 * @return BLW_PSP_Tag|null
	 */
	public function findTagById($id) {
		$view_root = $this->findAncestorWithClass("BLW_PSP_ViewRoot");
		if($view_root != null) {
			return $view_root->getTagById($id);
		} else {
			throw new BLW_CORE_NullException('No view root found in '.get_class($this).' !!!');
		}
	}

	/**
	 * sets the page context of the tag
	 *
	 * @param BLW_PSP_PageContext $pageContext
	 */
	public function setPageContext(BLW_PSP_PageContext $pageContext) {
		$this->pageContext = $pageContext;
		if(is_null($this->id)) {
			$this->id = $this->pageContext->getNewTagId();
		}
	}
	
	/**
	 * sets the parent of the tag and adds the tag as a child of the parent tag
	 *
	 * @param BLW_PSP_Tag $tag
	 */
	public function setParent(BLW_PSP_Tag $tag = null) {
		$this->parent = $tag;
		$this->parent->addChild($this);
	}

	/**
	 * returns the page context of the tag
	 *
	 * @return BLW_PSP_PageContext
	 */
	public function getPageContext() {
		return $this->pageContext;
	}
}
?>