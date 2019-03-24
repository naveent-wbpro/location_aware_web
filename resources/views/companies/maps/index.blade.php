@extends ('companies.company_layout')

@section ('sub-content')
    <h4>
        Map of "{{ $company->name }}" employees
    </h4>

    <div class="col-xs-12 col-md-8">
        <div id="map">Loading Map...</div>
    </div>

    <div class="col-xs-12 col-md-4">
        <p class="lead">Employees:</p>
        <ul id="employees"></ul>
    </div>


    <script>
            var map;
            var markers = {};

            function loadPosition() {
                    if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(initMap);
                    } else {
                            $("#map").html("Geolocation is not supported by this browser.");
                    }
            }

            function initMap(position) {
                    $("#map").css('height', '400px', 'width', '400px');
                    map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: position.coords.latitude, lng: position.coords.longitude},
                            zoom: 8
                    });
                    updateOffices();
                    updateEmployees();
                    startEmployeesTimer();
            }

            function startEmployeesTimer() {
                    window.setInterval(function() {
                            updateEmployees();		
                    }, 20000)
            }


            function updateOffices() {
                $.ajax({
                  url: '/companies/<?= $company->id ?>/maps/office_locations',
                  success:function(result) {
                      offices = JSON.parse(result);		
                      _.forEach(markers, function(value, key) {
                          value.map_marker.setMap(null)
                      })
                      _.forEach(offices, function(value, key) {
                          if(markers[value.unique_id] == undefined) {
                              markers[value.unique_id] = value;
                              markers[value.unique_id].latitude = parseFloat(value.latitude);
                              markers[value.unique_id].longitude = parseFloat(value.longitude);
                              markers[value.unique_id].map_marker = new google.maps.Marker({
                                                position: {lat: value.latitude, lng: value.longitude},
                                                map: map,
                                                icon: 'https://locationaware.io/images/homegardenbusiness.png',
                                                title: value.name
                                              });
                          } else {
                              markers[value.unique_id].latitude = parseFloat(value.latitude);
                              markers[value.unique_id].longitude = parseFloat(value.longitude);
                              markers[value.unique_id].map_marker.setMap(map);
                              markers[value.unique_id].map_marker.setPosition({ lat: markers[value.unique_id].latitude, lng: markers[value.unique_id].longitude })
                          }
                      })
                  }
              })
            }

            function updateEmployees() {
              $.ajax({
                  url: '/companies/<?= $company->id ?>/maps/employee_locations',
                  success:function(result) {
                      $employees = $("#employees");
                      $employees.empty();
                      employees = JSON.parse(result);		
                      _.forEach(markers, function(value, key) {
                      })
                      _.forEach(employees, function(value, key) {
                          $employees.append('<li>'+value.name+'</li>');
                          if(markers[value.unique_id] == undefined) {
                              markers[value.unique_id] = value;
                              markers[value.unique_id].latitude = parseFloat(value.latitude);
                              markers[value.unique_id].longitude = parseFloat(value.longitude);
                              markers[value.unique_id].map_marker = new google.maps.Marker({
                                                position: {lat: value.latitude, lng: value.longitude},
                                                map: map,
                                                title: value.name,
                                                icon: 'https://locationaware.io/images/truck.png'
                                              });
                          } else {
                              markers[value.unique_id].latitude = parseFloat(value.latitude);
                              markers[value.unique_id].longitude = parseFloat(value.longitude);
                              markers[value.unique_id].map_marker.setMap(map);
                              markers[value.unique_id].map_marker.setPosition({ lat: markers[value.unique_id].latitude, lng: markers[value.unique_id].longitude })
                          }
                      })
                  }
              })
            }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuyaG9bixr9omuv7C0Wc9kb7vRII5pKh4&callback=loadPosition" async defer></script>
@endsection
