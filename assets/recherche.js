var listNom = [];
var listData = [];
let radius = 1500;
let slider = document.getElementById("radiusSlider");


function getShops() {

  //récup coordonnées de l'utilisateur
  $.ajax({
    url: "/api/get/userPosition",
    dataType: "json"
  }).done((response) => {
    var userPosition;
    if (response.latitude != null && response.longitude != null) {
      userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
    }

    listNom = [];
    listData = [];
    //récup tous les magasins à proximité
    $.ajax({
      url: "/api/get/searchData",
      data: {
        latitude: userPosition.lat,
        longitude: userPosition.lng,
        radius: radius
      },
      dataType: "json"
    }).done((shops) => {
      //listing des magasins à proximité
      shops.forEach((shop) => {
        listData.push(shop)
        var name = shop.nom.toLowerCase()
        listNom.push(name);
      });
      console.log(listNom);
      //listing des articles à proximité
      shops.forEach((shop) => {
        shop.articles.forEach((article) => {
          listData.push(article);
          var name = article.nom.toLowerCase()
          listNom.push(name);
        });
      });

    });
  });
}

$(document).ready(function () {
  //lors de la saisie 
  $('.search-box input[type="text"]').on("input", function () {
    getShops();
    var inputVal = $(this).val();
    var resultDropdown = $(this).siblings(".result");

    if (inputVal.length) {
      //vide la dropdown
      resultDropdown.empty();
      //affine la recherche
      const noms = listNom.filter(mot => mot.indexOf(inputVal.toLowerCase()) > -1);
      listData.forEach(data => {
        noms.forEach((nom) => {
          if (data.nom.toLowerCase() === nom && data.hasOwnProperty('siren')) {
            //affiche les magasins
            resultDropdown.append(`
              <div class="result-option">
              <a href="/shop/${data.id}" style="text-decoration: none; display:flex; margin: 5px 15px 5px 15px">
                <i class="fas fa-store-alt" style="margin-right: 5px; line-height: 30px"></i>
                <p style="margin-bottom: 0px; font-size: 16px; line-height: 30px">${data.nom}</p>
              </a>
              </div> 
              `)
          } else if (data.nom.toLowerCase() === nom && !data.hasOwnProperty('siren')) {
            //affiche les articles
            resultDropdown.append(`
              <div class="result-option">
              <a href="/article/${data.id}" style="text-decoration: none; display:flex; margin: 5px 15px 5px 15px">
                <i class="fas fa-tag" style="margin-right: 5px; line-height: 30px"></i>
                <p style="margin-bottom: 0px; font-size: 16px; line-height: 30px">${data.nom}</p>
              </a>
              </div> 
                `)

          }
        })
      })

    } else {
      resultDropdown.empty();
    }
  });
});


slider.oninput = function () {
  radius = this.value * 100;
}   