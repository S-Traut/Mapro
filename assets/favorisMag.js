$(document).ready(function(){

    var listFavori = []
    var url = '/api/set/favorimag'
    
    //fait appel à l'api des favoris
    $.ajax({
        url: "/api/get/favorimag",
        dataType: "json"
    }).done((data) =>{

        data.favoriMagasins.forEach((favori)=>{
            listFavori.push(favori.idMagasin)
        })
    })

    //ajout d'un favori
    $('.far').on('click', function() {
        var id = $(this).attr('value')

        if($(this).attr('class') == "far fa-star"){
            $.ajax({  
                type: "POST",
                url: url,
                data: {mag_id: id},  
             })
             $(this).toggleClass('fas')
        }else if($(this).attr('class') == "far fa-star fas"){
    
        }
    
    })
})





