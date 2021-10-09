$(document).ready(function()
{
    $('.div-delete').on('click','.show-modal-delete',function(){
        var url      = $(this).data('url');
        var title    = $(this).data('title');
        var question = $(this).data('question');
        var yes      = $(this).data('yes');
        var no       = $(this).data('no');
        var id       = $(this).data('id');

        $("#frm-delete").attr("action",url);
        $("#frm-delete h3").text(title);
        $("#frm-delete h4").text(question);
        $("#frm-delete .btn-secondary").text(no);
        $("#frm-delete .btn-primary").text(yes);
        $("#frm-delete input[name='id']").val(id);

        $('#modalDeleteUser').modal('show');

    });
    //base
    $('input[name="date"],input[name="time"]').attr('readonly',true);
    $('.btn-loading').on('click',function(){
        $('.lds-ring').removeClass('hide');
    });
    $("div.alert-success").delay(7000).slideUp();
    $("div.alert-danger").delay(7000).slideUp();

});

function getDate()
{
    var now    = getDateJP();
    var date   = ( now.getDate() > 10 ) ? now.getDate() : '0' + now.getDate() ;
    var month  = ( ( now.getMonth() + 1 ) > 10 ) ? ( now.getMonth() + 1 ) : '0' + ( now.getMonth() + 1 );
    var year   = now.getFullYear();

    var today  = year + '-' + month + '-' + date;

    return today;
}

function getTime()
{
    var now    = getDateJP();
    var hour   = now.getHours();
    var minute = (now.getMinutes()%15 != 0) ? (now.getMinutes()+30) : (now.getMinutes()+45);

    if (minute > 59) {
        if (minute == 60) {
            minute = '00';
        }else {
            minute = minute - 60;
            hour   = hour + 1;
        }
    }
    if (hour > 23) {
        hour = '00';
    }
    var time   = hour + ':' + minute;
    return time;
}

function getDateJP()
{
    var offset = 9;
    return new Date(new Date(new Date().getTime() + offset * 3600 * 1000).toUTCString().replace( / GMT$/, "" ));
}

function callAjax(_url, _method, _data, callback)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: _url,
        method: _method,
        cache: false,
        data: _data
    }).done(function(response) {
        callback(response);
    }).fail(function(response) {
        alert( "error" );
    });
}
