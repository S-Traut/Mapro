$(document).ready(function(){
    
  $('.far').on('click', function(e) {
      e.preventDefault();
      
      var id = $(this).attr('value')

      if($(this).attr('class') == "far fa-star"){
          //ajout favori
          $.ajax({  
              type: "POST",
              url: '/api/set/favoriarticle',
              data: {art_id: id},  
           }) 

      }else if($(this).attr('class') == "far fa-star fas"){
          //sup favori
          $.ajax({  
              type: "POST",
              url: '/api/delete/favoriarticle',
              data: {art_id: id},  
           }) //alert("non vide");
      }

      $(this).toggleClass('fas') 
  
  })

})


