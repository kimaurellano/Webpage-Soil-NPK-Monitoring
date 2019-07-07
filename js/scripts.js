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

function loadLineChart() {
  $.ajax({
    type: "GET",
    url: "php/NPKContents.php?soil=*",
    success: function (data) { 
      var jsonData = JSON.parse(data);
      var context = document.getElementById("myChart").getContext("2d");
      new Chart(context, {
        type: "line",
        data: {
          datasets: [{
            label: "NPK Values",
            data: jsonData,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
          }]
        }
      })
    }
  });
}

/* EVENT LISTENERS */
window.addEventListener("load", () => { 
  $("#myTable").DataTable();
});

