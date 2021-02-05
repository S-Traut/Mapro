import { Loader } from "@googlemaps/js-api-loader"

let currentPosition;
let searchBar = document.getElementById('search');
let formCreationMagasin = document.forms[0];
let setLocalisation = document.getElementsByName('set_localisation')[0];

let userPosition = {lat: 0, lng: 0};

const loader = new Loader({
    apiKey: /*"AIzaSyAXUQPahIcvHZNrAMxqHo91JS7z5VOLLbI"*/ "",
    version: "weekly",
});

loader.load().then(() => {
    $.ajax({
        url: "/api/get/userPosition",
        dataType: "json"
    }).done((response) => {
        if(response.latitude != null && response.longitude != null) {
            userPosition = {lat: parseFloat(response.latitude), lng: parseFloat(response.longitude)};
        }

        let map = new google.maps.Map(document.getElementById("map"), {
            center: userPosition,
            zoom: 13,
            disableDefaultUI: true,
        });
        console.log(userPosition);
        let userMarker = new google.maps.Marker({
            map,
            position: userPosition
        });

        if(window.location.pathname == "/") {

            $.ajax({
                url: "/api/get/searchAround",
                dataType: "json"
            }).done((shops) => {
                shops.forEach((shop) => {
                    let shopMarker = new google.maps.Marker({map});
                    shopMarker.setPosition({lat: shop.latitude, lng: shop.longitude});

                    const infoWindow = new google.maps.InfoWindow({
                        content: `${shop.latitude} : ${shop.longitude}`
                    });

                    shopMarker.addListener("click", () => {
                        infoWindow.open(map, shopMarker);
                    });
                });
            });
        } else {
            let marker = new google.maps.Marker({
                map,
            });
            map.addListener('click', (e) => {
                currentPosition = e.latLng.toJSON();
                marker.setPosition(e.latLng);
            });
        }
    });

    

    
});

if (searchBar) {
    searchBar.addEventListener("click", () => {
        if (currentPosition != null) {
            document.cookie = (`userLongitude=${currentPosition.lng}`);
            document.cookie = (`userLatitude=${currentPosition.lat}`);
            location.href = "/";
        } else {
            console.log("test");
        }
    });
}

if (formCreationMagasin) {
    formCreationMagasin.addEventListener("submit", () => {
        document.getElementById('creation_magasin_latitude').value = currentPosition.lat;
        document.getElementById('creation_magasin_longitude').value = currentPosition.lng;
    });
}

if (setLocalisation) {
    setLocalisation.addEventListener("submit", () => {
        document.getElementById('set_localisation_latitude').value = currentPosition.lat;
        document.getElementById('set_localisation_longitude').value = currentPosition.lng;
    });
}