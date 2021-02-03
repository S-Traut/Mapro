import { Loader } from "@googlemaps/js-api-loader"

let currentPosition;
let searchBar = document.getElementById('search');
let formCreationMagasin = document.forms[0];
let setLocalisation = document.getElementsByName('set_localisation')[0];

const loader = new Loader({
    apiKey: "AIzaSyAXUQPahIcvHZNrAMxqHo91JS7z5VOLLbI",
    version: "weekly",
});

loader.load().then(() => {
    let map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 48.58, lng: 7.75 },
        zoom: 11,
        disableDefaultUI: true,
    });
    let marker = new google.maps.Marker({
        map,
    });
    map.addListener('click', (e) => {
        console.log("test");
        currentPosition = e.latLng.toJSON();
        marker.setPosition(e.latLng);
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
        console.log("test");
        document.getElementById('set_localisation_latitude').value = currentPosition.lat;
        document.getElementById('set_localisation_longitude').value = currentPosition.lng;
    });
}