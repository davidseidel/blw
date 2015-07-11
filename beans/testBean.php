<?php
class TestBean extends BLW_PSP_Bean {
	protected $farben = null;
	public function onInit() {
		$this->setFarben(new ArrayObject(array('rot', 'gruen', 'blau')));
	}
	
	public static function init() {
		$bean = BLW_PSP_PageContext::instance()->getBean('TestBean');
		$bean->onInit();
	}
	
	public static function addValue() {
		$bean = BLW_PSP_PageContext::instance()->getBean('TestBean');
		$farben = $bean->getFarben();
		$view_root = BLW_PSP_FacesContext::instance()->getViewRoot();
		if($view_root->getTagById('name') != null) {
			$value = $view_root->getTagById('name')->getStoredValue();
			$farben->append($value);
		}
		$bean->setFarben($farben);
	}
	
	public function makeGreen() {
		$view_root = BLW_PSP_FacesContext::instance()->getViewRoot(); 
		$tag = $view_root->getTagById('rahmen');
		$tag->setValue('style', 'background-color:green');
	}
	
	public function getFarben() {
		return $this->farben;
	}
	
	public function setFarben(ArrayObject $farben) {
		$this->pcs->firePropertyChange('farben', $this->farben, $farben);	
		$this->farben = $farben;
	} 
}
?>