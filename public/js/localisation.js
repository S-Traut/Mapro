var locateButton = document.getElementById("Locate");
locateButton.onclick = onLocate;

function onLocate() {
    if ("geolocation" in navigator) {
        console.log(navigator.geolocation.getCurrentPosition(success, error, options));

    }
}

function success(pos) {
    var crd = pos.coords;
    sessionStorage.setItem('userLatitude', crd.latitude);
    sessionStorage.setItem('userLongitude', crd.longitude); 
    location.href = "/home";
}

function error(err) {
    console.warn(`ERREUR (${err.code}): ${err.message}`);
}

var options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};