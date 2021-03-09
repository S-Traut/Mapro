$(document).ready(function(){
  var listNom = [];
  var listData = [];
  var idMag = document.getElementById('idMagasin').value;

  //lors de la saisie 
  $('.search-box input[type="text"]').on("input", function () {
      $.ajax({  
          type: "POST",
          url: '/api/searchArticle',
          data: {
              mag_id: idMag
          },
          dataType: "json"
      }).done((articles) => {
          //listing des articles à proximité
          //console.log(articles);
          articles.forEach((article) => {
              listData.push(article);
              var name = article.nom.toLowerCase()
              listNom.push(name);
          });
          var inputVal = $(this).val();
          var resultDropdown = $(this).siblings(".result");
          if (inputVal.length) {
              resultDropdown.empty();
              //affine la recherche
              const noms = listNom.filter(mot => mot.indexOf(inputVal.toLowerCase()) > -1);
              listData.forEach(data => {
                noms.forEach((nom) => {
                  //vide la dropdown
                  resultDropdown.empty();
                  //On la réactualise avec les nouvelles données
                  resultDropdown.append(`
              <div class="result-option p-3">
              <a  href="/article/${data.id}" style="text-decoration: none">
                <div class="post-thumb" style="float: left">
                  <img src="" style="max-width: 60px; display: block"/>
                </div>
                <div class="post-content">
                  <p style="margin-bottom: 0px; font-size: 23px;">${data.nom}</p>
                  <p style="margin-bottom: 0px;">${data.description}</p>
                  <p style="margin-bottom: 0px;">${data.prix} €</p>
                </div>
              </a>
              </div> 
              `)
                })
              })
          }
      });
  })
});