<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:membership_categories,id'],
            'level' => ['nullable', 'integer', 'exists:membership_levels,id'],
        ]);

        $members = Member::query()
            ->with(['category', 'level'])
            ->when($filters['search'] ?? null, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('full_name', 'like', '%'.$search.'%')
                        ->orWhereHas('category', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                        ->orWhereHas('level', fn ($query) => $query->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('membership_category_id', $category))
            ->when($filters['level'] ?? null, fn ($query, $level) => $query->where('membership_level_id', $level))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('members.index', [
            'members' => $members,
            'categories' => MembershipCategory::orderBy('name')->get(),
            'levels' => MembershipLevel::orderBy('name')->get(),
            'totalMembers' => Member::count(),
            'activeMembers' => Member::where('is_active', true)->count(),
            'inactiveMembers' => Member::where('is_active', false)->count(),
        ]);
    }

    public function create(): View
    {
        return view('members.create', [
            'categories' => MembershipCategory::orderBy('name')->get(),
            'levels' => MembershipLevel::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $member = Member::create($this->validatedMember($request));

        return redirect()->route('members.show', $member)->with('success', 'Member added successfully.');
    }

    public function show(Member $member): View
    {
        return view('members.show', ['member' => $member->load(['category', 'level'])]);
    }

    public function edit(Member $member): View
    {
        return view('members.edit', [
            'member' => $member,
            'categories' => MembershipCategory::orderBy('name')->get(),
            'levels' => MembershipLevel::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $member->update($this->validatedMember($request, $member));

        return redirect()->route('members.show', $member)->with('success', 'Member details updated.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->update(['is_active' => false]);

        return redirect()->route('members.index')->with('success', 'Member deactivated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_ids' => ['required', 'array', 'min:1', 'max:10'],
            'member_ids.*' => ['required', 'integer', 'distinct', 'exists:members,id'],
        ]);

        if (Member::whereIn('id', $validated['member_ids'])->whereHas('transactions')->exists()) {
            return back()->withErrors(['member_ids' => 'Selected members with transaction history cannot be deleted. No members were deleted.']);
        }

        DB::transaction(function () use ($validated): void {
            Member::whereIn('id', $validated['member_ids'])->delete();
        });

        return redirect()->route('members.index')->with('success', count($validated['member_ids']).' '.Str::plural('member', count($validated['member_ids'])).' deleted.');
    }

    public function activate(Member $member): RedirectResponse
    {
        $member->update(['is_active' => true]);

        return redirect()->route('members.show', $member)->with('success', 'Member reactivated.');
    }

    public function permanentlyDestroy(Member $member): RedirectResponse
    {
        if ($member->transactions()->exists()) {
            return back()->withErrors(['member' => 'This member has transaction history and cannot be permanently deleted.']);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member permanently deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedMember(Request $request, ?Member $member = null): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('members', 'email')->ignore($member)],
            'phone' => ['nullable', 'string', 'max:50'],
            'membership_category_id' => ['required', 'integer', 'exists:membership_categories,id'],
            'membership_level_id' => ['required', 'integer', 'exists:membership_levels,id'],
            'joined_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
