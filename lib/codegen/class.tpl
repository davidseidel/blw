<?php
require_once('core/BLW_CORE_ClassLoader.php');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_PageContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ViewRoot');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Page');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateManager');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StaticContent');
#{import}


class #{classname} extends BLW_PSP_Page {
	
	public function init() {
		parent::init();
		#{init}
	}
	
	public function service(BLW_HTTP_Request $request, BLW_HTTP_Response $response) {
		// init page context
		$page_context = new BLW_PSP_PageContext($request, $response, $this);
		
		// init state-manager
		$state_manager = BLW_PSP_StateManager::instance();
	
		// set state to build object tree
		$state_manager->changeState(BLW_PSP_StateManager::BUILD_TREE);
		
		// init view-root
		$view_root = new BLW_PSP_ViewRoot();
		$view_root->setPageContext($page_context);
		
		#{service}
		
		// set state to load session values
		$state_manager->changeState(BLW_PSP_StateManager::LOAD_SESSION);
		
		// set state to load session values
		$state_manager->changeState(BLW_PSP_StateManager::DECODE_REQUEST);
		
		// set state to invoke the application
		$state_manager->changeState(BLW_PSP_StateManager::INVOKE_APPLICATION);
		
		// set state to render_view
		$state_manager->changeState(BLW_PSP_StateManager::RENDER_VIEW);
		
		// send response
		$view_root->getOutput();
		
		// set state to render_view
		$state_manager->changeState(BLW_PSP_StateManager::SEND_RESPONSE);
	}
	
	public function destroy() {
		#{destroy}
	}
}

$page = new #{classname}();
$page->init();
$page->service(new BLW_HTTP_Request(), new BLW_HTTP_Response());
$page->destroy();
?>