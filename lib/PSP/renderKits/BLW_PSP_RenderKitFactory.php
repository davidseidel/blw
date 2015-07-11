<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlRenderKit');

class BLW_PSP_RenderKitFactory {
	protected $renderKits = null;
	private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
    
	public function __clone()
    {
        throw new Exception('Not allowed here!!!');
    }
	
	
	protected function __construct() {
		$this->renderKits = new ArrayObject();
		$this->renderKits->offsetSet('HTML', new BLW_PSP_HTMLRenderKit());
	}
	
	public function addRenderKit($renderKitId, BLW_PSP_RenderKit $renderKit) {
		$this->renderKits[$renderKitId] = $renderKit;
	}
          
   	public function getRenderKit($renderKitId) {
   		return $this->renderKits->offsetGet($renderKitId);
   	}
     
  	public function  getRenderKitIds() {
   		return array_keys($this->renderKits->getArrayCopy());
	}
}