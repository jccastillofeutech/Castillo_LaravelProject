@extends('members-layout')

@section('title', 'Categories')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Membership settings</p><h1>Categories</h1><p>Group members by the type of membership they hold.</p></div></div>
    <div class="settings-layout">
        <section class="panel"><h2>Add category</h2><form action="{{ route('categories.store') }}" method="POST">@csrf<div class="field"><label for="new-category-name">Category name</label><input id="new-category-name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="e.g. Student"></div><button class="button button-primary" type="submit">Add category</button></form></section>
        <section class="panel"><div class="section-heading"><h2>Available categories</h2><span class="muted">{{ $categories->count() }} total</span></div>
            @forelse ($categories as $category)
                <div class="setting-card"><form class="setting-form" action="{{ route('categories.update', $category) }}" method="POST">@csrf @method('PUT')<div class="field"><label for="category-{{ $category->id }}">Name · {{ $category->members_count }} {{ Illuminate\Support\Str::plural('member', $category->members_count) }}</label><input id="category-{{ $category->id }}" name="name" value="{{ $category->name }}" required maxlength="255"></div><button class="button button-outline" type="submit">Save</button></form><form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">@csrf @method('DELETE')<button class="text-button danger-text" type="submit" @disabled($category->members_count > 0)>Delete</button></form></div>
            @empty
                <div class="empty-state"><h3>No categories yet</h3><p>Add one to get started.</p></div>
            @endforelse
        </section>
    </div>
@endsection
