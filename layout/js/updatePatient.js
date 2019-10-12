/***** Debut Page Edit Admin ******/

    $('#btnUpdatePatient').click(function () {
        UpdatePatient();
    });

/** functions **/
// modification informations géneral admin :

    function UpdatePatient(){

        var errors = [];

        $('.alert-popup').hide();

        loaderBtn('btnUpdatePatient', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.querySelectorAll("input,textarea");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.classList.remove('has-error');
                elem[i].parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormUpdatePatient'));

        $.ajax({

            url         :$('#FormUpdatePatient').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                console.log(data);
                //if( data == '1' )
                //{
                //    loaderBtn('btnUpdatePatient', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                //    showAlertSuccess('Les informations est bien modifier');
                //}
                //else if( data == '0' )
                //{
                //    loaderBtn('btnUpdatePatient', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                //    ShowAlertError("La modefication à échoue !");
                //}
                //else
                //{
                //    loaderBtn('btnUpdatePatient', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                //    ShowAlertError("La modification a échoué tout les champs sont obligatoire !");
                //    errors = JSON.parse(data);
                //
                //    for (var err in errors)
                //    {
                //        var el = document.getElementById(err);
                //        el.parentNode.classList.add('has-error');
                //        el.parentNode.lastElementChild.innerText = errors[err];
                //    };
                //
                //    for (var err in errors)
                //    {
                //        var el = document.getElementById(err);
                //        el.parentNode.classList.add('has-error');
                //        el.parentNode.lastElementChild.innerText = errors[err];
                //    };
                //}
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

/***** Fin Page Edit Admin ******/
