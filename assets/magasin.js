import { Loader } from "@googlemaps/js-api-loader"

let shopPosition = null;

const loader = new Loader({
    apiKey: "AIzaSyAXUQPahIcvHZNrAMxqHo91JS7z5VOLLbI",
    version: "weekly",
});

loader.load().then(() => {
    let values = document.getElementById('map').getAttributeNode('value').nodeValue.split(',');
    const position = {lat: parseFloat(values[0]), lng: parseFloat(values[1])};
    const map = new google.maps.Map(document.getElementById("map"), {
        center: position,
        zoom: 13,
        disableDefaultUI: true,
    });
    const shopMarker = new google.maps.Marker({
        map,
        position: position,
        icon: "../../image/maps/marker_shop.png" 
    })

});