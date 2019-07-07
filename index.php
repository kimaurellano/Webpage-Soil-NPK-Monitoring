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
    <script src="js/scripts.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    
    <!-- CSS -->
    <link href="styles/style.css" type="text/css" rel="stylesheet" />
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet">
    
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300" rel="stylesheet" />
    
    <!-- Icon -->
    <link rel="icon" type="image/png" href="assets/icon.png"/>
    
    <!-- Title bar -->
    <title>NPK sensor</title>
    
  </head>
  
  <body onload="defaultTab()">
      <script>
          $(document).ready( function () {
            var soilTypes = ["NITROGEN", "PHOSPHOROUS", "POTASSIUM"];
            var curSelected = 1;
            
            // Map nav button behaviour
            $(".soil-npk-content > .nav").on("click", function(){
              $(".page-indicator > circle").css({
                "fill": "#ccc"
              });

              $(".page-indicator > .page-" + curSelected).css({
                "fill": "#519edd"
              });
            });
            
            // Left nav button
            $(".soil-npk-content > .nav-left > path").on("click", function() {
              $(".npk-text > ul > li").fadeOut(300, function() {
                $(".soil-content-type").text(soilTypes[curSelected] + " CONTENT");
              }).fadeIn(300);
              
              if(curSelected < 1) {
                return;
              }

              curSelected -= 1;

              // Update NPK values by soil type
              getNPKContent(soilTypes[curSelected]);
            });
            
            // Right nav button
            $(".soil-npk-content > .nav-right > path").on("click", function() {
              $(".npk-text > ul > li").fadeOut(300, function() {
                $(".soil-content-type").text(soilTypes[curSelected] + " CONTENT");
              }).fadeIn(300);

              if(curSelected > 1) {
                return;
              }

              curSelected += 1;

              // Update NPK values by soil type
              getNPKContent(soilTypes[curSelected]);
            });
          } );

          function getNPKContent(soilType) {
            $(document).ready(function () {
              // Fetch the soil type
              soilType = soilType.split(" ")[0].toLowerCase();
              
              var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "php/NPKContents.php?soil=" + soilType, true);
              xmlhttp.send();
                
              xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === XMLHttpRequest.DONE && xmlhttp.status === 200) {
                  // The result we get from the URL
                  var jsonData = JSON.parse(xmlhttp.responseText);
                  var idx = 1;
                  jsonData.forEach(element => {
                    document.getElementById("field-" + idx).innerHTML = "Field " + idx + ": " + element[soilType] + "%";
                    idx += 1;
                  });
                }
              }
            });
          }
      </script>

    <!-- The tab buttons -->
    <button class="tablink" onclick="openPage('Logs', this)">LOGS</button>
    <button class="tablink" onclick="openPage('Graph', this)">GRAPH</button>
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
          // Connection to the database
          include "php/DatabaseManager.php";
          
          $query = mysqli_query($connection, "SELECT * FROM mysql.Soil_Data");
          
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
        ?>
        </tbody>
      </table>
    </div>

    <!-- Graph content -->
    <div id="Graph" class="tabcontent">
      <!-- The chart -->
      <canvas id="myChart" height="100"></canvas>
    </div>

    <!-- Map content -->
    <div id="Map" class="tabcontent">
      <div class="map-content-block map-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 536.57 538.07">
            <defs>
              <style>
                .cls-1{fill:none;stroke:#ccc}.cls-1,.cls-3{stroke-miterlimit:10}.cls-1{stroke-width:.75px}.cls-8{stroke:#3fa9f5}.cls-3{fill:#fff;stroke:#fff;stroke-width:10px}.cls-8{stroke-linecap:square;fill:#3fa9f5}.cls-9{isolation:isolate;font-size:7.91px;font-family:Arial-BoldMT,Arial;font-weight:700}
              </style>
            </defs>
            <g id="Layer_2" data-name="Layer 2">
              <path d="M.38 14.2h522v514H.38zM.38 518.12h522M.38 508.05h522M.38 497.97h522M.38 487.89h522M.38 477.81h522M.38 467.73h522M.38 457.65h522M.38 447.57h522M.38 437.5h522M.38 427.42h522M.38 417.34h522M.38 407.26h522M.38 397.18h522M.38 387.1h522M.38 377.03h522M.38 366.95h522M.38 356.87h522M.38 346.79h522M.38 336.71h522M.38 326.63h522M.38 316.56h522M.38 306.48h522M.38 296.4h522M.38 286.32h522M.38 276.24h522M.38 266.16h522M.38 256.08h522M.38 246.01h522M.38 235.93h522M.38 225.85h522M.38 215.77h522M.38 205.69h522M.38 195.61h522M.38 185.54h522M.38 175.46h522M.38 165.38h522M.38 155.3h522M.38 145.22h522M.38 135.14h522M.38 125.07h522M.38 114.99h522M.38 104.91h522M.38 94.83h522M.38 84.75h522M.38 74.67h522M.38 64.59h522M.38 54.52h522M.38 44.44h522M.38 34.36h522M.38 24.28h522M512.14 14.2v514M501.9 14.2v514M491.67 14.2v514M481.43 14.2v514M471.2 14.2v514M460.96 14.2v514M450.73 14.2v514M440.49 14.2v514M430.26 14.2v514M420.02 14.2v514M409.79 14.2v514M399.55 14.2v514M389.32 14.2v514M379.08 14.2v514M368.85 14.2v514M358.61 14.2v514M348.37 14.2v514M338.14 14.2v514M327.9 14.2v514M317.67 14.2v514M307.43 14.2v514M297.2 14.2v514M286.96 14.2v514M276.73 14.2v514M266.49 14.2v514M256.26 14.2v514M246.02 14.2v514M235.79 14.2v514M225.55 14.2v514M215.32 14.2v514M205.08 14.2v514M194.85 14.2v514M184.61 14.2v514M174.38 14.2v514M164.14 14.2v514M153.9 14.2v514M143.67 14.2v514M133.43 14.2v514M123.2 14.2v514M112.96 14.2v514M102.73 14.2v514M92.49 14.2v514M82.26 14.2v514M72.02 14.2v514M61.79 14.2v514M51.55 14.2v514M41.32 14.2v514M31.08 14.2v514M20.85 14.2v514M10.61 14.2v514" class="cls-1"/>
            </g>
            <g id="Map">
              <path fill="#97ff8a" stroke="#3fa9f5" stroke-miterlimit="5" stroke-width=".75" d="M10.38 20.2v500h500v-500h-500" opacity=".3"/>
              <path d="M534.88 180.7l-487-176M315.88 536.7l112-393" class="cls-3"/>
              <path fill="#c1e082" stroke="#c1e082" stroke-miterlimit="5" stroke-width=".75" d="M146.88 72.2l248.5 93.74-75.63 246.76-62.67-19.13 43.22-141.55-105.89-36.35L146.88 72.2z"/>
            </g>
            <g id="Layer_3" data-name="Layer 3">
              <ellipse id="path2408" class="" cx="86.07" cy="445.06" fill="none" stroke="#3fa9f5" stroke-linecap="square" stroke-width=".89" rx="20.34" ry="19.74"/>
              <g id="g11797">
                <path id="path2122" d="M70.53 419.86l5.58 5.42H34.85z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path5805" d="M70.53 430.85l5.58-5.41H34.85z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8724" d="M81.86 430.85l-5.58-5.41h41.26z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8726" d="M81.86 419.86l-5.58 5.42h41.26z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8740" d="M70.53 430.85l5.58-5.41v40z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8742" d="M81.86 430.85l-5.58-5.41v40z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8746" d="M81.86 419.86l-5.58 5.42v-40z" class="cls-8" transform="translate(9.88 19.7)"/>
                <path id="path8748" d="M70.53 419.86l5.58 5.42v-40z" class="cls-8" transform="translate(9.88 19.7)"/>
              </g>
              <g id="g1561">
                <text class="cls-9" transform="matrix(1.03 0 0 1 129.48 447.94)">E</text>
                <text class="cls-9" transform="matrix(1.03 0 0 1 34.92 447.94)">W</text>
                <text class="cls-9" transform="matrix(1.03 0 0 1 83.34 492.76)">S</text>
                <text class="cls-9" transform="matrix(1.03 0 0 1 83.13 402.52)">N</text>
              </g>
            </g>
          </svg>
      </div>

      <!-- NPK -->
      <div class="map-content-block soil-npk-content">
        <svg class="nav nav-left block" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 150 200">
          <path fill="#1979ca" d="M99.22 0H38.01L0 101l38.01 101h61.21L61.21 101 99.22 0z"/>
        </svg>
        <div class="npk-text">
          <ul>
            <li class="soil-content-type">PHOSPHOROUS CONTENT</li>
            <li id="field-1">Field 1: 0%</li>
            <li id="field-2">Field 2: 0%</li>
            <li id="field-3">Field 3: 0%</li>
            <li id="field-4">Field 4: 0%</li>
            <li id="field-5">Field 5: 0%</li>
            <li id="field-6">Field 6: 0%</li>
          </ul>
        </div>
        <svg class="page-indicator" height="100" width="125">
            <circle class="page-0" cx="50" cy="50" r="5" stroke="none" stroke-width="3" fill="#ccc" />
            <circle class="page-1" cx="75" cy="50" r="5" stroke="none" stroke-width="3" fill="#519edd" />
            <circle class="page-2" cx="100" cy="50" r="5" stroke="none" stroke-width="3" fill="#ccc" />
        </svg>
        <svg class="nav nav-right" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 150 200">
          <path fill="#1979ca" d="M0 0h61.21l38.01 101-38.01 101H0l38.01-101L0 0z"/>
        </svg>
      </div>
    </div>
  </body>
</html>