<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_UIComponent');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ActionSource');
abstract class BLW_PSP_UICommand extends BLW_PSP_UIComponent implements BLW_PSP_ActionSource {
	protected $listeners = null;
	protected $method_expression = null;
	
	public function onInit() {
		parent::onInit();
		$this->listeners = new ArrayObject();
	}
	
	public function addActionListener(BLW_PSP_ActionListener $listener) {
		$this->listeners->offsetSet($listener->getId(), $listener);
	}
	
	public function getActionListeners() {
		return $this->listeners;
	}
	
	public function broadcast(BLW_PSP_ActionEvent $event) {
		foreach ($this->listeners as $listener) {
			$listener->processAction($event);
		}
	}
	
    /**
    * returns, if present, an action expression with the class and the static method called by performing this event
    */
    public function getActionExpression() {
    	return $this->getValue('action');
    }
     
    public function setActionExpression($expression) {
    	$this->setValue('action', $expression);
    }
	
}
?>