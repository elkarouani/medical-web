$(document).ready(function () {

    // Side menu :
    $('.sidebar-menu').tree();
    // Date picker :
    $('.datepicker').datepicker({
        autoclose: true
    });  
    
    $(document).ajaxStart(function() { Pace.restart(); });
    

    function headAlert() {
        var alert = document.getElementById('btn-hide-alert').parentNode;
        alert.fadeIn();
    }

});

function showAlertSuccess(text)
{
    Lobibox.notify('success', { position: 'top right', showClass: 'slideInRight', hideClass: 'slideOutRight', msg: text, title: 'Success'});
}

function ShowAlertError(text)
{
    Lobibox.notify('error', { position: 'top right', showClass: 'slideInRight', hideClass: 'slideOutRight', msg: text, title: 'Erreurs'});
}
function loaderBtn(idBtn, text)
{
    var btn = document.querySelector('#' + idBtn);
    btn.innerHTML = text;
}