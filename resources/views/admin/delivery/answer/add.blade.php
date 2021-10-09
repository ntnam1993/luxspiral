
@extends('adminlte::page')

@section('title', '応答メッセージ登録')

@section('content_header')
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/fileinput.min.css') }}">
@endsection

@section('content')
    <div class="box box-primary div-create-answer">
        <div class="box-header">
            <h2>応答メッセージ登録</h2>
        </div>
        {{-- <div class="box-body table-responsive "> --}}
        <div class="box-body">
            <div class="col-sm-8">
                <form class="form-horizontal frm-create-answer" action="{{ route("admin.delivery.answer.store") }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-2">タイトル</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" maxlength="255">
                            <p class="error-title hidden"></p>
                        </div>
                    </div>
                    <div class="file-upload-msg form-group">
                        <label class="control-label col-sm-2">応答メッセージ</label>
                        <div class="col-sm-10">
                            <div class="file-loading form-control">
                                <input id="file-upload" name="filemsgans" type="file" accept=".mp3" class="form-control">
                            </div>
                        </div>
                        <div id="kv-error-2" style="margin-top:10px;display:none"></div>
                        <input type="hidden" id="action-url" value="">
                        <input type="hidden" id="nameURL" value="">
                    </div>
                    <div class="form-group">
                        <div class="text-center">
                            <a href="{{ route('admin.delivery.list') }}" class="btn btn-default btn-cancel">キャンセル</a>
                            <button type="button" class="btn btn-primary btn-create">アップロード</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4">

            </div>
        </div>
        <div class="box-footer"></div>
    </div>
    <div class="box box-primary div-confirm-create-answer" style="display: none;">
        <div class="box-header">
            <h3>応答メッセージ登録</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
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
                    <div class="col-sm-12 col-xm-12 col-xs-12">
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
                    <a class="btn btn-default btn-md pull-right cancle-confirm-create pull-right">{{trans('message.btn-cancel')}}</a>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-7">
                    <input type="submit" class="btn btn-primary btn-md btn-custom btn-confirm-create btn-loading col-lg-7 pull-left" value="保存">
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{ asset('admin/js/fileinput/fileinput.min.js') }}"></script>
    <script src="{{ asset('admin/js/fileinput/theme.min.js') }}"></script>
    <script src="{{ asset('admin/js/fileinput/ja.js') }}"></script>
    <script src="{{ asset('/admin/js/confirm-close-window.js') }}"></script>
    <script src="{{ asset('/admin/js/answer.js') }}"></script>
    <script>
        $(document).ready(function(){
            var actionUrl = $('#action-url').val();

            $("#file-upload").fileinput({
                language: "ja",
                uploadAsync: false,
                theme: "fa",
                showPreview: true,
                showUpload: false,
                maxFileCount : 1,
                validateInitialCount: true,
                // uploadUrl: actionUrl,
                allowedFileExtensions: ["mp3"],
                maxFileSize: 300000,
                elErrorContainer: '#kv-error-2'
            }).on('filebatchuploadsuccess', function(event, data) {
                var response = data.response;

                if (response['msg']) {
                    $('#successModal').modal('show');
                    $('.msg-text').append(response['msg']);
                }

                if(response['api_url']) {
                    console.log(response['api_url']);
                    $.ajax({url: response['api_url'], success: function(result){

                    }});
                }

                if (response['msg_error']) {
                    $('#errorModal').modal('show');
                    $('.msg-text').append(response['msg_error']);
                }
            });
            $('.file-caption-name').prop('readonly', true);
        });
    </script>
@endsection