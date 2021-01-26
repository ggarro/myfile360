@extends('common::framework')

@section('progressive-app-tags')
	<link rel="manifest" href="client/manifest.json">
	<meta name="theme-color" content="#1976d2">
@endsection

@section('angular-styles')
    {{--angular styles begin--}}
		<link rel="stylesheet" href="client/styles.ad79e8d05c75455030dd.css">
	{{--angular styles end--}}
@endsection

@section('angular-scripts')
    {{--angular scripts begin--}}
		<script src="client/runtime-es2015.3a6fb1582d90a50c6f51.js" type="module"></script>
		<script src="client/runtime-es5.3a6fb1582d90a50c6f51.js" nomodule defer></script>
		<script src="client/polyfills-es5.fcf14f22db72d103a477.js" nomodule defer></script>
		<script src="client/polyfills-es2015.cf8a40a25e05e5019c0d.js" type="module"></script>
		<script src="client/main-es2015.f4fe1dd9f411efb7a8fb.js" type="module"></script>
		<script src="client/main-es5.f4fe1dd9f411efb7a8fb.js" nomodule defer></script>
	{{--angular scripts end--}}
@endsection
