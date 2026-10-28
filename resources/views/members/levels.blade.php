@extends('members-layout')

@section('title', 'Levels')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Membership settings</p><h1>Levels</h1><p>Define fees, validity, and benefits for each plan.</p></div></div>
    <div class="settings-layout">
        <section class="panel"><h2>Add level</h2><form action="{{ route('levels.store') }}" method="POST">@csrf<div class="field"><label for="new-level-name">Level name</label><input id="new-level-name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="e.g. Basic"></div><div class="field"><label for="new-level-fee">Fee (₱)</label><input id="new-level-fee" name="fee" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('fee', '0.00') }}" required></div><div class="field"><label for="new-level-validity">Validity (days)</label><input id="new-level-validity" name="validity_days" type="number" min="1" max="36500" value="{{ old('validity_days', 365) }}" required></div><div class="field"><label for="new-level-benefits">Benefits <span class="optional">(optional)</span></label><textarea id="new-level-benefits" name="benefits" rows="3" maxlength="5000">{{ old('benefits') }}</textarea></div><button class="button button-primary" type="submit">Add level</button></form></section>
        <section class="panel"><div class="section-heading"><h2>Available levels</h2><span class="muted">{{ $levels->count() }} total</span></div>
            @forelse ($levels as $level)
                <div class="setting-card"><form action="{{ route('levels.update', $level) }}" method="POST">@csrf @method('PUT')<div class="section-heading"><h3>{{ $level->name }}</h3><span class="muted">{{ $level->members_count }} {{ Illuminate\Support\Str::plural('member', $level->members_count) }}</span></div><div class="form-grid"><div class="field"><label for="level-name-{{ $level->id }}">Name</label><input id="level-name-{{ $level->id }}" name="name" value="{{ $level->name }}" maxlength="255" required></div><div class="field"><label for="level-fee-{{ $level->id }}">Fee (₱)</label><input id="level-fee-{{ $level->id }}" name="fee" type="number" min="0" max="99999999.99" step="0.01" value="{{ $level->fee }}" required></div><div class="field"><label for="level-validity-{{ $level->id }}">Validity (days)</label><input id="level-validity-{{ $level->id }}" name="validity_days" type="number" min="1" max="36500" value="{{ $level->validity_days }}" required></div><div class="field full-width"><label for="level-benefits-{{ $level->id }}">Benefits</label><textarea id="level-benefits-{{ $level->id }}" name="benefits" rows="3" maxlength="5000">{{ $level->benefits }}</textarea></div></div><button class="button button-outline" type="submit">Save level</button></form><form action="{{ route('levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Delete this level?');">@csrf @method('DELETE')<button class="text-button danger-text" type="submit" @disabled($level->members_count > 0 || $level->transactions()->exists())>Delete level</button></form></div>
            @empty
                <div class="empty-state"><h3>No levels yet</h3><p>Add one to get started.</p></div>
            @endforelse
        </section>
    </div>
@endsection
