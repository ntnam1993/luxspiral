$(document).ready(function(){
    $('.frm-create-delivery input[name="title"]').val(localStorage.getItem('title') ? localStorage.getItem('title') : '');
    $('.frm-create-delivery input[name="date"]').val(localStorage.getItem('date') ? localStorage.getItem('date') : getDate());
    $('.frm-create-delivery input[name="time"]').val(localStorage.getItem('time') ? localStorage.getItem('time') : getTime());

    $('.btn-edit, .btn-cancel, .btn-confirm, .btn-create-new, .btn-edit, .btn-edit-answer').on('click', function(){
        localStorage.clear();
    });
    setDisableButtonCreate();
    setDisableButtonEdit();

    $('.frm-create-delivery input[name="title"], .frm-edit-delivery input[name="title"]').on('keyup',function(){
        setDisableButtonCreate();
        setDisableButtonEdit();
        var name = $(this).attr('name');
        localStorage.setItem(name,$(this).val());
    });

    $('.frm-create-delivery input[name="date"], .frm-create-delivery input[name="time"], .frm-edit-delivery input[name="date"], .frm-edit-delivery input[name="time"]').on('change',function(){
        var name = $(this).attr('name');
        localStorage.setItem(name,$(this).val());
    });
});