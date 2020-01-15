<?php

	require_once("info.php");

	$media_arr = array();

	$type = $_REQUEST['type'];
	$path = $_REQUEST['path'];
	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
	$offset = 0;

	if($type == 'tumblr_posts'){

		$url = 'https://api.tumblr.com/v2/blog/'.$path.'/posts/photo?offset='.$offset.'&limit='.$limit.'&api_key='.$tumblr_api_key;

	}

	$data = getData($url, $media_arr, $offset, $limit);

    function getData($url, $media_arr, $offset, $limit){
    	
	    $content = file_get_contents($url);

	    if($content !== FALSE){

		    $response = json_decode($content, true);

		    $photos = $response["response"];

		    foreach($photos["posts"] as $post){
		        $media_arr[] = $post;
		    }

		    $posts = $photos["total_posts"];

		    if(count($media_arr) < $limit && $offset+20<=$posts){

		    	$offset+=20;
		    	$url = replace_between($url, '&offset=', '&limit', $offset);//update offset 
			    getData($url, $media_arr, $offset, $limit);

		    }else if(count($media_arr) > $limit){

		    	array_splice($media_arr, $limit);

		    }

		    echo json_encode($media_arr);
		    exit;

		}else{

			echo('Tumblr feed error!');
			exit;

		}

    }

    function replace_between($str, $needle_start, $needle_end, $replacement) {
	    $pos = strpos($str, $needle_start);
	    $start = $pos === false ? 0 : $pos + strlen($needle_start);

	    $pos = strpos($str, $needle_end, $start);
	    $end = $pos === false ? strlen($str) : $pos;

	    return substr_replace($str, $replacement, $start, $end - $start);
	}

	
?>
