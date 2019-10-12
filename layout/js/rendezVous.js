$(document).ready(function() {

    $('.select2').select2({ "width": "100%" });

    $('.dateTimepicker').datetimepicker({
        format: 'yyyy-mm-dd HH',
        autoclose: true
    });

    $('#idPatient').change(function() {
        var idPatient = $('#idPatient').val();
        getDossiers(idPatient);
    });

    getRdvs();
});

$('#calendrierRdv').fullCalendar({
    selectable: true,
    selectHelper: true,
    select: function(start, end, allDay) {
        var title = prompt('Event Title:');
        if (title) {
            start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:ss");
            end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
            $.ajax({
                url: 'http://localhost/fullcalendar/add_events.php',
                data: 'title=' + title + '&start=' + start + '&end=' + end,
                type: "POST",
                success: function(json) {
                    alert('OK');
                }
            });
            calendar.fullCalendar('renderEvent', {
                    title: title,
                    start: start,
                    end: end,
                    allDay: allDay
                },
                true // make the event "stick"
            );
        }
        calendar.fullCalendar('unselect');
    }
});

function getRdvs() {
    $.ajax({

        url: 'includes/functions/controller.php?action=getRdvs',

        type: 'GET',

        success: function(res) {
            var rdvs = JSON.parse(res);

            $('#calendrierRdv').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                defaultDate: '2017-11-12',
                navLinks: true,
                editable: true,
                eventLimit: true,
                events: rdvs
            });
        },

        error: function(resultat, statut, erreur) {
            console.log(resultat);
        }

    });
}

function getDossiers(idPatient) {
    $.ajax({

        url: 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

        type: 'GET',

        success: function(res) {
            var a = JSON.parse(res);

            $.each(a, function(i, value) {
                $('#idDossier').append($('<option>').text(value).attr('value', value));
            });

            console.log(res);
        },

        error: function(resultat, statut, erreur) {
            console.log(resultat);
        }

    });
}