// parametre loader
var opts = {
    lines: 15, // The number of lines to draw
    length: 22, // The length of each line
    width: 6, // The line thickness
    radius: 22, // The radius of the inner circle
    corners: 1, // Corner roundness (0..1)
    rotate: 0, // The rotation offset
    direction: 1, // 1: clockwise, -1: counterclockwise
    color: '#fff', // #rgb or #rrggbb or array of colors
    speed: 1.3, // Rounds per second
    trail: 73, // Afterglow percentage
    shadow: true, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    className: 'spinner', // The CSS class to assign to the spinner
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    top: 'auto', // Top position relative to parent in px
    left: 'auto' // Left position relative to parent in px
};


$( document ).ready(function() {

    /* 
     * permet d'adapter la div du loader à la taille du conteneur (largeur/hauteur) 
     * pour prendre toute la place sans le menu + et de garder même avec le resize de la fenêtre
     */
    $('#loading').height($('#wrap').height() - 51);

    $( window ).resize(function() {
        $('#loading').height($('#wrap').height() - 51);
    });

});

function create_html_service(service, etat){

    var html = '<tr><td>'+service+'</td><td>';

    if(etat == 1){
        html = html+'<span class="label label-success"><i class="fa fa-play"></i> En service</span></td><td><div class="btn-group bt-switch"><button type="button" class="btn btn-xs btn-success active" disabled="disabled">On</button><button type="button" class="btn btn-xs btn-danger bt-switch-active-right bt-switch-action-off">Off</button></div></td>';
    }
    else{
        html = html+'<span class="label label-danger"><i class="fa fa-stop"></i> Arreté</span></td><td><div class="btn-group bt-switch"><button type="button" class="btn btn-xs btn-success bt-switch-active-left bt-switch-action-on">On</button><button type="button" class="btn btn-xs btn-danger active" disabled="disabled">Off</button></div></td>';
    }

    return html;

}