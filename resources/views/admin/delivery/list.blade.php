@extends('adminlte::page')

@section('title', 'メッセージ一覧')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>メッセージ一覧</h3>
            @if(Session::has('error'))
                <div class="alert alert-danger" >
                    <strong>{!! Session::get('error') !!}</strong>
                </div>
            @elseif(Session::has('success'))
                <div class="alert alert-success" >
                    <strong>{!! Session::get('success') !!}</strong>
                </div>
            @endif
        </div>
        <div class="box-body">
            <form class="row form-horizontal form-filter-data" action="{{ route('admin.delivery.list') }}">

                <div class="col-xs-12 form-group">
                    <div class="col-sm-4">
                        <input type="text" name="titleSearch" class="form-control" value="{{ getValueSearch('titleSearch') }}" placeholder="キーワード">
                    </div>
                    <button type="submit" class="btn btn-default btn-search">{{ trans('auth.search') }}</button>
                </div>
            </form>
            <div class="pull-right">
                <a href="{{ route("admin.delivery.send.create") }}" class="btn btn-primary btn-create-new">配信メッセージ新規登録</a>
            </div>
        </div>
        <div class="box-body show-list-delivery div-delete">
            <table id="" class="datatable-style text-center table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-lg-1 ">
                        <span data-sort="0">配信</span>
                    </th>
                    <th class="col-lg-2 ">
                        <span data-sort="1">配信日時</span>
                    </th>
                    <th class="col-lg-5">
                        <span data-sort="2">タイトル</span>
                    </th>
                    <th class="col-lg-2">
                        <span data-sort="2">登録日時</span>
                    </th>
                    <th class="col-lg-2">
                        <span data-sort="3">操作</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(count($listDeliveries) > 0)
                @foreach($listDeliveries as $delivery)
                    <tr>
                        <td><input type="checkbox" {{checkDateActive($delivery->schedule)}} disabled></td>
                        <td>{{ formartTime($delivery->schedule) }}</td>
                        <td>{{ $delivery->title }}</td>
                        <td>{{ formartTime($delivery->created_at) }}</td>
                        @if( $delivery->schedule < date("Y-m-d H:i:s"))
                            <td>
                                <a href="{{ route('admin.delivery.send.detail', $delivery->id) }}" class="btn btn-primary">詳細</a>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('admin.delivery.send.edit', $delivery->id )}}" class="btn btn-success btn-edit">編集</a>
                                <a class="show-modal-delete btn btn-danger" data-url="{{ route("admin.delivery.send.delete") }}" data-id="{{ $delivery->id }}" data-title="アラート" data-question="メッセージを削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="5">{{trans('message.no-data')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>

            <br>
            <br>

        </div>
        <div class="box-body">
            <div class="pull-right paginate-delivery">
                @if ($listDeliveries->hasPages())
                    {{$listDeliveries->appends(['listDeli' => $listDeliveries->currentPage()])->links()}}
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="pull-right">
                <a href="{{ route('admin.delivery.answer.add') }}" class="btn btn-primary btn-create-new">応答メッセージ登録 / 編集</a>
            </div>
        </div>
        <div class="box-body show-list-answer div-delete">
            <table id="" class="datatable-style text-center table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-lg-10 ">
                        <span data-sort="0">タイトル</span>
                    </th>
                    <th class="col-lg-2 ">
                        <span data-sort="1">操作</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(count($listAns) > 0)
                    @foreach($listAns as $answer)
                        <tr>
                            <td>{{ $answer->title }}</td>
                            <td>
                                <a href="{{ route("admin.delivery.answer.edit", $answer->id) }}" class="btn btn-success btn-sm btn-edit">詳細</a>
                                <a class="show-modal-delete btn btn-danger btn-sm" data-url="{{ route("admin.delivery.answer.delete") }}" data-id="{{ $answer->id }}" data-title="アラート" data-question="メッセージを削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">{{trans('message.no-data')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="box-body">
            <div class="pull-right paginate-answer">
                @if ($listAns->hasPages())
                    {{$listAns->appends(['listAns' => $listAns->currentPage()])->links()}}
                @endif
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
    @include('layouts.delete.form-delete')
@stop
@section('js')
    <script>
        $('.paginate-answer').on('click','a.page-link', function(e){
            e.preventDefault();
            var url    = $(this).attr('href');
            var method = 'get';
            var data   = '';
            callAjax(url,method,data,function(data){
                $('.show-list-answer tbody').html(data.listAns);
                $('.paginate-answer').html(data.paginate);
            });
            window.history.pushState("", "", url);
        });

        $('.paginate-delivery').on('click','a.page-link', function(e){
            e.preventDefault();
            var url    = $(this).attr('href');
            var method = 'get';
            var data   = '';
            callAjax(url,method,data,function(data){
                $('.show-list-delivery tbody').html(data.listDeliveries);
                $('.paginate-delivery').html(data.paginate);
            });
            window.history.pushState("", "", url);
        });
    </script>
@stop

