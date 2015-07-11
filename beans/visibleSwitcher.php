<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Bean');
class visibleSwitcher extends BLW_PSP_Bean {
	protected static $id = null;
	public function onInit() { }
	
	public function getId() {
		return self::$id;
	}
	
	public function setId($id) {
		$this->pcs->firePropertyChange('id', self::$id, $id);
		self::$id = $id;
	} 
	
	public static function switchVisible(BLW_PSP_ActionEvent $e) {
		$source = $e->getSource();
		$tag = $source->findTagById(self::$id);
		if($tag->getValue('visible') == "true") {
			$tag->setValue('visible', "false");
		} else {
			$tag->setValue('visible', "true");
		}
	} 
}
?>