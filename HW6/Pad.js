google.load("maps", "3", {
    callback: initialize,
    other_params: "sensor=false&libraries=places,drawing"
});
var iconStatus = [];
var remain = 1119;
var monstersNumber = 1119;

function initialize() {
    var latlng = new google.maps.LatLng(24.7941898, 120.9914202);

    infowindow = new google.maps.InfoWindow();
    geocoder = new google.maps.Geocoder();

    if (google.loader.ClientLocation) {
        latlng = new google.maps.LatLng(google.loader.ClientLocation.latitude,
            google.loader.ClientLocation.longitude);
    }

    var markers = [];
    var infos = [];
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        center: latlng,
        zoom: 16,
        noClear: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var opt = {
        minZoom: 6,
        maxZoom: 16
    };
    map.setOptions(opt);
    var image = {
        url: "res/pad-icon/home.png",
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(50, 50)
    };

    var userMarker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(24.7941898, 120.9914202),
        title: "Home",
        draggable: false,
        animation: google.maps.Animation.DROP,
        icon: image
    });

    var info = new google.maps.InfoWindow({
        content: '<img height="150px" width="425px" src="res/banner.png"><br>Welcome to new world!<br>Enjoy catching monsters!'
    });

    google.maps.event.addListener(userMarker, 'mouseover', function() {
        info.open(map, userMarker);
    });

    google.maps.event.addListener(userMarker, 'mouseout', function() {
        info.close();
    });

    infos.push(info);
    markers.push(userMarker);
    for (var i = 0; i < padIcon.length; i++) {
        iconStatus.push(false);
        pushMarker(map, markers, infos, padIcon[i][1], padIcon[i][2], i, 30);
    }
    var defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(24.7941920, 120.9914220),
        new google.maps.LatLng(24.7901698, 120.9924002));
    map.fitBounds(defaultBounds);

    // Create the search box and link it to the UI element.
    var input = /** @type {HTMLInputElement} */ (
        document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var searchBox = new google.maps.places.SearchBox(
        /** @type {HTMLInputElement} */
        (input));

    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();
        // For each place, get the icon, place name, and location.
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place = places[i]; i < places.length; i++) {
            bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);

    });

    // Bias the SearchBox results towards places that are within the bounds of the
    // current map's viewport.

    google.maps.event.addListener(map, 'bounds_changed', function() {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
    });



    var drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.CIRCLE,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                //google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,
                google.maps.drawing.OverlayType.POLYLINE,
                google.maps.drawing.OverlayType.RECTANGLE
            ]
        },
        markerOptions: {
            icon: 'res/down_arrow.png',
            draggable: true
        },
        circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 10
        }
    });
    drawingManager.setMap(map);

    document.getElementById('blackScreen').style.display = 'none';
    document.getElementById('blackScreen').onclick = function() {
        document.getElementById('blackScreen').style.display = 'none';
        document.getElementById('done').style.display = 'none';
        document.getElementById('raw').style.display = 'none';
    };
}

function pushMarker(map, markers, infos, lat, lng, fileID, size) {
    var iconUrl, bannerUrl;
    iconUrl = padIcon[fileID][0];
    bannerUrl = "res/pad-banner/" + (fileID + 1) + ".jpg";
    var image = {
        url: iconUrl,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(20, 20),
        scaledSize: new google.maps.Size(size, size)
    };

    var marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lat, lng),
        title: "PAD" + (fileID + 1),
        draggable: false,
        /* 可否拖放 */
        animation: google.maps.Animation.DROP,
        /* 動畫設定 */
        icon: image,
        id: (fileID + 1)
    });

    var info = new google.maps.InfoWindow({
        content: '<img height="160px" width="160px" src="' + bannerUrl + '"><br>No. ' + (fileID + 1)
    });

    google.maps.event.addListener(marker, 'mouseover', function() {
        info.open(map, marker);
    });

    google.maps.event.addListener(marker, 'dblclick', function() {
        document.getElementById('blackScreen').style.display = 'block';
        if (iconStatus[marker.id] === true) {
            showDoneBox();
        } else {
            showCatchBox(marker.id);
            if (iconStatus[marker.id] === true) {
                var tmp = {
                    url: "res/egg.png",
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(20, 20),
                    scaledSize: new google.maps.Size(size, size)
                };
                marker.setIcon(tmp);
            }
        }
    });
    google.maps.event.addListener(marker, 'mouseout', function() {
        info.close();
    });

    markers.push(marker);
    infos.push(info);
}

function showDoneBox() {
    document.getElementById('done').style.display = 'block';
}

function codeLatLng() {
    var input = document.getElementById('llng').value;
    var latlngStr = input.split(',', 2);
    var lat = parseFloat(latlngStr[0]);
    var lng = parseFloat(latlngStr[1]);
    var latlng = new google.maps.LatLng(lat, lng);
    var isDone = false;
    geocoder.geocode({
        'latLng': latlng
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setZoom(16);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                infowindow.setContent(results[1].formatted_address);
                infowindow.open(map, marker);
                isDone = true;
            } else {
                isDone = false;
            }
        } else {
            isDone = false;
        }
    });
    if (!isDone) {
        map.setZoom(12);
        marker = new google.maps.Marker({
            position : latlng,
            map: map
        });
        map.setCenter(latlng);
    }
}

function showCatchBox(id) {
    document.getElementById('raw').style.display = 'block';
    document.getElementById('monsterIcon').src = "res/pad-banner/" + id + ".jpg";
    document.getElementById('monsterIcon').style.width = "420px";
    document.getElementById('monsterIcon').style.height = "400px";
    document.getElementById('monsterID').innerHTML = "No. " + id + " get!";
    remain--;
    document.getElementById('ratio').innerHTML = "Ratio: " + (monstersNumber - remain) + " / " + monstersNumber + " = " + Math.round(((monstersNumber - remain) / monstersNumber) * 100) + " %";
    var title;
    if (remain > 1100) {
        title = "Beginner";
    } else if (remain > 900) {
        title = "Hunter";
    } else if (remain > 700) {
        title = "HunterXHunter";
    } else if (remain > 400) {
        title = "Sniper";
    } else if (remain > 1) {
        title = "Master";
    } else {
        title = "King of P&D";
    }
    document.getElementById('titleName').innerHTML = "Title: " + title;
    iconStatus[id] = true;

    var linePlanCoordinates = [
        new google.maps.LatLng(24.7941898, 120.9914202),
        new google.maps.LatLng(padIcon[id - 1][1], padIcon[id - 1][2])
    ];
    var linePath = new google.maps.Polyline({
        path: linePlanCoordinates,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 5
    });

    linePath.setMap(map);

}

function share() {
    var output = "";
    output += "You have catched " + (monstersNumber - remain) + " monsters!<br>";
    for (var i = 0; i < monstersNumber; i++) {
        if (iconStatus[i]) {
            output += "No. " + i + " at [ " + padIcon[i-1][1] + ", " + padIcon[i-1][2] + " ]<br>";
        }
    }
    var newWindowObj = window.open("", "newwindow", "height=600, width=800, toolbar=yes, menubar=yes, scrollbars=no");
    newWindowObj.document.write(output);
}
//google.maps.event.addDomListener(window, 'load', initialize);