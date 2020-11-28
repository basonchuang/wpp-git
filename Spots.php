<?php
  include_once "./database/database.php";
  $name = $_GET["name"];
?>
<!DOCTYPE html>
<html>
<head>
  <title>朴實無華 - <?php echo $name; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script src="https://code.jquery.com/jquery-3.4.1.js"integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOURKEY&libraries=places" defer></script>
  <script>
    const googleMap = new Vue({
      el: '#app',
      data: {
        map: null
      },
      methods: {
        // init google map
        initMap() {
          //找到此spot name的經緯度資料，將marker定在那，也將整個地圖的center定在那
          fetch('./database/map.geojson')
            .then(results => results.json())
            .then(result => {
              let res = result.features;
              Array.prototype.forEach.call(res, r => {
                if(r.properties.name == '<?php echo $name; ?>'){
                  let latLng = new google.maps.LatLng(r.geometry.coordinates[0], r.geometry.coordinates[1]);
                  this.map = new google.maps.Map(document.getElementById('map'), {
                    center: latLng,
                    zoom: 16,
                    mapTypeId: 'terrain'
                  });
                  let request = {
                    placeId: r.properties.place_id,
                    fields:["name","rating", "reviews"],
                  };
                  let infowindow = new google.maps.InfoWindow();
                  let service = new google.maps.places.PlacesService(this.map);
                  service.getDetails(request,(place,status)=>{
                    if(status === google.maps.places.PlacesServiceStatus.OK){
                      let marker = new google.maps.Marker({
                        position: latLng,
                        map: this.map
                      });
                      google.maps.event.addListener(marker,"click",function(){
                        infowindow.setContent(
                          "<div>"+
                          place.name +
                          "<br>" +
                          place.rating +
                          "<br>" +
                          place.reviews[0].text +
                          "</div>"
                        );
                        infowindow.open(this.map,this);
                      });
                    }
                  });
                }
              });
          });
        }
      },
      created() {
        window.addEventListener('load', () => {
          this.initMap();
        });
      }
    });
  </script>
  <style>
    #map{
      height:50%;
      width:50%;
    }
    html,
    body {
      height: 100%;
}
  </style>
</head>

<body>
  <table id="Spot" border="3">
  </table>
  <div id="map"></div>
  <div id="comment"></div>

  <script type="text/javascript">
    $.ajax({
      url: "./database/config.php?method=spot&name=<?php echo $name; ?>",
      dataType: "json",
      success: function (data) {
        var htmls = '';
        htmls += '<tr><td>' + data[0] + '</td></tr>' +
                  '<tr><td>'+ data[1] + '</td></tr>' + 
                  '<tr><td><a href="https://www.google.com/maps/place/'+ data[2] +'">' + data[2] + '</a></td></tr>' +
                  '<tr><td><a href="tel:'+ data[3] +'">' + data[3] + '</a></td></tr>' +
                  '<tr><td>' +data[4] + '</td></tr>' +
                  '<tr><td><button type="button" id="btn" onclick="pass();">加入選點清單</button>';
        document.getElementById("Spot").innerHTML = '' + htmls;
      }
    });

    function pass(){
      <?php
        session_start();
        if(!isset($_SESSION['cart'])){
          $_SESSION['cart'] = array();
        }
        array_push($_SESSION['cart'],$name);
        //$_SESSION['cart'][] = $name;
      ?>
      alert('已將 <?php echo $name; ?> 加入選點組合');
    }
  </script>
</body>
</html>
