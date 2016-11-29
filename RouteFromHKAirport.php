<!DOCTYPE html>

<html>
  <head>
    <meta charset="UTF-8" />
    <title>Find a route using Geolocation and Google Maps API</title>
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">

    <script>
      function clearDiv(){
        document.getElementById("panel").innerHTML = "";
      }
      function calculateRoute(from, to) {
        // Center initialized to Naples, Italy
        var myOptions = {
          zoom: 10,
          center: new google.maps.LatLng(40.84, 14.25),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // Draw the map
        var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);

        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
          origin: from,
          destination: to,
          travelMode: google.maps.DirectionsTravelMode.TRANSIT,
          unitSystem: google.maps.UnitSystem.METRIC
        };

        directionsDisplay.setMap(mapObject);
        directionsDisplay.setPanel(document.getElementById('panel'));
        directionsService.route(
                directionsRequest,
                function(response, status)
                {
                  if (status == google.maps.DirectionsStatus.OK)
                  {
                    /*
                     new google.maps.DirectionsRenderer({
                     map: mapObject,
                     directions: response
                     });
                     */
                    directionsDisplay.setDirections(response);
                  }
                  else
                    $("#error").append("Unable to retrieve your route<br />");
                }
        );
      }

      $(document).ready(function() {
        // If the browser supports the Geolocation API
        if (typeof navigator.geolocation == "undefined") {
          $("#error").text("Your browser doesn't support the Geolocation API");
          return;
        }

        $("#from-link, #to-link").click(function(event) {
          event.preventDefault();
          var addressId = this.id.substring(0, this.id.indexOf("-"));

          navigator.geolocation.getCurrentPosition(function(position) {
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                              "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                            },
                            function(results, status) {
                              if (status == google.maps.GeocoderStatus.OK)
                                $("#" + addressId).val(results[0].formatted_address);
                              else
                                $("#error").append("Unable to retrieve your address<br />");
                            });
                  },
                  function(positionError){
                    $("#error").append("Error: " + positionError.message + "<br />");
                  },
                  {
                    enableHighAccuracy: true,
                    timeout: 10 * 1000 // 10 seconds
                  });
        });

        $("#calculate-route").submit(function(event) {
          event.preventDefault();
          clearDiv();
          calculateRoute("Hong Kong International Airport", $("#to").val());
        });
      });
    </script>
    <style type="text/css">
      .mainDiv{
        margin-top: 70px;
      }
      .mainDiv > h1{
        font-size: 20px;
      }

      #map {
        width: 500px;
        height: 400px;
        margin-top: 10px;
      }
      form > input:nth-child(4),form > input:nth-child(5){
        margin-top: 10px;
        padding:6px;
        /*background-color: #757575;*/
        background-color: #337ab7;
        border: none;
        border-radius: 3px;
        color: white;

      }
      form > input:nth-child(4):hover,form > input:nth-child(5):hover{
        background-color: #2b669a;
      }
      form > input:nth-child(2){
        padding:5px;
        font-size: 12px;
      }

    </style>
  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">COMP3421</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="conference.php">Home</a></li>
      <li><a href="search.php">Search</a></li>
          <li><a href="./presentation.php">Presentations</a></li>
          <li><a href="./speakers.php">Speakers</a></li>
          <li><a href="./attractions.html">Attractions</a></li>
          <li class="active"><a href="#">Route<span class="sr-only">(current)</span></a></li>
          <li><a href="#">About</a></li>
        </ul>
       
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <div class="mainDiv">
    <h1>From HK Airport to your conference venue:</h1>
    <form id="calculate-route" name="calculate-route" action="#" method="get">
      <label for="to">To:</label>
      <input type="text" id="to" name="to" required="required" placeholder="Enter your conference venue" size="30" />
      <br />

      <input type="submit" />
      <input type="reset"  onclick="document.location.reload();" />
    </form>
    <div id="map"></div>
    <div id="panel"></div>
    <p id="error"></p>
  </div>

  <footer>
    <address>
      COMP PolyU HongKong<br/>
      <abbr title="Phone">P:</abbr>+852xxxxxxxx
    </address>
    <address>
      <strong>COMP3421</strong><br/>
      <strong>Members:</strong><br/>
      <span title="email">Anna:</span><a href="mailto:anna.huang@connect.polyu.hk">anna.huang@connect.polyu.hk</a><br/>
      <span title="email">Laura:</span><a href="mailto:anna.huang@connect.polyu.hk">anna.huang@connect.polyu.hk</a><br>
      <span title="email">Lian:</span><a href="mailto:14040502d@connect.polyu.hk">14040502d@connect.polyu.hk</a><br>
      <span title="email">Ivan:</span><a href="mailto:13068412d@connect.polyu.hk">13068412d@connect.polyu.hk</a>
    </address>
  </footer>
  <script>
      <?php
      if(isset($_GET['venue'])){?>
      $("#to").val('<?echo $_GET['venue'];?>');
      <?}
      ?>

  </script>
  </body>


</html>
