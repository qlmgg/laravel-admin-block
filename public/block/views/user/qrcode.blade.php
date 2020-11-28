@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">

@endsection

@section('appcontent')
    <div class="page">
        <center style="position:relative;top:3rem">
            <img src="{{$image}}" alt="">
        </center>
    </div>
@endsection
@section('appfooter')
@endsection
