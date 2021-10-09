@extends('adminlte::page')

@section('title', 'お知らせ登録')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary div-create-faq">
        <div class="box-header">
            <h3>FAQ新規登録</h3>
        </div>
        <div class="box-body">
            <div class="col-sm-10 col-sm-offset-1">
                <form action="{{route('admin.faq.store')}}" method="post" role="form" class="form-horizontal frm-create-faq">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="">タイトル</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" id="" placeholder="" maxlength="255">
                            <p class="error-title hidden"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="">内容</label>
                        <div class="col-sm-10">
                            <textarea rows="20" cols="50" name="content" class="form-control" maxlength="3000">{{ old('content') }}</textarea>
                            <p class="error-content hidden"></p>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ URL::previous() }}" class="btn btn-default btn-lg btn-cancel">{{trans('message.btn-back')}}</a>
                        <button type="button" class="btn btn-primary btn-lg btn-create">{{trans('message.submit-form')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
    <div class="box box-primary div-create-confirm-faq" style="display: none">
        <div class="box-header">
            <h3>FAQ作成</h3>
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
                        <strong> <p class="title-confirm"></p></strong>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xm-3 col-xs-3 text-center no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <p class="text-right">内容</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9 text-left no-padding">
                    <div class="col-sm-12 col-xm-12 col-xs-12">
                        <strong><p class="content-confirm"></p></strong>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="col-sm-3 col-xm-3 col-xs-3">
                    <a class="btn btn-default btn-lg pull-right cancle-confirm-faq">{{trans('message.btn-back')}}</a>
                </div>
                <div class="col-sm-9 col-xm-9 col-xs-9">
                    <a class="btn btn-primary btn-lg btn-confirm-create pull-left">{{trans('message.btn-confirm')}}</a>
                </div>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
@stop

@section('js')
    <script src="{{ asset('/admin/js/faq.js') }}"></script>
    <script>
        setDisableButtonCreate();
    </script>
@stop
