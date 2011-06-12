<?php

$config             = parse_ini_file('config.ini');
$dbhost             = ( null == $config['dbport'] ? $config['dbhost']:$config['dbhost'].':'.$config['dbport']);
$mysqli             = new mysqli(
    $dbhost, $config['dbuser'], $config['dbpwd'], $config['dbname']
    );
    
$results            = $mysqli->query("select * from ircip where 1");
while ($row = $results->fetch_row()) {

        $html .="<tr>";    
    foreach ($row as $cell) 
        $html .="<td>$cell</td>";
        $html .="</tr>";

}

?>
<table><?php echo $html ?></table>