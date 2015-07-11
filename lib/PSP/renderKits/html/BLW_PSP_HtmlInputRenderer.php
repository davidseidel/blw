<?php
BLW_CORE_ClassLoader::import("app.lib.PSP.BLW_PSP_Renderer");

class BLW_PSP_HtmlInputRenderer extends BLW_PSP_Renderer {
	public function render(BLW_PSP_UIComponent $component) {
		// get the writer
		$page_context = BLW_PSP_FacesContext::instance()->getViewRoot()->getPageContext();
		$writer = $page_context->getResponse()->getWriter();
		$attributes = $component->getAttributesToRender();
		
		
		// start the element
		switch($component->getRenderType()) {
			case 'inputText':
			case 'inputSecret' :
			case 'inputHidden' : {
				$writer->startElement('input');
				break;
			}
			
			case'inputTextArea' : {
				$writer->startElement('textarea');
				break;
			}
		}
		
		// add attributes to element
		if($attributes->offsetExists('accesskey')) {
			$writer->writeAttribute('accesskey', $attributes->offsetGet('accesskey'));
		}
		
		if($attributes->offsetExists('style')) {
			$writer->writeAttribute('style', $attributes->offsetGet('style'));
		}
		
		if($attributes->offsetExists('class')) {
			$writer->writeAttribute('class', $attributes->offsetGet('class'));
		}
		
		if($attributes->offsetExists('tabindex')) {
			$writer->writeAttribute('tabindex', $attributes->offsetGet('tabindex'));
		}
		
		$writer->writeAttribute('id', $attributes->offsetGet('id'));
		$writer->writeAttribute('name', $attributes->offsetGet('id'));

		switch($component->getRenderType()) {
			
			case'inputText':{
				$writer->writeAttribute('type', 'text');
				
				if($attributes->offsetExists('disabled')) {
					$writer->writeAttribute('disabled', $attributes->offsetGet('disabled'));
				}
				
				if($attributes->offsetExists('maxlength')) {
					$writer->writeAttribute('maxlength', $attributes->offsetGet('maxlength'));
				}
				
				if($attributes->offsetExists('readonly')) {
					$writer->writeAttribute('readonly', $attributes->offsetGet('readonly'));
				}
				
				if($attributes->offsetExists('value')) {
					$writer->writeAttribute('value', $attributes->offsetGet('value'));
				}
				break;
			}
			
			case'inputSecret':{
				$writer->writeAttribute('type', 'password');
				
				if($attributes->offsetExists('maxlength')) {
					$writer->writeAttribute('maxlength', $attributes->offsetGet('maxlength'));
				}
				
				if($attributes->offsetExists('value')) {
					$writer->writeAttribute('value', $attributes->offsetGet('value'));
				}
				break;
			}
			
			case'inputHidden':{
				$writer->writeAttribute('type', 'hidden');
				
				if($attributes->offsetExists('value')) {
					$writer->writeAttribute('value', $attributes->offsetGet('value'));
				}
				break;
			}
			
			case'inputTextArea': {
				if($attributes->offsetExists('rows')) {
					$writer->writeAttribute('rows', $attributes->offsetGet('rows'));
				}
				
				if($attributes->offsetExists('cols')) {
					$writer->writeAttribute('cols', $attributes->offsetGet('cols'));
				}
				if($attributes->offsetExists('maxlength')) {
					$writer->writeAttribute('maxlength', $attributes->offsetGet('maxlength'));
				}
				
				if($attributes->offsetExists('wrap')) {
					$writer->writeAttribute('wrap', $attributes->offsetGet('wrap'));
				}
				
				if($attributes->offsetExists('disabled')) {
					$writer->writeAttribute('disabled', $attributes->offsetGet('disabled'));
				}
				
				$writer->writeText($attributes->offsetGet('value'));
				break;
			}
		}
		// close the element
		switch($component->getRenderType()) {
			case 'inputText':
			case 'inputSecret' :
			case 'inputHidden' : {
				$writer->endElement('input');
				break;
			}
			
			case 'inputTextArea' : {
				$writer->endElement('textarea');
				break;
			}
		}
	}
}
?>