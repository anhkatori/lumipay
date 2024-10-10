@extends('admintheme::layouts.main')

@section('title', '404 Error Page')

@section('content')
<div class="error-page text-center">
    <h2 class="headline text-warning"> 404</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
        <p>
        We could not find the page you were looking for.
        Meanwhile, you may <a href="{{route('admin.dashboard.index')}}">return to dashboard</a> or try using the search form.
        </p>
    </div>
<input value='aaa' id="test" hidden>
</div>
<script  type="module">
    console.log($('#test').val());
</script>

@endsection