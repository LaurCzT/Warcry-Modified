<?PHP
if (!defined('init_pages'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

?>

<!-- Secondary navigation -->
<nav id="secondary" class="disable-tabbing">
	<ul>
		<li class="current"><a href="index.php?page=media">Movies</a></li>
        <li><a href="index.php?page=movie-add">New Movie</a></li>
		<li><a href="index.php?page=screenshots">Screenshots</a></li>
	</ul>
</nav>

<?php
if ($success = $ERRORS->successPrint(array('add_movie', 'delete_movie')))
{
	echo $success;
}
unset($success);
if ($error = $ERRORS->DoPrint('delete_movie'))
{
	echo $error;
}
unset($error);
		
//check for permissions
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_MEDIA_MOVIES))
{
	$CORE->ErrorBox('You do not have the required permissions.');
}
?>

<!-- The content -->
<section id="content">

    <div class="tab" id="maintab">
      	<h2>Movies Management</h2>
        
        <div>
            <div class="edit-form">
                <form action="execute.php?take=edit_movie" method="post" id="edit_movie">
                    <section>
                        <label>Name</label>

                        <div>
                            <input type="hidden" name="id" value="" />
                            <input type="text" placeholder="Required" class="required" name="name">
                        </div>
                    </section>

                    <section>
                        <label>Description</label>
                        <div>
                            <input type="text" placeholder="Required" class="required" name="descr">
                        </div>
                    </section>

                    <section>
                        <label>Image</label>
                        <div>
                            <input type="text" placeholder="Required" class="required" name="image">
                        </div>
                    </section>

                    <section>
                        <label>Short Text</label>
                        <div>
                            <input type="text" placeholder="Required" class="required" name="short_text">
                        </div>
                    </section>

                    <section>
                        <input type="submit" class="button primary big" value="Save">
                        <input type="submit" class="button primary big cancelEdit" value="Cancel">
                    </section>
                </form>
            </div>



            <?php
				//Pull them movies
				$res = $DB->query("SELECT `id`, `name`, `image`, `dirname` FROM `movies` ORDER BY `id` DESC;");
				
				if ($res->rowCount() > 0)
				{
					echo '<ul class="imagelist">';
					
					while ($arr = $res->fetch())
					{
						echo '
						<li>
							<img src="', $config['BaseURL'], '/uploads/media/movies/', $arr['dirname'], '/thumbnails/medium_', $arr['image'], '" alt="', stripslashes($arr['name']), '" style="opacity: 1;">
							<span>
								<a href="', $config['BaseURL'], '/index.php?page=open-video&id=', $arr['id'], '" target="_new" class="name ajax cboxElement">', substr(stripslashes($arr['name']), 0, 20), (strlen(stripslashes($arr['name'])) > 20 ? '...' : ''), '</a>
								<a href="#" data-id="'.$arr['id'].'" class="edit ajax cboxElement editAJAX"></a>
								<a href="execute.php?take=delete&action=movie&id='.$arr['id'].'" class="delete" onclick="return deletecheck(\'Are you sure you want to delete this movie?\');"></a>
							</span>
						</li>';
					}
					
					echo '</ul>';
				}
				else
				{
					echo '<p>There are no movies.</p>';
				}
				unset($res);
			?>
            
        </div>
        <div class="clear"></div>
        
    </div>

<script>
	$(document).ready(function()
	{
        var name = $('input[name="name"]');
        var description = $('input[name="descr"]');
        var image = $('input[name="image"]');
        var short_text = $('input[name="short_text"]');
        var idd = $('input[name="id"]');
        $('.edit-form').fadeOut("fast").hide();

        $('.cancelEdit').click(function(e){
            e.preventDefault();
            name.val("");
            description.val("");
            image.val("");
            short_text.val("");
            idd.val("");
            $('.edit-form').fadeOut("fast").hide();
        });

        $('.editAJAX').click(function(e){
            $('.edit-form').fadeOut("fast").hide();
            id = $(this).attr('data-id');

            name.val("");
            description.val("");
            image.val("");
            short_text.val("");
            idd.val("");

            $.ajax({
                url: 'ajax.php?phase=20',
                type: 'GET',
                data: {id: id},
                success: function(data) {
                    var data = JSON.parse(data);
                    idd.val(data.id);
                    name.val(data.name);
                    description.val(data.descr);
                    image.val(data.image);
                    short_text.val(data.short_text);
                    $('.edit-form').fadeIn("fast").show();
                }
            });
        });


		$('.imagelist img').hover(function()
		{
			console.log('test');
			$(this).stop().animate({ opacity: '0.75'}, 'fast');
		},
		function()
		{
			$(this).stop().animate({ opacity: '1'}, 'fast');
		});
	});
</script>