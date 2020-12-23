var locateButton = document.getElementById("Locate");
locateButton.onclick = onLocate;

function onLocate() {
    if ("geolocation" in navigator) {
        console.log(navigator.geolocation.getCurrentPosition(success, error, options));

    }
}

function success(pos) {
    var crd = pos.coords;

    console.log('Votre position actuelle est :');
    console.log(`Latitude : ${crd.latitude}`);
    console.log(`Longitude : ${crd.longitude}`);
    console.log(`La précision est de ${crd.accuracy} mètres.`);
    
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