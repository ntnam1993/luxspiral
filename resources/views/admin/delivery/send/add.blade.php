@extends('adminlte::page')

@section('title', '配信メッセージ登録')

@section('content_header')
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/fileinput.min.css') }}">
@endsection

@section('content')
    <div class="box box-primary div-create-send">
        <div class="box-header">
            <h2>配信メッセージ登録</h2>
        </div>
        <div class="box-body">
            <div class="col-sm-9">
                <form class="form-horizontal frm-create-send" action="{{ route("admin.delivery.send.store") }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="control-label col-sm-2 no-padding">テスト配信</label>
                            <div class="col-sm-10">
                                <label><input type="checkbox" name="test_flg" value="1">特定ユーザのみに配信</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 no-padding">タイトル</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="" maxlength="255">
                            <p class="error-title hidden"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 no-padding" for="">配信日時</label>
                        <div class="col-sm-10 no-padding">
                            <div class="col-sm-3">
                                <input type="text" id="date" class="form-control text-center" value="{{ old('date') ? old('date') : date('Y-m-d') }}" name="date" placeholder="YYYY 年 MM 月 DD 日"/>
                            </div>
                            <div class="col-sm-3 input-group bootstrap-timepicker">
                                <input type="text" id="time" name="time" class="form-control text-center" placeholder="hh : mm" value="{{ old('time') }}">
                            </div>
                        </div>
                        <p class="col-sm-10 col-sm-push-2 error-date-time" style="display: none;color: red;"></p>
                        <input type="hidden" id="check-date">
                    </div>
                    <div class="file-upload-msg form-group">
                        <label class="control-label col-sm-2 no-padding">配信メッセージ</label>
                        <div class="col-sm-10">
                            <div class="file-loading form-control">
                                <input id="file-upload1" name="filemsgsend" type="file" accept=".mp3" class="form-control">
                            </div>
                        </div>
                        <div id="error-upload-1" style="margin-top:10px;display:none"></div>
                        <input type="hidden" id="action-url" value="">
                    </div>
                    <input type="hidden" id="nameURL1">
                    <input type="hidden" id="nameURL2">
                    <div class="file-upload-msg form-group">
                        <label class="control-label col-sm-2 no-padding">非応答メッセージ</label>
                        <div class="col-sm-10">
                            <div class="file-loading form-control">
                                    <input id="file-upload2" name="filemsgnoans" type="file" accept=".mp3" class="form-control">
                            </div>
                        </div>
                        <div id="error-upload-2" style="margin-top:10px;display:none"></div>
                        <input type="hidden" id="action-url" value="">
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <a href="{{ route('admin.delivery.list') }}" class="btn btn-default btn-cancel">{{trans('message.btn-back')}}</a>
                            <button type="button" class="btn btn-primary btn-create">アップロード</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-3">

            </div>
        </div>
    </div>
    <div class="box box-primary div-confirm-create-send" style="display: none">
        <div class="box-header">
            <h3>配信メッセージ登録</h3>
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
                        <p class="text-right">配信日時</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding ">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="schedule-confirm"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="text-right">配信メッセージ</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding ">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="sound-send-confirm"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="text-right">非応答メッセージ</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding ">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="sound-no-answer-confirm"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="rơw">
                <div class="col-sm-3 col-xm-3 col-xs-5">
                    <a class="btn btn-default btn-md pull-right cancle-confirm-send">{{trans('message.btn-cancel')}}</a>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-7">
                    <input type="submit" class="btn btn-primary btn-md btn-custom btn-confirm-create btn-loading col-lg-7" value="保存">
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
    <script src="{{ asset('/admin/js/send.js') }}"></script>
    <script>
        $(document).ready(function(){
            var actionUrl = $('#action-url').val();

            $("#file-upload1").fileinput({
                language: "ja",
                uploadAsync: false,
                theme: "fa",
                showPreview: true,
                showUpload: false,
                // uploadUrl: actionUrl,
                allowedFileExtensions: ["mp3"],
                maxFileSize: 300000,
                elErrorContainer: '#error-upload-1'
            }).on('filebatchuploadsuccess', function(event, data) {

            });
            $("#file-upload2").fileinput({
                language: "ja",
                uploadAsync: false,
                theme: "fa",
                showPreview: true,
                showUpload: false,
                // uploadUrl: actionUrl,
                allowedFileExtensions: ["mp3"],
                maxFileSize: 300000,
                elErrorContainer: '#error-upload-2'
            }).on('filebatchuploadsuccess', function(event, data) {

            });
            $('.file-caption-name').prop('readonly', true);
        });
    </script>
@endsection

