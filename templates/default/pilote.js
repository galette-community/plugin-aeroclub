var jour_resa;
var avion_id;
var message;
var origine_click;

function go_reservation(jour, avion_id, resa_id, resa_jour, resa_heure, clone_resa_id, origine) {
    show_ajax_pilote();
    var url = "reservation.php?mode=ajax&jour=" + jour + "&avion_id=" + avion_id;
    if (resa_id !== undefined) {
        url += "&resa_id=" + resa_id;
    }
    if (resa_jour !== undefined) {
        url += "&resa_jour=" + resa_jour;
    }
    if (resa_heure !== undefined) {
        url += "&resa_heure=" + resa_heure;
    }
    if (clone_resa_id !== undefined) {
        url += "&clone_resa_id=" + clone_resa_id;
    }
    if (origine !== undefined) {
        url += "&origine=" + origine;
    }

    origine_click = origine === undefined ? 'reservation' : origine;

    $.get(url, function(data) {
        $('#ajax_pilote').html(data);
        $('.tooltip_pilote').tooltipster({
            position: 'bottom',
            theme: '.tooltipster-pilote'
        });
    });
    return false;
}

function ajax_post_reservation(button_id) {
    $('#button_container').html('<img src="picts/wait.png" alt="Loading / Chargement en cours ..." height="32" width="32"/>');

    var jour = $('input[name=resa_jour_debut]').val().split('/');
    jour_resa = jour[2] + '-' + jour[1] + '-' + jour[0];
    avion_id = $('input[name=avion_id]').val();

    if (button_id === 'cloner') {
        var resa_id = $('input[name=resa_id]').val();
        var url = "reservation.php?mode=ajax&jour=" + jour_resa + "&avion_id=" + avion_id + "&clone_resa_id=" + resa_id;
        $.get(url, function(data) {
            $('#ajax_pilote').html(data);
            $('.tooltip_pilote').tooltipster({
                position: 'bottom',
                theme: '.tooltipster-pilote'
            });
        });
    } else {
        var form_seralize = $('#form_reservation').serialize() + '&' + button_id + '=1';
        message = button_id === 'supprimer' ? 'resa_supprime' : 'resa_ok';
        $.ajax({
            type: "POST",
            url: "reservation.php",
            data: form_seralize,
            success: success_post_reservation
        });
    }
}

function success_post_reservation(post_data) {
    if (post_data === "OK") {
        $.get(origine_click + ".php?mode=ajax&&msg=" + message + "&jour=" + jour_resa + "&avion_id=" + avion_id, function(get_data) {
            close_ajax_pilote();
            $('#pilote_content').html(get_data);
            $('.tooltip_pilote').tooltipster({
                position: 'bottom',
                theme: '.tooltipster-pilote'
            });
        });
    }
    else {
        $('#ajax_pilote').html(post_data);
        $('.tooltip_pilote').tooltipster({
            position: 'bottom',
            theme: '.tooltipster-pilote'
        });
    }
}

function show_ajax_pilote() {
    var y = mouseY - 200;
    if (typeof top === 'number' && top > 0) {
        y = top;
    }

    $('body').append('<div id="ajax_overlay"></div>');
    $('#ajax_overlay').click(close_ajax_pilote);
    $('body').append('<div id="ajax_pilote"></div>');
    $('#ajax_pilote').html('<center><img src="picts/wait.png" alt="Loading / Chargement en cours ..."/></center>');
    $('#ajax_pilote').css({"top": y});
}

function close_ajax_pilote() {
    $("#ajax_pilote").fadeOut(600, function() {
        $("#ajax_overlay").remove();
        $("#ajax_pilote").remove();
    });
}

function clignotement() {
    if ($('#message').css('color') === 'black') {
        $('#message').css({'color': '#FFB619'});
    } else {
        $('#message').css({'color': 'black'});
    }
}

function ajax_messages_box_styles() {
    $('#infobox').css({"position": "fixed", "top": 20, "left": "20%", "width": "60%"}).fadeOut(6000, function() {
    });
    $('#infobox h1').css({"background": "#04CC65"});
    $('#warningbox').css({"position": "fixed", "top": 20, "left": "20%", "width": "60%"}).fadeOut(8000, function() {
    });
    $('#warningbox h1').css({"background": "#FFB619"});
    $('#errorbox').css({"position": "fixed", "top": 20, "left": "20%", "width": "60%"}).fadeOut(10000, function() {
    });
    $('#warningbox h1').css({"background": "#CC0000"});
}

var mouseX = -1;
var mouseY = -1;
$(document).on("mousemove", function(event) {
    mouseX = event.pageX;
    mouseY = event.pageY;
});
