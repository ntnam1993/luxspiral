$(document).ready(function(){
    $('.frm-create-notify input[name="date"]').val(getDate());
    $('.frm-create-notify input[name="time"]').val(getTime());
    $('input[name="date"]').on('change',function(){
        var time = $('input[name="time"]').val();
        var day  = $(this).val();
        var date = new Date(day+ ' ' +time);
        var now  = getDateJP();

        if (date < now ) {
            $('.error-date-time').css('display','block');
            $('.error-date-time').css('height','20px');
            $('.error-date-time').text('配信日時が過去日です。');
            $('#check-date').val('');
        }else {
            $('.error-date-time').css('display','none');
            $('#check-date').val(day+ ' ' +time);
        }
        setDisableButtonCreate();
        setDisableButtonEdit();
    });

    $('input[name="time"]').on('change',function(){
        var time = $(this).val();
        var day  = $('input[name="date"]').val();
        var date = new Date(day+ ' ' +time);
        var now  = getDateJP();

        if (date < now ) {
            $('.error-date-time').css('display','block');
            $('.error-date-time').css('height','20px');
            $('.error-date-time').text('配信日時が過去日です。');
            $('#check-date').val('');
        }else {
            $('.error-date-time').css('display','none');
            $('#check-date').val(day+ ' ' +time);
        }
        setDisableButtonCreate();
        setDisableButtonEdit();
    });
    //create
    $('.div-create-notify input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        LimitInput($(this));
        setDisableButtonCreate();
    });
    $('.div-create-notify textarea[name="description"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-description" ).removeClass('hidden');
            $(this).next( ".error-description" ).css('height', '20px');
            $(this).next( ".error-description" ).text('お知らせ本文が登録されていません。');
            $(this).next( ".error-description" ).css('color', 'red');
        }else{
            $(this).next( ".error-description" ).addClass('hidden');
        }
        setDisableButtonCreate();
    });
    $('.btn-create').on('click',function(){
        $(".div-create-notify").fadeOut("fast", function() {
            $(".div-create-confirm-notify").fadeIn("fast",function(){
                $('.div-create-confirm-notify .title-confirm').text($('.div-create-notify input[name=title]').val());
                $('.div-create-confirm-notify .schedule-confirm').text($('#check-date').val());
                $('.div-create-confirm-notify .description-confirm').html(nl2br($('.div-create-notify textarea[name=description]').val()));
            });
        });
    });
    $('.cancle-confirm-notify').on('click',function(){
        $(".div-create-confirm-notify").fadeOut("fast", function() {
            $(".div-create-notify").fadeIn("fast",function(){
            });
        });
    });
    //edit
    $('.div-edit-notify input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        LimitInput($(this));
        setDisableButtonEdit();
    });
    $('.div-edit-notify textarea[name="description"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-description" ).removeClass('hidden');
            $(this).next( ".error-description" ).css('height', '20px');
            $(this).next( ".error-description" ).text('お知らせ本文が登録されていません。');
            $(this).next( ".error-description" ).css('color', 'red');
        }else{
            $(this).next( ".error-description" ).addClass('hidden');
        }
        setDisableButtonEdit();
    });
    $('.btn-update').on('click',function(){
        $(".div-edit-notify").fadeOut("fast", function() {
            $(".div-edit-confirm-notify").fadeIn("fast",function(){
                $('.div-edit-confirm-notify .title-confirm').text($('.div-edit-notify input[name=title]').val());
                $('.div-edit-confirm-notify .schedule-confirm').text($('#check-date').val());
                $('.div-edit-confirm-notify .description-confirm').html(nl2br($('.div-edit-notify textarea[name=description]').val()));
            });
        });
    });

    $('.cancle-confirm-edit').on('click',function(){
        $(".div-edit-confirm-notify").fadeOut("fast", function() {
            $(".div-edit-notify").fadeIn("fast",function(){
            });
        });
    });

    //submit
    $('.btn-confirm-create').on('click',function(){
        $('.frm-create-notify').submit();
    });

    $('.btn-confirm-update').on('click',function(){
        $('.frm-edit-notify').submit();
    });
});
function setDisableButtonCreate()
{
    if ($('.frm-create-notify input[name="title"]').val() != '' && $('.frm-create-notify textarea[name="description"]').val() !='' && $('#check-date') != ''){
        $('.frm-create-notify .btn-create').prop('disabled', false);
    } else {
        $('.frm-create-notify .btn-create').prop('disabled', true);
    }
}
function setDisableButtonEdit()
{
    if ($('.frm-edit-notify input[name="title"]').val() != '' && $('.frm-edit-notify textarea[name="description"]').val() !='' && $('#check-date') != ''){
        $('.frm-edit-notify .btn-update').prop('disabled', false);
    } else {
        $('.frm-edit-notify .btn-update').prop('disabled', true);
    }
}
function LimitInput(input)
{
    if ( (input).val().length > 255 ) {
        input.val(input.val().substr(0, 255));
        alert("255文以内入力してください。");
    }
}

function LimitTextarea(textarea)
{
    if ( (textarea).val().length > 3000 ) {
        textarea.val(textarea.val().substr(0, 3000));
        alert("3000字以内で入力してください。");
    }
}

function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}