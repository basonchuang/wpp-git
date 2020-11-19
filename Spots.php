<?php
  include_once "./database/database.php";
  $name = $_GET["name"];
?>

<html>
  <head>
    <title>朴實無華 - <?php echo $name; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://code.jquery.com/jquery-3.4.1.js"integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  </head>

  <body>
    <table id="Spot" border="3">
    </table>
    <script type="text/javascript">
      $.ajax({
        url: "./database/config.php?method=spot&name=<?php echo $name; ?>",
        dataType: "json",
        success: function (data) {
          var htmls = '';
          for (var i = 0; i < data.length; i++) {
            htmls += '<tr><td>' + data[i] + '</td></tr>';
            document.getElementById("Spot").innerHTML = '' + htmls;
          }
        }
      });
    </script>
  </body>
</html>