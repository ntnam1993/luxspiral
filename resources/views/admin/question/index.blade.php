@extends('adminlte::page')

@section('title', 'お知らせ一覧')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>お問い合わせ一覧</h3>
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
            <form class="row form-horizontal form-filter-data" action="{{route('admin.question.index')}}">
                <div class="col-xs-12 form-group">
                    <div class="col-sm-3">
                        <input type="text" name="name" class="form-control" placeholder="名前" value="{{ getValueSearch('name') }}">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="question" class="form-control" placeholder="キーワード" value="{{ getValueSearch('question') }}">
                    </div>
                    <button type="submit" class="btn btn-default btn-search">{{ trans('auth.search') }}</button>
                </div>
            </form>
        </div>
        <div class="box-body">
            <table id="" class="text-center table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-lg-1 dont-break-out">
                        <span data-sort="0">Id</span>
                    </th>
                    <th class="col-lg-2 dont-break-out">
                        <span data-sort="0">名前</span>
                    </th>
                    <th class="col-lg-3 dont-break-out">
                        <span data-sort="1">メールアドレス</span>
                    </th>
                    <th class="col-lg-1 dont-break-out">
                        <span data-sort="2">User_id</span>
                    </th>
                    <th class="col-lg-3 dont-break-out">
                        <span data-sort="3">送信日時</span>
                    </th>
                    <th class="col-lg-2 dont-break-out">
                        <span data-sort="3">操作</span>

                    </th>
                </tr>
                </thead>
                <tbody class="div-delete">
                @if( count($question) > 0 )
                    @foreach ( $question as $key => $value )
                        <tr>
                            <td class="dont-break-out">{{ $value->id }}</td>
                            <td class="dont-break-out">{{ $value->name }}</td>
                            <td class="dont-break-out">{{$value->email}}</td>
                            <td class="dont-break-out">{{$value->user_id}}</td>
                            <td class="dont-break-out">{{$value->created_at}}</td>
                            <td>
                                <a href="{{ route('admin.question.show', $value->id) }}" class="btn btn-primary">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">{{trans('message.no-data')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="box-body">
                <div class="pull-right">
                    @if( count($question) > 0 ) {{ $question->links() }} @endif
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
