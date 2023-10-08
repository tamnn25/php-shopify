@extends('base')

@section('content')
    <!-- You are: (shop domain name) -->
    <nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
        <a class="navbar-brand" href="/">logo</a>
        </div>
        <ul class="nav navbar-nav">
        <li class="active"><a href="#">Product</a></li>
        </ul>
    </div>
    </nav>
  
    @include('table_homepage')

@endsection

<!-- @section('scripts')
<script>
    @if(session('X-Alert-Open-File'))
        alert('File downloaded successfully. Please check your downloads folder.');
    @endif
</script>
@endsection -->