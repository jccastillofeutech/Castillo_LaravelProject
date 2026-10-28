<?php

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('members can be created, viewed, edited, deactivated, and reactivated', function () {
    $category = MembershipCategory::factory()->create(['name' => 'Student']);
    $level = MembershipLevel::factory()->create(['name' => 'Gold']);

    $this->get(route('members.create'))->assertOk()->assertSee('Add a member');

    $this->post(route('members.store'), [
        'full_name' => 'Jane Santos',
        'email' => 'jane@example.com',
        'phone' => '09123456789',
        'membership_category_id' => $category->id,
        'membership_level_id' => $level->id,
        'joined_at' => '2026-10-28',
        'notes' => 'New member',
    ])->assertRedirect();

    $member = Member::where('email', 'jane@example.com')->firstOrFail();
    $this->get(route('members.show', $member))->assertOk()->assertSee('Jane Santos')->assertSee('Gold');

    $this->put(route('members.update', $member), [
        'full_name' => 'Jane Reyes',
        'email' => 'jane@example.com',
        'membership_category_id' => $category->id,
        'membership_level_id' => $level->id,
        'joined_at' => '2026-10-28',
    ])->assertRedirect(route('members.show', $member));

    expect($member->fresh()->full_name)->toBe('Jane Reyes');

    $this->delete(route('members.destroy', $member))->assertRedirect(route('members.index'));
    expect($member->fresh()->is_active)->toBeFalse();

    $this->patch(route('members.activate', $member))->assertRedirect(route('members.show', $member));
    expect($member->fresh()->is_active)->toBeTrue();
});

test('member directory searches names, categories, and levels', function () {
    $student = MembershipCategory::factory()->create(['name' => 'Student']);
    $corporate = MembershipCategory::factory()->create(['name' => 'Corporate']);
    $basic = MembershipLevel::factory()->create(['name' => 'Basic']);
    $platinum = MembershipLevel::factory()->create(['name' => 'Platinum']);
    Member::factory()->create(['full_name' => 'Ana Cruz', 'membership_category_id' => $student->id, 'membership_level_id' => $basic->id]);
    Member::factory()->create(['full_name' => 'Ben Lopez', 'membership_category_id' => $corporate->id, 'membership_level_id' => $platinum->id]);

    $this->get(route('members.index', ['search' => 'Ana']))->assertOk()->assertSee('Ana Cruz')->assertDontSee('Ben Lopez');
    $this->get(route('members.index', ['search' => 'Corporate']))->assertOk()->assertSee('Ben Lopez')->assertDontSee('Ana Cruz');
    $this->get(route('members.index', ['search' => 'Platinum']))->assertOk()->assertSee('Ben Lopez')->assertDontSee('Ana Cruz');
    $this->get(route('members.index', ['category' => $student->id, 'level' => $basic->id]))->assertOk()->assertSee('Ana Cruz')->assertDontSee('Ben Lopez');
});

test('categories and levels can be managed without deleting those in use', function () {
    $this->post(route('categories.store'), ['name' => 'Senior Citizen'])->assertRedirect(route('categories.index'));
    $category = MembershipCategory::where('name', 'Senior Citizen')->firstOrFail();
    $this->put(route('categories.update', $category), ['name' => 'Regular'])->assertRedirect(route('categories.index'));

    $this->post(route('levels.store'), [
        'name' => 'Silver', 'fee' => 150, 'validity_days' => 365, 'benefits' => 'Priority support',
    ])->assertRedirect(route('levels.index'));
    $level = MembershipLevel::where('name', 'Silver')->firstOrFail();
    $this->get(route('levels.index'))->assertOk()->assertSee('Silver');

    Member::factory()->create(['membership_category_id' => $category->id, 'membership_level_id' => $level->id]);

    $this->delete(route('categories.destroy', $category))->assertSessionHasErrors('category');
    $this->delete(route('levels.destroy', $level))->assertSessionHasErrors('level');
    expect($category->fresh())->not->toBeNull();
    expect($level->fresh())->not->toBeNull();
});

test('member forms reject duplicate email and invalid membership choices', function () {
    $member = Member::factory()->create();

    $this->post(route('members.store'), [
        'full_name' => 'Another Member',
        'email' => $member->email,
        'membership_category_id' => 999999,
        'membership_level_id' => 999999,
        'joined_at' => '2026-10-28',
    ])->assertSessionHasErrors(['email', 'membership_category_id', 'membership_level_id']);
});
