<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ValueHolder');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ActionListener');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_PropertyChangeListener');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_MethodNotExistsException');
class iteratorTag extends BLW_PSP_Tag implements BLW_PSP_ActionListener, BLW_CORE_PropertyChangeListener, BLW_PSP_ValueHolder {
	protected $collection = null;
	protected $actual_item = null;
	protected $selected_item = -1;
	
	public function processAction(BLW_PSP_ActionEvent $event) {
		$action = $this->getActionNameByClient($event->getSource());	
		$offset_names = array_keys($this->collection->getArrayCopy());
		switch ($action) {
			case 'delete' : {
				if($this->selected_item > -1 && ((count($offset_names) - 1) >= $this->selected_item)) {
					$offset_name = $offset_names[$this->selected_item];
					$this->collection->offsetUnset($offset_name);
					$this->updateBean();
					$this->selected_item = -1;
				}
				break;
			}
		}
	}

	public function propertyChange(BLW_CORE_PropertyChangeEvent $e) {
		if($e->getSource() == $this->pageContext->getBean($this->getValue('name'))) {
			$this->collection = $e->getNewValue();
		}
	}
	
	public function setStoredValue($value) {
		$this->selected_item = $value;
		$this->setValue('selectedItem', $value);
	}
	
	public function getStoredValue() {
		return $this->selected_item;
	}
	
	public function getActionNameByClient(BLW_PSP_ActionSource $source) {
		$actions = array('delete', 'select');
		foreach ($actions as $action) {
			if($source->getId() == $this->getValue($action)) {
				return $action;
			}
		}
		return null;
	} 
	
	public function doStartTag() {
		if(is_null($this->getValue('property'))) {
			$this->collection = $this->pageContext->findBean($this->getValue('name'));
		} else {
			$bean = $this->pageContext->findBean($this->getValue('name'));
			$pcs = $bean->getPropertyChangeSupport();
			$pcs->addListener($this);
			$method_name = 'get'.ucfirst($this->getValue('property'));
			if(method_exists($bean, $method_name)) {
				$this->collection = call_user_func_array(array($bean ,$method_name), array());
			} else {
				throw new BLW_CORE_MethodNotExistsException(get_class($bean).'::'.$method_name);
			}
		}
		
		if(!is_null($this->getValue('selectedItem'))) {
			$this->selected_item = $this->getValue('selectedItem');
		}
	}
	
	public function doEndTag() {
		if($this->getValue('delete') != null) {
			$view_root = BLW_PSP_FacesContext::instance()->getViewRoot();
			$action_source = $view_root->getTagById($this->getValue('delete'));
			$action_source->addActionListener($this);
		}
		
		if($this->getValue('select') != null) {
			$view_root = BLW_PSP_FacesContext::instance()->getViewRoot();
			$action_source = $view_root->getTagById($this->getValue('select'));
			$action_source->addActionListener($this);
		}
	}
	
	public function doAfterBody() {
	}
	
	protected function updateBean() {
		if(!is_null($this->getValue('property'))) {
			$bean = $this->pageContext->findBean($this->getValue('name'));
			$method_name = 'set'.ucfirst($this->getValue('property'));
			if(method_exists($bean, $method_name)) {
				return call_user_func_array(array($bean ,$method_name), array($this->collection));
			} else {
				throw new BLW_CORE_MethodNotExistsException(get_class($bean).'::'.$method_name);
			}
		}
	}
	
	public function getOutput() {		
		if(!is_null($this->collection)) {
			$count = 0;
			foreach ($this->collection as $item) {
				$this->pageContext->setBean($this->getId(), $item);
				BLW_PSP_RenderContext::instance()->registerCount($this, $count);
				$this->actual_item = $item;
				foreach ($this->getChildren() as $child) {			
					$child->getOutput();
				}
				$this->pageContext->removeBean($this->getId());
				$count++;
			}
			BLW_PSP_RenderContext::instance()->unregister($this);
			$this->actual_item = null;
		}
		
	}
	
}

?>