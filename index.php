<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
header('Content-Type: text/html; charset=utf-8');
?>
<!--
Вер. хз какая +2
- разные метки для фото и не фото
Вер. хз какая +1
- галочки Дат
- ссылочки на разные карточки
- фулскрин карта и плавающе-сворачиваемое меню
Версия хз какая.
- Превью фото
- Галочки файликов
-->
<head>

    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_UA" type="text/javascript"></script>
	<script src="http://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="styles.css" media="screen">
</head>

<body>
<div style="width: 100%; height: 100%; position:absolute;">
    <div id="map" style="width: 100%; height: 100%; text-align:right"></div>
    <div class="links">
    	<a href='#' id='mpro' target=_blank>MPRO</a> | <a href='#' id='nmap' target=_blank>NMAP</a> | <a href='#' id='osm' target=_blank>OSM</a> | <a href='#' id='here' target=_blank>HERE</a>
    </div>
    <div class="trigger">
	    <div class="menu_header">GPX files</div>
    	<div class="close"></div>
	</div>
    <div class="fcontainer" style="border-width: 1px; border-style:solid; border-color: #999;border-radius: 0 0 3px 3px;background-color: #333; position:absolute; right: 10px; top: 76px; max-height: 88%; overflow:scroll; width: 218px; padding: 5px;margin-bottom: 30px;background-image: linear-gradient(#FFF 0px, #EEE 100%);">
    	<?include("filelist.php");?>
    </div>
</div>
    
<script type="text/javascript">
$(".files").click(function (e) {
	e.preventDefault();
	location.hash = "!" + encodeURIComponent($(this).text());
	$(".files").css({"font-weight":"normal"}); // un-bold all
	$(this).css({"font-weight":"bold"});	
	LoadGpx($(this).text())

});

$(".trigger").click(function(){ 
	$(".fcontainer, .close").toggle();
});

$(function (){//ready
	gpxfile = decodeURIComponent(location.hash.slice(2));
	if(gpxfile){
		id = gpxfile.replace(/\./ig, "\\.");
		$(".save#"+id).next(".files").css({"font-weight":"bold"});
		$("#" + gpxfile.split("_")[0].replace(/\./ig, "\\.")).prop('checked', true);
		LoadGpx(gpxfile);
	}
});

function LoadGpx(gpxfile){
	$(".fcontainer, .close").toggle();
	$(".menu_header").html(gpxfile);
	ymaps.ready( function(){
		$('#map').empty();
		init( encodeURIComponent(gpxfile) );
	});
}

function init(url){
	createMapFromUrl("http://<?=$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>gpx/" + url, function (map) {

		myMap.events.add('boundschange', function (event) {
			var center = myMap.getCenter();
	
			$('#mpro').attr("href", "https://mpro.maps.yandex.ru/?ll=" + center[1] + "," + center[0] + "&z=" + (myMap.getZoom()+1));
			$('#nmap').attr("href", "https://n.maps.yandex.ru/#!/?z=" + (myMap.getZoom()+1) + "&ll=" + center[1] + "," + center[0]);
			$('#osm').attr("href", "https://www.openstreetmap.org/#map=" + (myMap.getZoom()+1) + "/" + center[0] + "/" + center[1]);
			$('#here').attr("href", "https://www.here.com/?map=" + center[0] + "," + center[1] + "," + (myMap.getZoom()+1) + ",satellite");
		});

		myMap.geoObjects.events.add('mousedown', function (e) {
			e.get('target').options.set('iconColor', '#FFCB1A');
		});

	});
	
}
    
function createMapFromUrl(url, callback) {
	var timestamp = new Date().getTime();
    ymaps.geoXml.load(url + "?" + timestamp).then(function (res) {
            
        callback(myMap = new ymaps.Map("map", {
            center: res.geoObjects.get(0).geometry.getCoordinates(),
            zoom: 14
        }));
        
        /*
        Аааа, ну почему так нельзя!!!
        callback(
        	$mapElement = $('#map'), console.log( res.geoObjects.getBounds() ),
        	myMap = new ymaps.Map($mapElement[0], ymaps.util.bounds.getCenterAndZoom(res.geoObjects.getBounds(), [$mapElement.width(), $mapElement.height()]) )
        );*/
  
		res.geoObjects.each(function (obj) {
    		descrImg = obj.properties.get('description');
			var pattern = new RegExp(/(photo\/\S*\.jpg)/i);
			if (pattern.test(descrImg)) {
				obj.options.set('preset', 'islands#dotIcon');
				imgUrl = descrImg.match(pattern)[1];	
				obj.properties.set( {description:  descrImg + '<br><a href="' + imgUrl + '" target=_blank><img src="' + imgUrl + '" width=300></a>'} );
			}
		});

		myMap.geoObjects.add(res.geoObjects); 
		myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true});
        myMap.events.add('click', function (e) {  
			myMap.balloon.close();
		});
    });
}

</script>
</body>
