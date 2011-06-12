<?php 
require_once('library/botManager.php');
$botManager = new botManager();
$pid        = $_POST['pid'];
if( null != $pid ){
    if( $botManager->kill($pid)) {
        echo json_encode(array('code'=>'ok','message'=>'Process killed.'));        
    }else{
        echo json_encode(array('code'=>'error','message'=>'Invalid PID or Kill error.'));
    }
}
echo json_encode(array('code'=>'error','message'=>'Empty PID.'));