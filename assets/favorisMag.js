$(document).ready(function(){

    $.ajax({
        url: "/api/get/favorimag",
        dataType: "json"
    }).done(data =>{
        console.log(data)
    })
})

var url = '/api/set/favorimag/'
$('.far').on('click', function() {
    var id = $(this).attr('value')

    //alert($(this).attr('class'))
    if($(this).attr('class') == "far fa-star"){
        $.post(url + id)
    }else if($(this).attr('class') == "far fa-star fas"){
        alert('fas')
    }

    $(this).toggleClass('fas')

    

    //$.post(url + id,{magId: id});

    /*$.ajax({
        type: "POST",
        url: '/api/set/favorimag/' + id,
      });*/

})


