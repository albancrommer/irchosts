<?php
    require_once('library/Net_SmartIRC/SmartIRC.php');
?>
<?php

$config             = parse_ini_file('config.ini');
$dbhost             = ( null == $config['dbport'] ? $config['dbhost']:$config['dbhost'].':'.$config['dbport']);
$mysqli             = new mysqli(
    $dbhost, $config['dbuser'], $config['dbpwd'], $config['dbname']
    );
    

$server             = $argv[1];
$port               = $argv[2];
$channel            = $argv[3];
$user               = $argv[4];
$pwd                = $argv[5];
$channel = trim($channel,"'");
echo ("####### CHANNEL : $channel #########\n");
echo ("####### SERVER : $server #########\n");


class myBot
{
    
    public  $channel;
    public  $server;
    public  $mysqli;
    
    
    function __construct( $options = null )
    {
        $this->channel  = $options['channel'];
        $this->server   = $options['server'];
        $this->mysqli   = $options['mysqli'];
    }
    
    function drill(&$irc)
    {
        $channel            = $this->channel;
        $server             = $this->server;
        $chanData           = $irc->channel["#$channel"];
        $chanUsers          = $chanData->users;
        $n                  = 0;
        if( count($chanUsers) > 0 ){
            foreach ($chanUsers as $user) {
                $n++;
                $stmt ="REPLACE ircip (`server`,`channel`,`nick`,`host`, `dt`) "; 
                $stmt.="VALUES ('$server','$channel','$user->nick','$user->host','".date('Y-m-d H:i:s')."');";
                $sql.=$stmt;
                if( !$this->mysqli->query( $stmt ) ){
                    echo("***** Failed Statement : $stmt ----\n");
                }
            }
            echo("---- ".date('Ymd H:i:s')." : $n users ----\n");

            echo $sql."\n";
            
        }else{
            echo("---- ".date('Ymd H:i:s')." : Empty chan ----\n");
        }
            
    }
}

$bot = &new myBot(array(
    "channel"=>$channel,
    "server"=>$server,
    "mysqli"=>$mysqli
    ));
$irc = &new Net_SmartIRC();
// $irc->setDebug(SMARTIRC_DEBUG_ALL);
$irc->setUseSockets(TRUE);
$irc->setChannelSyncing(TRUE);
$irc->registerTimehandler(10000, $bot, 'drill', $bot);
$irc->connect($server, $port);
$irc->login($user, 'Client '.SMARTIRC_VERSION, 0, 'Net_SmartIRC');
$irc->join(array("#$channel"));
$irc->listen();

$irc->disconnect();
