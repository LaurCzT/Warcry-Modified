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
		<li class="current"><a href="index.php?page=forums">Forums</a></li>
		<li><a href="index.php?page=forum-cats">Categories</a></li>
	</ul>
</nav>

<?php
//check for permissions
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_FORUMS))
{
	$CORE->ErrorBox('You do not have the required permissions.');
}
?>

<section id="content">

<div class="tab" id="maintab">
    <div class="column left twothird">
        <h2>Forums</h2>

        <style>
            .sortable-placeholder {
                height: 45px;
            }
        </style>

        <script>
            $(function()
            {
                $("#sortable").sortable({
                    placeholder: "sortable-placeholder",
                    update: function ()
                    {
                        var elements = $('#sortable').find('tr');
                        var data = new Array();

                        for (var i = 0; i < elements.length; i++)
                        {
                            data[$(elements[i]).index()] = parseInt($(elements[i]).attr('data-id'));
                        }

                        $.post("./ajax.php?phase=6",
                            {
                                'order': data
                            },
                            function(data)
                            {
                                console.log(data);
                            });
                    }
                });
                $( "#sortable").disableSelection();
            });
        </script>

        <table id="datatable2" class="datatable datatable_editable">

            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="sortable">

            <?php

            $res = $DB->query("SELECT * FROM `wcf_forums` ORDER BY name DESC;");

            if ($res->rowCount() > 0)
            {
                while ($arr = $res->fetch())
                {
                    echo '
                          <tr data-id="', $arr['id'], '">
                            <td>', $arr['name'] ? $arr['name'] : 'undefined', '</td>
                                                     <td>
                                <div style="width: 500px;">
                                    <input type="text" disabled="disabled" value="', $arr['description'], '" id="cat-editable-', $arr['id'], '" isOpen="false" style="float: left;">
                                    <div class="clear"></div>
                                </div>
                                <div id="cat-editable-', $arr['id'], '-infobox" class="datatable_editable-infobox">Error</div>
                            </td>
                            <td>',
                            $arr["category"]
                            ,'</td>
                            <td>
                              <span class="button-group">
                                <a href="javascript: void(0);" onclick="return editableDatatable(this, \'cat-editable-', $arr['id'], '\', ', $arr['id'], ');" class="button icon edit">Edit</a>
                                <a href="execute.php?take=delete&action=forum&id=', $arr['id'], '" onclick="return deletecheck(\'Are you sure you want to delete this forum?\');" class="button icon remove danger">Remove</a>
                              </span>
                            </td>
                          </tr>';
                }
            }
            unset($res);

            ?>

            </tbody>

        </table>

    </div>

    <div class="column right third">

        <h2>Add New Forum</h2>

        <form action="execute.php?take=add_forum" method="post" id="add_forumcat_form">
            <section>
                <label>Title*</label>

                <div>
                    <input type="text" placeholder="Required" class="required" name="name" />
                </div>
            </section>

            <section>
                <label>Description*</label>
                <div>
                    <input type="text" placeholder="Required" class="required" name="description" />
                </div>
            </section>

            <section>
                <label>Category*</label>
                <div>
                    <input type="text" placeholder="Required" class="required" name="category" />
                </div>
            </section>

            <section>
                <label>Position*</label>
                <div>
                    <input type="text" placeholder="Required" class="required" name="position" />
                </div>
            </section>

            <section>
                <input type="submit" class="button primary big" value="Submit" />
            </section>
        </form>

    </div>

    <div class="clear"></div>

</div>

<script src="template/js/jquery.form.js" type="text/javascript"></script>
<script src="template/js/jquery.datatables.js" type="text/javascript"></script>
<script src="template/js/jquery-ui-1.10.0.sortable.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var $configURL = '<?php echo $config['BaseURL']; ?>';
    var $editable_onChangeTimer = null;
    var $click_bug_fix = new Date().getTime();

    //apply the BBcode Editors
    $(document).ready(function(e)
    {
        //Init datatables
        if ($("#datatable2").length > 0)
        {
            var newsTable = $("#datatable2").dataTable(
                {
                    "bFilter": false,
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 0 ] },
                        { "bSortable": false, "aTargets": [ 1 ] },
                        { "bSortable": false, "aTargets": [ 2 ] }
                    ]
                });
            //sort the table
            newsTable.fnSort( [ [0, 'asc'] ] );
        }

        //custom settings for validation
        $("#add_forumcat_form").validate(
            {
                rules:
                {
                    name:
                    {
                        required: true,
                        maxlength: 250
                    },
                    description:
                    {
                        required: true,
                        maxlength: 250
                    },
                    category:
                    {
                        required: true,
                        maxlength: 250
                    }
                }
            });
    });

    function editableDatatable(btn, id, record)
    {
        var input = $('#' + id);

        if (Math.abs($click_bug_fix - new Date().getTime()) < 300)
        {
            return false;
        }

        if (input.attr('isOpen') == 'false')
        {
            //activate the btn
            $(btn).addClass('active');
            //activate the input
            input.removeAttr('disabled');
            //activate the select
            input.parent().find('.cmf-skinned-select').parent().css('display', 'block');
            //set the isOpen attr
            input.attr('isOpen', 'true');
        }
        else
        {
            //deactivate the btn
            $(btn).removeClass('active');
            //deactivate the input
            input.attr('disabled', 'disabled');
            //deactivate the select
            input.parent().find('.cmf-skinned-select').parent().css('display', 'none');
            //set the isOpen attr
            input.attr('isOpen', 'false');
            //save for the data
            save_CategoryData(id, record);
        }

        $click_bug_fix = new Date().getTime();

        return false;
    }

    function save_CategoryData(id, record)
    {
        var input = $('#' + id);
        var select = $('#' + id + '-select');
        var infobox = $('#' + id + '-infobox');

        $.post(
            $configURL + "/admin/execute.php?take=edit_forum",
            {
                id: record,
                description: input.val()
            },
            function(data)
            {
                //check for errors
                if (data != 'OK' && data != 'SKIP')
                {
                    infobox.html(data);
                    infobox.fadeIn('fast');
                }
                else if (data != 'SKIP')
                {
                    //check if we need to hide the infobox
                    if (infobox.css('display') != 'none')
                    {
                        infobox.fadeOut('fast');
                    }
                    else
                    {
                        infobox.html('Saved!');
                        infobox.css('display', 'block');
                        infobox.fadeOut('slow');
                    }
                }
            }
        );
    }
</script>
