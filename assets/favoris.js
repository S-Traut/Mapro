$(document).ready(function(){
    
    $('.far').on('click', function(e) {
        e.preventDefault();
        
        var id = $(this).attr('value')
        var type = 'favoriarticle'

        const valId = type == 'favorimag' ? {mag_id: id} : {art_id : id};
        if($(this).hasClass('fav-magasin')){
            type= 'favorimag'
        }

        if($(this).hasClass('fas')){

            //sup favori
            $.ajax({  
                type: "POST",
                url: `/api/delete/${type}`,
                data: valId​​​​​,
             })

                 
        }else {
            
             //ajout favori
            $.ajax({  
                type: "POST",
                url: `/api/set/${type}`,
                data: valId​​​​​, 
             })
             
        }

        $(this).toggleClass('fas') 

        
    
    })

})





