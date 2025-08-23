@extends('user.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1 class="text-center">Xin chào, {{ Auth::user()->fullname ?? Auth::user()->email }}!</h1>

        <!-- User Info Section -->
        <div class="row">
               <marquee behavior="" direction="">Chúc bạn một ngày mua sắm thật vui vẻ </marquee> 
        </div>
    </div>
@endsection
