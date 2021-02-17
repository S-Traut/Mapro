

$(document).ready(function(){
  var listNom = [];
  var listData = [];
  var listRes = [];

  //récup coordonnées de l'utilisateur
    $.ajax({
      url: "/api/get/userPosition",
      dataType: "json"
  }).done((response) => {
    var userPosition;
      if (response.latitude != null && response.longitude != null) {
          userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
      }

    //récup tous les magasins à proximité
    $.ajax({
        url: "/api/get/searchData",
        data: {
            latitude: userPosition.lat,
            longitude: userPosition.lng
        },
        dataType: "json"
    }).done((shops) => {
      
      //récup noms des magasins et articles à proximité
      shops.forEach((shop) => {
        listData.push(shop)
        listNom.push(shop.nom);
        shop.articles.forEach((article) => {
          listData.push(article);
          listNom.push(article.nom);
        });
      });
    });
  });

  $('.search-box input[type="text"]').on("input", function(){
      var inputVal = $(this).val();
      var resultDropdown = $(this).siblings(".result");

      if(inputVal.length){
        listRes = [];
        
        //vide la dropdown
        resultDropdown.empty();
        //affine la recherche
        const noms = listNom.filter(mot => mot.indexOf(inputVal) > -1);
        //affiche les options
        noms.forEach((nom) => {
          listRes.push(nom);
          resultDropdown.append(`<option>${nom}</option>`)
        });
    } else{
        resultDropdown.empty();
    }
  });

  //selection d'une option
  $(document).on("click", ".result option", function(){
    $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
    $(this).parent(".result").empty();
    

  });

  $('.valider').on('click', function() {
    //vider dans le DOM
    $('.result').empty();
    $('#search-result').empty();

    listData.forEach((data) => {
      listRes.forEach((res) => {
        if(res == data.nom){
          if(data.siren !== "undefined"){
            $('#search-result').append(`
                <div class="shop-item">
                <a style="margin-bottom: 0px; font-size: 23px;" href="/shop/${data.id}">${data.nom}</a>
                <p style="margin-bottom: 0px;">${data.adresse}</p>
                </div>
            `);
          }else{
            $('#search-result').append(`
                <div class="article-item">
                <a style="margin-bottom: 0px; font-size: 23px;" href="/article/${data.id}">${data.nom}</a>
                </div>
            `);
          }
        }
      });
    });


  });
});

/*function search() {
    $.ajax({
      url: "/api/get/userPosition",
      dataType: "json"
  }).done((response) => {
    var userPosition;
      if (response.latitude != null && response.longitude != null) {
          userPosition = { lat: parseFloat(response.latitude), lng: parseFloat(response.longitude) };
      }

    $.ajax({
        url: "/api/get/searchData",
        data: {
            latitude: userPosition.lat,
            longitude: userPosition.lng
        },
        dataType: "json"
    }).done((shops) => {

    });
  });

}*/







  