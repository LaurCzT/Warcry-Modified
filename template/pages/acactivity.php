<?php
if (!defined('init_pages'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

$CORE->loggedInOrReturn();

$CORE->load_CoreModule('account.activity');
$ACTIVITY = new AccountActivity();

//Set the title
$TPL->SetTitle('Account Activity');
//CSS
$TPL->AddCSS('template/style/page-activity-all.css');
//Print the header
$TPL->LoadHeader();

// Pagination
if(!isset($_GET['p'])) {
    $page = 1;
} else {
    $page = $_GET['p'];
}

$offset = ($page - 1) * 5;
$total = $ACTIVITY->getActivitiesTotal();

$pageCount = $total / 5;
$lastPage = ceil($total / 5);

if($page > $lastPage || $page < 0) {
    $activities = NULL;
    $offset = 0;
} else {
    $activities = $ACTIVITY->getActivitiesFrom($offset);
}
?>

<div class="content_holder">

<div class="sub-page-title">
	<div id="title"><h1>Account Panel<p></p><span></span></h1></div>
  
    <div class="quick-menu">
    	<a class="arrow" href="#"></a>
        <ul class="dropdown-qmenu">
        	<li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=store">Store</a></li>
            <li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=teleporter">Teleporter</a></li>
            <li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=buycoins">Buy Coins</a></li>
            <li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=vote">Vote</a></li>
            <li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=pstore">Premium Store</a></li>
            <li><a href="<?php echo $config['BaseURL']; ?>/index.php?page=unstuck">Unstuck</a></li>
            <!--<li id="messages-ddm">
            	<a href="<?php echo $config['BaseURL']; ?>/index.php?page=pm">
                	<b>55</b> <i>Private Messages</i>
                </a>
            </li>-->
        </ul>
    </div>
</div>
 
  	<div class="container_2 account" align="center">
     <div class="cont-image">
    
      <div class="container_3 account_sub_header">
         <div class="grad">
       		<div class="page-title">Account activity</div>
       		<a href="<?php echo $config['BaseURL'], '/index.php?page=account'; ?>">Back to account</a>
      	 </div>
      </div>
      
      <!-- Account Activity -->
      	<div class="account-activity">
      		
       		<div class="page-desc-holder">
  		All your account activity will be shown on this page.
            </div>       
            
            <div class="container_3 account-wide" align="center">
            		<ul class="activity-list">
                        <?php
                        if($activities) {
                            foreach ($activities as $activity) {
                                switch ($activity["type"]) {
                                    case 'teleport':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b><?php echo htmlspecialchars($activity["char"]); ?></b> has been teleported succesfuly.</b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    case 'passupdate':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b>Your account password has been updated</b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    case 'codered':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b>You have redeemed code <?php echo htmlspecialchars($activity["addon"]) ?> on your account.</b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    case 'emailup':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b>You have updated email to <?php echo htmlspecialchars($activity["addon"]); ?></b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    case 'displayname':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b>Your display name has been change to <?php echo htmlspecialchars($activity["addon"]); ?></b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    case 'donate':
                                        ?>
                                        <li>
                                            <p id="r-title" class="act"><b>You have donated <?php echo htmlspecialchars($activity["addon"]); ?> to example.</b></p>
                                            <p id="ar-date"><?php echo $activity["date"]; ?></p>
                                        </li>
                                        <?php
                                        break;
                                    default:
                                        break;
                                }
                            }
                        } else { ?>
                            <li><p id="r-title"><i>There's no activity to show.</i></p></li>
                        <?php
                        }
                        ?>

            		</ul>
             
            </div>
            
            <!-- Pagination -->
	            <div class="d-cont wide pagination-holder">
                	<ul class="pagination" id="store-pagination">
                    
						<li id="pagination-nav-first"><a href="?page=acactivity&p=1">First</a></li>
                        <?php if($page != 1 && $page > 0) { ?>
	                    <li id="pagination-nav-prev"><a href="?page=acactivity&p=<?php echo $page - 1; ?>">Previous</a></li>
                        <?php } ?>
	                        
	                    <li id="pages"><p>|&nbsp;&nbsp;</p><?php echo $offset; ?>-<?php echo $total; ?> of <?php echo $lastPage; ?><p>&nbsp;&nbsp;|</p></li>
                        <?php if($page < $lastPage) { ?>
					  	<li id="pagination-nav-next"><a href="?page=acactivity&p=<?php echo $page + 1; ?>">Next</a></li>
                        <?php } ?>
	                   	<li id="pagination-nav-last"><a href="?page=acactivity&p=<?php echo ceil($pageCount); ?>">Last</a></li>
						                    
                    </ul>
                    <div class="clear"></div>
	            </div>
            
      	</div>
      <!-- Account Activity.End -->
    
     </div>
	</div>
 
</div>
 
</div>

<?php
	$TPL->LoadFooter();
?>