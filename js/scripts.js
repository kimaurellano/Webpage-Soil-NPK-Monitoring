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

      // Dataset of nitrogen
      const n = new Array(10);
      const p = new Array(10);
      const k = new Array(10);
      const time = new Array(10);

      for (let i = 0; i < n.length; i++) {
        console.log(jsonData[i].nitrogen);
        n[i] = jsonData[i]['nitrogen'];
        p[i] = jsonData[i]['phosphorous'];
        k[i] = jsonData[i]['potassium'];
        time[i] = jsonData[i]['time'];
      }

      const context = document.getElementById('myChart').getContext('2d');

      const data = {
        labels: time,
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
      };

      // Create chart instance
      new Chart(context, {
        type: 'line',
        data: data,
      });
    },
  });
}

/**
 * @param {array} array - list of data
 * @return {array} array
 */
function arrayOfData(array) {
  return null;
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
          const soilValue = parseInt(element[soilType]);
          let soilState;

          // 3% is considered low
          if (soilValue <= 3 && soilType == 'nitrogen') { 
            soilState = 'LOW';
          } else if (soilValue > 5 &&
            soilValue <= 28 &&
            soilType == 'nitrogen') {
            soilState = 'MID';
          } else if (soilValue > 28 && soilType == 'nitrogen') {
            soilState = 'HIGH';
          }

          if (soilValue <= 3 && soilType == 'phosphorous' ||
            soilType == 'potassium') { // 3% is considered low
            soilState = 'LOW';
          } else if (soilValue > 3 &&
            soilValue <= 7 &&
            soilType == 'phosphorous' ||
            soilType == 'potassium') {
            soilState = 'MID';
          } else if (soilValue > 7 &&
            soilType == 'phosphorous' ||
            soilType == 'potassium') {
            soilState = 'HIGH';
          }

          document
              .getElementById(`field-${idx}`)
              .innerHTML = `Field ${idx}: ${soilState}`;
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

  // Time stamps the last logged NPK values
  const query = 'SELECT time FROM soil_data ORDER BY id DESC LIMIT 1';
  $.ajax({
    type: 'GET',
    url: `php/NPKContents.php?query=${query}`,
    success: (result) => {
      const jsonData = JSON.parse(result);
      // The x-axis label of chart
      const time = jsonData.map((e) => {
        return e.time;
      });

      document.getElementById('time-log').innerHTML = `Last log: ${time}`;
    },
  });
}

/**
 * Clock tick
 */
setInterval(refreshTime, 1000);

/* EVENT LISTENERS */
window.addEventListener('load', () => {
  // Load datatable
  $('#myTable').DataTable({
    // Descending
    'order': [[0, 'desc']],
    // Hide 'state' column
    'columnDefs': [{'visible': false, 'targets': 6}],
    // Will check the data loaded on the table
    'createdRow': function(row, data, dataIndex) {
      // Change row color base on value
      if (data[6] == 'PROCEDURALLY-FILLED') {
        $(row).addClass('validation-color');
      }
    },
  });

  // Load chart
  loadLineChart('Node-1');

  // Default page on start
  document.getElementById('defaultOpen').click();

  // Load phosphorous data on start
  getNPKContent('phosphorous');

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
