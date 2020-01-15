<?php

require_once("../info.php");

if(!isset($_REQUEST['type']) || !isset($_REQUEST['page']) || !isset($_REQUEST['per_page']) || IsNullOrEmpty($vimeo_key) || IsNullOrEmpty($vimeo_secret) || IsNullOrEmpty($vimeo_token)) exit("PHP Vimeo access information missing!");

function IsNullOrEmpty($v){
	return (!isset($v) || trim($v)==='');
}

$type = $_REQUEST['type'];
$page = $_REQUEST['page'];
$per_page = $_REQUEST['per_page'];
$path = isset($_REQUEST['path']) && !IsNullOrEmpty($_REQUEST['path']) ? $_REQUEST['path'] : null;
$user = isset($_REQUEST['user']) && !IsNullOrEmpty($_REQUEST['user']) ? $_REQUEST['user'] : null;
$query = isset($_REQUEST['query']) && !IsNullOrEmpty($_REQUEST['query']) ? $_REQUEST['query'] : null;
$sort = isset($_REQUEST['sort']) && !IsNullOrEmpty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'date';
$sortDirection = isset($_REQUEST['sortDirection']) && !IsNullOrEmpty($_REQUEST['sortDirection']) ? $_REQUEST['sortDirection'] : 'asc';



require("autoload.php");
use Vimeo\Vimeo;
$vimeo = new Vimeo($vimeo_key, $vimeo_secret, $vimeo_token);



if($type == 'vimeo_channel'){

	//Get a list of videos in a Channel - https://developer.vimeo.com/api/playground/channels/{channel_id}/videos
	$result = $vimeo->request("/channels/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,								
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));

}else if($type == 'vimeo_group'){														
												
	//Get a list of videos in a Group - https://developer.vimeo.com/api/playground/groups/{group_id}/videos
	$result = $vimeo->request("/groups/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,						
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
	
}else if($type == 'vimeo_user_album'){	
	
	//Get the list of videos in an Album - https://developer.vimeo.com/api/playground/users/{user_id}/albums/{album_id}/videos
	$result = $vimeo->request("/users/$user/albums/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,							
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
										
}else if($type == 'vimeo_user_appearance'){		
	
	//Get all videos that a user appears in - https://developer.vimeo.com/api/playground/users/{user_id}/appearances
	$result = $vimeo->request("/users/$user/appearances", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => 'asc',
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));

}else if($type == 'vimeo_user_like'){	
													
	//Get a list of videos that a user likes - https://developer.vimeo.com/api/playground/users/{user_id}/likes
	$result = $vimeo->request("/users/$user/likes", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,										
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
												
}else if($type == 'vimeo_user_portfolio'){	
												
	//Get the videos in user Portfolio - https://developer.vimeo.com/api/playground/users/{user_id}/portfolios/{portfolio_id}/videos
	$result = $vimeo->request("/users/$user/portfolios/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
												
}else if($type == 'vimeo_user_uploaded'){							
						
	//Get a list of videos uploaded by a user - https://developer.vimeo.com/api/playground/users/{user_id}/videos
	$result = $vimeo->request("/users/$user/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,										
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
												
}else if($type == 'vimeo_video_query'){	
												
	//Search for videos - https://developer.vimeo.com/api/playground/videos
	$result = $vimeo->request("/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													'direction' => $sortDirection,										
													'query' => $query
													));
													
}else if($type == 'vimeo_single'){	

	//Get a video - https://developer.vimeo.com/api/playground/videos/{video_id}
	$result = $vimeo->request("/videos/$path", array(
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes'
													));
												
}else if($type == 'vimeo_related_video'){	
												
	//Get related videos - https://developer.vimeo.com/api/playground/videos/{video_id}/videos
	$result = $vimeo->request("/videos/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'filter' => 'related'
													));

}

echo json_encode($result);


?>