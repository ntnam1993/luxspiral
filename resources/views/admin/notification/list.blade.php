@extends('adminlte::page')

@section('title', 'お知らせ一覧')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>お知らせ一覧</h3>
            @if(Session::has('error'))
                <div class="alert alert-danger" >
                    <strong></strong>{!! Session::get('error') !!}
                </div>
            @elseif(Session::has('success'))
                <div class="alert alert-success" >
                    <strong></strong>{!! Session::get('success') !!}
                </div>
            @endif
        </div>
        {{-- <div class="box-body table-responsive "> --}}
        <div class="box-body">
            <form class="row form-horizontal form-filter-data" action="{{route('admin.notification.index')}}">
                <div class="col-xs-12 form-group">
                    <div class="col-sm-4">
                        <input type="text" name="keySearch" class="form-control" placeholder="キーワード" value="{{ getValueSearch('keySearch') }}">
                    </div>
                    <button type="submit" class="btn btn-default btn-search">{{ trans('auth.search') }}</button>
                </div>
            </form>
            <div class="pull-right">
                <a href="{{route('admin.notification.create')}}" class="btn btn-primary btn-create">お知らせ新規登録</a>
            </div>
        </div>
        <div class="box-body">
            <table id="" class="text-center table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-lg-1 dont-break-out">
                            <span data-sort="0">配信</span>
                        </th>
                        <th class="col-lg-3 dont-break-out">
                            <span data-sort="1">配信日時</span>
                        </th>
                        <th class="col-lg-6 dont-break-out">
                            <span data-sort="2">タイトル</span>
                        </th>
                        <th class="col-lg-2 dont-break-out">
                            <span data-sort="3">操作</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="div-delete">
                    @if( count($notification) > 0 )
                        @foreach ( $notification as $value )
                            <tr>
                                <td class="dont-break-out"><input type="checkbox" {{checkDateActive($value->schedule)}} disabled></td>
                                <td class="dont-break-out">{{ formartTime($value->schedule) }}</td>
                                <td class="dont-break-out">{{$value->title}}</td>
                                <td>
                                    @if( $value->schedule < date("Y-m-d H:i:s"))
                                        <a href="{{ route('admin.notification.detail', $value->id) }}" class="btn btn-primary">詳細</a>
                                    @else
                                        <a href="{{route('admin.notification.edit',$value->id)}}" class="btn btn-success btn-sm btn-edit">編集</a>
                                        <a class="show-modal-delete btn btn-danger btn-sm" data-url="{{route('admin.notification.destroy',$value->id)}}" data-title="アラート" data-question="お知らせを削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">{{trans('message.no-data')}}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="box-body">
                <div class="pull-right">
                    @if( count($notification) > 0 ) {{ $notification->links() }} @endif
                </div>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
    @include('layouts.delete.form-delete')
@stop

@section('js')
    <script>
        $('.btn-create, .btn-cancel, .btn-confirm, .btn-edit' ).on('click', function(){
            localStorage.clear();
        });
    </script>
@stop
