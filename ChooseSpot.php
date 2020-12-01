<html>
<head>
  <title>朴實無華 - 選點組合</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="dragdrop.css" type="text/css" />
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $( function() {
      $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable"
      }).disableSelection();
    } );
  </script>
</head>

<body>
  <ul id="sortable1" class="connectedSortable">
  </ul>
  起點：<input type="text" id="origin"><br>
  <ul id="sortable2" class="connectedSortable">
  </ul>
  交通方式：<select name="travelmode">
    <option value="DRIVING">開車</option>
    <option value="BICYCLING">騎單車</option>
    <option value="TRANSIT">大眾運輸</option>
    <option value="WALKING">步行</option>
  </select>

  <button type="button" onclick="initialize();calcRoute();">送出選點組合</button>

  <div id="map_canvas" style="width:100%;height:100%"></div>

  <script type="text/javascript">
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
    //var directionsService = new google.map.DirectionsService();
    var map;
    var start;

    

    var end = $('#sortable2 li:last-child').text();
    var choosepoints = [];

    function initialize(){
      start = document.getElementById("origin").value ? start = document.getElementById("origin").value : start = '';
      $("#sortable2").each(function(){
        $(this).find('li').each(function(){
          choosepoints.push($(this).text());
        });
      });
      if(start=''){
        start = choosepoints[choosepoints.length];
        alert(start);
      }
      alert(choosepoints); 
    }
    function calcRoute(){
      ;
    }
    // for 
  </script>
</body>

</html>