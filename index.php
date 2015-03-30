<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

// config and includes
$config = 'hybridauth/config.php';
require_once( "hybridauth/Hybrid/Auth.php" );

try {
    // hybridauth EP
    $hybridauth = new Hybrid_Auth( $config );

    // automatically try to login with Twitter
    $vk = $hybridauth->authenticate( "Vkontakte" );

    // return TRUE or False <= generally will be used to check if the user is connected to twitter before getting user profile, posting stuffs, etc..
    $is_user_logged_in = $vk->isUserConnected();

    $uid = '';    // Указать id

    $userAudio = $vk->getUserAudioById($uid);


    $fp = fopen($uid . '.csv', 'w');
    foreach ($userAudio as $item) {
        fputcsv($fp, $item, ';');
    }
    fclose($fp);

    $vk->logout();
    echo $uid;
}  catch( Exception $e ){
    echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();
    echo "<hr /><h3>Trace</h3> <pre>" . $e->getTraceAsString() . "</pre>";
}
