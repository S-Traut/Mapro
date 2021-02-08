import { Loader } from "@googlemaps/js-api-loader"

let currentPosition;
let map;
let shopMarkers = [];
let searchBar = document.getElementById('search');
let formCreationMagasin = document.forms[0];
let setLocalisation = document.getElementsByName('set_localisation')[0];

let userPosition = false;

const loader = new Loader({
    apiKey: /*"AIzaSyAXUQPahIcvHZNrAMxqHo91JS7z5VOLLbI"*/ "",
    version: "weekly",
});

loader.load().then(() => {
    $.ajax({
        url: "/api/get/userPosition",
        dataType: "json"
    }).done((response) => {
        if (response.latitude != null && response.longitude != null) {
            userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
        }

        if (userPosition)
            searchShops();

        map = new google.maps.Map(document.getElementById("map"), {
            center: userPosition ? userPosition : { lat: 47.0725, lng: 2.4605 },
            zoom: userPosition ? 13 : 5,
            disableDefaultUI: true,
        });

        let userMarker = new google.maps.Marker({
            map,
            position: userPosition ? userPosition : null
        });

        if (window.location.pathname == "/") {
            map.addListener('click', (e) => {
                currentPosition = e.latLng.toJSON();
                userMarker.setPosition(e.latLng);
                locateUser();
                searchShops();
            });
        }
    });
});

function searchShops() {
    $('#popular-shops').html("");
    resetMarkers();
    $.ajax({
        url: "/api/get/searchAround",
        dataType: "json"
    }).done((shops) => {
        if(shops.length == 0) {
            $('#popular-shops').html("Aucun magasin n'a été trouvé autour de chez vous. :(");
        }
        
        shops.forEach((shop, index) => {
            let shopMarker = new google.maps.Marker({
                map,
                position: { lat: shop.latitude, lng: shop.longitude },
                icon: "../image/maps/marker_shop.png"
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<b>${shop.nom}</b><br>${shop.adresse}`
            });

            shopMarker.addListener("click", () => {
                infoWindow.open(map, shopMarker);
            });
            shopMarkers.push(shopMarker);

            $('#popular-shops').append(`
                <div class="shop-item" style="animation-delay: ${index/10}s";>
                <a style="margin-bottom: 0px; font-size: 23px;" href="/shop/${shop.id}">${shop.nom}</a>
                <p style="margin-bottom: 0px;">${shop.adresse}</p>
                </div>
            `);

        });
    });
}

function resetMarkers() {
    shopMarkers.forEach((marker) => {
        marker.setMap(null);
    });
}

function locateUser() {
    document.cookie = (`userLongitude=${currentPosition.lng}`);
    document.cookie = (`userLatitude=${currentPosition.lat}`);
}

if (searchBar) {
    searchBar.addEventListener("click", () => {
        if (currentPosition != null) {
            document.cookie = (`userLongitude=${currentPosition.lng}`);
            document.cookie = (`userLatitude=${currentPosition.lat}`);
            location.href = "/";
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