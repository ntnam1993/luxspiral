@extends('adminlte::page')

@section('title', 'お知らせ一覧')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>お知らせ一覧</h3>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="row "><h4 class="text-bold">お問い合わせ情報</h4></div>
                <br>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">名前</label></div>
                    <div class="col-sm-8"><p>{{$question->name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">メールアドレス</label></div>
                    <div class="col-sm-8"><p>{{$question->email}}</p></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">お問い合わせ内容</label></div>
                    <div class="col-sm-8 dont-break-out white-space"><p>{!! $question->question !!}</p></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row"><h4 class="text-bold">ユーザー情報</h4></div>
                <br>
                <div class="row">
                    <div class="row">
                        <div class="col-sm-4"><label for="name" class="pull-right">端末名</label></div>
                        <div class="col-sm-8"><p>{{$question->user->device_name}}</p></div>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-sm-4"><label for="name" class="pull-right">OSバージョン</label></div>
                        <div class="col-sm-8"><p>{{$question->user->device_os}}</p></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">課金有効期間</label></div>
                    <div class="col-sm-8"><p>{{$question->user->expired}}</p></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">アプリインストール日時</label></div>
                    <div class="col-sm-8"><p>{{$question->user->created_at}}</p></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><label for="name" class="pull-right">電話認証状況</label></div>
                    <div class="col-sm-8"><p>@php echo ($question->user->verify_status == 1 )? '認証済み' : '未認証' @endphp</p></div>
                </div>
            </div>
        </div>
        <div class="box-footer text-center">
            <a class="btn btn-default" href="{{route('admin.question.index')}}">{{trans('message.btn-back')}}</a>
        </div>
    </div>
@stop

@section('js')
    <script>

    </script>
@stop
