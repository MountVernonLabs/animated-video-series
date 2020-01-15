<?php

	require_once("info.php");

	$type = $_REQUEST['type'];
	if(isset($_REQUEST['path']))$path = $_REQUEST['path'];
	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
	$media_arr = array();

	if($type == 'facebook_album'){

    	$url = 'https://graph.facebook.com/v2.9/'.$path.'/photos?fields=source,name,picture,link&access_token='.$facebook_access_token;

    }

	getData($url, $media_arr, $limit);

    function getData($url, $media_arr, $limit){
    	
	    $content = file_get_contents($url);

	    if($content !== FALSE){

		    $response = json_decode($content, true);

		    foreach($response['data'] as $media){
		        $media_arr[] = $media;
		    }

		    if(count($media_arr) < $limit){

				if(array_key_exists("next", $response["paging"])){	
					$url = $response["paging"]["next"];
					getData($url, $media_arr, $limit);
				}

		    }else if(count($media_arr) > $limit){

		    	array_splice($media_arr, $limit);

		    }

		    echo json_encode($media_arr);
		    exit;

		}else{

			echo('Facebook feed error!');
			exit;

		}

    }

	
?>
