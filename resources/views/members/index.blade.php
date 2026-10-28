@extends('members-layout')

@section('title', 'Members')

@section('content')
    <section class="hero">
        <p class="eyebrow">Membership workspace</p>
        <div class="hero-row">
            <div>
                <h1>Members</h1>
                <p>Keep profiles, categories, and levels in one place.</p>
            </div>
            <a class="button button-light" href="{{ route('members.create') }}">+ Add member</a>
        </div>
        <span class="hero-orbit" aria-hidden="true"></span>
    </section>

    <section class="stats-grid" aria-label="Membership summary">
        <div class="stat-card"><span>Total members</span><strong>{{ $totalMembers }}</strong><small>All profiles</small></div>
        <div class="stat-card stat-blue"><span>Active</span><strong>{{ $activeMembers }}</strong><small>Current members</small></div>
        <div class="stat-card stat-dark"><span>Inactive</span><strong>{{ $inactiveMembers }}</strong><small>Deactivated profiles</small></div>
    </section>

    <section class="panel" aria-labelledby="member-directory-heading">
        <div class="section-heading">
            <div><p class="eyebrow">Directory</p><h2 id="member-directory-heading">Member directory</h2></div>
            <div class="directory-actions">
                <span class="muted">{{ $members->total() }} {{ Illuminate\Support\Str::plural('result', $members->total()) }}</span>
                @if ($members->isNotEmpty())
                    <button class="button button-outline selection-open" type="button" data-selection-open>Delete</button>
                @endif
            </div>
        </div>
        <form class="filters" method="GET" action="{{ route('members.index') }}" role="search">
            <div class="field search-field">
                <label for="search">Search name, category, or level</label>
                <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Search members...">
            </div>
            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="level">Level</label>
                <select id="level" name="level">
                    <option value="">All levels</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" @selected(request('level') == $level->id)>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button button-primary filter-button" type="submit">Search</button>
            @if (request()->filled('search') || request()->filled('category') || request()->filled('level'))
                <a class="button button-outline clear-link" href="{{ route('members.index') }}">Clear</a>
            @endif
        </form>

        @if ($members->isNotEmpty())
            <form id="bulk-member-form" action="{{ route('members.bulk-destroy') }}" method="POST" data-bulk-form>
                @csrf
                @method('DELETE')
                <div class="selection-toolbar" data-selection-toolbar hidden>
                    <div class="selection-scope"><label class="select-page-label"><input type="checkbox" data-select-page> <span>Select all {{ $members->count() }} on this page</span></label><small>Bulk actions are limited to this page.</small></div>
                    <span class="selection-count"><strong data-selected-count>0</strong> selected</span>
                    <div class="selection-buttons">
                        <button class="button button-danger" type="submit" data-bulk-delete disabled>Delete selected</button>
                        <button class="button button-outline" type="button" data-selection-cancel>Cancel</button>
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="member-table">
                        <thead>
                            <tr>
                                <th class="member-select-column"><span class="sr-only">Select</span></th>
                                <th scope="col">Member</th>
                                <th scope="col">Category</th>
                                <th scope="col">Level</th>
                                <th scope="col">Status</th>
                                <th scope="col"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr class="member-row" data-member-row>
                                    <td class="member-select-column"><label class="member-select" hidden aria-label="Select {{ $member->full_name }}"><input type="checkbox" name="member_ids[]" value="{{ $member->id }}" disabled data-member-checkbox><span class="sr-only">Select {{ $member->full_name }}</span></label></td>
                                    <td><div class="member-identity"><span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($member->full_name, 0, 1)) }}</span><div class="member-main"><a class="member-name" href="{{ route('members.show', $member) }}">{{ $member->full_name }}</a><span class="muted">{{ $member->email }}</span></div></div></td>
                                    <td><span class="membership-tag category-tag category-{{ Illuminate\Support\Str::slug($member->category->name) }}">{{ $member->category->name }}</span></td>
                                    <td><span class="membership-tag level-tag level-{{ Illuminate\Support\Str::slug($member->level->name) }}">{{ $member->level->name }}</span></td>
                                    <td><span class="member-state {{ $member->is_active ? 'active' : 'inactive' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    <td><a class="text-link member-profile-link" href="{{ route('members.show', $member) }}">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            <dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="bulk-confirm-title" aria-describedby="bulk-confirm-description">
                <div class="confirm-dialog-content">
                    <span class="confirm-icon" aria-hidden="true">!</span>
                    <h2 id="bulk-confirm-title">Delete selected members?</h2>
                    <p id="bulk-confirm-description">Review these profiles before continuing. Deletion is permanent.</p>
                    <ul class="confirm-member-list" data-confirm-members></ul>
                    <label class="confirm-phrase-label" for="confirm-delete-phrase">Type <strong>DELETE</strong> to confirm</label>
                    <input class="confirm-phrase-input" id="confirm-delete-phrase" type="text" autocomplete="off" data-confirm-phrase>
                    <div class="confirm-actions">
                        <button class="button button-outline" type="button" data-confirm-cancel>Cancel</button>
                        <button class="button button-danger" type="button" data-confirm-delete disabled>Delete <span data-confirm-count>0 members</span></button>
                    </div>
                </div>
            </dialog>
        @else
            <div class="empty-state"><span class="empty-icon">+</span><h3>No members found</h3><p>Try a different search or add your first member.</p></div>
        @endif

        @if ($members->hasPages())
            <nav class="pagination" aria-label="Member pages">
                @if ($members->onFirstPage())
                    <span class="pagination-disabled">Previous</span>
                @else
                    <a href="{{ $members->previousPageUrl() }}" rel="prev">Previous</a>
                @endif
                <span>Page {{ $members->currentPage() }} of {{ $members->lastPage() }}</span>
                @if ($members->hasMorePages())
                    <a href="{{ $members->nextPageUrl() }}" rel="next">Next</a>
                @else
                    <span class="pagination-disabled">Next</span>
                @endif
            </nav>
        @endif
    </section>
@endsection
