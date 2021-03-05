$(document).ready(function(){

    var listFavori = []
    
    //fait appel à l'api des favoris
    /*
    $.ajax({
        url: "/api/get/favorimag",
        dataType: "json"
    }).done((data) =>{

        data.favoriMagasins.forEach((favori)=>{
            listFavori.push(favori.idMagasin)
        })
    })*/

    
    $('.far').on('click', function() {
        var id = $(this).attr('value')

        if($(this).attr('class') == "far fa-star"){
            //ajout favori
            $.ajax({  
                type: "POST",
                url: '/api/set/favorimag',
                data: {mag_id: id},  
             })

        }else if($(this).attr('class') == "far fa-star fas"){
            //sup favori
            $.ajax({  
                type: "POST",
                url: '/api/delete/favorimag',
                data: {mag_id: id},  
             })
        }

        $(this).toggleClass('fas') 
    
    })

})





