<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2006 by David Seidel. All rights reserved.
 *
 * To contact the author write to {@link mailto:seidel.david@googlemail.com David Seidel}
 * The latest version of BlueWonder can be obtained from: {@link http://www.bluewonder-framework.de/}
 *
 * @author David Seidel <seidel.david@googlemail.com>
 * @package blw.lib.PSP
 */

/**
 * base class for all render of faces
 */
abstract class BLW_PSP_Renderer {
	/**
	 * render a component
	 *
	 * @param BLW_PSP_UIComponent $component component which should be rendered
	 */
	public abstract  function render(BLW_PSP_UIComponent $component);
}
?>