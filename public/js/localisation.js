var locateButton = document.getElementById("Locate");
locateButton.onclick = onLocate;

function onLocate() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(success, error, options);

    }
}

function success(pos) {
    var crd = pos.coords;
    document.cookie = (`userLongitude=${crd.longitude}`);
    document.cookie = (`userLatitude=${crd.latitude}`);
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