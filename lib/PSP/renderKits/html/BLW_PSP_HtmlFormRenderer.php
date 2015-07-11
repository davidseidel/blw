<?php
BLW_CORE_ClassLoader::import("app.lib.PSP.BLW_PSP_Renderer"); 

class BLW_PSP_HtmlFormRenderer extends BLW_PSP_Renderer {
	public function render(BLW_PSP_UIComponent $component, $id_prefix = null) {
		// get the writer
		$page_context = BLW_PSP_FacesContext::instance()->getViewRoot()->getPageContext();
		$writer = $page_context->getResponse()->getWriter();
		
		// start the element
		$writer->startElement('form');
		
		// add attributes to element
		$attributes = $component->getAttributesToRender();
		if($attributes->offsetExists('action')) {
			$writer->writeAttribute('action', $attributes->offsetGet('action'));
		}
		
		if($attributes->offsetExists('method')) {
			$writer->writeAttribute('method', $attributes->offsetGet('method'));
		}
		
		if($attributes->offsetExists('enctype')) {
			$writer->writeAttribute('enctype', $attributes->offsetGet('enctype'));
		}
		
		if($attributes->offsetExists('target')) {
			$writer->writeAttribute('target', $attributes->offsetGet('target'));
		}
		
		if($attributes->offsetExists('onSubmit')) {
			$writer->writeAttribute('onSubmit', $attributes->offsetGet('onSubmit'));
		}
		
		$writer->writeAttribute('id', $attributes->offsetGet('id'));
		$writer->writeAttribute('name', $attributes->offsetGet('id'));
		
		// add the rendered child elements to element
		foreach ($component->getChildren() as $child) {
			$child->getOutput();
		}
		
		// close the element
		$writer->endElement('form');
	}
}
?>