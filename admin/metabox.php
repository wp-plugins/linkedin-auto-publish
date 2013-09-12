<?php 

add_action( 'add_meta_boxes', 'xyz_lnap_add_custom_box' );
function xyz_lnap_add_custom_box()
{
	$posttype="";
	if(isset($_GET['post_type']))
	$posttype=$_GET['post_type'];
	
if(isset($_GET['action']) && $_GET['action']=="edit")
	{
		$postid=$_GET['post'];
		$get_post_meta=get_post_meta($postid,"xyz_lnap",true);
		if($get_post_meta==1)
			return ;
		global $wpdb;
		$table='posts';
		$accountCount = $wpdb->query( 'SELECT * FROM '.$wpdb->prefix.$table.' WHERE id="'.$postid.'" and post_status!="draft" LIMIT 0,1' ) ;
		if($accountCount>0)
		return ;
	}

	if($posttype=="")
		$posttype="post";

	if ($posttype=="page")
	{

		$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
		if($xyz_lnap_include_pages==0)
			return;
	}
	else if($posttype!="post")
	{

		$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');


		$carr=explode(',', $xyz_lnap_include_customposttypes);
		if(!in_array($posttype,$carr))
			return;

	}

	if(get_option('xyz_lnap_lnaf')==0)
	add_meta_box( "xyz_lnap", '<strong>LinkedIn Auto Publish - Post Options</strong>', 'xyz_lnap_addpostmetatags') ;
}

function xyz_lnap_addpostmetatags()
{
	$imgpath= plugins_url()."/linkedin-auto-publish/admin/images/";
	$heimg=$imgpath."support.png";
	?>
<script>
function displaycheck_lnap()
{
var lcheckid=document.getElementById("xyz_lnap_lnpost_permission").value;
if(lcheckid==1)
{

	
    document.getElementById("lnimg_lnap").style.display='';
	document.getElementById("lnmf_lnap").style.display='';	
	document.getElementById("shareprivate_lnap").style.display='';	
}
else
{
    document.getElementById("lnimg_lnap").style.display='none';
	document.getElementById("lnmf_lnap").style.display='none';	
	document.getElementById("shareprivate_lnap").style.display='none';		
}

}


</script>
<script type="text/javascript">
function detdisplay_lnap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_lnap(id)
{
	document.getElementById(id).style.display='none';
}
</script>
<table>
	<tr valign="top">
		<td>Enable auto publish	posts to my linkedin account
		</td>
		<td><select id="xyz_lnap_lnpost_permission" name="xyz_lnap_lnpost_permission"
			onchange="displaycheck_lnap()">
				<option value="0"
				<?php  if(get_option('xyz_lnap_lnpost_permission')==0) echo 'selected';?>>
					No</option>
				<option value="1"
				<?php  if(get_option('xyz_lnap_lnpost_permission')==1) echo 'selected';?>>Yes</option>
		</select>
		</td>
	</tr>
	
	<tr valign="top" id="lnimg_lnap">
		<td>Attach image to linkedin post
		</td>
		<td><select id="xyz_lnap_lnpost_image_permission" name="xyz_lnap_lnpost_image_permission"
			onchange="displaycheck_lnap()">
				<option value="0"
				<?php  if(get_option('xyz_lnap_lnpost_image_permission')==0) echo 'selected';?>>
					No</option>
				<option value="1"
				<?php  if(get_option('xyz_lnap_lnpost_image_permission')==1) echo 'selected';?>>Yes</option>
		</select>
		</td>
	</tr>
	
	<tr valign="top" id="shareprivate_lnap">
	<input type="hidden" name="xyz_lnap_ln_sharingmethod" id="xyz_lnap_ln_sharingmethod" value="0">
	<td>Share post content with</td>
	<td>
		<select id="xyz_lnap_ln_shareprivate" name="xyz_lnap_ln_shareprivate" >
		 <option value="0" <?php  if(get_option('xyz_lnap_ln_shareprivate')==0) echo 'selected';?>>
Public</option><option value="1" <?php  if(get_option('xyz_lnap_ln_shareprivate')==1) echo 'selected';?>>Connections only</option></select>
	</td></tr>

	<tr valign="top" id="lnmf_lnap">
		<td>Message format for posting <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_lnap('xyz_lnap')" onmouseout="dethide_lnap('xyz_lnap')">
						<div id="xyz_lnap" class="informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - Insert the title of your post.<br />{PERMALINK} -
							Insert the URL where your post is displayed.<br />{POST_EXCERPT}
							- Insert the excerpt of your post.<br />{POST_CONTENT} - Insert
							the description of your post.<br />{BLOG_TITLE} - Insert the name
							of your blog.<br />{USER_NICENAME} - Insert the nicename
							of the author.
						</div>
		</td>
		<td>
		<textarea id="xyz_lnap_lnmessage" name="xyz_lnap_lnmessage"><?php echo esc_textarea(get_option('xyz_lnap_lnmessage'));?></textarea>
		</td>
	</tr>
	
</table>
<script type="text/javascript">
	displaycheck_lnap();
	</script>
<?php 
}
?>