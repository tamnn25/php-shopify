@extends('base')

@section('content')
    <!-- You are: (shop domain name) -->
    <nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
        <a class="navbar-brand" href="/">
            <img style="height: 20px;" src="https://i.ibb.co/xKbzN0N/490091-1.png">   
        </a>
        </div>
        <ul class="nav navbar-nav">
        <li class="active"><a href="#">Product</a></li>
        </ul>
    </div>
    </nav>
  
    @include('table_homepage')

@endsection

