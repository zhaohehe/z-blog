@extends('blog.layouts.frame')

@section('content')
    <blog-post :id="{{ $id }}"></blog-post>
@endsection