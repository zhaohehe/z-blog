@extends('blog.layouts.frame')

@section('content')

    <blog-home :auth="{{ \Illuminate\Support\Facades\Auth::user() ? '0' : '0'}}"></blog-home>
@endsection
@section('style')
@endsection