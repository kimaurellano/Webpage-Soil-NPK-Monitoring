/**
 * Target page and containing element on tab click
 * @param {string} pageToOpen
 * @param {string} element
 */
function openPage(pageToOpen, element) { // eslint-disable-line no-unused-vars
  // Clear tab content first
  tabcontent = document.getElementsByClassName('tabcontent');
  for (let i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = 'none';
  }

  const tabList = document.getElementsByClassName('tablink');
  for (let idx = 0; idx < tabList.length; idx++) {
    // The not selected tab
    if (tabList[idx].innerHTML != pageToOpen) {
      tabList[idx].style.backgroundColor = '#1979CA';
      tabList[idx].style.color = '#FFF';
    }
  }

  // Show content
  document.getElementById(pageToOpen).style.display = 'block';

  // Highlight tablinks with white and text with blue
  element.style.backgroundColor = '#FFF';
  element.style.color = '#1979CA';
}

/**
 * Populates NPK chart
 * @param {string} sensorid - The sensor we get info from
 */
function loadLineChart(sensorid) {
  const query =
    `SELECT * FROM soil_data WHERE sensorid='${sensorid}' ORDER BY id ASC`;
  $.ajax({
    type: 'GET',
    url: `php/NPKContents.php?query=${query}`,
    success: function(result) {
      const jsonData = JSON.parse(result);

      // The x-axis label of chart
      const labels = jsonData.map((e) => {
        const arr = e.time.split(' ');
        return arr[1];
      });

      // Dataset of nitrogen
      const n = jsonData.map((e) => {
        return e.nitrogen;
      });

      // Dataset of phosphorous
      const p = jsonData.map((e) => {
        return e.phosphorous;
      });

      // Dataset of potassium
      const k = jsonData.map((e) => {
        return e.potassium;
      });

      const context = document.getElementById('myChart').getContext('2d');
      const config = {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Nitrogen',
            data: n,
            borderColor: 'rgba(17, 0, 255, 0.6)',
            fill: false,
          }, {
            label: 'Phosphorous',
            data: p,
            borderColor: 'rgba(86, 255, 0, 0.6)',
            fill: false,
          }, {
            label: 'Potassium',
            data: k,
            borderColor: 'rgba(255, 173, 0, 0.6)',
            fill: false,
          }],
        },
      };

      // Create chart instance
      new Chart(context, config);
    },
  });
}

/**
 * @param {string} soilType
 */
function getNPKContent(soilType) {
  $(document).ready(function() {
    // Fetch the soil type
    soilType = soilType.split(' ')[0].toLowerCase();

    // Sets new colour
    let color;
    if (soilType == 'nitrogen') {
      color = '#0000FF';
    } else if (soilType == 'phosphorous') {
      color = '#A6FF00';
    } else if (soilType == 'potassium') {
      color = '#FF5B00';
    }

    // Apply
    $('.f-path').css({
      'fill': color,
    });

    // Get the logs from 6 NodeMCUs. The latest/last 6 values/rows from database
    const query = `SELECT ${soilType} FROM soil_data ORDER BY id DESC LIMIT 6`;

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET', `php/NPKContents.php?query=${query}`, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState === XMLHttpRequest.DONE &&
          xmlhttp.status === 200) {
        // The result we get from the URL
        const jsonData = JSON.parse(xmlhttp.responseText);
        let idx = 1;
        jsonData.forEach((element) => {
          document
              .getElementById(`field-${idx}`)
              .innerHTML = `Field ${idx}: ${element[soilType]}%`;
          $('.f' + idx).css({
            'opacity': element[soilType] / 100,
          });
          idx += 1;
        });
      }
    };
  });
}

/**
 * Map page
 */
$(document).ready( function() {
  const soilTypes = ['NITROGEN', 'PHOSPHOROUS', 'POTASSIUM'];
  let curSelected = 1;

  // Map nav button behaviour
  $('.soil-npk-content > .nav').on('click', function() {
    $('.page-indicator > circle').css({
      'fill': '#ccc',
    });

    $(`.page-indicator > .page-${curSelected}`).css({
      'fill': '#519edd',
    });
  });

  // Left nav button
  $('.soil-npk-content > .nav-left > path').on('click', function() {
    $('.npk-text > ul > li').fadeOut(300, function() {
      $('.soil-content-type').text(`${soilTypes[curSelected]} CONTENT`);
    }).fadeIn(300);

    if (curSelected < 1) {
      return;
    }

    curSelected -= 1;

    // Update NPK values by soil type
    getNPKContent(soilTypes[curSelected]);
  });

  // Right nav button
  $('.soil-npk-content > .nav-right > path').on('click', function() {
    $('.npk-text > ul > li').fadeOut(300, function() {
      $('.soil-content-type').text(`${soilTypes[curSelected]} CONTENT`);
    }).fadeIn(300);

    if (curSelected > 1) {
      return;
    }

    curSelected += 1;

    // Update NPK values by soil type
    getNPKContent(soilTypes[curSelected]);
  });
} );

/**
 * Real-time clock
 */
function refreshTime() {
  const dateString =
    new Date()
        .toLocaleString(
            'en-US',
            {timeZone: 'Singapore'});
  const formattedString = dateString.replace(', ', ' - ');
  document.getElementById('real-time').innerHTML = formattedString;
}

/**
 * Clock tick
 */
setInterval(refreshTime, 1000);

/* EVENT LISTENERS */
window.addEventListener('load', () => {
  // Initialize database on start
  $('#myTable').DataTable();

  // Default page on start
  document.getElementById('defaultOpen').click();

  // Load phosphorous data on start
  getNPKContent('phosphorous');

  // Load the NodeMCUs ip addresses to the select box
  const query = 'SELECT DISTINCT * FROM soil_data ORDER BY id DESC LIMIT 6';
  $.ajax({
    type: 'GET',
    url: `php/NPKContents.php?query=${query}`,
    success: (result) => {
      const jsonData = JSON.parse(result);
      loadLineChart(jsonData[0].sensorid);
      defOpSelected = jsonData[0].sensorid;
      // Populate the selection box
      jsonData.forEach((element) => {
        const sensorOption = new Option(element.sensorid, element.sensorid);
        $(sensorOption).html(element.sensorid);
        $('.node-selection')
            .append(sensorOption);
      });
    },
  });

  // Listen for which data must be in graph
  $('.selector-box').on('change', () => {
    const selectedVal = $('.selector-box option:selected').text();
    loadLineChart(selectedVal);
  });

  // Map field emphasis on hover
  for (let index = 1; index < 7; index++) {
    document.getElementById(`field-${index}`)
        .addEventListener('mouseover', function() {
          $('#field-' + index).css({
            'font-weight': 'bold',
          });

          $('.f' + index).css({
            'stroke': '#fff',
            'stroke-width': '5px',
          });
        });

    document.getElementById(`field-${index}`)
        .addEventListener('mouseleave', function() {
          $('#field-' + index).css({
            'font-weight': '300',
          });

          $('.f' + index).css({
            'stroke': 'none',
            'stroke-width': '1px',
          });
        });
  }
});
