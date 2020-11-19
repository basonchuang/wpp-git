function search(){
  var keywordElement = document.getElementById("keyword");
  var keyword = keywordElement.value;
  $.ajax({
        url: "./database/config.php?method=search&keyword="+keyword,
        dataType: "json",
        success: function (data) {
          var htmls = '';
          for (var i = 0; i < data.length; i++) {
            htmls += '<tr><td><a href="Spots.php?name='+ data[i] +'">' + data[i] + '</a></td></tr>';
            document.getElementById("Search").innerHTML = '<tr><td>地點名稱</td></tr>' + htmls;
          }
        }
      });
}