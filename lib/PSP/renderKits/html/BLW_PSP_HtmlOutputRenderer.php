<?php
BLW_CORE_ClassLoader::import("app.lib.PSP.BLW_PSP_Renderer");

class BLW_PSP_HtmlOutputRenderer extends BLW_PSP_Renderer {
	public function render(BLW_PSP_UIComponent $component) {
		// get the writer
		$page_context = BLW_PSP_FacesContext::instance()->getViewRoot()->getPageContext();
		$writer = $page_context->getResponse()->getWriter();
		$attributes = $component->getAttributesToRender();
		$writer->startElement('span');
		$writer->writeAttribute('id', $attributes->offsetGet('id'));
		$writer->writeAttribute('style', $attributes->offsetGet('style'));
		$writer->writeText($attributes->offsetGet('value'));
		$writer->endElement('span');
	}
}
?>