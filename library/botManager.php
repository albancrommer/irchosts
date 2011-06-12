<?php
/**
*  
*/
class botManager
{
    
    function __construct()
    {
        # code...
    }
    
    public function add( $options = null)
    {
        $handle     = fopen('bots/list.txt',"a");
        fwrite($handle,implode(',',$options)."\n");
        fclose($handle);
        
    }
    public function kill( $pid = null )
    {
        $cmd = "kill $pid";
        exec($cmd,$output,$result);
        if( $result == 0)
            return true;
        return false;

    }
    public function running( $pid = null )
    {
        $cmd ="ps $pid";
        exec($cmd,$output,$result);
        if( count($output)>1)
            return true;
        return false;
    }
    
    public function save( $bots = null )
    {
        if( null == $bots)          $c="";
        elseif( count($bots) < 1 )  $c="";
        else foreach ($bots as $key => $value) {
            $c  .= $value;
        }
        file_put_contents('bots/list.txt',$c);
        
    }
}
            
