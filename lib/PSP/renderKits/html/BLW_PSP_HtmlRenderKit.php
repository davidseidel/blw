<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_RenderKit');
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlFormRenderer');
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlPanelRenderer');
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlCommandRenderer');
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlInputRenderer');
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.html.BLW_PSP_HtmlOutputRenderer');

class BLW_PSP_HTMLRenderKit extends BLW_PSP_Renderkit {
	private $renderer = null;
	
	public function __construct() {
		$this->renderer = new ArrayObject();
	}
	
	public function loadConfig() {
		$this->renderer->offsetSet('Form:form', new BLW_PSP_HtmlFormRenderer());
		$this->renderer->offsetSet('Output:message', new BLW_PSP_HtmlOutputRenderer());
		$this->renderer->offsetSet('Input:inputText', new BLW_PSP_HtmlInputRenderer());
		$this->renderer->offsetSet('Input:inputSecret', new BLW_PSP_HtmlInputRenderer());
		$this->renderer->offsetSet('Input:inputTextArea', new BLW_PSP_HtmlInputRenderer());
		$this->renderer->offsetSet('Panel:panel', new BLW_PSP_HtmlPanelRenderer());
		$this->renderer->offsetSet('Command:commandButton', new BLW_PSP_HtmlCommandRenderer());
		$this->renderer->offsetSet('Command:commandLink', new BLW_PSP_HtmlCommandRenderer());
	}
	
	public function addRenderer($family, $rendererType, BLW_PSP_Renderer $renderer) {
		$this->renderer->offsetSet($family.':'.$rendererType, $renderer);
	}
	
	public function getRenderer($family, $rendererType) {
		return $this->renderer->offsetGet($family.':'.$rendererType);
	}
}
?>