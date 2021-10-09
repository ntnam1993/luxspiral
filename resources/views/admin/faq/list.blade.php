@extends('adminlte::page')

@section('title', 'お知らせ一覧')

@section('content_header')
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3>FAQ一覧</h3>
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
            <form class="row form-horizontal form-filter-data" action="{{route('admin.faq.index')}}">
                <div class="col-xs-12 form-group">
                    <div class="col-sm-4">
                        <input type="text" name="title" class="form-control" placeholder="キーワード" value="{{ getValueSearch('title') }}">
                        <input type="hidden" id="changeDisplayOrder" data-url="{{ route('admin.changeDisplayOrder') }}">
                    </div>
                    <button type="submit" class="btn btn-default btn-search">{{ trans('auth.search') }}</button>
                </div>
            </form>
            <div class="pull-right">
                <a href="{{route('admin.faq.create')}}" class="btn btn-primary btn-create">FAQ新規登録</a>
            </div>
        </div>
        <div class="box-body">
            <table id="" class="text-center table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-lg-3 dont-break-out">
                        <span>タイトル</span>
                    </th>
                    <th class="col-lg-4 dont-break-out">
                        <span>内容</span>
                    </th>
                    <th class="col-lg-3 dont-break-out">
                        <span>順番</span>
                    </th>
                    <th class="col-lg-2 dont-break-out">
                        <span>操作</span>
                    </th>
                </tr>
                </thead>
                <tbody class="list-faq div-delete">
                @if( count($faq) > 0 )
                    @foreach ( $faq as $value )
                        <tr>
                            <td class="text-center dont-break-out">{{ $value->title }}</td>
                            <td class="text-center dont-break-out white-space">{!! $value->content !!}</td>
                            <td class="text-center displayOrder dont-break-out" data-id="{{$value->id}}">
                                @if(count($faq) > 1)
                                    @if ($loop->first)
                                        @php orderDisplayFirst() @endphp
                                    @elseif($loop->last)
                                        @php orderDisplayLast() @endphp
                                    @else
                                        @php orderDisplay() @endphp
                                    @endif
                                @else
                                    <span class="fa fa-ban fa-3x"></span>
                                @endif
                            <td class="text-center dont-break-out">
                                <a href="{{route('admin.faq.edit',$value->id)}}" class="btn btn-success btn-sm btn-edit">編集</a>
                                <a class="show-modal-delete btn btn-danger btn-sm" data-url="{{route('admin.faq.destroy',$value->id)}}" data-title="アラート" data-question="こちらの質問を削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
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
                   @if(isset($faq)) {{ $faq->links() }} @endif
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
        $('.list-faq').on('click','.fa-arrow-circle-down',function () {
            var id     = $(this).parent().data('id');
            var url    = $('#changeDisplayOrder').data('url');
            var method = 'get';
            var data   = {
                'key' : 'down',
                'id'  : id
            };
            callAjax(url, method, data, function (res) {
                $('.list-faq').html(res);
            });
        });
        $('.list-faq').on('click','.fa-arrow-circle-up',function () {
            var id     = $(this).parent().data('id');
            var url    = $('#changeDisplayOrder').data('url');
            var method = 'get';
            var data   = {
                'key' : 'up',
                'id'  : id
            };
            callAjax(url, method, data, function (res) {
                $('.list-faq').html(res);
            });
        });
    </script>
@stop
