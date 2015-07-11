<?php
BLW_CORE_ClassLoader::import("app.lib.PSP.BLW_PSP_Renderer");

class BLW_PSP_HtmlCommandRenderer extends BLW_PSP_Renderer {
	public function render(BLW_PSP_UIComponent $component, $id_prefix = null) {
		// get the writer
		$page_context = BLW_PSP_FacesContext::instance()->getViewRoot()->getPageContext();
		$writer = $page_context->getResponse()->getWriter();
		
		$attributes = $component->getAttributesToRender();
		
		switch($component->getRenderType()) {
			
			case'commandButton':{
				// start the element
				$writer->startElement('button');
				
				// add attributes to element
				if($attributes->offsetExists('type')) {
					$writer->writeAttribute('type', $attributes->offsetGet('type'));
				}
				
				$writer->writeAttribute('name', $attributes->offsetGet('id'));
				
				if($attributes->offsetExists('value')) {
					$writer->writeAttribute('value', $attributes->offsetGet('value'));
				}
				
				if($attributes->offsetExists('accesskey')) {
					$writer->writeAttribute('accesskey', $attributes->offsetGet('accesskey'));
				}
				
				if($attributes->offsetExists('disabled')) {
					$writer->writeAttribute('disabled', $attributes->offsetGet('disabled'));
				}
				
				if($attributes->offsetExists('lang')) {
					$writer->writeAttribute('lang', $attributes->offsetGet('lang'));
				}
				
				if($attributes->offsetExists('style')) {
					$writer->writeAttribute('style', $attributes->offsetGet('style'));
				}
				
				if($attributes->offsetExists('tabindex')) {
					$writer->writeAttribute('tabindex', $attributes->offsetGet('tabindex'));
				}
				
				if($attributes->offsetExists('title')) {
					$writer->writeAttribute('title', $attributes->offsetGet('title'));
				}
				
				if($attributes->offsetExists('onClick')) {
					$writer->writeAttribute('onClick', $attributes->offsetGet('onClick'));
				}
				
				$writer->writeAttribute('id', $attributes->offsetGet('id'));
				
				$children = $component->getChildren();
				
				if(($children instanceof ArrayObject) && ($children->count() > 0)) {
					// add the rendered child elements to element
					foreach ($component->getChildren() as $child) {
						$child->getOutput();
					}
				} else {
					$writer->writeText($attributes->offsetGet('value'));
				}
		
				// close the element
				$writer->endElement('button');
				break;
			}
			case'commandLink': {
				// start the element
				$writer->startElement('a');
				
				// add attributes to element
				if($attributes->offsetExists('href')) {
					$writer->writeAttribute('href', $attributes->offsetGet('href'));
				} else {
					$writer->writeAttribute('href', '#');
				}
				
				if($attributes->offsetExists('hreflang')) {
					$writer->writeAttribute('hreflang', $attributes->offsetGet('hreflang'));
				}
				
				if($attributes->offsetExists('charset')) {
					$writer->writeAttribute('charset', $attributes->offsetGet('charset'));
				}
				
				if($attributes->offsetExists('accesskey')) {
					$writer->writeAttribute('accesskey', $attributes->offsetGet('accesskey'));
				}
				
				if($attributes->offsetExists('class')) {
					$writer->writeAttribute('class', $attributes->offsetGet('class'));
				}
				
				if($attributes->offsetExists('lang')) {
					$writer->writeAttribute('lang', $attributes->offsetGet('lang'));
				}
				
				if($attributes->offsetExists('name')) {
					$writer->writeAttribute('name', $attributes->offsetGet('name'));
				}
				
				if($attributes->offsetExists('style')) {
					$writer->writeAttribute('style', $attributes->offsetGet('style'));
				}
				
				if($attributes->offsetExists('tabindex')) {
					$writer->writeAttribute('tabindex', $attributes->offsetGet('tabindex'));
				}
				
				if($attributes->offsetExists('target')) {
					$writer->writeAttribute('target', $attributes->offsetGet('target'));
				}
				
				if($attributes->offsetExists('title')) {
					$writer->writeAttribute('title', $attributes->offsetGet('title'));
				}
				
				if($attributes->offsetExists('type')) {
					$writer->writeAttribute('type', $attributes->offsetGet('type'));
				}
				
				$writer->writeAttribute('id', $component->getId());
				
				$form = $component->findAncestorWithClass('BLW_PSP_UIForm');
				
				// $onClick = 'document.forms[\''.$form->getId().'\']'.'.elements[\''.$writer->writeAttribute('id', $attributes->offsetGet('id'));'].value = \''.$component->getId().'\';';
				
				$onClick = 'var input = document.createElement(\'input\');';
				$onClick.= 'input.setAttribute(\'type\', \'hidden\');';
				$onClick.= 'input.setAttribute(\'name\', \''. $attributes->offsetGet('id') .'\');';
				if($attributes->offsetExists('value')) {
					$onClick.= 'input.setAttribute(\'value\', \''.$attributes->offsetGet('value').'\');';
				}
				$onClick.= 'document.getElementById(\''.$component->findAncestorWithClass('BLW_PSP_UIForm')->getId().'\').appendChild(input);';
				$onClick.= 'document.getElementById(\''.$component->findAncestorWithClass('BLW_PSP_UIForm')->getId().'\').submit();';
								
				// $onClick.= 'document.forms[\''.$form->getId().'\'].submit(); return false;';
				
				$writer->writeAttribute('onClick', $onClick);
				
				$children = $component->getChildren();
				if(($children instanceof ArrayObject) && ($children->count() > 0)) {
					// add the rendered child elements to element
					foreach ($component->getChildren() as $child) {
						$child->getOutput();
					}
				} else {
					$writer->writeText($attributes->offsetGet('value'));
				}
				
				// close the element
				$writer->endElement('a');
				break;
			}
			
		}
	}
}
?>