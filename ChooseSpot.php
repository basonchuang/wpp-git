<html>
<head>
  <title>朴實無華 - 選點組合</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="dragdrop.css" type="text/css" />
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOURKEY&libraries=directions" defer></script>
  <script>
    $( function() {
      $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable"
      }).disableSelection();
    } );
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
  <script>
    //取得使用者所在位置的經緯度
    function textclick(){
      if(navigator.geolocation) {
      // 使用者不提供權限，或是發生其它錯誤
        function error() {
          alert('無法取得你的位置');
        }

        // 使用者允許抓目前位置，回傳經緯度
        function success(position) {
          console.log(position.coords.latitude, position.coords.longitude);
        }

        // 跟使用者拿所在位置的權限
        navigator.geolocation.getCurrentPosition(success, error);

      } else {
        alert('Sorry, 你的裝置不支援地理位置功能。')
      }
    }
  </script>
</head>

<body>
  <ul id="sortable1" class="connectedSortable">
  </ul>
  起點：<input type="text" id="origin" onclick="textclick()"><br>
  <ul id="sortable2" class="connectedSortable">
  </ul>
  交通方式：<select id="travelmode">
    <option value="DRIVING">開車</option>
    <option value="BICYCLING">騎單車</option>
    <option value="TRANSIT">大眾運輸</option>
    <option value="WALKING">步行</option>
  </select>

  <button type="button" onclick="initialize();calcRoute();">送出選點組合</button>
  <br><br>
  <div id="map"></div>

  <script type="text/javascript">
    //所有地點&已選地點
    $.ajax({
      url: "./database/config.php?method=allspots",
      dataType: "json",
      success: function (data1) {
        $.ajax({
          url: "./database/config.php?method=choosespots",
          dataType: "json",
          success: function (data2) {
            var html1 = '';       //存放所有地點的html標籤
            var html2 = '';       //存放已選地點的html標籤

            //去除所有地點裡面已選的地點
            var all = [];
            var excchoose = [];
            for (var i = 0; i < data2.length; i++) {
              all[data2[i]] = true;
            }
            for (var i = 0; i < data1.length; i++) {
              if(!all[data1[i]]){
                excchoose.push(data1[i]);
              }
            }

            //將去除已選地點之後的剩餘所有地點放入html標籤中
            for (var i = 0; i < excchoose.length; i++) {
              html1 += '<li class="ui-state-default">' + excchoose[i] + '</li>';
              document.getElementById("sortable1").innerHTML = '<h2>所有地點</h2>' + html1;
            }
            //將已選地點放入html標籤中
            for (var i = 0; i < data2.length; i++) {
              html2 += '<li class="ui-state-default">' + data2[i] + '</li>';
              document.getElementById("sortable2").innerHTML = '<h2>已選地點</h2>' + html2;
            }
          }
        });
      }
    });
  </script>
  <script>
    //選點路線
    var map;
    var start;
    var end;
    var choosepoints = [];
    var travelmode;

    function initialize(){
      var directionsService = new google.maps.DirectionsService();
      var directionsDisplay = new google.maps.DirectionsRenderer();
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: { lat: 23.4638, lng: 120.2473 }
      });
      directionsDisplay.setMap(map);

      //取得html頁面上使用者所填入的start, end, choosepoints, travelmode
      //start = document.getElementById("origin").value;
      $("#sortable2").each(function(){
        $(this).find('li').each(function(){
          choosepoints.push($(this).text());
        });
      });
      end = choosepoints[choosepoints.length-1];
      
      if(document.getElementById("origin").value){
        start = document.getElementById("origin").value;
      }
      else{
        start = choosepoints[0];
      }
      //travelmode = document.getElementById('travelmode').value;

      //將start, end, choosepoints的place_id從geojson檔取出
      fetch('./database/map.geojson')
        .then(results => results.json())
        .then(result => {
          let res = result.features;
          let startLatLng, endLatLng;
          Array.prototype.forEach.call(res,r => {
            //取得start的經緯度
            if(r.properties.name == start){
              startLatLng = new google.maps.LatLng(r.geometry.coordinates[0], r.geometry.coordinates[1]);
            }
            else{   //找到使用者輸入的地址的地點資訊
              
            }

            //取得end的經緯度
            if(r.properties.name == end){
              endLatLng = new google.maps.LatLng(r.geometry.coordinates[0], r.geometry.coordinates[1]);
            }
            let travelmode = document.getElementById('travelmode').value;

            var request = {
              origin:startLatLng,
              destination:endLatLng,
              travelMode: travelmode
            };

            // 繪製路線
            directionsService.route(request, function (result, status) {
                if (status == 'OK') {
                    // 回傳路線上每個步驟的細節
                    directionsDisplay.setDirections(result);
                } else {
                    //console.log(status);
                }
            });
          })
          
        })
      //路線request
      while(choosepoints.length){
        choosepoints.pop();
      }
    }
    function calcRoute(){
      ;
    }
  </script>
</body>

</html>
