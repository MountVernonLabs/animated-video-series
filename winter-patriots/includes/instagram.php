<?php

	require_once("info.php");

	$media_arr = array();

	$type = $_REQUEST['type'];
	if(isset($_REQUEST['path']))$path = $_REQUEST['path'];
	if(isset($_REQUEST['user_id']))$user_id = $_REQUEST['user_id'];
	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;

	if($type == 'instagram_user'){

		$url = 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent?count='.$limit.'&access_token='.$instagram_token;

	}else if($type == 'instagram_hash'){

		$url = 'https://api.instagram.com/v1/tags/'.$path.'/media/recent?count='.$limit.'&access_token='.$instagram_token;
	
	}else if($type == 'instagram_location'){
		
		$url = 'https://api.instagram.com/v1/locations/'.$path.'/media/recent?count='.$limit.'&access_token='.$instagram_token;
	}

	getData($url, $media_arr, $limit);

    function getData($url, $media_arr, $limit){
    	
	    $content = file_get_contents($url);

	    if($content !== FALSE){

		    $response = json_decode($content, true);

		    foreach($response["data"] as $media){
		        $media_arr[] = $media;
		    }

		    if(count($media_arr) < $limit){

		    	$pagination = $response["pagination"];
			    if(array_key_exists("next_url", $pagination)){
			    	getData($pagination["next_url"], $media_arr, $limit);
			    }

		    }else if(count($media_arr) > $limit){

		    	array_splice($media_arr, $limit);

		    }

		    echo json_encode($media_arr);
		    exit;

		}else{

			echo('Instagram feed error!');
			exit;

		}

    }

	
?>
