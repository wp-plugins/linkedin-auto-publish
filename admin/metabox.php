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
		
		$postpp= get_post($postid);
		if($postpp->post_status=="publish")
			add_meta_box("xyz_lnap1", ' ', 'xyz_lnap_addpostmetatags1') ;
		
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

function xyz_lnap_addpostmetatags1()
{
?>
	<input type="hidden" name="xyz_lnap_hidden_meta" value="1" >
	<script type="text/javascript">
		jQuery('#xyz_lnap1').hide();
		</script>
<?php 
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
<table class="xyz_lnap_metalist_table">

<tr ><td colspan="2" >

<table class="xyz_lnap_meta_acclist_table"><!-- LI META -->


<tr>
		<td colspan="2" class="xyz_lnap_pleft15 xyz_lnap_meta_acclist_table_td"><strong>LinkedIn</strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	
	<tr valign="top">
		<td class="xyz_lnap_pleft15">Enable auto publish	posts to my linkedin account
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
		<td class="xyz_lnap_pleft15">Attach image to linkedin post
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
	<td class="xyz_lnap_pleft15">Share post content with</td>
	<td>
		<select id="xyz_lnap_ln_shareprivate" name="xyz_lnap_ln_shareprivate" >
		 <option value="0" <?php  if(get_option('xyz_lnap_ln_shareprivate')==0) echo 'selected';?>>
Public</option><option value="1" <?php  if(get_option('xyz_lnap_ln_shareprivate')==1) echo 'selected';?>>Connections only</option></select>
	</td></tr>

	<tr valign="top" id="lnmf_lnap">
		<td class="xyz_lnap_pleft15">Message format for posting <img src="<?php echo $heimg?>"
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
	<select name="xyz_lnap_info" id="xyz_lnap_info" onchange="xyz_lnap_info_insert(this)">
		<option value ="0" selected="selected">--Select--</option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		</select> </td></tr><tr><td>&nbsp;</td><td>
		<textarea id="xyz_lnap_lnmessage"  name="xyz_lnap_lnmessage" style="height:80px !important;" ><?php echo esc_textarea(get_option('xyz_lnap_lnmessage'));?></textarea>
	</td></tr>
	
	</table>
	
	</td></tr>
	
	
</table>
<script type="text/javascript">
	displaycheck_lnap();

function xyz_lnap_info_insert(inf){
		
	    var e = document.getElementById("xyz_lnap_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_lnap_lnmessage").val()+ins_opt;
	    jQuery("textarea#xyz_lnap_lnmessage").val(str);
	    jQuery('#xyz_lnap_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_lnap_lnmessage").focus();

	}
	</script>
<?php 
}
?>