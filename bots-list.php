<?php 
require_once("library/botManager.php");

$botManager         = new botManager();
$file   = './bots/list.txt';
if( !file_exists($file) || !is_readable($file)){
    echo ('<p class="errors">Invalid file : either create or chmod 777 bots/list.txt');
}
$arFile  = file($file);
if (count($arFile) < 1  ) {
    echo("<p><strong>No bot yet</strong></p>");
    return;
}else{

    $line = 0;
    
    foreach ($arFile as $bot) {
        
        $line++;
        
        $botStatus=explode(',',$bot);
        
        foreach ($botStatus as $key => $value) {
           $response[$line][] = $value;
        }
        
        if( $botManager->running($botStatus[0])){
            $response[$line][] = "Running";
            $response[$line][] = '
            
            <form class="form-bot-kill" action="bot-kill.php" method="get" accept-charset="utf-8">
                <input type="hidden" name="pid" value="'.$botStatus[0].'" id="pid">
                <p><input type="submit" value="Kill &rarr;"></p>
            </form>';
            $current[]=$bot;
            
        }else{
            $response[$line][] = "Stopped";
        }
         
        
    }
    $html           .='<table style="border-collapse:collapse;width:100%;" cellpadding=3 border=1>';
    foreach ($response as $row  ) {
        $html      .= "<tr>";

        foreach ($row as $cell) {
            $html   .= "<td>$cell</td>";
        }
        $html       .= '</tr>';
    }
    $html           .="</table>";
    $html           .='
    <script type="text/javascript" charset="utf-8">
     $(".form-bot-kill").submit(function(e){
        var form = $(this);
        N.notificationManager.begin();
        $.ajax({
            url: form.attr("action"),
            type: "post",
            dataType:"json",
            data: form.serialize(),
            complete: N.notificationManager.end,
            success: function(data) {
                if (data.code == "ok") {
                    $("#bots-list").load("bots-list.php");
                } else {
                    alert("Something went wrong.");//dump(data.output) + dump(data.return_var)
                }
            }
        });        
        return false;
     });
    </script>
    ';
    echo($html);
    $botManager->save( $current );
    
}


