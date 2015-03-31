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

    $uid = '1830961';    // Указать id

    $userFriendsIds = $vk->getUserFriends($uid);
    error_log('Friends = '.count($userFriendsIds));
    $counter = 0;
    foreach($userFriendsIds as $userFriendsId) {
        if($counter % 2 == 0) sleep(5);

        $userAudio = $vk->getUserAudioById($userFriendsId);

        error_log($counter.' User '.$userFriendsId.' audio count = '.count($userAudio));
        $fp = fopen($uid . '.csv', 'a');
        foreach ($userAudio as $item) {
            fputcsv($fp, $item, ";");
        }
        fclose($fp);
        $counter ++;
    }

    $vk->logout();
}  catch( Exception $e ){
    echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();
    echo "<hr /><h3>Trace</h3> <pre>" . $e->getTraceAsString() . "</pre>";
}
