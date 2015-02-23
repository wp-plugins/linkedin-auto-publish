<?php

global $current_user;
$auth_varble=0;
get_currentuserinfo();
$imgpath= plugins_url()."/linkedin-auto-publish/admin/images/";
$heimg=$imgpath."support.png";

require( dirname( __FILE__ ) . '/authorization.php' );

$lms1="";
$lms2="";
$lms3="";
$lerf=0;

if(isset($_POST['linkdn']))
{
	$lnappikeyold=get_option('xyz_lnap_lnapikey');
	$lnapisecretold=get_option('xyz_lnap_lnapisecret');


	$lnappikey=$_POST['xyz_lnap_lnapikey'];
	$lnapisecret=$_POST['xyz_lnap_lnapisecret'];
	
	$lmessagetopost=trim($_POST['xyz_lnap_lnmessage']);
	
	$lnposting_permission=$_POST['xyz_lnap_lnpost_permission'];
	$xyz_lnap_ln_shareprivate=$_POST['xyz_lnap_ln_shareprivate'];
	$xyz_lnap_ln_sharingmethod=$_POST['xyz_lnap_ln_sharingmethod'];
	$xyz_lnap_lnpost_image_permission=$_POST['xyz_lnap_lnpost_image_permission'];
	if($lnappikey=="" && $lnposting_permission==1)
	{
		$lms1="Please fill linkedin api key";
		$lerf=1;
	}
	elseif($lnapisecret=="" && $lnposting_permission==1)
	{
		$lms2="Please fill linked api secret";
		$lerf=1;
	}
	elseif($lmessagetopost=="" && $lnposting_permission==1)
	{
		$lms3="Please fill mssage format for posting.";
		$lerf=1;
	}
	else
	{

		$lerf=0;
		
		if($lmessagetopost=="")
		{
			$lmessagetopost="New post added at {BLOG_TITLE} - {POST_TITLE}";
		}
		
		if($lnappikey!=$lnappikeyold || $lnapisecret!=$lnapisecretold )
		{
			update_option('xyz_lnap_lnaf',1);
		}

		
		update_option('xyz_lnap_lnapikey',$lnappikey);
		update_option('xyz_lnap_lnapisecret',$lnapisecret);
		update_option('xyz_lnap_lnpost_permission',$lnposting_permission);
		update_option('xyz_lnap_ln_shareprivate',$xyz_lnap_ln_shareprivate);
		update_option('xyz_lnap_ln_sharingmethod',$xyz_lnap_ln_sharingmethod);
		update_option('xyz_lnap_lnmessage',$lmessagetopost);
		update_option('xyz_lnap_lnpost_image_permission',$xyz_lnap_lnpost_image_permission);
		
}	
}

if(isset($_POST['linkdn']) && $lerf==0)
{
	?>

<div class="system_notice_area_style1" id="system_notice_area">
	Settings updated successfully. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php }
if(isset($_GET['msg']) && $_GET['msg']==1)
{
?>
<div class="system_notice_area_style0" id="system_notice_area">
	Unable to authorize the linkedin application. Please check the details. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
	<?php 
}
if(isset($_POST['linkdn']) && $lerf==1)
{
	?>
<div class="system_notice_area_style0" id="system_notice_area">
	<?php 
	if(isset($_POST['fb']))
	{
		echo $ms1;echo $ms2;echo $ms3;echo $ms4;
	}
	else if(isset($_POST['twit']))
	{
		echo $tms1;echo $tms2;echo $tms3;echo $tms4;echo $tms5;echo $tms6;
	}
	else if(isset($_POST['linkdn']))
	{
		echo $lms1;echo $lms2;echo $lms3;
	}
	?>
	&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php } ?>
<script type="text/javascript">
function detdisplay(id)
{
	document.getElementById(id).style.display='';
}
function dethide(id)
{
	document.getElementById(id).style.display='none';
}

function drpdisplay()
{
	var shmethod= document.getElementById('xyz_lnap_ln_sharingmethod').value;
	if(shmethod==1)	
	{
		document.getElementById('shareprivate').style.display="none";
	}
	else
	{
		document.getElementById('shareprivate').style.display="";
	}
}
</script>

<div style="width: 100%">

		
	<h2>
		 <img	src="<?php echo plugins_url()?>/linkedin-auto-publish/admin/images/linkedin.png" height="16px"> Linkedin Settings
	</h2>
	

<?php
$lnappikey=esc_html(get_option('xyz_lnap_lnapikey'));
$lnapisecret=esc_html(get_option('xyz_lnap_lnapisecret'));
$lmessagetopost=esc_textarea(get_option('xyz_lnap_lnmessage'));


$lnaf=get_option('xyz_lnap_lnaf');
	if($lnaf==1 && $lnappikey!="" && $lnapisecret!="" )
	{
	?>
	
	<span style="color:red; ">Application needs authorisation</span><br>	
            <form method="post" >
			
			<input type="submit" class="submit_lnap_new" name="lnauth" value="Authorize	" />
			<br><br>
			</form>
			<?php  }
			else if($lnaf==0 && $lnappikey!="" && $lnapisecret!="" )
			{
				?>
            <form method="post" >
			
			<input type="submit" class="submit_lnap_new" name="lnauth" value="Reauthorize" title="Reauthorize the account" />
			<br><br>
			</form>
			<?php }
			
if(isset($_GET['auth']) && $_GET['auth']==3)
			{
				if(isset($_GET['auth_problem']))
					break;
			?>
			
			<span style="color: green;">Application is authorized ,go posting </span><br>
			
			<?php 	
			}
			?>
			
			<table class="widefat" style="width: 99%;background-color: #FFFBCC">
	<tr>
	<td id="bottomBorderNone">
	
	<div>


		<b>Note :</b> You have to create a Linkedin application before filling the following details.
		<b><a href="https://www.linkedin.com/secure/developer?newapp" target="_blank">Click here</a></b> to create new Linkedin application. 
		<br>Specify the website url for the application as : 
		<span style="color: red;"><?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?></span>
<br>For detailed step by step instructions <b><a href="http://docs.xyzscripts.com/wordpress-plugins/linkedin-auto-publish/creating-linkedin-application/" target="_blank">Click here</a></b>.
	</div>

	</td>
	</tr>
	</table>

	

	<form method="post" >
	
	
			
	

	<div style="font-weight: bold;padding: 3px;">All fields given below are mandatory</div> 
	
	<table class="widefat xyz_lnap_widefat_table" style="width: 99%">
	<tr valign="top">
	<td width="50%">Api key </td>					
	<td>
		<input id="xyz_lnap_lnapikey" name="xyz_lnap_lnapikey" type="text" value="<?php if($lms1=="") {echo esc_html(get_option('xyz_lnap_lnapikey'));}?>"/>
	<a href="http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/creating-linkedin-application" target="_blank">How can I create a Linkedin Application?</a>
	</td></tr>
	

	<tr valign="top"><td>Api secret</td>
	<td>
		<input id="xyz_lnap_lnapisecret" name="xyz_lnap_lnapisecret" type="text" value="<?php if($lms2=="") { echo esc_html(get_option('xyz_lnap_lnapisecret')); }?>" />
	</td></tr>
	
	<tr valign="top">
					<td>Message format for posting <img src="<?php echo $heimg?>"
						onmouseover="detdisplay('xyz_ln')" onmouseout="dethide('xyz_ln')">
						<div id="xyz_ln" class="informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - Insert the title of your post.<br />{PERMALINK} -
							Insert the URL where your post is displayed.<br />{POST_EXCERPT}
							- Insert the excerpt of your post.<br />{POST_CONTENT} - Insert
							the description of your post.<br />{BLOG_TITLE} - Insert the name
							of your blog.<br />{USER_NICENAME} - Insert the nicename
							of the author.
						</div></td>
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
		<textarea id="xyz_lnap_lnmessage"  name="xyz_lnap_lnmessage" style="height:80px !important;" ><?php if($lms3==""){echo esc_textarea(get_option('xyz_lnap_lnmessage'));}?></textarea>
	</td></tr>

	<tr valign="top">
					<td>Attach image to linkedin post
					</td>
					<td><select id="xyz_lnap_lnpost_image_permission"
						name="xyz_lnap_lnpost_image_permission">
							<option value="0"
							<?php  if(get_option('xyz_lnap_lnpost_image_permission')==0) echo 'selected';?>>
								No</option>
							<option value="1"
							<?php  if(get_option('xyz_lnap_lnpost_image_permission')==1) echo 'selected';?>>Yes</option>
					</select>
					</td>
				</tr>
				
	<tr valign="top" id="shareprivate">
	<input type="hidden" name="xyz_lnap_ln_sharingmethod" id="xyz_lnap_ln_sharingmethod" value="0">
	<td>Share post content with</td>
	<td>
		<select id="xyz_lnap_ln_shareprivate" name="xyz_lnap_ln_shareprivate" > <option value="0" <?php  if(get_option('xyz_lnap_ln_shareprivate')==0) echo 'selected';?>>
Public</option><option value="1" <?php  if(get_option('xyz_lnap_ln_shareprivate')==1) echo 'selected';?>>Connections only</option></select>
	</td></tr>
	
	<tr valign="top"><td>Enable auto publish posts to my linkedin account</td>
	<td>
		<select id="xyz_lnap_lnpost_permission" class="al2fb_text" name="xyz_lnap_lnpost_permission" > <option value="0" <?php  if(get_option('xyz_lnap_lnpost_permission')==0) echo 'selected';?>>
No</option><option value="1" <?php  if(get_option('xyz_lnap_lnpost_permission')==1) echo 'selected';?>>Yes</option></select>
	</td></tr>
	
		<tr>
			<td   id="bottomBorderNone"></td>
					<td   id="bottomBorderNone"><div style="height: 50px;">
							<input type="submit" class="submit_lnap_new"
								style=" margin-top: 10px; "
								name="linkdn" value="Save" /></div>
					</td>
				</tr>

</table>


</form>


	<?php 

	if(isset($_POST['bsettngs']))
	{

		$xyz_lnap_include_pages=$_POST['xyz_lnap_include_pages'];
		$xyz_lnap_include_posts=$_POST['xyz_lnap_include_posts'];

		if($_POST['xyz_lnap_cat_all']=="All")
			$lnap_category_ids=$_POST['xyz_lnap_cat_all'];//redio btn name
		else
			$lnap_category_ids=$_POST['xyz_lnap_sel_cat'];//dropdown

		$xyz_customtypes="";
		
        if(isset($_POST['post_types']))
		$xyz_customtypes=$_POST['post_types'];
        
        $xyz_lnap_peer_verification=$_POST['xyz_lnap_peer_verification'];
        $xyz_lnap_premium_version_ads=$_POST['xyz_lnap_premium_version_ads'];
        $xyz_lnap_default_selection_edit=$_POST['xyz_lnap_default_selection_edit'];
        
		$lnap_customtype_ids="";

		if($xyz_customtypes!="")
		{
			for($i=0;$i<count($xyz_customtypes);$i++)
			{
				$lnap_customtype_ids.=$xyz_customtypes[$i].",";
			}

		}
		$lnap_customtype_ids=rtrim($lnap_customtype_ids,',');


		update_option('xyz_lnap_include_pages',$xyz_lnap_include_pages);
		update_option('xyz_lnap_include_posts',$xyz_lnap_include_posts);
		if($xyz_lnap_include_posts==0)
			update_option('xyz_lnap_include_categories',"All");
		else
			update_option('xyz_lnap_include_categories',$lnap_category_ids);
		update_option('xyz_lnap_include_customposttypes',$lnap_customtype_ids);
		update_option('xyz_lnap_peer_verification',$xyz_lnap_peer_verification);
		update_option('xyz_lnap_premium_version_ads',$xyz_lnap_premium_version_ads);
		update_option('xyz_lnap_default_selection_edit',$xyz_lnap_default_selection_edit);
	}

	$xyz_credit_link=get_option('xyz_credit_link');
	$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
	$xyz_lnap_include_posts=get_option('xyz_lnap_include_posts');
	$xyz_lnap_include_categories=get_option('xyz_lnap_include_categories');
	$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
	$xyz_lnap_peer_verification=esc_html(get_option('xyz_lnap_peer_verification'));
	$xyz_lnap_premium_version_ads=esc_html(get_option('xyz_lnap_premium_version_ads'));
	$xyz_lnap_default_selection_edit=esc_html(get_option('xyz_lnap_default_selection_edit'));

	?>
		<h2>Basic Settings</h2>


		<form method="post">

			<table class="widefat xyz_lnap_widefat_table" style="width: 99%">

				<tr valign="top">

					<td  colspan="1" width="50%">Publish wordpress `pages` to linkedin
					</td>
					<td><select name="xyz_lnap_include_pages">

							<option value="1"
							<?php if($xyz_lnap_include_pages=='1') echo 'selected'; ?>>Yes</option>

							<option value="0"
							<?php if($xyz_lnap_include_pages!='1') echo 'selected'; ?>>No</option>
					</select>
					</td>
				</tr>

				<tr valign="top">

					<td  colspan="1">Publish wordpress `posts` to linkedin
					</td>
					<td><select name="xyz_lnap_include_posts" onchange="xyz_lnap_show_postCategory(this.value);">

							<option value="1"
							<?php if($xyz_lnap_include_posts=='1') echo 'selected'; ?>>Yes</option>

							<option value="0"
							<?php if($xyz_lnap_include_posts!='1') echo 'selected'; ?>>No</option>
					</select>
					</td>
				</tr>
				
				<tr valign="top" id="selPostCat">

					<td  colspan="1">Select post categories for auto publish
					</td>
					<td><input type="hidden"
						value="<?php echo $xyz_lnap_include_categories;?>"
						name="xyz_lnap_sel_cat" id="xyz_lnap_sel_cat"> <input type="radio"
						name="xyz_lnap_cat_all" id="xyz_lnap_cat_all" value="All"
						onchange="rd_cat_chn(1,-1)"
						<?php if($xyz_lnap_include_categories=="All") echo "checked"?>>All<font
						style="padding-left: 10px;"></font><input type="radio"
						name="xyz_lnap_cat_all" id="xyz_lnap_cat_all" value=""
						onchange="rd_cat_chn(1,1)"
						<?php if($xyz_lnap_include_categories!="All") echo "checked"?>>Specific

						<span id="cat_dropdown_span"><br /> <br /> <?php 


						$args = array(
								'show_option_all'    => '',
								'show_option_none'   => '',
								'orderby'            => 'name',
								'order'              => 'ASC',
								'show_last_update'   => 0,
								'show_count'         => 0,
								'hide_empty'         => 0,
								'child_of'           => 0,
								'exclude'            => '',
								'echo'               => 0,
								'selected'           => '1 3',
								'hierarchical'       => 1,
								'name'               => 'xyz_lnap_catlist',
								'id'                 => 'xyz_lnap_catlist',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'category',
								'hide_if_empty'      => false );

						if(count(get_categories($args))>0)
							echo str_replace( "<select", "<select multiple onClick=setcat(this) style='width:200px;height:auto !important;border:1px solid #cccccc;'", wp_dropdown_categories($args));
						else
							echo "NIL";

						?><br /> <br /> </span>
					</td>
				</tr>


				<tr valign="top">

					<td  colspan="1">Select wordpress custom post types for auto publish</td>
					<td><?php 

					$args=array(
							'public'   => true,
							'_builtin' => false
					);
					$output = 'names'; // names or objects, note names is the default
					$operator = 'and'; // 'and' or 'or'
					$post_types=get_post_types($args,$output,$operator);

					$ar1=explode(",",$xyz_lnap_include_customposttypes);
					$cnt=count($post_types);
					foreach ($post_types  as $post_type ) {

						echo '<input type="checkbox" name="post_types[]" value="'.$post_type.'" ';
						if(in_array($post_type, $ar1))
						{
							echo 'checked="checked"/>';
						}
						else
							echo '/>';

						echo $post_type.'<br/>';

					}
					if($cnt==0)
						echo 'NA';
					?>
					</td>
				</tr>
				<tr valign="top">

					<td scope="row" colspan="1" width="50%">Default selection of auto publish while editing posts/pages	
					</td><td><select name="xyz_lnap_default_selection_edit" >
					
					<option value ="1" <?php if($xyz_lnap_default_selection_edit=='1') echo 'selected'; ?> >Yes </option>
					
					<option value ="0" <?php if($xyz_lnap_default_selection_edit=='0') echo 'selected'; ?> >No </option>
					</select> 
					</td>
				</tr>

				<tr valign="top">
				
				<td scope="row" colspan="1" width="50%">SSL peer verification	</td><td><select name="xyz_lnap_peer_verification" >
				
				<option value ="1" <?php if($xyz_lnap_peer_verification=='1') echo 'selected'; ?> >Enable </option>
				
				<option value ="0" <?php if($xyz_lnap_peer_verification=='0') echo 'selected'; ?> >Disable </option>
				</select> 
				</td></tr>
				

				<tr valign="top">

					<td  colspan="1">Enable credit link to author
					</td>
					<td><select name="xyz_credit_link" id="xyz_lnap_credit_link">

							<option value="lnap"
							<?php if($xyz_credit_link=='lnap') echo 'selected'; ?>>Yes</option>

							<option
								value="<?php echo $xyz_credit_link!='lnap'?$xyz_credit_link:0;?>"
								<?php if($xyz_credit_link!='lnap') echo 'selected'; ?>>No</option>
					</select>
					</td>
				</tr>
				
				<tr valign="top">

					<td  colspan="1">Enable premium version ads
					</td>
					<td><select name="xyz_lnap_premium_version_ads" id="xyz_lnap_premium_version_ads">

							<option value="1"
							<?php if($xyz_lnap_premium_version_ads=='1') echo 'selected'; ?>>Yes</option>

							<option
								value="0"
								<?php if($xyz_lnap_premium_version_ads=='0') echo 'selected'; ?>>No</option>
					</select>
					</td>
				</tr>


				<tr>

					<td id="bottomBorderNone">
							

					</td>

					
<td id="bottomBorderNone"><div style="height: 50px;">
<input type="submit" class="submit_lnap_new" style="margin-top: 10px;"	value=" Update Settings" name="bsettngs" /></div></td>
				</tr>


			</table>
		</form>
		
		
</div>		

	<script type="text/javascript">
	//drpdisplay();
var catval='<?php echo $xyz_lnap_include_categories; ?>';
var custtypeval='<?php echo $xyz_lnap_include_customposttypes; ?>';
var get_opt_cats='<?php echo get_option('xyz_lnap_include_posts');?>';
jQuery(document).ready(function() {
	  if(catval=="All")
		  jQuery("#cat_dropdown_span").hide();
	  else
		  jQuery("#cat_dropdown_span").show();

	  if(get_opt_cats==0)
		  jQuery('#selPostCat').hide();
	  else
		  jQuery('#selPostCat').show();
			  
	}); 
	
function setcat(obj)
{
var sel_str="";
for(k=0;k<obj.options.length;k++)
{
if(obj.options[k].selected)
sel_str+=obj.options[k].value+",";
}


var l = sel_str.length; 
var lastChar = sel_str.substring(l-1, l); 
if (lastChar == ",") { 
	sel_str = sel_str.substring(0, l-1);
}

document.getElementById('xyz_lnap_sel_cat').value=sel_str;

}

var d1='<?php echo $xyz_lnap_include_categories;?>';
splitText = d1.split(",");
jQuery.each(splitText, function(k,v) {
jQuery("#xyz_lnap_catlist").children("option[value="+v+"]").attr("selected","selected");
});

function rd_cat_chn(val,act)
{//xyz_lnap_cat_all xyz_lnap_cust_all 
	if(val==1)
	{
		if(act==-1)
		  jQuery("#cat_dropdown_span").hide();
		else
		  jQuery("#cat_dropdown_span").show();
	}
	
}

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
function xyz_lnap_show_postCategory(val)
{
	if(val==0)
		jQuery('#selPostCat').hide();
	else
		jQuery('#selPostCat').show();
}
</script>
	<?php 
?>