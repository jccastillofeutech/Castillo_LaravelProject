@extends('members-layout')

@section('title', 'Edit member')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Members / Edit</p><h1>Edit member</h1><p>Update {{ $member->full_name }}'s profile.</p></div></div>
    <section class="panel form-panel">@include('members.form')</section>
@endsection
