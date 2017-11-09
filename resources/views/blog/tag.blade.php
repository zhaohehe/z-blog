@extends('blog.layouts.frame')

@section('content')
    <blog-tag :tag="{{ $tag }}"></blog-tag>
@endsection