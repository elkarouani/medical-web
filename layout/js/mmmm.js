
                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button onclick="DeleteDossiers(' + aData.idDossier + ')"  data-toggle="tooltip" id="btnDeleteDossiers" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Editer" href="editPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="viewPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-info btn-sm btn-flat">' +
                        '<i class="fa fa-eye"></i>' +
                        '</a>'
                    )

                    return nRow;
                },
