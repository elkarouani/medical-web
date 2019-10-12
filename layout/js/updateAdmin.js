/***** Debut Page Edit Admin ******/

    $('#btnChoisieImage').click(function () {
        $('#inputFileImage').click();
    });

    $('#btnUpdateInfoAdmin').click(function () {
        UpdateInfoAdmin();
    });

    $('#btnUpdateLoginAdmin').click(function () {
        UpdateLoginAdmin();
    });

/** functions **/
// modification informations géneral admin :
    function UpdateInfoAdmin(){
        var errors = [];
        $('.alert-popup').hide();
        loaderBtn('btnUpdateInfoAdmin', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#frmUpdAdmin'));

        $.ajax({

            url         :$('#frmUpdAdmin').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnUpdateInfoAdmin', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    //getAdmins();
                    showAlertSuccess('Les informations est bien modifier');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnUpdateInfoAdmin', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    ShowAlertError("l'ajout a echoue !");
                }
                else
                {
                    loaderBtn('btnUpdateInfoAdmin', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    ShowAlertError("La modification a échoué tout les champs sont obligatoire !");
                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // modification login admin :
    function UpdateLoginAdmin(){

        var errors = [];
        $('.alert-popup').hide();
        loaderBtn('btnUpdateLoginAdmin', 'Chargement...' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#frmUpdateLoginadmin'));

        $.ajax({

            url         :$('#frmUpdateLoginadmin').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnUpdateLoginAdmin', '<i class="fa fa-edit"></i>  Modifier Le Login ');
                    showAlertSuccess('Les informations est bien modifier');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnUpdateLoginAdmin', '<i class="fa fa-edit"></i>  Modifier Le Login ');
                    ShowAlertError("Respecter le format de login !");
                }
                else
                {
                    loaderBtn('btnUpdateInfoAdmin', '<i class="fa fa-edit"></i>  Modifier Le Login ');
                    ShowAlertError("Respecter le format de login !");
                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // methode change password admin :
    function ChangePassAdmin(){

        var errors = [];
        $('.alert-popup').hide();
        loaderBtn('btnChangePass', 'Chargement...' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormChangePass'));

        $.ajax({

            url         :$('#FormChangePass').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    //getAdmins();
                    showAlertSuccess('Le mot de pass est bien changer !');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    ShowAlertError("Le mot de pass n'est pas été changer !");
                }
                else
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    ShowAlertError("Le mot de pass n'est pas été changer !");
                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // methode change image admin :
    function UpdateImageAdmin()
    {
        $('.alert-popup').hide();

        loaderBtn('btnUpdateImageAdmin', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var fd = new FormData(document.querySelector('#FormUpdateImage'));

        $.ajax({

            url         :$('#FormUpdateImage').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '-1' )
                {
                    ShowAlertError("Le format d'image est incorrect !");
                }
                else if ( data == '0' )
                {
                    ShowAlertError("Impossible de modifier l'image !");
                }
                else if ( data == '1' )
                {
                    showAlertSuccess('L\'image à été bien enregestrer !');
                }
                else if ( data == '2' )
                {
                    ShowAlertError("Impossible de modifier l'image, Choiosie une image !");
                }
                else
                {
                    console.log('noooo');
                }
            },
            error : function(status)
            {
                console.log(status);
            },
            complete : function ()
            {
                loaderBtn('btnUpdateImageAdmin', 'Modifier l\'image');
            }
        });
    }

/***** Fin Page Edit Admin ******/
