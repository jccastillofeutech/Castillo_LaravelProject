<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        return view('members.levels', [
            'levels' => MembershipLevel::withCount('members')->orderBy('fee')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MembershipLevel::create($this->validatedLevel($request));

        return redirect()->route('levels.index')->with('success', 'Level added.');
    }

    public function update(Request $request, MembershipLevel $level): RedirectResponse
    {
        $level->update($this->validatedLevel($request, $level));

        return redirect()->route('levels.index')->with('success', 'Level updated.');
    }

    public function destroy(MembershipLevel $level): RedirectResponse
    {
        if ($level->members()->exists() || $level->transactions()->exists()) {
            return back()->withErrors(['level' => 'This level is in use and cannot be deleted.']);
        }

        $level->delete();

        return redirect()->route('levels.index')->with('success', 'Level deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedLevel(Request $request, ?MembershipLevel $level = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('membership_levels', 'name')->ignore($level)],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:36500'],
            'benefits' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
