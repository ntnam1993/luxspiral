@extends('adminlte::page')

@section('title', '応答メッセージ登録')

@section('content_header')
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/fileinput.min.css') }}">
@endsection

@section('content')
    <div class="box box-primary div-edit-answer">
        <div class="box-header">
            <h3>応答メッセージ登録</h3>
        </div>
        <div class="box-body">
            <div class="col-sm-10">
                <form class="form-horizontal frm-edit-answer" action="{{ route("admin.delivery.answer.update", $msgAns->id) }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-2">タイトル</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" value="{{ $msgAns->title }}" maxlength="255">
                            <p class="error-title hidden"></p>
                        </div>
                    </div>
                    <div class="file-upload-msg form-group">
                        <label class="control-label col-sm-2">応答メッセージ</label>
                        <div class="col-sm-10">
                            <div class="file-loading form-control">
                                <input id="file-upload" name="fileeditmsgans" type="file" accept=".mp3" class="form-control">
                            </div>
                        </div>
                        <div id="kv-error-2" style="margin-top:10px;display:none"></div>
                        <input type="hidden" id="urlDelete" value="{{route('admin.delivery.delete-mp3','filemsgsend').'?_token='.csrf_token()}}">
                        <input type="hidden" id="nameURL" value="{{$msgAns->sound->name}}">
                    </div>
                    <input type="hidden" name="urlAns" value="{{$msgAns->sound->url}}">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <a href="{{URL::previous()}}" class="btn btn-default btn-lg pull-right">{{trans('message.btn-back')}}</a>
                        </div>
                        <div class="col-sm-6">
                                <button type="button" class="btn btn-primary btn-lg btn-update">アップロード</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-2">

            </div>
        </div>
    </div>
    <div class="box box-primary div-confirm-edit-answer" style="display: none;">
        <div class="box-header">
            <h3>応答メッセージ登録</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12 no-padding">
                        <p class="text-right">タイトル</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding ">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="title-confirm"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12 no-padding">
                        <p class="text-right">応答メッセージ</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding ">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="sound-confirm"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-sm-3 col-xm-3 col-xs-5">
                    <a class="btn btn-default btn-md pull-right cancle-confirm-edit">{{trans('message.btn-back')}}</a>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-7">
                    <input type="submit" class="btn btn-primary btn-md   btn-custom btn-loading btn-confirm-edit col-lg-7" value="保存">
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('admin/js/fileinput/fileinput.min.js') }}"></script>
    <script src="{{ asset('admin/js/fileinput/theme.min.js') }}"></script>
    <script src="{{ asset('admin/js/fileinput/ja.js') }}"></script>
    <script>
        $(document).ready(function(){
            var actionUrl = $('#urlDelete').val();
            var urlAns  = $('input[name=urlAns]').val();

            $("#file-upload").fileinput({
                language: "ja",
                initialPreview:urlAns,
                initialPreviewAsData: true,
                initialPreviewConfig: [
                    {
                        type: "audio",
                        filetype: "audio/mp3",
                        caption: "Message Answer",
                        url: actionUrl
                    }
                ],
                maxFileCount : 1,
                validateInitialCount: true,
                overwriteInitial: true,
                theme: "fa",
                showPreview: true,
                showUpload: false,
                allowedFileExtensions: ["mp3"],
                maxFileSize: 300000,
                elErrorContainer: '#kv-error-2'
            }).on('filebatchuploadsuccess', function(event, data) {
                console.log(data);
            });
            $('.file-caption-name').prop('readonly', true);
        });
    </script>
    <script src="{{ asset('/admin/js/answer.js') }}"></script>
@endsection

