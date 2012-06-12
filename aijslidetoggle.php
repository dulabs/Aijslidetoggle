<?php
/*
// AiJSlideToggle Joomla plugin - Version 1.x
// License: http://www.gnu.org/copyleft/gpl.html
// Copyright (c) 2009-2010, Abdul Ibad.
// More info at http://ibad.bebasbelanja.com
// Developers: Abdul Ibad
// ***Last update: Jan 7th, 2010***
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');
JPlugin::loadLanguage( 'plg_content_aijslidetoggle' );

class plgContentAiJSlidetoggle extends JPlugin
{

	//Constructor
	function plgContentAiJSlidetoggle( &$subject )
	{
		parent::__construct( $subject );
		// load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin( 'content', 'aijslidetoggle' );
		$this->_params = new JParameter( $this->_plugin->params );
	}


	function onPrepareContent(&$row, &$params, $limitstart) {

		// just startup
		global $mainframe;
	
		// checking
		if ( !preg_match("#{slidetoggle=.+?}#s", $row->text) ) {
			return;
		}


		$plugin =& JPluginHelper::getPlugin('content', 'aijslidetoggle');
		$pluginParams = new JParameter( $plugin->params );

		// j!1.5 paths
		$mosConfig_absolute_path 	= JPATH_SITE;
		$mosConfig_live_site 		= $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		if(substr($mosConfig_live_site, -1)=="/") $mosConfig_live_site = substr($mosConfig_live_site, 0, -1);

		// Parameters

		$slidetoggle_slidespeed 	= $pluginParams->get('slidetoggle_slidespeed', '400');
		$slidetoggle_display		= $pluginParams->get('slidetoggle_display', 'close');

			/*$header = "<!-- AiJeffect Plugin starts here -->
						<script type=\"text/javascript\" src=\"$mosConfig_live_site/plugins/content/plugin_aijeffect/jquery-1.3.2.js\"></script>";*/

		     $header = "<!-- AiJSlideToggle Plugin starts here -->
		<script type=\"text/javascript\" src=\"$mosConfig_live_site/plugins/content/plugin_aijslidetoggle/jquery.js\"></script>
		<script type=\"text/javascript\" src=\"$mosConfig_live_site/plugins/content/plugin_aijslidetoggle/aijslidetoggle.js\"></script>";
			// cache check
			if($mainframe->getCfg('caching') && ($option=='com_frontpage' || $option=='')) {
				echo $header;
			} else {
				$mainframe->addCustomHeadTag($header);
			}
		
		$openslide =JText::_('Click To Open');
		
	
		$p = $row->id;
		$uniqueSlideID = 0;
		$uniqueToggleID = 0;
		// Start SlideToggle Replacement
			if(!isset($_REQUEST['print']) && isset($_REQUEST['format'])!="pdf") {
			 if (preg_match_all("/{slidetoggle=.+?}/", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
	
			    foreach ($matches[0] as $match) {
				  $content = $row->text;
			      $match = str_replace("{slidetoggle=", "", $match);
			      $match = str_replace("}", "", $match);
				  
				  $title = $match;
				  $link = str_replace(" ","-",strtolower($title));	
				 
				 if(strstr($match,'|',true)){
				  $matches = explode("|",$match);
				  $title = $matches[0];
				  $closeOther = $matches[1];	 
				  $link = str_replace(" ","-",strtolower($title));	
					
				  	if(!empty($closeOther)){
					
						$onclick  = "aijslidetoggle_accordion('.wts-closeOther".$closeOther."','#hideslide".$uniqueSlideID.$p."',".$slidetoggle_slidespeed.")";
						$closeOtherID = 'wts-closeOther'.$closeOther." ";
					}
					
				  }else{
					 $onclick  = "aijslidetoggle('#hideslide".$uniqueSlideID.$p."',".$slidetoggle_slidespeed.")";
					 $closeOtherID = '';
				  }
				
				 
				  if($slidetoggle_display == "close"){
			      
					$content = str_replace( "{slidetoggle=".$match."}", "<div class=\"wts_title\"><a href=\"javascript:void(null);\" title=\"$openslide\" class=\"jtoggle\" id=\"".$link."\" onclick=\"".$onclick."\">".$title."</a></div><div class=\"wts_slidewrapper ".$closeOtherID."sliderwrapper".$uniqueSlideID."\" id=\"hideslide".$uniqueSlideID.$p."\" style=\"display:none\">", $content );
				
					}else{
					
					$content = str_replace( "{slidetoggle=".$match."}", "<div class=\"wts_title\"><a href=\"javascript:void(null);\" title=\"$openslide\" class=\"jtoggle\" id=\"".$link."\" onclick=\"".$onclick."\">".$title."</a></div><div class=\"wts_slidewrapper ".$closeOtherID."sliderwrapper".$uniqueSlideID."\" id=\"hideslide".$uniqueSlideID.$p."\">", $content );
									
					}
				
				  //$content = $content.$script;
			
			      $row->text = str_replace( "{/slidetoggle}", "</div>", $content );
			      $uniqueSlideID++;
				  $uniqueToggleID++;
		  			}
					
				}
			
			} else {
				if (preg_match_all("/{slidetoggle=.+?}/", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
					foreach ($matches[0] as $match) {
							$match = str_replace("{slidetoggle=", "", $match);
							$match = str_replace("}", "", $match);
							$row->text = str_replace( "{slidetoggle=".$match."}", "<h3>&nbsp;".$match."</h3><div>", $row->text );
							$row->text = str_replace( "{/slidetoggle}", "</div>", $row->text );
				}
			}
		}
		// End SlideToggle Replacement

	} // onPrepareContent

} // class

?>