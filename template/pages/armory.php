<?php
if (!defined('init_pages'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

$TPL->AddCSS('template/style/armory.css');

//Set the title
$TPL->SetTitle('Armory');
//Print the header
$TPL->LoadHeader();
$CORE->load_ServerModule('armory');

$ARMORY = new Armory();
$realms = $ARMORY->getRealms();



if(isset($_GET["name"]) == true && isset($_GET["realm"]) == true) {
    $name = $_GET["name"];
    $realm = $_GET["realm"];
    if($name && $realm) {
        $data = $ARMORY->getData($name, $realm);
    }
}


?>
<div class="content_holder armory-page">
 <div class="sub-page-title">
  <div id="title"><h1>Armory<p></p><span></span></h1></div>
 </div>
    <div class="cont-image">
        <?php if(!isset($_GET["name"]) || $_GET["name"] == '' || !isset($_GET["realm"]) || $_GET["realm"] == '') {?>
        <div class="container_3 account-wide armory-search" align="center">
            <br><br><br>
            <div class="armory-search-input">
                <form action="http://www.example.com/execute.php?take=armory" method="get">
                    <input type="text" name="name" placeholder="Search for characters...">
                    <select styled="true" id="realm-select" name="realm" style="display:none">
                        <?php
                            foreach($realms as $realm) {
                                printf("<option value='%s'>%s</option>", $realm["id"], $realm["name"]);
                            }
                        ?>
                    </select>
                    <input type="submit" value="Search">
                </form>
            </div>
        </div>
        <div class="container_3 account-wide search-results" align="center">
            <p class="no-input">There's no character to show, please write a character name.</p>
        </div>
        <?php } else if(!$ARMORY->checkValidity($name, $realm)){ ?>
        <div class="container_3 account-wide search-results" align="center">
                <p class="no-input">There's no character with that name.</p>
            </div>
           <?php }else {?>
            <div class="left-armory">
                <div class="general-info">
                    <img src="./template/style/images/armory/<?php echo (array_key_exists('avatar', $data) ? $data["avatar"] : 'default'); ?>.gif" class="profile-avatar" />
                    <div class="profile-level"><?php echo htmlspecialchars($data["level"]); ?></div>
                    <div class="profile-info">
                        <h4 class="name <?php echo str_replace(' ', '', strtolower($data["class"])); ?>"><?php echo htmlspecialchars($data["name"]); ?><div class="<?php echo ($data["online"] == 1 ? 'online' : 'offline'); ?>"></div></h4>
                        <h6 class="under-name">Level <?php echo htmlspecialchars($data["level"]); ?> <?php echo htmlspecialchars($data["class"]); ?></h6>
                        <h5><?php echo htmlspecialchars($data["guild"]); ?></h5>
                    </div>
                </div>
                <div class="powers">
                    <div class="health-bar"><?php echo htmlspecialchars($data["health"]); ?></div>
                    <div class="power-bar <?php echo htmlspecialchars($data["powerType"]); ?>""><?php echo htmlspecialchars($data["power"]); ?></div>
                </div>
                <div class="stats">
                    <h3>Player vs Player</h3>
                    <div class="stat">
                        <h5>Arena Points</h5>
                        <p><?php echo htmlspecialchars($data["arena"]); ?></p>
                    </div>
                    <div class="stat">
                        <h5>Honor Points</h5>
                        <p><?php echo htmlspecialchars($data["honor"]); ?></p>
                    </div>
                    <div class="stat">
                        <h5>PvP</h5>
                        <p><?php echo htmlspecialchars($data["kills"]); ?> kills</p>
                    </div>
                </div>
                <div class="stats">
                    <h3>Professions</h3>
                    <?php if($proffesions= $data["proffesions"]) {
                        foreach ($proffesions as $proffesion) {
                            ?>
                            <div class="stat">
                                <h5><?php echo $proffesion["name"]; ?></h5>

                                <p><?php echo $proffesion["value"].'/'.$proffesion["max"]; ?></p>
                            </div>
                        <?php
                        }
                    } else {?>
                        <div class="stat">
                            <p>No proffesions known by this character.</p>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
            <div class="center-armory">
                <h3>Last 5 Achievements</h3>
                <ul>
                    <?php if($data["achievements"]) {
                        foreach($data["achievements"] as $achievement) {?>
                            <li><a href="http://wotlk.wowhead.com/achievement=<?php echo $achievement["achievement"]; ?>">[<?php echo $achievement["name"]; ?>]</a></li>
                        <?php
                        }
                      }
                     else { ?>
                        <li>No achievements found.</li>
                    <?php }?>
                </ul>
            </div>
            <div class="character-model">
                <div class="left-row">
                    <div class="item">
                        <?php echo ($data["itemsIDs"][0] == 0 ? '<img src="./template/style/images/armory/icons/head.png" />' : $ARMORY->getItemData($data["itemsIDs"][0])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][2] == 0 ? '<img src="./template/style/images/armory/icons/neck.png" />' : $ARMORY->getItemData($data["itemsIDs"][2])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][4] == 0 ? '<img src="./template/style/images/armory/icons/shoulder.png" />' : $ARMORY->getItemData($data["itemsIDs"][4])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][28] == 0 ? '<img src="./template/style/images/armory/icons/back.png" />' : $ARMORY->getItemData($data["itemsIDs"][28])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][8] == 0 ? '<img src="./template/style/images/armory/icons/chest.png" />' : $ARMORY->getItemData($data["itemsIDs"][8])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][6] == 0 ? '<img src="./template/style/images/armory/icons/shirt.png" />' : $ARMORY->getItemData($data["itemsIDs"][6])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][36] == 0 ? '<img src="./template/style/images/armory/icons/tabard.png" />' :  $ARMORY->getItemData($data["itemsIDs"][36])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][16] == 0 ? '<img src="./template/style/images/armory/icons/wrist.png" />' : $ARMORY->getItemData($data["itemsIDs"][16])); ?>
                    </div>
                </div>
                <div class="bottom-row">
                    <div class="item">
                        <?php echo ($data["itemsIDs"][30] == 0 ? '<img src="./template/style/images/armory/icons/mainhand.png" />' : $ARMORY->getItemData($data["itemsIDs"][30])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][32] == 0 ? '<img src="./template/style/images/armory/icons/secondaryhand.png" />' : $ARMORY->getItemData($data["itemsIDs"][32])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][34] == 0 ? '<img src="./template/style/images/armory/icons/ranged.png" />' : $ARMORY->getItemData($data["itemsIDs"][34])); ?>
                    </div>
                </div>
                <object type="application/x-shockwave-flash" data="http://wow.zamimg.com/modelviewer/ZAMviewerfp11.swf" id="dsjkgbdsg2346" class="model-viewer">
                    <param name="quality" value="high">
                    <param name="allowscriptaccess" value="always">
                    <param name="allowfullscreen" value="true">
                    <param name="menu" value="false">
                    <param name="bgcolor" value="#181818">
                    <param name="wmode" value="direct">
                    <param name="flashvars" value="<?php echo $ARMORY->generateModel($data["itemsIDs"], $data["raceID"], $data["gender"]); ?>">
                </object>
                <div class="right-row">
                    <div class="item">
                        <?php echo ($data["itemsIDs"][18] == 0 ? '<img src="./template/style/images/armory/icons/hands.png" />' : $ARMORY->getItemData($data["itemsIDs"][18])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][10] == 0 ? '<img src="./template/style/images/armory/icons/waist.png" />' : $ARMORY->getItemData($data["itemsIDs"][10])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][12] == 0 ? '<img src="./template/style/images/armory/icons/legs.png" />' : $ARMORY->getItemData($data["itemsIDs"][12])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][14] == 0 ? '<img src="./template/style/images/armory/icons/feet.png" />' : $ARMORY->getItemData($data["itemsIDs"][14])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][20] == 0 ? '<img src="./template/style/images/armory/icons/finger.png" />' : $ARMORY->getItemData($data["itemsIDs"][20])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][22] == 0 ? '<img src="./template/style/images/armory/icons/finger.png" />' : $ARMORY->getItemData($data["itemsIDs"][22])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][24] == 0 ? '<img src="./template/style/images/armory/icons/trinket.png" />' : $ARMORY->getItemData($data["itemsIDs"][24])); ?>
                    </div>
                    <div class="item">
                        <?php echo ($data["itemsIDs"][26] == 0 ? '<img src="./template/style/images/armory/icons/trinket.png" />' : $ARMORY->getItemData($data["itemsIDs"][26])); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
$(function() {
    var submit = $('input[type="submit"]');
        input  = $('input[name="name"]');
        realm  = $('select');


    submit.click(function(e){
        e.preventDefault();
        searchCharacters();
    });

    input.keyup(function(){
        searchCharacters();
    });

    realm.on("change", function(){
        searchCharacters();
    })

    function showResults(results) {
        var chars = JSON.parse(results);
        $('p.no-input').hide();
        $('.character').remove();

        for(var i = 0; i < chars.length; i++) {
            var charHTML = '<a href="index.php?page=armory&name='+chars[i].name+'&realm='+chars[i].realm+'"><div class="character"><div class="s-class-icon '+chars[i].class+'" style="background-image:url(http://wow.zamimg.com/images/wow/icons/medium/class_'+chars[i].class+'.jpg)"></div><div class="profile"><h5>'+chars[i].name+'</h5><p>level '+chars[i].level+'</p></div></div></a>';
            $('.search-results').append(charHTML);
        }
    }

    function searchCharacters() {
        $('.search-results').html('<div class="spinner"></div>');

        $.ajax({
            url: '/ajax.php?phase=26',
            data: {
                name: input.val(),
                realm: realm.val()
            },
            success: function(data) {
                if(!data) {
                    $('.search-results').html('<p class="no-input not-found">There are no character results having that name</p>');
                } else {
                   $('.spinner').remove();
                   showResults(data);
                }
            }
        });
    }
});
</script>

<?php
    $TPL->AddFooterJs($config['WoWDB_JS'], true);
	$TPL->LoadFooter();
?>