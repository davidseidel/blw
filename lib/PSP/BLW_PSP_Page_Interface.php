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

BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Request');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Response');
/**
 * basic interface for all pages
 *
 */
interface BLW_PSP_Page_Interface {
	/**
	 * this method is called during the initialisation of the page
	 *
	 */
	public function init();
	/**
	 * this is the main method of the page, which handles the request an process the response
	 *
	 * @param BLW_HTTP_Request $request
	 * @param BLW_HTTP_Response $response
	 */
	public function service(BLW_HTTP_Request $request, BLW_HTTP_Response $response);
	/**
	 * this method is called during destroying the page
	 *
	 */
	public function destroy();
}