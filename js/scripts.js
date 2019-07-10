function openPage(pageToOpen, element) {
  // Clear tab content first
  tabcontent = document.getElementsByClassName("tabcontent");
  for (let i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  var tabList = document.getElementsByClassName("tablink");
  for (let idx = 0; idx < tabList.length; idx++) {
    // The not selected tab
    if (tabList[idx].innerHTML != pageToOpen) {
      tabList[idx].style.backgroundColor = "#1979CA";
      tabList[idx].style.color = "#FFF";
    }
  }

  // Show content
  document.getElementById(pageToOpen).style.display = "block";

  if (pageToOpen == "Graph") {
    loadLineChart();
  }

  // Highlight tablinks with white and text with blue
  element.style.backgroundColor = "#FFF";
  element.style.color = "#1979CA";
}

function loadLineChart() {
  var query = "SELECT * FROM Soil_Data";
  $.ajax({
    type: "GET",
    url: "php/NPKContents.php?query=" + query,
    success: function (result) {
      var jsonData = JSON.parse(result);

      // The x-axis label of chart
      var labels = jsonData.map((e) => {
        return e.time;
      });

      // Dataset of nitrogen
      var n = jsonData.map((e) => { 
        return e.nitrogen;
      });

      // Dataset of phosphorous
      var p = jsonData.map((e) => { 
        return e.phosphorous;
      });

      // Dataset of potassium
      var k = jsonData.map((e) => { 
        return e.potassium;
      });

      var context = document.getElementById("myChart").getContext("2d");
      var config = {
        type: "line",
        data: {
          labels: labels,
          datasets: [{
            label: "Nitrogen",
            data: n,
            borderColor: "rgba(17, 0, 255, 1)",
            fill: false,
          }, {
            label: "Phosphorous",
            data: p,
            borderColor: "rgba(86, 255, 0, 1)",
            fill: false
          }, {
            label: "Potassium",
            data: k,
            borderColor: "rgba(255, 173, 0, 1)",
            fill: false
          }]
        }
      };

      // Create chart instance
      new Chart(context, config);
    }
  });
}

function getNPKContent(soilType) {
  $(document).ready(function () {
    // Fetch the soil type
    soilType = soilType.split(" ")[0].toLowerCase();

    // Sets new colour
    var color;
    if(soilType == "nitrogen"){
      color = "#0000FF";
    } else if(soilType == "phosphorous"){
      color = "#A6FF00";
    } else if(soilType == "potassium"){
      color = "#FF5B00";
    }
    
    // Apply
    $(".f-path").css({
        "fill": color
    });

    var query = "SELECT " + soilType + " FROM Soil_Data ORDER BY id DESC LIMIT 6";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "php/NPKContents.php?query=" + query, true);
    xmlhttp.send();
      
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === XMLHttpRequest.DONE && xmlhttp.status === 200) {
        // The result we get from the URL
        var jsonData = JSON.parse(xmlhttp.responseText);
        var idx = 1;
        jsonData.forEach(element => {
          document.getElementById("field-" + idx).innerHTML = "Field " + idx + ": " + element[soilType] + "%";
          $(".f" + idx).css({
            "opacity": element[soilType] / 100
          });
          idx += 1;
        });
      }
    }
  });
}

function refreshTime() {
  var dateString = new Date().toLocaleString("en-US", {timeZone: "Singapore"});
  var formattedString = dateString.replace(", ", " - ");
  console.log(formattedString);
  document.getElementById("real-time").innerHTML = formattedString;
}

setInterval(refreshTime, 1000);

/* EVENT LISTENERS */
window.addEventListener("load", () => { 
  $("#myTable").DataTable();

  document.getElementById("defaultOpen").click();
  
  getNPKContent("phosphorous");

  // Map field emphasis on hover
  for (let index = 1; index < 7; index++) {
    document.getElementById("field-" + index).addEventListener("mouseover", function () { 
      $("#field-" + index).css({
        "font-weight": "bold"
      });

      $(".f" + index).css({
        "stroke": "#fff",
        "stroke-width": "5px"
      });
    })

    document.getElementById("field-" + index).addEventListener("mouseleave", function () { 
      $("#field-" + index).css({
        "font-weight": "300"
      });

      $(".f" + index).css({
        "stroke": "none",
        "stroke-width": "1px"
      });
    })
  }
});

