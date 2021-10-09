<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<style>
#float-header {
    background-color:#FEEC67;
    height:40px;
    font-weight:bold;
    font-size:24px;
    padding-top:3px;
    position: fixed;
    top: 0;
    right:0;
    left: 0;
    z-index: 999999999;
    transform: translate3d(0, 0, 0);
}
.dont-break-out {

    /* These are technically the same, but use both */
    overflow-wrap: break-word;
    word-wrap: break-word;

    -ms-word-break: break-all;
    /* This is the dangerous one in WebKit, as it breaks things wherever */
    word-break: break-all;
    /* Instead use this non-standard one: */
    word-break: break-word;

    /* Adds a hyphen where the word breaks, if supported (No Blink) */
    -ms-hyphens: auto;
    -moz-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;

}
</style>
</head>
<body style="padding-top: 10px;">

{{--<div class="text-center" id="float-header">--}}
    {{--<span class="glyphicon glyphicon-question-sign" style="vertical-align:-2px;"> </span> FAQ</div>--}}
<div class="container">
  <div class="panel-group" id="accordion">
    @foreach($faq as $key => $value)
      <div class="panel panel-default">
        <div class="panel-heading" style="background-color:#FEEC67;">
          <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key+1}}" class="panel-title expand dont-break-out">
            <div class="right-arrow pull-right"></div>
            <a href="#">{{$value->title}}</a>
          </h4>
        </div>
        <div id="collapse{{$key+1}}" class="panel-collapse dont-break-out collapse">
          <div class="panel-body" style="white-space: pre-wrap;">{{$value->content}}</div>
        </div>
      </div>
    @endforeach
  </div> 
</div>

</body>
</html>
