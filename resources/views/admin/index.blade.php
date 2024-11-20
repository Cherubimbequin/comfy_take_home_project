@extends('admin.layouts.app')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome {{ Auth::user()->name }}</h3>
          </div>
          <div class="col-12 col-xl-4">
              <div class="justify-content-end d-flex">
                  <div class="flex-md-grow-1 flex-xl-grow-0">
                      <span class="btn btn-sm btn-light bg-white " id="dateDisplay" data-toggle="" aria-haspopup="true"
                          aria-expanded="true">
                          <i class="mdi mdi-calendar"></i>
                      </span>
                  </div>
              </div>
          </div>
      </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card tale-bg">
          <div class="card-people mt-auto">
            <img src="{{ asset('images/dashboard/people.svg')}}" alt="people">
            <div class="weather-info">
              <div class="d-flex">
                <div>
                  <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                </div>
                <div class="ml-2">
                  <h4 class="location font-weight-normal">Bangalore</h4>
                  <h6 class="font-weight-normal">India</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin transparent">
        <div class="row">
          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">Sold Policies</p>
                <p class="fs-30 mb-2">4006</p>
                <p>10.00% (30 days)</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
              <div class="card-body">
                <p class="mb-4">Number of Expired Policy</p>
                <p class="fs-30 mb-2">61344</p>
                <p>22.00% (30 days)</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
            <div class="card card-light-blue">
              <div class="card-body">
                <p class="mb-4">Number of Agents</p>
                <p class="fs-30 mb-2">34040</p>
                <p>2.00% (30 days)</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 stretch-card transparent">
            <div class="card card-light-danger">
              <div class="card-body">
                <p class="mb-4">Number of Clients</p>
                <p class="fs-30 mb-2">47033</p>
                <p>0.22% (30 days)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </div>
</div> 


<script>
  // Function to format date as 'DD MMM YYYY'
  function formatDate(date) {
      const options = {
          day: '2-digit',
          month: 'short',
          year: 'numeric'
      };
      return date.toLocaleDateString('en-GB', options).replace(/ /g, ' ');
  }

  // Get today's date
  const today = new Date();
  const formattedDate = formatDate(today);

  // Update the date display
  document.getElementById('dateDisplay').innerHTML = `<i class="mdi mdi-calendar"></i> Today (${formattedDate})`;
</script>

<script>
  // Function to fetch weather data from wttr.in
  function fetchWeather() {
      const city = 'Accra, Ghana';
      const url = `https://wttr.in/${city}?format=j1`;

      fetch(url)
          .then(response => response.json())
          .then(data => updateWeather(data))
          .catch(error => console.error('Error fetching weather data:', error));
  }

  // Function to update the weather info in the HTML
  function updateWeather(data) {
      const temp = data.current_condition[0].temp_C;
      const weather = data.current_condition[0].weatherDesc[0].value;
      const city = data.nearest_area[0].areaName[0].value;
      const region = data.nearest_area[0].region[0].value;

      document.getElementById('temp-value').innerText = temp;
      document.getElementById('location').innerText = city;
      document.getElementById('region').innerText = region;

      // Update weather icon based on the weather condition
      const weatherIcon = document.getElementById('weather-icon');
      switch (weather.toLowerCase()) {
          case 'clear':
          case 'sunny':
              weatherIcon.className = 'icon-sun me-2';
              break;
          case 'cloudy':
          case 'partly cloudy':
              weatherIcon.className = 'icon-cloud me-2';
              break;
          case 'rain':
          case 'light rain':
          case 'showers':
              weatherIcon.className = 'icon-rain me-2';
              break;
          case 'snow':
          case 'light snow':
              weatherIcon.className = 'icon-snow me-2';
              break;
          case 'thunderstorm':
              weatherIcon.className = 'icon-thunderstorm me-2';
              break;
          case 'drizzle':
              weatherIcon.className = 'icon-drizzle me-2';
              break;
          case 'mist':
              weatherIcon.className = 'icon-mist me-2';
              break;
          default:
              weatherIcon.className = 'icon-sun me-2';
              break;
      }
  }

  // Fetch weather data when the page loads
  document.addEventListener('DOMContentLoaded', fetchWeather);

  // Optionally, refresh the weather data every hour
  setInterval(fetchWeather, 3600000); // 3600000 milliseconds = 1 hour
</script>
@endsection