

$(document).ready(function(){
  var listNom = [];
  var listData = [];

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
      
      //listing des magasins à proximité
      shops.forEach((shop) => {
        listData.push(shop)
        var name = shop.nom.toLowerCase()
        listNom.push(name);
      });

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

  //lors de la saisie 
  $('.search-box input[type="text"]').on("input", function(){
      var inputVal = $(this).val();
      var resultDropdown = $(this).siblings(".result");

      if(inputVal.length){
        //vide la dropdown
        resultDropdown.empty();
        //affine la recherche
        const noms = listNom.filter(mot => mot.indexOf(inputVal.toLowerCase()) > -1);
        listData.forEach(data =>{
          noms.forEach((nom) => {
            if(data.nom.toLowerCase() === nom && data.hasOwnProperty('siren')){
              //affiche les magasins
              resultDropdown.append(`
              <div class="result-option p-3">
              <a  href="/shop/${data.id}">
                <div class="post-thumb" style="float: left">
                  <img src="http://dummyimage.com/200x200/f0f/fff" style="max-width: 60px; display: block"/>
                </div>
                <div class="post-content">
                  <p style="margin-bottom: 0px; font-size: 23px;">${data.nom}</p>
                  <p style="margin-bottom: 0px;">${data.description}</p>
                </div>
              </a>
              </div> 
              `)
            }else if(data.nom.toLowerCase() === nom && !data.hasOwnProperty('siren')){
              //affiche les articles
              resultDropdown.append(`
              <div class="result-option p-3">
              <a  href="/article/${data.id}">
                <div class="post-thumb" style="float: left">
                  <img src="http://dummyimage.com/200x200/f0f/fff" style="max-width: 60px; display: block"/>
                </div>
                <div class="post-content">
                  <p style="margin-bottom: 0px; font-size: 23px;">${data.nom}</p>
                  <p style="margin-bottom: 0px;">${data.description}</p>
                </div>
              </a>
              </div> 
              `)
            }
          })
        })
        
    } else{
        resultDropdown.empty();
    }
  });

  //selection d'une option
  /*$(document).on("click", ".result .result-option", function(){
    $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
    $(this).parent(".result").empty();
    $('#search-result').empty();

    const mot = $('.search-box input[type="text"]').val()

    listData.forEach(res => {
      if(res.nom === mot){
        if(res.hasOwnProperty('siren')){
          $('#search-result').append(`
                <div class="shop-item m-3">
                <a style="margin-bottom: 0px; font-size: 23px;" href="/shop/${res.id}">${res.nom}</a>
                <p style="margin-bottom: 0px;">${res.adresse}</p>
                </div>
            `)
        }else{
          $('#search-result').append(`
          <div class="post-container p-3">
          <div class="post-thumb" style="float: left">
            <img src="http://dummyimage.com/200x200/f0f/fff" style="max-width: 100px; display: block"/>
          </div>
          <div class="post-content">
            <a style="margin-bottom: 0px; font-size: 23px;" href="/article/${res.id}">${res.nom}</a>
            <p style="margin-bottom: 0px;">${res.description}</p>
            <p style="margin-bottom: 0px;">${res.prix} €</p>
          </div>
        </div> 
            `)
        }
        
      }
    })

  });*/
});
