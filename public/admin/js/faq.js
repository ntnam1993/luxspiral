$(document).ready(function(){
    //create
    $('.div-create-faq input[name="title"]').on('keyup',function(){
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
    $('.div-create-faq textarea[name="content"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-content" ).removeClass('hidden');
            $(this).next( ".error-content" ).css('height', '20px');
            $(this).next( ".error-content" ).text('お知らせ本文が登録されていません。');
            $(this).next( ".error-content" ).css('color', 'red');
        }else{
            $(this).next( ".error-content" ).addClass('hidden');
        }
        setDisableButtonCreate();
    });
    $('.btn-create').on('click',function(){
        $(".div-create-faq").fadeOut("fast", function() {
            $(".div-create-confirm-faq").fadeIn("fast",function(){
                $('.div-create-confirm-faq .title-confirm').text($('.div-create-faq input[name=title]').val());
                $('.div-create-confirm-faq .content-confirm').html(nl2br($('.div-create-faq textarea[name=content]').val()));
            });
        });
    });
    $('.cancle-confirm-faq').on('click',function(){
        $(".div-create-confirm-faq").fadeOut("fast", function() {
            $(".div-create-faq").fadeIn("fast",function(){
            });
        });
    });
    //edit
    $('.div-edit-faq input[name="title"]').on('keyup',function(){
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
    $('.div-edit-faq textarea[name="content"]').on('keyup',function(){
        if ($(this).val() == '') {
            $(this).next( ".error-content" ).removeClass('hidden');
            $(this).next( ".error-content" ).css('height', '20px');
            $(this).next( ".error-content" ).text('お知らせ本文が登録されていません。');
            $(this).next( ".error-content" ).css('color', 'red');
        }else{
            $(this).next( ".error-content" ).addClass('hidden');
        }
        setDisableButtonEdit();
    });
    $('.btn-update').on('click',function(){
        $(".div-edit-faq").fadeOut("fast", function() {
            $(".div-edit-confirm-faq").fadeIn("fast",function(){
                $('.div-edit-confirm-faq .title-confirm').text($('.div-edit-faq input[name=title]').val());
                $('.div-edit-confirm-faq .content-confirm').html(nl2br($('.div-edit-faq textarea[name=content]').val()));
            });
        });
    });

    $('.cancle-confirm-edit').on('click',function(){
        $(".div-edit-confirm-faq").fadeOut("fast", function() {
            $(".div-edit-faq").fadeIn("fast",function(){
            });
        });
    });

    //submit
    $('.btn-confirm-create').on('click',function(){
        $('.frm-create-faq').submit();
    });

    $('.btn-confirm-update').on('click',function(){
        $('.frm-edit-faq').submit();
    });
});
function setDisableButtonCreate()
{
    if ($('.frm-create-faq input[name="title"]').val() != '' && $('.frm-create-faq textarea[name="content"]').val() !=''){
        $('.frm-create-faq .btn-create').prop('disabled', false);
    } else {
        $('.frm-create-faq .btn-create').prop('disabled', true);
    }
}
function setDisableButtonEdit()
{
    if ($('.frm-edit-faq input[name="title"]').val() != '' && $('.frm-edit-faq textarea[name="content"]').val() !=''){
        $('.frm-edit-faq .btn-update').prop('disabled', false);
    } else {
        $('.frm-edit-faq .btn-update').prop('disabled', true);
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