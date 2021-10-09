@extends('adminlte::page')

@section('title', 'メッセージ一覧')

@section('content_header')
@stop

@section('content')
    <div class="lds-ellipsis" style="display: none;">
        <img src="/calling.gif" style="width: 100%">
    </div>
    <style>
        .lds-ellipsis {
            display: inline-block;
            width: 64px;
            height: 64px;
            left: 6%;
            bottom: 10%;
            z-index: 999;
            position: fixed;
        }
    </style>
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
            <div class="alert alert-success hidden show-call-success" >
                <strong></strong>
            </div>
        </div>
        <div class="box-body show-list-delivery div-delete">
            <table id="" class="datatable-style text-center table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-lg-2">
                        <span data-sort="2">テスト配信</span>
                    </th>
                    <th class="col-lg-4">
                        <span data-sort="2">タイトル</span>
                    </th>
                    <th class="col-lg-2 ">
                        <span data-sort="1">配信日時</span>
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
                        <td>@php echo $delivery->test_flg ? 'テスト配信' : '' @endphp </td>
                        <td>{{ $delivery->title }}</td>
                        <td>{{ formartTime($delivery->schedule) }}</td>
                        <td>{{ formartTime($delivery->created_at) }}</td>
                        <td>
                            {{--<a href="{{ route('admin.call.startCall050', $delivery->id )}}" class="btn btn-success btn-edit delivery-call">Call</a>--}}
                            <a class="btn btn-success btn-edit delivery-call" data-url={{route('call050',$delivery->id)}}>Call</a>
                        </td>
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

        <div id="addNayoseModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style="padding: 20px; max-width: 500px;">
                    <div class="modal-body">
                        <h4 class="text-left">Warning!</h4>
                        <div class="bg-yellow-custom padding-5px margin-bottom-7px">
                            <div class="padding-7px text-center">
                                <span class="text-bold">Do you want Call?</span>
                            </div>
                        </div>

                        <div class="row">
                            <button class="btn btn-primary btn-cancel back-compare">キャンセル</button>
                            <a class="btn btn-primary startCall050 pull-right">発信する</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer"></div>
    </div>
    @include('layouts.delete.form-delete')
@stop
@section('js')
    <script>
        $('.begin-call').on('click', function(e) {

            var _url = $('input[name="actionCall"]').val();
            $('#addNayoseModal').modal('hide');
            {{--$.ajax({--}}
                {{--url: _url,--}}
                {{--type: "POST",--}}
                {{--data: {--}}
                    {{--"_token": "{{ csrf_token() }}"--}}
                {{--}--}}
            {{--}).fail(function (xhr, status, thrown) {--}}
                {{--alert('error');--}}
            {{--}).done(function (data) {--}}
                {{--data = JSON.parse(data);--}}
                {{--ajaxCall(data, 0);--}}
            {{--});--}}
        });
        $('.startCall050').on('click',function () {
            var url    = $(this).data('url');
            console.log(url);
            var method = 'get';
            var data   = {};
            $('#addNayoseModal').modal('hide');
            $('.modal-content').modal('hide');
            $('.show-call-success').removeClass('hidden');
            $('.show-call-success strong').html('Call Success');
            httpGet(url);
        });

        function httpGet(theUrl)
        {
            // var xmlHttp = new XMLHttpRequest();
            // xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
            // xmlHttp.send( null );
            // return xmlHttp.responseText;
            $.ajax({
                url: theUrl,
            }).done(function() {
                //$( this ).addClass( "done" );
            });
        }

        $('.delivery-call').on('click', function(e) {
            e.preventDefault();
            $('#addNayoseModal').modal('show');
            var _url = $(this).data('url');
            // $('input[name="actionCall"]').val(_url);
            $('.startCall050').data('url',_url);
        });

        $('.back-compare').on('click', function(e) {
            e.preventDefault();
            $('#addNayoseModal').modal('hide');
        });

        {{--function ajaxCall(data, $i) {--}}
            {{--$('.lds-ellipsis').show();--}}
            {{--if( $i >= data.data.length ) {--}}
                {{--$('.lds-ellipsis').hide();--}}
                {{--return alert('Call Done');--}}
            {{--}--}}

            {{--$.ajax({--}}
                {{--url: '/admin/call/delivery',--}}
                {{--type: "POST",--}}
                {{--data: {--}}
                    {{--"_token": "{{ csrf_token() }}",--}}
                    {{--"url"   : data.url,--}}
                    {{--"tels"  : data.data[$i]--}}
                {{--}--}}
            {{--}).fail(function (xhr, status, thrown) {--}}
                {{--$i = $i + 1;--}}
                {{--ajaxCall(data, $i);--}}
            {{--}).done(function () {--}}
                {{--$i = $i + 1;--}}
                {{--ajaxCall(data, $i);--}}
            {{--});--}}
        {{--}--}}
    </script>
@stop
