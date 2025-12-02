@extends('web.layouts.app')
@section('contact')
    <div class="alert alert-danger text-center">
        <h3>{{ $message }}</h3>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">رجوع</a>
    </div>
@endsection
