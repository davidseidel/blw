<?php
BLW_CORE_ClassLoader::import("app.lib.PSP.BLW_PSP_Renderer"); 

class BLW_PSP_HtmlPanelRenderer extends BLW_PSP_Renderer {
	public function render(BLW_PSP_UIComponent $component) {
		// get the writer
		$page_context = BLW_PSP_FacesContext::instance()->getViewRoot()->getPageContext();
		$writer = $page_context->getResponse()->getWriter();
		
		// start the element
		$writer->startElement('div');
		
		// add attributes to element
		$attributes = $component->getAttributesToRender();
		if($attributes->offsetExists('class')) {
			$writer->writeAttribute('class', $attributes->offsetGet('class'));
		}
		
		$attributes = $component->getAttributesToRender();
		if($attributes->offsetExists('style')) {
			$writer->writeAttribute('style', $attributes->offsetGet('style'));
		}
		
		$writer->writeAttribute('id', $attributes->offsetGet('id'));
		
		// add the rendered child elements to element
		foreach ($component->getChildren() as $child) {
			$child->getOutput();
		}
		
		// close the element
		$writer->endElement('div');
	}
}
?>