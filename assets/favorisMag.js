$(document).ready(function(){
    
    $('.far').on('click', function(e) {
        e.preventDefault();
        
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




