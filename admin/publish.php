<?php 

add_action('publish_post', 'xyz_lnap_link_publish');
add_action('publish_page', 'xyz_lnap_link_publish');



$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
$carr=explode(',', $xyz_lnap_include_customposttypes);
foreach ($carr  as $cstyps ) {
	add_action('publish_'.$cstyps, 'xyz_lnap_link_publish');

}


function xyz_lnap_string_limit($string, $limit) {
	
	$space=" ";$appendstr=" ...";
	if(mb_strlen($string) <= $limit) return $string;
	if(mb_strlen($appendstr) >= $limit) return '';
	$string = mb_substr($string, 0, $limit-mb_strlen($appendstr));
	$rpos = mb_strripos($string, $space);
	if ($rpos===false) 
		return $string.$appendstr;
   else 
	 	return mb_substr($string, 0, $rpos).$appendstr;
}

function xyz_lnap_getimage($post_ID,$description_org)
{
	$attachmenturl="";
	$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
	if($post_thumbnail_id!="")
	{
		$attachmenturl=wp_get_attachment_url($post_thumbnail_id);
		$attachmentimage=wp_get_attachment_image_src( $post_thumbnail_id, full );
		
	}
	else {
		preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/is', $description_org, $matches);
		if(isset($matches[1][0]))
		$attachmenturl = $matches[1][0];
		else
		{
			apply_filters('the_content', $description_org);
			preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/is', $description_org, $matches);
			if(isset($matches[1][0]))
				$attachmenturl = $matches[1][0];
		}
		
	
	}
	return $attachmenturl;
}
function xyz_lnap_link_publish($post_ID) {
	
	
	$get_post_meta=get_post_meta($post_ID,"xyz_lnap",true);
	if($get_post_meta!=1)
		add_post_meta($post_ID, "xyz_lnap", "1");
	else 
		return;
	global $current_user;
	get_currentuserinfo();
		
	////////////linkedin////////////
	
	$lnoathtoken=get_option('xyz_lnap_lnoauth_token');
	$lnoathseret=get_option('xyz_lnap_lnoauth_secret');
	$lnappikey=get_option('xyz_lnap_lnapikey');
	$lnapisecret=get_option('xyz_lnap_lnapisecret');
	$lnoauthverifier=get_option('xyz_lnap_lnoauth_verifier');
	$lmessagetopost=get_option('xyz_lnap_lnmessage');
	
  $xyz_lnap_ln_shareprivate=get_option('xyz_lnap_ln_shareprivate'); 
  if(isset($_POST['xyz_lnap_ln_shareprivate']))
  $xyz_lnap_ln_shareprivate=$_POST['xyz_lnap_ln_shareprivate'];
 
  $xyz_lnap_ln_sharingmethod=get_option('xyz_lnap_ln_sharingmethod');
  if(isset($_POST['xyz_lnap_ln_sharingmethod']))
  $xyz_lnap_ln_sharingmethod=$_POST['xyz_lnap_ln_sharingmethod'];
  

  $lnpost_permission=get_option('xyz_lnap_lnpost_permission');
  if(isset($_POST['xyz_lnap_lnpost_permission']))
  	$lnpost_permission=$_POST['xyz_lnap_lnpost_permission'];
  
  $post_ln_image_permission=get_option('xyz_lnap_lnpost_image_permission');
  if(isset($_POST['xyz_lnap_lnpost_image_permission']))
  	$post_ln_image_permission=$_POST['xyz_lnap_lnpost_image_permission'];

    $lnaf=get_option('xyz_lnap_lnaf');
	
	////////////////////////
	$postpp= get_post($post_ID);global $wpdb;
	$entries0 = $wpdb->get_results( 'SELECT user_nicename FROM '.$wpdb->prefix.'users WHERE ID='.$postpp->post_author);
	foreach( $entries0 as $entry ) {			
		$user_nicename=$entry->user_nicename;}
	
	if ($postpp->post_status == 'publish')
	{
		$posttype=$postpp->post_type;
			
		if ($posttype=="page")
		{

			$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
			if($xyz_lnap_include_pages==0)
				return;
		}
			
		if($posttype=="post")
		{
			$xyz_lnap_include_categories=get_option('xyz_lnap_include_categories');
			if($xyz_lnap_include_categories!="All")
			{
				$carr1=explode(',', $xyz_lnap_include_categories);
					
				$defaults = array('fields' => 'ids');
				$carr2=wp_get_post_categories( $post_ID, $defaults );
				$retflag=1;
				foreach ($carr2 as $key=>$catg_ids)
				{
					if(in_array($catg_ids, $carr1))
						$retflag=0;
				}
					
					
				if($retflag==1)
					return;
			}
		}

		$link = get_permalink($postpp->ID);



		$content = $postpp->post_content;apply_filters('the_content', $content);

		$excerpt = $postpp->post_excerpt;apply_filters('the_excerpt', $excerpt);
		if($excerpt=="")
		{
			if($content!="")
			{
				$content1=$content;
				$content1=strip_tags($content1);
				$content1=strip_shortcodes($content1);
				
				$excerpt=implode(' ', array_slice(explode(' ', $content1), 0, 50));
			}
		}
		else
		{
			$excerpt=strip_tags($excerpt);
			$excerpt=strip_shortcodes($excerpt);
		}
		$description = $content;
		
		$description_org=$description;
		$attachmenturl=xyz_lnap_getimage($post_ID, $postpp->post_content);
		if($attachmenturl!="")
			$image_found=1;
		else
			$image_found=0;
		

		$name = html_entity_decode(get_the_title($postpp->ID), ENT_QUOTES, get_bloginfo('charset'));
		$caption = html_entity_decode(get_bloginfo('title'), ENT_QUOTES, get_bloginfo('charset'));
		apply_filters('the_title', $name);

		$name=strip_tags($name);
		$name=strip_shortcodes($name);
		
		$description=strip_tags($description);		
		$description=strip_shortcodes($description);
		
		if($lnappikey!="" && $lnapisecret!="" && $lnoathtoken!="" && $lnoathseret!="" && $lnpost_permission==1 && $lnoauthverifier!="" && $lnaf==0)
		{		
			$contentln=array();
			
			//$description=str_replace("&nbsp;", "", $description);
			
			$description_li=xyz_lnap_string_limit($description, 400);
			$caption_li=xyz_lnap_string_limit($caption, 200);
			$name_li=xyz_lnap_string_limit($name, 200);
				
			$message1=str_replace('{POST_TITLE}', $name, $lmessagetopost);
			$message2=str_replace('{BLOG_TITLE}', $caption,$message1);
			$message3=str_replace('{PERMALINK}', $link, $message2);
			$message4=str_replace('{POST_EXCERPT}', $excerpt, $message3);
			$message5=str_replace('{POST_CONTENT}', $description, $message4);
			$message5=str_replace('{USER_NICENAME}', $user_nicename, $message5);
			
			//$message5=xyz_lnap_string_limit($message5, 700);
						
				$contentln['comment'] =$message5;
				$contentln['title'] = $name_li;
				$contentln['submitted-url'] = $link;
				if($attachmenturl!="" && $post_ln_image_permission==1)
				$contentln['submitted-image-url'] = $attachmenturl;
				$contentln['description'] = $description_li;
		
		
			$API_CONFIG = array(
			'appKey'       => $lnappikey,
			'appSecret'    => $lnapisecret
			);
		
			if($xyz_lnap_ln_shareprivate==1)
			{
			$private = TRUE;
		}
		else
		{
		$private = FALSE;
		}
		$OBJ_linkedin = new LNAPLinkedIn($API_CONFIG);
		$xyz_lnap_application_lnarray=get_option('xyz_lnap_application_lnarray');
	
		
		$OBJ_linkedin->setTokenAccess($xyz_lnap_application_lnarray);
		
		if($xyz_lnap_ln_sharingmethod==0)
		{
				try{
			$response2 = $OBJ_linkedin->share('new', $contentln,$private);
			}
			catch(Exception $e)
			{
			//echo $e->getmessage();
			}
		}
		else
		{
		$description_liu=xyz_lnap_string_limit($description, 950);
		try{
		     $response2=$OBJ_linkedin->updateNetwork($description_liu);
		   }
			catch(Exception $e)
			{
				//echo $e->getmessage();
			}
		}
		
		/*if(isset($response2['success']))
			echo "posted successfully";
			else
			echo "posting failed";die;*/
		
		
		}
	}
	

}

?>