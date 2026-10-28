<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('members.categories', [
            'categories' => MembershipCategory::withCount('members')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MembershipCategory::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:membership_categories,name'],
        ]));

        return redirect()->route('categories.index')->with('success', 'Category added.');
    }

    public function update(Request $request, MembershipCategory $category): RedirectResponse
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('membership_categories', 'name')->ignore($category)],
        ]));

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(MembershipCategory $category): RedirectResponse
    {
        if ($category->members()->exists()) {
            return back()->withErrors(['category' => 'This category is in use and cannot be deleted.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
