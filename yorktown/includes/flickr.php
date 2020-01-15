<?php

	require_once("info.php");

	$type = $_REQUEST['type'];
	if(isset($_REQUEST['user_id']))$user_id = $_REQUEST['user_id'];
	if(isset($_REQUEST['path']))$path = $_REQUEST['path'];
	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
	$page = 1;
	$per_page = 100;
	$media_arr = array();

	if($type == 'flickr_user_photoset'){
		//https://www.flickr.com/services/api/flickr.photosets.getPhotos.html

    	$url = 'https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key='.$flickr_api_key.'&photoset_id='.$path.'&user_id='.$user_id.'&page='.$page.'&per_page='.$per_page.'&media=photos&extras=media&format=json&nojsoncallback=1';

    }else if($type == 'flickr_user_favorites'){
    	//https://www.flickr.com/services/api/flickr.favorites.getList.html

    	$url = 'https://api.flickr.com/services/rest/?method=flickr.favorites.getList&api_key='.$flickr_api_key.'&user_id='.$user_id.'&page='.$page.'&per_page='.$per_page.'&media=photos&extras=media&format=json&nojsoncallback=1';

    }else if($type == 'flickr_user_photos'){
    	//https://www.flickr.com/services/api/flickr.people.getPublicPhotos.html    	

    	$url = 'https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key='.$flickr_api_key.'&user_id='.$user_id.'&page='.$page.'&per_page='.$per_page.'&media=photos&extras=media&format=json&nojsoncallback=1';

    }else if($type == 'flickr_gallery_photos'){
    	//https://www.flickr.com/services/api/flickr.galleries.getPhotos.html

    	$url = 'https://api.flickr.com/services/rest/?method=flickr.galleries.getPhotos&api_key='.$flickr_api_key.'&gallery_id='.$path.'&user_id='.$user_id.'&page='.$page.'&per_page='.$per_page.'&media=photos&extras=media&format=json&nojsoncallback=1';

    }else if($type == 'flickr_photo_search'){
    	//https://www.flickr.com/services/api/flickr.photos.search.html

    	$url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key='.$flickr_api_key.'&tags='.$path.'&page='.$page.'&per_page='.$per_page.'&media=photos&extras=media&format=json&nojsoncallback=1';

    }

	getData($type, $url, $media_arr, $limit);

    function getData($type, $url, $media_arr, $limit){
    	
	    $content = file_get_contents($url);

	    if($content !== FALSE){

		    $response = json_decode($content, true);

		    if($type == 'flickr_user_photoset'){
		    	$photos = $response["photoset"];
		    }else{
		    	$photos = $response["photos"];
		    }

		    foreach($photos['photo'] as $media){
		        $media_arr[] = $media;
		    }

		    if(count($media_arr) < $limit){

		    	$page = $photos["page"];
				$pages = $photos["pages"];
				if($page < $pages){
					$page++;
					$url = replace_between($url, '&page=', '&per_page', $page);//update page 
					getData($type, $url, $media_arr, $limit);
				}

		    }else if(count($media_arr) > $limit){

		    	array_splice($media_arr, $limit);

		    }

		    echo json_encode($media_arr);
		    exit;

		}else{

			echo('Flickr feed error!');
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
