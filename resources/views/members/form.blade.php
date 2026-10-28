<form id="member-form" action="{{ $member ? route('members.update', $member) : route('members.store') }}" method="POST">
    @csrf
    @if ($member)
        @method('PUT')
    @endif
    <div class="form-grid">
        <div class="field">
            <label for="full_name">Full name</label>
            <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $member?->full_name) }}" maxlength="255" required>
            @error('full_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $member?->email) }}" maxlength="255" required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="phone">Phone <span class="optional">(optional)</span></label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $member?->phone) }}" maxlength="50">
            @error('phone') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="joined_at">Join date</label>
            <input id="joined_at" name="joined_at" type="date" value="{{ old('joined_at', $member?->joined_at?->toDateString() ?? now()->toDateString()) }}" required>
            @error('joined_at') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="membership_category_id">Category</label>
            <select id="membership_category_id" name="membership_category_id" required>
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('membership_category_id', $member?->membership_category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('membership_category_id') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="membership_level_id">Level</label>
            <select id="membership_level_id" name="membership_level_id" required>
                <option value="">Select level</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}" @selected(old('membership_level_id', $member?->membership_level_id) == $level->id)>{{ $level->name }} · ₱{{ number_format($level->fee, 2) }}</option>
                @endforeach
            </select>
            @error('membership_level_id') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field full-width">
            <label for="notes">Notes <span class="optional">(optional)</span></label>
            <textarea id="notes" name="notes" rows="4" maxlength="2000" placeholder="Anything useful to remember about this member">{{ old('notes', $member?->notes) }}</textarea>
            @error('notes') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>
</form>
<div class="form-actions">
    <a class="button button-outline" href="{{ $member ? route('members.show', $member) : route('members.index') }}">Cancel</a>
    @if ($member)
        <form id="delete-member-form" action="{{ route('members.permanently-destroy', $member) }}" method="POST">@csrf @method('DELETE')<button class="button button-danger" type="button" data-single-delete-trigger>Delete member</button></form>
    @endif
    <button class="button button-primary" type="submit" form="member-form">{{ $member ? 'Save changes' : 'Add member' }}</button>
</div>
@if ($member)
    <dialog class="confirm-dialog" data-single-delete-dialog aria-labelledby="single-delete-title" aria-describedby="single-delete-description">
        <div class="confirm-dialog-content">
            <span class="confirm-icon" aria-hidden="true">!</span>
            <h2 id="single-delete-title">Delete this member?</h2>
            <p id="single-delete-description">This permanently removes <strong>{{ $member->full_name }}</strong>. The deletion cannot be undone, and members with transaction history cannot be deleted.</p>
            <div class="confirm-actions">
                <button class="button button-outline" type="button" data-single-delete-cancel>Cancel</button>
                <button class="button button-danger" type="button" data-single-delete-confirm>Delete permanently</button>
            </div>
        </div>
    </dialog>
@endif
