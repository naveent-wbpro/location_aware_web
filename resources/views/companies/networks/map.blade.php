@extends ('companies.company_layout')

@section ('sub-content')

    <h4>
        Map of "{{ $network->name }}" employees
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
                    updateEmployees();
                    startEmployeesTimer();
            }

            function startEmployeesTimer() {
                    window.setInterval(function() {
                            updateEmployees();		
                    }, 20000)
            }

            function updateEmployees() {
                    $.ajax({
                            url: '/companies/<?= $company->id ?>/networks/<?= $network->id ?>/locations',
                            success:function(result) {
                                    $employees = $("#employees");
                                    $employees.empty();
                                    employees = JSON.parse(result);		
                                    _.forEach(markers, function(value, key) {
                                            value.map_marker.setMap(null)
                                    })
                                    _.forEach(employees, function(value, key) {
                                            $employees.append('<li>'+value.name+'</li>');
                                            if(markers[value.id] == undefined) {
                                                    markers[value.id] = value;
                                                    markers[value.id].latitude = parseFloat(value.latitude);
                                                    markers[value.id].longitude = parseFloat(value.longitude);
                                                    markers[value.id].map_marker = new google.maps.Marker({
                                                                                        position: {lat: value.latitude, lng: value.longitude},
                                                                                        map: map,
                                                                                        title: value.name
                                                                                  });
                                            } else {
                                                    markers[value.id].latitude = parseFloat(value.latitude);
                                                    markers[value.id].longitude = parseFloat(value.longitude);
                                                    markers[value.id].map_marker.setMap(map);
                                                    markers[value.id].map_marker.setPosition({ lat: markers[value.id].latitude, lng: markers[value.id].longitude })
                                            }
                                    })
                            }
                    })
            }


    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuyaG9bixr9omuv7C0Wc9kb7vRII5pKh4&callback=loadPosition" async defer></script>

@endsection
