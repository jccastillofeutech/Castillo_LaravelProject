@extends('members-layout')

@section('title', $member->full_name)

@section('content')
    <div class="page-heading profile-heading">
        <div><p class="eyebrow">Member profile</p><h1>{{ $member->full_name }}</h1><span class="status {{ $member->is_active ? 'active' : 'inactive' }}">{{ $member->is_active ? 'Active member' : 'Inactive member' }}</span></div>
        <a class="button button-primary" href="{{ route('members.edit', $member) }}">Edit details</a>
    </div>
    <div class="profile-grid">
        <section class="panel"><h2>Personal details</h2><dl class="details"><div><dt>Email</dt><dd>{{ $member->email }}</dd></div><div><dt>Phone</dt><dd>{{ $member->phone ?: 'Not provided' }}</dd></div><div><dt>Joined</dt><dd>{{ $member->joined_at->format('F j, Y') }}</dd></div><div><dt>Notes</dt><dd class="notes">{{ $member->notes ?: 'No notes added.' }}</dd></div></dl></section>
        <section class="panel"><h2>Membership</h2><dl class="details"><div><dt>Category</dt><dd>{{ $member->category->name }}</dd></div><div><dt>Level</dt><dd>{{ $member->level->name }}</dd></div><div><dt>Level fee</dt><dd>₱{{ number_format($member->level->fee, 2) }}</dd></div><div><dt>Validity</dt><dd>{{ $member->level->validity_days }} days</dd></div><div><dt>Benefits</dt><dd class="notes">{{ $member->level->benefits ?: 'No benefits listed.' }}</dd></div></dl></section>
    </div>
    <div class="profile-actions">
        <a class="text-link" href="{{ route('members.index') }}">← Back to members</a>
        @if ($member->is_active)
            <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Deactivate this member? Their profile will be kept.');">@csrf @method('DELETE')<button class="button button-danger" type="submit">Deactivate member</button></form>
        @else
            <form action="{{ route('members.activate', $member) }}" method="POST">@csrf @method('PATCH')<button class="button button-primary" type="submit">Reactivate member</button></form>
        @endif
    </div>
@endsection
