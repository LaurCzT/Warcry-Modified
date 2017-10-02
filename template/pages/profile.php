<?php
if (!defined('init_pages'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

//Set the title
$TPL->SetTitle('User Profile');
//Print the header
$TPL->LoadHeader();

$uid = isset($_GET["uid"]) ? $_GET["uid"] : false;

if($uid) {
    $CORE->load_CoreModule('profile');
    $profile = new Profile($uid);
    $userProfile = $profile->getData();
}

?>

<div class="content_holder">

 <div class="sub-page-title">
  <div id="title"><h1>User's Profile<p></p><span></span></h1></div>
 </div>
 
  	<div class="container_2" align="center">
            <?php if(!$uid) { ?>
            <div class="container_3 archived-news under-construction" align="left">
                <div class="holder">
                    <h5>oops...<span></span></h5>
                    <h4>There's nobody with this profile.<span></span></h4>
               	</div>
            </div>
            <?php } else {?>
                <div class="container_3 account_light_cont account_info_cont" align="left">
                    <div class="account_info" align="left">
                        <ul class="account_avatar">
                            <li id="avatar"><span style="background:url(./resources/avatars/<?php echo htmlspecialchars($userProfile["avatar"]); ?>) no-repeat; background-size: 100%;"></span><p></p></li>
                        </ul>
                        <ul class="account_info_main">
                            <li id="displayname"><span>Display name:</span><p><?php echo htmlspecialchars($userProfile["name"]); ?></p></li>
                            <li id="rank"><span>Rank:</span><p><?php echo htmlspecialchars($userProfile["rank"]); ?></p></li>
                            <li><span>Last login:</span><p><?php echo htmlspecialchars($userProfile["last_login"]); ?></p></li>
                        </ul>
                        <ul class="account_info_second">
                            <li><span>Referred members:</span><p><a href="http://www.example.com/index.php?page=recruit-a-friend"><?php echo htmlspecialchars($userProfile["refCount"]); ?></a></p></li>
                        </ul>
                        <div class="clear"></div>
                    </div>


            <?php } ?>
    	</div>
        
    </div>
    
</div>

<?php
	$TPL->LoadFooter();
?>