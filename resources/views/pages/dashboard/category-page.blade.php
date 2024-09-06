@extends('layout.sidenav-layout')
@section('content')
    @include('components.category.category-list')
    @include('components.category.category-delete')
    @include('components.category.category-create')
    @include('components.category.category-update')
@endsection
<!--
 Data Show করার জন্য jQuery একটি library use করা হয়েছে  
 
 ১। data table (GS)
    https://datatables.net/
 -->