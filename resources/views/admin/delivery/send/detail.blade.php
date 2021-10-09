@extends('adminlte::page')

@section('title', '配信メッセージ詳細')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>配信メッセージ詳細</h3>
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
                        <p class=""><strong>{{$msgDeli->title}}</strong></p>
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
                        <p class=""><strong>{{ formartTime($msgDeli->schedule) }}</strong></p>
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
                        <p><strong>{{ $fineNameMsgAns }}</strong></p>
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
                        <p><strong>{{ $fineNameMsgNoAns }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="col-sm-6 text-center">
                <a href="{{URL::previous()}}" class="btn btn-default btn-lg">戻る</a>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{ asset('/admin/js/delivery.js') }}"></script>
@stop



