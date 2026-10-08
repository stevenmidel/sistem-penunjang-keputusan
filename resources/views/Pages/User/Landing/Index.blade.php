@extends('layouts.user.app')

@section('title', 'LaundryFinder')

@section('content')

    @include('pages.user.landing.hero')
    @include('pages.user.landing.features')
    @include('pages.user.landing.PartnerLaundry')
    @include('pages.user.landing.cta')
    @include('pages.user.landing.Contact')

@endsection
