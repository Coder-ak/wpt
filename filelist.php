<ol class='tree'>
    <li>
        <label for="folder2">Folder 2</label> <input type="checkbox" id="folder2" />
        <ol>
            <li>
                <label for="subfolder2">Subfolder 1</label> <input type="checkbox" id="subfolder2" />
                <ol>
                    <li class="file"><a href="">Subfile 1</a></li>
                    <li class="file"><a href="">Subfile 2</a></li>
                    <li class="file"><a href="">Subfile 3</a></li>
                    <li class="file"><a href="">Subfile 4</a></li>
                    <li class="file"><a href="">Subfile 5</a></li>
                    <li class="file"><a href="">Subfile 6</a></li>
                </ol>
            </li>
        </ol>
    </li>
</ol>

<?
require("auth.php");

function sortDate( $a, $b ) {
    return strtotime($a.".2015") - strtotime($b.".2015");
}

$files = scandir("./gpx/");
    	$gpx = preg_grep("/.+\.(gpx|kml)$/", $files);
    	
    	foreach($gpx AS $name){
    		$date = explode("_", $name);
            if(count($date)==1) {
                $date = explode(".", $name);
            }
    		$date_array[$date[0]][] = $name;
    	}
    	
    	$folder = '';

        uksort($date_array, "sortDate");

    	foreach($date_array AS $date => $files) {
    		$checked = '';
    		$folder .= "<li>
		<label for='$date'><img src='img/un-checked.png' class='save' id='d_$date' />".$date."</label> <input type='checkbox' id='$date' />
		<ol>";
		
    		foreach($files AS $file){
    			$folder .= "<li class='file'><img src='img/un-checked.png' class='save' id='$file' /> <a href='' class='files'>$file</a></li>";
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
		$("#"+i.replace(/\./ig, "\\.")).attr('src', 'img/checked.png');
	});
	
    $(".save").click(function(event) {
	    event.preventDefault();
    	if(localStorage.getItem(event.target.id)) {
    		$(this).attr('src', 'img/un-checked.png');
    		localStorage.removeItem(event.target.id);
    	}
    	else {
    		$(this).attr('src', 'img/checked.png');
        	localStorage.setItem(event.target.id, 1 );
		}
    });
});
</script>
