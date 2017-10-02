<?php
if (!defined('init_pages'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

//Set the title
$TPL->SetTitle('Downloads');
//CSS
$TPL->AddCSS('template/style/page-support-all.css');
//Print the header
$TPL->LoadHeader();

?>
<div class="content_holder">

 <div class="sub-page-title">
  <div id="title"><h1>Downloads<p></p><span></span></h1></div>
 </div>
 
  	<div class="container_2 features" align="center">
    
    	<br/>
        
        <ul>
        
        	<!-- ADDON ROW -->
        	<li class="container_3 archived-news w-addons" id="launcher">
            	<div class="w-addon-row">
            	<img src="template/style/images/media/dclient.gif" width="268" height="163" alt="example Launcher (Windows)"/>
                <div class="addon-info">
                	<h1>example Launcher (Windows)</h1>
                    <p>
                        Here is the full client which you need to play.
                    </p>
                    <div class="war-links">
                    	<a class="download" href="<?php echo $config['BaseURL']; ?>/resources/addons/WOW Wotlk 3.3.5a.rar" title="Download example XP Rate Changer" target="_self">Download</a>
                    </div>
                </div>
                </div>
                <div class="clear"></div>
            </li>
            <!-- ADDON ROW . End -->

            <!-- Launcher ROW -->
        	<li class="container_3 archived-news w-addons" id="launcher">
            	<div class="w-addon-row">
            	<img src="template/style/images/media/dclient.gif" width="268" height="163" alt="example Launcher (MAC)"/>
                <div class="addon-info">
                	<h1>example Launcher (MAC)</h1>
                    <p>
                        Here is the full client which you need to play.
                    </p>
                    <div class="war-links">
                    	<a class="download" href="<?php echo $config['BaseURL']; ?>/resources/addons/WOTLK 3.3.5a.zip" title="Download example XP Rate Changer" target="_self">Download</a>
                    </div>
                </div>
                </div>
                <div class="clear"></div>
            </li>
            <!-- Launcher ROW . End -->
            
        </ul>
        
        
        <!-- FEATURES BG --> <div class="features-bg"></div>
        
    </div>
    
</div>

<?php
	$TPL->LoadFooter();
?>