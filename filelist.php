<ol class='tree'>
<?

if (isset($_GET['exit'])) { 
	header('Location: http://logout@$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]');
}

$Password = "Peshehod8"; //Da ty ebanis!

function authenticate(){
    header("WWW-Authenticate: Basic realm=\"GPX\"");
    header('HTTP/1.0 401 Unauthorized');
    echo "You must enter a valid user name and password to access the requested resource.";
    exit;
}

for(; 1; authenticate()){
    if (!isset($_SERVER['PHP_AUTH_USER']))
        continue;
	
	if($_SERVER['PHP_AUTH_PW'] != $Password)
		continue;
	
    break;
}

function sortDate( $a, $b ) {
    return strtotime($a.".2015") - strtotime($b.".2015");
}

$files = scandir("./gpx/");
    	$gpx = preg_grep("/.+\.(gpx|kml)$/", $files);
    	
    	foreach($gpx AS $name){
    		$date = explode("_", $name);
    		$date_array[$date[0]][] = $name;
    	}
    	
    	$folder = '';

        uksort($date_array, "sortDate");

    	foreach($date_array AS $date => $files) {
    		$checked = '';
    		$folder .= "<li>
		<label for='$date'>".$date." <img src='un-checked.png' class='save' id='d_$date' /></label> <input type='checkbox' id='$date' />
		<ol>";
		
    		foreach($files AS $file){
    			$folder .= "<li class='file'><img src='un-checked.png' class='save' id='$file' /> <a href='' class='files'>$file</a></li>";
    		}
    		$folder .= "</ol></li>";
    	}
    	
    	echo $folder;
?>
</ol>

<script>
$(document).ready(function() {
	//localStorage.clear(); //Delete it all
	
	$.each(localStorage, function(i) { //a ne huynyu li ya nesu?
		$("#"+i.replace(/\./ig, "\\.")).attr('src', 'checked.png');
	});
	
    $(".save").click(function(event) {
	    event.preventDefault();
    	if(localStorage.getItem(event.target.id)) {
    		$(this).attr('src', 'un-checked.png');
    		localStorage.removeItem(event.target.id);
    	}
    	else {
    		$(this).attr('src', 'checked.png');
        	localStorage.setItem(event.target.id, 1 );
		}
    });
});
</script>