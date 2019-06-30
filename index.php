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
    <button id="defaultOpen" class="tablink" onclick="openPage('Logs', this)">LOGS</button>
    <button class="tablink" onclick="openPage('Graph', this), lineChart()">GRAPH</button>
    <button class="tablink" onclick="openPage('Map', this)">MAP</button>
    
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
      </script>
      
    </div>

    <!-- Graph content -->
    <div id="Graph" class="tabcontent">
      <!-- The chart -->
      <canvas id="myChart" height="100"></canvas>
    </div>

    <!-- Map content -->
    <div id="Map" class="tabcontent">
        <!-- The chart -->
        <h3>This is the map</h3>
    </div>
      
    
  </body>
</html>

