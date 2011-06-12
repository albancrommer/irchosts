<html>
<head>
<script  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<style type="text/css">
body{text-align:center;background:#eee;color:#222;font: 12pt "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;}
#form-add-bot{position:fixed;top:0px;left:0px;width:100%;background:#ccc;font: 75%;padding:10px;}
#content{margin-top:70px;}
.notification{display:block;position:fixed;top:0px;left:0px;color:#000000;background-color:#DDDDDD;padding:5px;z-index:10000}
#link-records-view{background:white;color:gray;position:fixed;bottom:0px;right:0px;padding:14px;z-index:6;}
#records-view{background:rgba(0,0,0,0.17);display:none;#height:100%;#position:fixed;text-align:center;top:0px;width:100%;z-index:2;}
#records-view .container{background:#eee;width:600px;margin:auto;overflow:auto;}
#records-view .container td{font-size:0.5em;;}
</style>

</head>
<body>
    
<form action="bot-add.php" method="get" id="form-add-bot">
<label for="server">Server</label><input type="text" name="server" value="" id="server" size="15">
<label for="port">Port</label><input type="text" name="port" value="6667" id="port" size="4">
<label for="channel">Channel #</label><input type="text" name="channel" value="" id="channel" size="15">
<label for="user">User</label><input type="text" name="user" value="" id="user" size="8">
<label for="password">Password (optional)</label><input type="text" name="password" value="" id="password" size="8">
<input type="submit" name="submit" value="&#x2192; GO &#x2192;" id="">
</form>
<div class="notification" style="display:none;"><p class="message">Loading</p></div>
<div id="content">
    <div id="bots-list"></div>
</div>
<a id="link-records-view" href="records-view.php">View records</a>
<div id="records-view"><div class="container">Loading...</div></div>







<script type="text/javascript">
(function(){

var _N = window.N;
var N = window.N = {
    notificationManager: (function() {
        var _instance = null;
        if (_instance != null) {
            return _instance;
        }
        
        var _notificationCount = 0;
        var _fn = null;
        var _nodeList = [];
        function notificationManager() {
            _fn = this;
        }
        notificationManager.prototype = {
            begin: function() {
                _notificationCount ++;
                _fn.render();
            },
            end: function() {
                _notificationCount --;
                _fn.render();
            },
            addNode: function(node) {
                _nodeList[_nodeList.length] = node;
                _fn.render();
            },
            render: function() {
                if (_notificationCount == 0) {
                    for (var i = 0 ; i < _nodeList.length ; i++) {
                        _nodeList[i].hide();
                    }
                } else {
                    for (var i = 0 ; i < _nodeList.length ; i++) {
                        _nodeList[i].show();
                    }
                }
            }
        };
        _instance = new notificationManager();
        _instance.render();
        return _instance;
    })(), // no ;
    custom: {}
}

})(); // End notification manager

/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

$('#link-records-view').click( function(){
    $('#records-view').toggle();
    $('#records-view .container').load('records-view.php');
    return false;
})

$('#form-add-bot').submit(function(e) {
        
    e.preventDefault();
    var form = $(this);
    N.notificationManager.begin();
    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType:"json",
        data: form.serialize(),
        complete: N.notificationManager.end,
        success: function(data) {
            if (data.code == 'ok') {
                $('#bots-list').load('bots-list.php');
                return false;
            } else {
                alert("Something went wrong.");//dump(data.output) + dump(data.return_var)
                return false;
            
            }
        }
    });
    return false;
});

function refreshBotsList () {
    $('#bots-list').load('bots-list.php');
    var t = setTimeout("refreshBotsList()",5000);
}
refreshBotsList ();

</script>






</body>
</html>