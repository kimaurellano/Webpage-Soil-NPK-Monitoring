<?php 
  include 'dbManager.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Metadata here. Link references -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Chartjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    
    <!-- Javascript -->
    <script src="js/tab.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    
    <!-- CSS -->
    <link href="styles/style.css" type="text/css" rel="stylesheet" />
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet">
    
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300" rel="stylesheet" />
    
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png"/>
    
    <!-- Title bar -->
    <title>NPK sensor</title>
    
  </head>
  
  <body onload="defaultTab()">
    <!-- The buttons -->
    <button class="tablink" onclick="openPage('Logs', this)">LOGS</button>
    <button class="tablink" onclick="openPage('Graph', this), lineChart()">GRAPH</button>
    <button id="defaultOpen" class="tablink" onclick="openPage('Map', this)">MAP</button>
    
    <!-- Logs content -->
    <div id="Logs" class="tabcontent">
      <!-- The table to populate with data -->
      <table id="myTable">
        <thead>
          <tr>
            <th>id</th>
            <th>sensorid</th>
            <th>time</th>
            <th>nitrogen content</th>
            <th>phosphorous content</th>
            <th>potassium content</th>
          </tr>
        </thead>
        
        <tbody>
        <!-- PHP script -->
        <?php 
          $conn = mysqli_connect("localhost", "root", "admin123", "mysql");
    
          // Check connection
          if (!$conn) {
              die ("failed");
          }
        
          $query = mysqli_query($conn, "SELECT * FROM mysql.Soil_Data");
          
          while($result_metadata = mysqli_fetch_assoc($query)){
            echo 
            "<tr align='center'>
              <td> " . $result_metadata['id'] . "</td>
              <td> " . $result_metadata['sensorid'] . "</td>
              <td> " . $result_metadata['time'] . "</td>
              <td> " . $result_metadata['nitrogen'] . "</td>
              <td> " . $result_metadata['phosphorous'] . "</td>
              <td> " . $result_metadata['potassium'] . "</td>
            </tr>";
          }
          
          $conn -> close();
        ?>
        </tbody>
      </table>
      
      <script>
        // Defines table native appearance
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
        
        // Behaviour of the map
        $(document).ready(function() {
        $("g.svg-field").hover(
          function() {
            $(this).css({ stroke: "#fff" });
          },
          function() {
            $(this).css({ stroke: "#1979ca" });
          }
        );
      });
      </script>
      
    </div>

    <!-- Graph content -->
    <div id="Graph" class="tabcontent">
      <!-- The chart -->
      <canvas id="myChart" height="100"></canvas>
    </div>

    <!-- Map content -->
    <div id="Map" class="tabcontent">
        <!-- The direction -->
        <svg class="svg-map-direction" xmlns="http://www.w3.org/2000/svg" viewBox="170 -5 150 150">
          <path fill="#3fa9f5" stroke="#3fa9f5" stroke-miterlimit="10" stroke-width=".75" d="M52.05 19.77L43.97 9.35l-8.1 10.42h16.18zM52.05 41.92l-8.08 10.43-8.1-10.43h16.18zM31.35 22.12l-9.52 8.86 9.52 8.86V22.12zM56.81 39.84l9.52-8.86-9.52-8.86v17.72z"/>
          <text fill="#007994" font-family="MyriadPro-Regular,Myriad Pro" font-size="8" transform="translate(34 6.7)">
            No<tspan x="9.66" y="0" letter-spacing=".024em">r</tspan><tspan x="12.46" y="0">th</tspan>
          </text>
          <text fill="#007994" font-family="MyriadPro-Regular,Myriad Pro" font-size="8" transform="translate(34 59.7)">
            <tspan letter-spacing=".006em">S</tspan><tspan x="3.99" y="0">outh</tspan>
          </text>
          <text fill="#007994" font-family="MyriadPro-Regular,Myriad Pro" font-size="8" letter-spacing="-.033em" transform="translate(0 32.7)">
            W<tspan x="6.5" y="0" letter-spacing="0em">est</tspan>
          </text>
          <text fill="#007994" font-family="MyriadPro-Regular,Myriad Pro" font-size="8" transform="translate(72 32.7)">
            East
          </text>
        </svg>
      
        <!-- The map -->
        <svg class="svg-map" xmlns="http://www.w3.org/2000/svg" viewBox="-60 -15 300 450">
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M201.4 370.57l-7 55-135-5-1-84 143 34z"/>
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M208.86 310.57l-6.91 56.01L59.4 332.57l-.54-100 150 78z"/>
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M59.4 228.57l150.3 78.17 12.7-99.17-163 21z"/>
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M59.4 223.57l163.1-20.99 12.9-100.01-176 121z"/>
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M53.4 220.57l176-121-85-40-91 161z"/>
        <path class="svg-map-field" fill="#B2D2ED" stroke="#B2D2ED" stroke-miterlimit="10" stroke-width=".75" d="M18.4.57l-18 203 49 15 90.64-161L18.4.57z"/>
        <line x1="80" y1="0" x2="300" y2="100" style="stroke:rgb(237,210,178);stroke-width:8" />
        <line x1="275" y1="90" x2="225" y2="500" style="stroke:rgb(237,210,178);stroke-width:8" />
        <text class="svg-map-field-text-1" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(48.9 112.57)">
          0%
        </text>
        <text class="svg-map-field-text-2" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(130.9 136.57)">
          0%
        </text>
        <text class="svg-map-field-text-3" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(165.9 183.57)">
          0%
        </text>
        <text class="svg-map-field-text-4" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(172.9 250.57)">
          0%
        </text>
        <text class="svg-map-field-text-5" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(124.9 313.57)">
          0%
        </text>
        <text class="svg-map-field-text-6" font-family="MyriadPro-Regular,Myriad Pro" font-size="13" transform="translate(121.9 391.57)">
          0%
        </text>
      </svg>
      
      <!-- NPK -->
      <div class="NPK-content-container">
        <h3>SOIL CONTENT(NITROGEN)</h3><br><br>
        <text>Field#1: 0%</text><br><br>
        <text>Field#1: 0%</text><br><br>
        <text>Field#1: 0%</text><br><br>
        <text>Field#1: 0%</text><br><br>
        <text>Field#1: 0%</text><br><br>
        <text>Field#1: 0%</text><br><br>
      </div>
    </div>
  </body>
</html>

