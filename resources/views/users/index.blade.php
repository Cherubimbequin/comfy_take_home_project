@extends('users.layouts.app')

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
                  <div class=" flex-md-grow-1 flex-xl-grow-0">
                    <button class="btn btn-sm btn-light bg-white " type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="mdi mdi-calendar"></i> Today ({{ date('d M Y') }})
                    </button>
                </div>
                 </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            {{-- <div class="col-md-6 grid-margin stretch-card">
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
            </div> --}}
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="{{ asset('images/dashboard/people.svg')}}" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div id="weather-temperature">
                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>--<sup>°C</sup></h2>
                      </div>
                      <div class="ml-2" id="weather-location">
                        <h4 class="location font-weight-normal">Loading...</h4>
                        <h6 class="font-weight-normal"></h6>
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
                      <p class="mb-4">Today’s Bookings</p>
                      <p class="fs-30 mb-2">4006</p>
                      <p>10.00% (30 days)</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Total Bookings</p>
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
                      <p class="mb-4">Number of Meetings</p>
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
        document.addEventListener("DOMContentLoaded", function () {
  const weatherTemp = document.getElementById("weather-temperature");
  const weatherLocation = document.getElementById("weather-location");

  // Get user's location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;

        // Fetch WOEID from MetaWeather
        fetch(`https://www.metaweather.com/api/location/search/?lattlong=${latitude},${longitude}`)
          .then((response) => response.json())
          .then((locations) => {
            if (locations && locations.length > 0) {
              const woeid = locations[0].woeid;

              // Fetch weather data
              fetch(`https://www.metaweather.com/api/location/${woeid}/`)
                .then((response) => response.json())
                .then((data) => {
                  const today = data.consolidated_weather[0];
                  const temperature = Math.round(today.the_temp);
                  const city = data.title;

                  // Update the UI
                  weatherTemp.innerHTML = `<h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>${temperature}<sup>°C</sup></h2>`;
                  weatherLocation.innerHTML = `
                    <h4 class="location font-weight-normal">${city}</h4>
                    <h6 class="font-weight-normal">Current Weather</h6>
                  `;
                })
                .catch((error) => {
                  console.error("Error fetching weather data:", error);
                  weatherLocation.innerHTML = `<h4 class="location font-weight-normal">Weather data unavailable</h4>`;
                });
            } else {
              weatherLocation.innerHTML = `<h4 class="location font-weight-normal">Location not found</h4>`;
            }
          })
          .catch((error) => {
            console.error("Error fetching WOEID:", error);
            weatherLocation.innerHTML = `<h4 class="location font-weight-normal">Weather data unavailable</h4>`;
          });
      },
      (error) => {
        console.error("Error getting location:", error);
        weatherLocation.innerHTML = `<h4 class="location font-weight-normal">Location access denied</h4>`;
      }
    );
  } else {
    weatherLocation.innerHTML = `<h4 class="location font-weight-normal">Geolocation not supported</h4>`;
  }
});

      </script>
@endsection