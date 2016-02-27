<?php

	class ShowBizFront extends UniteBaseFrontClassBiz{
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct($mainFilepath){
			
			parent::__construct($mainFilepath,$this);
			GlobalsShowBiz::initGlobals();			
		}		
		
		/**
		 * 
		 * a must function. you can not use it, but the function must stay there!.
		 */		
		public static function onAddScripts(){
			
			$operations = new BizOperations();
			$arrValues = $operations->getGeneralSettingsValues();
			
			$includesGlobally = UniteFunctionsBiz::getVal($arrValues, "includes_globally","on");
			$includesFooter = UniteFunctionsBiz::getVal($arrValues, "js_to_footer","off");
			$strPutIn = UniteFunctionsBiz::getVal($arrValues, "pages_for_includes");
			$isPutIn = ShowBizOutput::isPutIn($strPutIn,true);
			$includeFancy = UniteFunctionsBiz::getVal($arrValues, "includes_globally_facybox","on");
					
					
			//put the includes only on pages with active widget or shortcode
			// if the put in match, then include them always (ignore this if)			
			if($isPutIn == false && $includesGlobally == "off"){
				$isWidgetActive = is_active_widget( false, false, "showbiz-widget", true );
				$hasShortcode = UniteFunctionsWPBiz::hasShortcode("showbiz");
				
				if($isWidgetActive == false && $hasShortcode == false)
					return(false);
			}
			
			
			self::addStyle("settings","showbiz-settings","showbiz-plugin/css");
						
			$url_jquery = "http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js?app=showbiz";
			self::addScriptAbsoluteUrl($url_jquery, "jquery");
			
			if($includeFancy == "on"){
				self::addStyle("jquery.fancybox","fancybox","showbiz-plugin/fancybox");
				self::addScript("jquery.fancybox.pack","showbiz-plugin/fancybox","fancybox");
			}
			
			if($includesFooter == "off"){
				self::addScript("jquery.themepunch.plugins.min","showbiz-plugin/js",'themepunchtools');
				self::addScript("jquery.themepunch.showbizpro.min","showbiz-plugin/js");
			}else{
				//put javascript to footer
				UniteBaseClassBiz::addAction('wp_footer', 'putJavascript');
			}
			
		}
		
		/**
		 * 
		 * javascript output to footer
		 */
		public function putJavascript(){
			$urlPlugin = UniteBaseClassBiz::$url_plugin."showbiz-plugin/";
			?>
			<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.plugins.min.js?rev=<?php echo GlobalsShowBiz::SLIDER_REVISION; ?>'></script>
			<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.showbizpro.min.js?rev=<?php echo  GlobalsShowBiz::SLIDER_REVISION; ?>'></script>
			<?php
		}
		
	}
	

?>