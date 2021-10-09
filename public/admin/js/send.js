$(document).ready(function(){
    // base answer
    $('.btn-cancel, .btn-confirm, .btn-create-new').on('click', function(){
        localStorage.clear();
    });
    // set default date time
    $('.div-create-send input[name="date"]').val(getDate());
    $('.div-create-send input[name="time"]').val(getTime());
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
        setDisableButtonCreateSend();
        setDisableButtonEditSend();
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
        setDisableButtonCreateSend();
        setDisableButtonEditSend();
    });
    //create
    $('.div-create-send input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        setDisableButtonCreateSend();
    });
    $('.div-create-send #file-upload1').on('fileclear',function(event){
        $('.div-create-send #nameURL1').val('');
        setDisableButtonCreateSend();
    });
    $('.div-create-send #file-upload2').on('fileclear',function(event){
        $('.div-create-send #nameURL2').val('');
        setDisableButtonCreateSend();
    });
    $('.div-create-send #file-upload1').on('fileselect', function(event, numFiles, label) {
        $('.div-confirm-create-send .sound-send-confirm strong').text(label);
        $('.div-create-send #nameURL1').val(label);
        setDisableButtonCreateSend();
    });
    $('.div-create-send #file-upload2').on('fileselect', function(event, numFiles, label) {
        $('.div-confirm-create-send .sound-no-answer-confirm strong').text(label);
        $('.div-create-send #nameURL2').val(label);
        setDisableButtonCreateSend();
    });
    $('.btn-create').on('click',function(){
        $(".div-create-send").fadeOut("fast", function() {
            $(".div-confirm-create-send").fadeIn("fast",function(){
                $('.div-confirm-create-send .title-confirm strong').text($('.div-create-send input[name=title]').val());
                $('.div-confirm-create-send .sound-send-confirm strong').text($('#nameURL1').val());
                $('.div-confirm-create-send .sound-no-answer-confirm strong').text($('#nameURL2').val());
                $('.div-confirm-create-send .schedule-confirm strong').text($('#check-date').val());
            });
        });
    });
    $('.cancle-confirm-send').on('click',function(){
        $(".div-confirm-create-send").fadeOut("fast", function() {
            $(".div-create-send").fadeIn("fast",function(){
            });
        });
    });
    //edit
    $('.div-edit-send input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        setDisableButtonEditSend();
    });
    $('.div-edit-send #file-upload1').on('fileclear',function(event){
        $('.div-edit-send #nameSound1').val('');
        setDisableButtonEditSend();
    });
    $('.div-edit-send #file-upload2').on('fileclear',function(event){
        $('.div-edit-send #nameSound2').val('');
        setDisableButtonEditSend();
    });
    $('.div-edit-send #file-upload1').on('fileselect', function(event, numFiles, label) {
        $('.div-confirm-edit-send .sound-send-confirm strong').text(label);
        $('.div-edit-send #nameSound1').val(label);
        setDisableButtonEditSend();
    });
    $('.div-edit-send #file-upload2').on('fileselect', function(event, numFiles, label) {
        $('.div-confirm-edit-send .sound-no-answer-confirm strong').text(label);
        $('.div-edit-send #nameSound2').val(label);
        setDisableButtonEditSend();
    });
    $('.btn-update').on('click',function(){
        $(".div-edit-send").fadeOut("fast", function() {
            $(".div-confirm-edit-send").fadeIn("fast",function(){
                nameSound1 = $('#nameSound1').val();
                nameSound2 = $('#nameSound2').val();
                $('.div-confirm-edit-send .title-confirm strong').text($('.div-edit-send input[name=title]').val());
                $('.div-confirm-edit-send .sound-send-confirm strong').text(nameSound1);
                $('.div-confirm-edit-send .sound-no-answer-confirm strong').text(nameSound2);
                $('.div-confirm-edit-send .schedule-confirm strong').text($('#check-date').val());
            });
        });
    });

    $('.cancle-confirm-edit').on('click',function(){
        $(".div-confirm-edit-send").fadeOut("fast", function() {
            $(".div-edit-send").fadeIn("fast",function(){
            });
        });
    });

    //submit
    $('.btn-confirm-create').on('click',function(){
        $('.frm-create-send').submit();
    });

    $('.btn-confirm-update').on('click',function(){
         $('.frm-edit-send').submit();
    });
});

function setDisableButtonCreateSend()
{
    if($('.div-create-send #nameURL1').val() == '' || $('.div-create-send #nameURL2').val() == '' || $('.div-create-send input[name="title"]').val() == '' || $('#check-date').val() == '') {
        $('.div-create-send .btn-create').prop('disabled', true);
    }else {
        $('.div-create-send .btn-create').attr('disabled', false);
    }
}
function setDisableButtonEditSend()
{
    if( $('.div-edit-send #nameURL1').val() == '' || $('.div-edit-send #nameURL2').val() == '' || $('.div-edit-send input[name="title"]').val() == '' || $('#check-date').val() == '') {
        $('.frm-edit-send .btn-update').prop('disabled', true);
    }else {
        $('.frm-edit-send .btn-update').prop('disabled', false);
    }
}

function LimitInput(input)
{
    if ( (input).val().length > 255 ) {
        input.val(input.val().substr(0, 255));
        alert("255文以内入力してください。");
    }
}