@extends('layouts.app')

@section('content')
    <div class="container">
        <group-chat :group="{{$group}}"></group-chat>
    </div>
@endsection
