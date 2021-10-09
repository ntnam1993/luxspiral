@extends('adminlte::page')

@section('title', 'アプリ バージョン管理')

@section('content_header')
    <style>
        span{
            margin-right: 10px;
        }
    </style>
@stop

@section('content')
    <div class="box box-primary div-create-faq">
        <div class="box-header">
            <h3>アプリ バージョン管理</h3>
        </div>
        <div class="box-body">
            @if(Session::has('error'))
                <div class="alert alert-danger" >
                    <strong></strong>{!! Session::get('error') !!}
                </div>
            @elseif(Session::has('success'))
                <div class="alert alert-success" >
                    <strong></strong>{!! Session::get('success') !!}
                </div>
            @endif
            <div class="col-sm-12">
                <h4>&#9632; Android</h4>
            </div>
            <div class="col-sm-11 col-sm-offset-1">
                <span for="">現在のアプリバージョン</span><span for="">:</span><label>{{$appVersion->android_version}}</label>
            </div>
            <div class="col-sm-11 col-sm-offset-1">
                <div class="col-sm-3 no-padding">
                    <input type="text" class="form-control android_version" onkeyup="check()">
                </div>
                <div class="col-sm-4">
                    <a href="" class="btn btn-primary load-modal" data-toggle="modal" data-target="#confirm">更新</a>
                </div>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">アラート</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>アプリバージョンを更新します。 よろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{route('admin.appVersion.update',$appVersion->id)}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="android_version">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-primary">更新する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function(){
            check();
            $('input[name=android_version]').on('keyup',function () {
                check();
            });
            $('.load-modal').on('click',function () {
                console.log($('.android_version').val());
                $('input[name=android_version]').val($('.android_version').val());
            })
        });
        function check() {
            if ( $('.android_version').val() != '' ) {
                $('.load-modal').prop('disabled', false);
            }else{
                $('.load-modal').prop('disabled', true);
            }
        }
    </script>
@stop
