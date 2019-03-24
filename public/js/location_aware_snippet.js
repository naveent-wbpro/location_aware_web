var markers = {}
var base_url = 'http://localhost:8000';
var network_id;
var script = document.createElement("script");
var street_address = '';
var zipcode = '';
var radius;

script.type="text/javascript";
script.src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuyaG9bixr9omuv7C0Wc9kb7vRII5pKh4&callback=getLocation";
document.getElementsByTagName("head")[0].appendChild(script);

var api_key = document.getElementById("location-aware-script").getAttribute("data-api-key");
var company_id = document.getElementById("location-aware-script").getAttribute("data-company-id");
var center = document.getElementById("location-aware-script").getAttribute('data-center');
var prime_id = document.getElementById("location-aware-script").getAttribute('data-prime-id');

function getLocation() {
    window.setTimeout(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(initMap, function(error) {
                if (error.code == error.PERMISSION_DENIED) {
                    alert('Geolocation has been blocked by your browser.');
                } 
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }, 2500);
}

function initMap(position) {
    snippet_info = getSnippetInfo();
    map_container = document.getElementById("location-aware-map");
    width = map_container.parentElement.offsetWidth;
    height = map_container.parentElement.offsetHeight;

    map_container.style.width = width+"px";
    map_container.style.height = height+"px";
    var address_found = false;
    address = JSON.parse(center);

    if (center != null && (address.error !== undefined || address.latitude !== undefined)) {
        if (address.error === 'not_found') {
            center_latitude = position.coords.latitude;
            center_longitude = position.coords.longitude;
            alert('Address not found. Using user location as fallback'); 
        } else {
            address_found = true;
            center_latitude = address.latitude;
            center_longitude = address.longitude;
        }
    } else {
        center_latitude = position.coords.latitude;
        center_longitude = position.coords.longitude;
    }

    map = new google.maps.Map(document.getElementById("location-aware-map"), {
        center: {lat: center_latitude, lng: center_longitude},
        zoom: 13
    });

    var center_marker = new google.maps.Marker({
        position: {lat: center_latitude, lng: center_longitude},
        map: map,
        title: 'You are here.'
    });

    if (address_found == true) {
        new google.maps.Marker({
            map: map,
            position: {lat: center_latitude, lng: center_longitude}
        });
    }

    radius = new google.maps.Circle({
       strokeColor: '#0080FF',
       strokeOpacity: 0.4,
       strokeWeight: 1,
       fillColor: '#0080FF',
       fillOpacity: 0.15,
       map:map,
       radius: 64373,
       center: {lat: center_latitude, lng: center_longitude }
    });

    map_container.insertAdjacentHTML('beforeend', '<a id="instant-response" style="cursor: pointer; text-align: center; position: absolute; top: 10px; right: 10px; color: #fff; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left-width: 0px; background-color: #d62c1a; background-clip: padding-box; z-index: 1000;">&#9990; Instant Response</a>');

    map_container.insertAdjacentHTML('beforeend', '<a id="search" style="cursor: pointer; text-align: center; position: absolute; top: 10px; right: 130px; color: #333; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left-width: 0px; background-color: #FFFFFF; background-clip: padding-box; z-index: 1000;">&#10038; Search</a>');


    map_container.insertAdjacentHTML('beforeend', '<a style="text-align: center; position: absolute; top: 10px; right: 200px; color: #333; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left-width: 0px; background-color: #FFFFFF; background-clip: padding-box; z-index: 1000;" href="http://localhost:8000/companies" target="_blank">Contractor List</a>');

    document.getElementById("instant-response").onclick = function(){
        window.open(base_url+'/companies/'+company_id+'/requests/create?key='+api_key+'&instant_response=1&network_id='+network_id+'&street_address='+street_address+'&zipcode='+zipcode+'&prime_id='+prime_id,'Request','height=600,width=550');
    };

    document.getElementById("search").onclick = function() {
        var address = prompt('Enter an address to center the map.');
        if (address != '') {
          geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var myOptions = {
                        zoom: 8,
                        center: results[0].geometry.location,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    map.panTo(results[0].geometry.location);
                    radius.setMap(null);

                    radius = new google.maps.Circle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.4,
                        strokeWeight: 1,
                        fillColor: '#FF0000',
                        fillOpacity: 0.15,
                        map:map,
                        radius: 64373,
                        center: results[0].geometry.location 
                    });

                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                }
            });
        }
    };

    initCompanyTracking();
}

function initCompanyTracking() {
    getEmployees();
    getOffices();
    window.setInterval(function() {
        getEmployees();
    }, 30000);
}

function getSnippetInfo() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            result = JSON.parse(xhttp.responseText);

            if (result.network_id != undefined && result.network_id != 'undefined') {
                network_id = result.network_id;
                map_container.insertAdjacentHTML('beforeend', '<a style="text-align: center; position: absolute; top: 50px; right: 10px; color: #333; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left-width: 0px; background-color: #FFFFFF; background-clip: padding-box; z-index: 1000;">'+result.network.name+'</a>');
            } else {
                map_container.insertAdjacentHTML('beforeend', '<a style="text-align: center; position: absolute; top: 50px; right: 10px; color: #333; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left-width: 0px; background-color: #FFFFFF; background-clip: padding-box; z-index: 1000;">'+result.company.name+'</a>');
            }

            return result;
        }
    }

    xhttp.open("GET", base_url+"/api/code_snippets?api_key="+api_key, true);
    xhttp.send();
}

function getEmployees() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            result = JSON.parse(xhttp.responseText);
            for (var key in result) {
                if (result.hasOwnProperty(key)) {
                    var value = result[key];
                        if(markers[value.unique_id] == undefined) {
                            markers[value.unique_id] = value;
                            markers[value.unique_id].latitude = parseFloat(value.latitude);
                            markers[value.unique_id].longitude = parseFloat(value.longitude);
                            markers[value.unique_id].map_marker = new google.maps.Marker({
                                position: {lat: value.latitude, lng: value.longitude},
                                company_id: value.company_id,
                                map: map,
                                id: value.id,
                                title: value.name,
                                icon: base_url+'/images/'+value.image
                            });
                            google.maps.event.addListener(markers[value.unique_id].map_marker, 'click', function() {
                                window.open(base_url+'/companies/'+this.company_id+'/requests/create?key='+api_key+'&employee_id='+this.id+'&network_id='+network_id+'&street_address='+street_address+'&zipcode='+zipcode+'&prime_id='+prime_id,'Request','height=600,width=550');
                            });
                        } else {
                            markers[value.unique_id].latitude = parseFloat(value.latitude);
                            markers[value.unique_id].longitude = parseFloat(value.longitude);
                            markers[value.unique_id].map_marker.setMap(map);
                            markers[value.unique_id].map_marker.setPosition({ lat: markers[value.unique_id].latitude, lng: markers[value.unique_id].longitude })
                        }
                }
            }
        }		
    };
    xhttp.open("GET", base_url+"/api/companies/"+company_id+"/employees/locations?api_key="+api_key, true);
    xhttp.send();

    return true
}

function getOffices() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            result = JSON.parse(xhttp.responseText);
            for (var key in result) {
                if (result.hasOwnProperty(key)) {
                    var value = result[key]
                    if(markers[value.unique_id] == undefined) {
                        markers[value.unique_id] = value;
                        markers[value.unique_id].latitude = parseFloat(value.latitude);
                        markers[value.unique_id].longitude = parseFloat(value.longitude);
                        markers[value.unique_id].map_marker = new google.maps.Marker({
                            position: {lat: value.latitude, lng: value.longitude},
                            map: map,
                            title: value.name,
                            office_id: value.office_id,
                            icon: base_url+'/images/office.png'
                        });
                        google.maps.event.addListener(markers[value.unique_id].map_marker, 'click', function() {
                            window.open(base_url+'/companies/'+company_id+'/requests/create?key='+api_key+'&office_id='+this.office_id+'&network_id='+network_id+'&street_address='+street_address+'&zipcode='+zipcode+'&prime_id='+prime_id,'Request','height=600,width=550');
                        });
                    }
                }
            }
        }		
    };
    xhttp.open("GET", base_url+"/api/companies/"+company_id+"/offices/locations?api_key="+api_key, true);
    xhttp.send();

    return true
}
