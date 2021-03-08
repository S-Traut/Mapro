import { Loader } from "@googlemaps/js-api-loader"

let userPosition = false;
let map;
let shopMarkers = [];
let searchBar = document.getElementById('search');
let formCreationMagasin = document.forms[0];
let setLocalisation = document.getElementsByName('set_localisation')[0];
let isShopEdit = false;
let shopLocationVariable;
let homeShops;

let articlesPopulaires = new Swiper('.swiper-container-articles', {
    direction: 'horizontal',
    slidesPerView: "auto",
    spaceBetween: 20,
    freeMode: true,
}); 

if(window.location.pathname == "/") {
    homeShops = new Swiper('.swiper-container', {
        direction: 'horizontal',
        slidesPerView: "auto",
        spaceBetween: 20,
        freeMode: true,
    });    
}

if (window.location.pathname.match("(\/shop\/0*[1-9][0-9]*\/edit)")) {
    document.addEventListener('DOMContentLoaded', function () {
        let shopDOM = document.querySelector('.shopLocation');
        let locationValue = JSON.parse(shopDOM.dataset.location);
        shopLocationVariable = { lat: locationValue[0], lng: locationValue[1] };
    });
    isShopEdit = true;
} else if (window.location.pathname.match("(\/shop\/new)")) {
    isShopEdit = true;
}


const loader = new Loader({
    apiKey: /*"AIzaSyAXUQPahIcvHZNrAMxqHo91JS7z5VOLLbI"*/ "",
    version: "weekly",
});

loader.load().then(() => {
    if (isShopEdit) {
        shopLocation();
    } else {
        userLocation();
    }
});

function userLocation() {
    $.ajax({
        url: "/api/get/userPosition",
        dataType: "json"
    }).done((response) => {
        if (response.latitude != null && response.longitude != null) {
            userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
        }

        map = new google.maps.Map(document.getElementById("map"), {
            center: userPosition ? userPosition : { lat: 47.0725, lng: 2.4605 },
            zoom: userPosition ? 13 : 5,
            disableDefaultUI: true,
        });

        let userMarker = new google.maps.Marker({
            map,
            position: userPosition ? userPosition : null
        });

        map.addListener('click', (e) => {
            userPosition = e.latLng.toJSON();
            userMarker.setPosition(e.latLng);
            locateUser();

            if (window.location.pathname == "/")
                searchShops();
        });

        if (window.location.pathname == "/") {
            if (userPosition)
                searchShops();
        }
    });
}

function shopLocation() {
    $.ajax({
        url: "/api/get/userPosition",
        dataType: "json"
    }).done((response) => {
        if (response.latitude != null && response.longitude != null) {
            userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
        }

        map = new google.maps.Map(document.getElementById("map"), {
            center: shopLocationVariable ? shopLocationVariable : userPosition,
            zoom: 13,
            disableDefaultUI: true,
        });

        let shopMarker = new google.maps.Marker({
            map,
            position: shopLocationVariable,
            icon: "../../image/maps/marker_shop.png"
        });

        map.addListener('click', (e) => {
            shopMarker.setPosition(e.latLng);
            userPosition = e.latLng.toJSON();
        });
    });
}

function searchShops() {
    
    $.ajax({
        url: "/api/get/searchAround",
        data: {
            latitude: userPosition.lat,
            longitude: userPosition.lng
        },
        dataType: "json"
    }).done((shops) => {
        homeShops.removeAllSlides();
        resetMarkers();
        if (shops.length == 0) {
            
            homeShops.appendSlide(`<div class="swiper-slide">Aucun magasin n\'est disponnible autour de chez vous.</div>`);
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

            homeShops.appendSlide(`
                <div class="swiper-slide shop-item" style="height: 250px; max-width: 300px">
                <div class="shop-img"><img src="https://picsum.photos/400"></div>
                <a style="padding: 0px 10px 0px 10px; font-size: 23px;" href="/shop/${shop.id}">${shop.nom}</a>
                <p style="padding: 0px 10px 0px 10px;">${shop.adresse}</p>
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
    document.cookie = (`userLongitude=${userPosition.lng}`);
    document.cookie = (`userLatitude=${userPosition.lat}`);
}

if (searchBar) {
    searchBar.addEventListener("click", () => {
        if (userPosition) {
            document.cookie = (`userLongitude=${userPosition.lng}`);
            document.cookie = (`userLatitude=${userPosition.lat}`);
            location.href = "/";
        }
    });
}

if (formCreationMagasin) {
    formCreationMagasin.addEventListener("submit", () => {
        document.getElementById('creation_magasin_latitude').value = userPosition.lat;
        document.getElementById('creation_magasin_longitude').value = userPosition.lng;
    });
}

if (setLocalisation) {
    setLocalisation.addEventListener("submit", () => {
        document.getElementById('set_localisation_latitude').value = userPosition.lat;
        document.getElementById('set_localisation_longitude').value = userPosition.lng;
    });
}

