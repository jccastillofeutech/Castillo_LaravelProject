@extends('members-layout')

@section('title', 'Add member')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Members / New</p><h1>Add a member</h1><p>Set up a profile and choose a membership plan.</p></div></div>
    <section class="panel form-panel">
        @if ($categories->isEmpty() || $levels->isEmpty())
            <p>Create at least one <a href="{{ route('categories.index') }}">category</a> and <a href="{{ route('levels.index') }}">level</a> before adding members.</p>
        @else
            @include('members.form', ['member' => null])
        @endif
    </section>
@endsection
