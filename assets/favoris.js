$(document).ready(function () {

    $('.far').on('click', function (e) {
        e.preventDefault();

        var id = $(this).attr('value')
        var type = 'favoriarticle'

        if ($(this).hasClass('fav-magasin')) {
            type = 'favorimag'
        }

        const valId = type == 'favorimag' ? { mag_id: id } : { art_id: id };

        if ($(this).hasClass('fas')) {
            if (type == 'favorimag') {
                $.ajax({
                    type: "POST",
                    url: `/api/delete/${type}`,
                    data: { mag_id: id }
                })
            } else {
                $.ajax({
                    type: "POST",
                    url: `/api/delete/${type}`,
                    data: { art_id: id }
                })
            }
        } else {
            if (type == 'favorimag') {
                //ajout favori
                $.ajax({
                    type: "POST",
                    url: `/api/set/${type}`,
                    data: {mag_id: id},
                })
            } else {
                $.ajax({
                    type: "POST",
                    url: `/api/set/${type}`,
                    data: {art_id: id},
                })
            }
        }
        $(this).toggleClass('fas')
    })
})