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

function defaultTab() {
  document.getElementById("defaultOpen").click();
}

function loadLineChart() {
  var query = "SELECT * FROM Soil_Data";
  $.ajax({
    type: "GET",
    url: "php/NPKContents.php?query=" + query,
    success: function (result) {
      var jsonData = JSON.parse(result);

      var labels = jsonData.map((e) => {
        return e.time;
      });

      var n = jsonData.map((e) => { 
        return e.nitrogen;
      });

      var p = jsonData.map((e) => { 
        return e.phosphorous;
      });

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

/* EVENT LISTENERS */
window.addEventListener("load", () => { 
  $("#myTable").DataTable();
});

