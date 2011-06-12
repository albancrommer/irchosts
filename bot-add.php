<?php
require_once('library/botManager.php');
try {

    $scriptCurrentDir = dirname(__FILE__);
    chdir($scriptCurrentDir);

    $server     = escapeshellarg($_POST["server"]);
    $channel    = escapeshellarg($_POST["channel"]);
    $user       = escapeshellarg($_POST["user"]);
    $port       = escapeshellarg($_POST["port"]);
    $pwd        = escapeshellarg($_POST["pwd"]);
    
    $timestamp  = time();
    
    $logFile    = escapeshellarg("logs/$timestamp-$server-$port-$channel-$user.log");
    exec("echo '' > $logFile");
    
    // could eventually use different languages
    exec("php bot-spawn.php $server $port $channel $user $password >> ".$logFile." 2>&1 & echo $!", $output, $return_var );
    $pid    = $output[0];
    if( 0 != $return_var){
        throw new Exception("Process returned an error", 1);
    }elseif( $pid > 0 ){
        
        $botList = new botManager();
        $botList->add( array(
            "pid"       => $pid,
            "server"    => $_POST['server'],
            "port"      => $_POST['port'],
            "channel"   => $_POST['channel'],
            "user"      => $_POST['user'],
            "timestamp" => $timestamp
        ));
        
    }else{
        
        echo json_encode(array("code"=>"error","message"=>"Something went wrong.","output"=>$output,"return_var"=>$return_var));        
        return;
    }
    
    echo json_encode(array("code"=>"ok","output"=>$output,"return_var"=>$return_var));        
    
} catch (Exception $e) {
echo json_encode(array("code"=>"error : $e"));    
return;
}  
