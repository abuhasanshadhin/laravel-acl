<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $params = 'category-list|category-create|category-edit|category-delete';
        $this->middleware('permission:' . $params, ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $allCategory = $this->getCategoryTree()['items'];

        $categories = Category::select([
            'id', 'name', 'parent_id',
        ])->where('parent_id', 0)->latest()->get();

        return view('admin.category.index', compact('allCategory', 'categories'));
    }

    private function getCategoryTree($parentId = 0)
    {
        $_categories = Category::where('parent_id', $parentId)->latest()->get();
        $categories = [
            'total' => null,
            'items' => '',
        ];
        if ($_categories->count()) {
            $categories['total'] = $_categories->count();
            $categories['items'] .= '<ul ' . ($parentId ? 'class="nested"' : '') . '>';
            foreach ($_categories as $key => $category) {
                $hasChildren = $this->getCategoryTree($category->id);
                $categories['items'] .= '<li><span ' . ($hasChildren['items'] ? 'class="arrow"' : '') . '>';
                $categories['items'] .= $category->name . ($hasChildren['total'] ? ' (' . $hasChildren['total'] . ')' : '') . '</span>';
                $categories['items'] .= '<a href="' . route('admin.category.edit', $category->id) . '" class="ml-2">Edit</a>';
                $categories['items'] .= '<form action="' . route('admin.category.destroy', $category->id) . '" method="POST" class="d-inline ml-2">' . csrf_field();
                $categories['items'] .= '<input type="hidden" name="_method" value="DELETE">';
                $categories['items'] .= '<button type="submit" class="btn-delete" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                $categories['items'] .= '</form>';
                $categories['items'] .= $hasChildren['items'];
                $categories['items'] .= '</li>';
            }
            $categories['items'] .= '</ul>';
        }
        return $categories;
    }

    public function subCategories($id)
    {
        $categories = Category::select([
            'id', 'name', 'parent_id',
        ])->where('parent_id', $id)->latest()->get();

        return response()->json($categories);
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_cat_ids' => 'nullable|array',
            'category_name' => 'required|max:180',
        ]);

        $parentCatIds = $request->parent_cat_ids;
        $parentCatIds = array_filter($parentCatIds);

        if (count($parentCatIds) > 0) {
            Category::create([
                'name' => $request->category_name,
                'parent_id' => end($parentCatIds),
            ]);
        } else {
            Category::create([
                'name' => $request->category_name,
                'parent_id' => 0,
            ]);
        }

        session()->flash('success', 'Category added successfully');
        return redirect()->route('admin.category.index');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        $allCategory = $this->getCategoryTree()['items'];

        $categories = Category::select([
            'id', 'name', 'parent_id',
        ])->latest()->get();

        return view('admin.category.edit', compact('allCategory', 'categories', 'category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'parent_cat_id' => 'nullable',
            'category_name' => 'required|max:180',
        ]);

        $category->update([
            'name' => $request->category_name,
            'parent_id' => $request->parent_cat_id ?? 0,
        ]);

        session()->flash('success', 'Category updated successfully');
        return redirect()->route('admin.category.index');
    }

    public function destroy(Category $category)
    {
        $catName = $category->name;
        $subCats = Category::where('parent_id', $category->id)->get();
        if ($subCats->count()) {
            session()->flash('error', 'This Category has \'' . $subCats->count() . '\' sub category.');
        } else {
            $category->delete();
            session()->flash('success', 'Category \'' . $catName . '\' deleted successfully');
        }
        return redirect()->route('admin.category.index');
    }
}
