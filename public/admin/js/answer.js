$(document).ready(function(){
    //base answer
    $('.btn-cancel, .btn-confirm, .btn-create-new').on('click', function(){
        localStorage.clear();
    });

    //create
    $('.div-create-answer input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        LimitInput($(this));
        setDisableButtonCreateAnswer();
    });

    $('.div-create-answer #file-upload').on('fileclear',function(event){
        $('.div-create-answer #nameURL').val('');
        setDisableButtonCreateAnswer();
    });

    $('.div-create-answer #file-upload').on('fileselect', function(event, numFiles, label) {
        $('.div-confirm-create-answer .sound-confirm strong').text(label);
        $('.div-create-answer #nameURL').val(label);
        setDisableButtonCreateAnswer();
    });
    $('.btn-create').on('click',function(){
        $(".div-create-answer").fadeOut("fast", function() {
            $(".div-confirm-create-answer").fadeIn("fast",function(){
                $('.div-confirm-create-answer .title-confirm strong').text($('.div-create-answer input[name=title]').val());
                $('.div-confirm-create-answer .sound-confirm strong').text($('#nameURL').val());
            });
        });
    });

    $('.cancle-confirm-create').on('click',function(){
        $(".div-confirm-create-answer").fadeOut("fast", function() {
            $(".div-create-answer").fadeIn("fast",function(){
            });
        });
    });

    //edit
    $('.div-edit-answer input[name="title"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-title" ).removeClass('hidden');
            $(this).next( ".error-title" ).css('height', '20px');
            $(this).next( ".error-title" ).text('タイトルが登録されていません。');
            $(this).next( ".error-title" ).css('color', 'red');
        }else{
            $(this).next( ".error-title" ).addClass('hidden');
        }
        LimitInput($(this));
        setDisableButtonEditAnswer();
    });
    $('.div-edit-answer #file-upload').on('fileselect', function(event, numFiles, label) {
        $('.div-edit-answer #nameURL').val(label);
        setDisableButtonEditAnswer();
    });
    $('.div-edit-answer #file-upload').on('fileclear',function(event){
        $('.div-edit-answer #nameURL').val('');
        setDisableButtonEditAnswer();
    });
    $('.btn-update').on('click',function(){
        $(".div-edit-answer").fadeOut("fast", function() {
            $(".div-confirm-edit-answer").fadeIn("fast",function(){
                $('.div-confirm-edit-answer .title-confirm strong').text($('.div-edit-answer input[name=title]').val());
                $('.div-confirm-edit-answer .sound-confirm strong').text($('#nameURL').val());
            });
        });
    });

    $('.cancle-confirm-edit').on('click',function(){
        $(".div-confirm-edit-answer").fadeOut("fast", function() {
            $(".div-edit-answer").fadeIn("fast",function(){
            });
        });
    });
    setDisableButtonEditAnswer();
    setDisableButtonCreateAnswer();

    //submit
    $('.btn-confirm-create').on('click',function(){
        $('.frm-create-answer').submit();
    });

    $('.btn-confirm-edit').on('click',function(){
        $('.frm-edit-answer').submit();
    });
});

function setDisableButtonCreateAnswer()
{
    if($('.div-create-answer #nameURL').val() == '' || $('.div-create-answer input[name="title"]').val() == '') {
        $('.btn-create').prop('disabled', true);
    }else {
        $('.btn-create').attr('disabled', false);
    }
}
function setDisableButtonEditAnswer()
{
    if( $('#nameURL').val() != '' && $('.div-edit-answer input[name="title"]').val() != '') {
        $('.frm-edit-answer .btn-update').prop('disabled', false);
    }else {
        $('.frm-edit-answer .btn-update').prop('disabled', true);
    }
}

function LimitInput(input)
{
    if ( (input).val().length > 255 ) {
        input.val(input.val().substr(0, 255));
        alert("255文以内入力してください。");
    }
}