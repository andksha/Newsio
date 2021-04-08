<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Attribute;
use App\Model\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class EAVController extends Controller
{
    public function categories(): JsonResponse
    {
        $c = Category::query()->orderBy('id')->get();

        return response()->json($c);
    }

    public function lastLvlCategories(): JsonResponse
    {
        $c = Category::query()->where('depth', 2)->get();

        return response()->json($c);
    }

    public function category(int $id): JsonResponse
    {
        $c = Category::query()->where('id', $id)->with(['attributes'])->first();

        return response()->json($c);
    }

    public function addCategory(Request $request): JsonResponse
    {
        $parentID = $request->parent_id;
        $name = $request->name;

        $c = $this->addCat($parentID, $name);
        return response()->json($c);
    }

    private function addCat(?int $parentID, string $name): Category
    {
        if (!$parentID) {
            return $this->addRoot($name);
        }

        return $this->addNode($parentID, $name);
    }

    private function addRoot(string $name): Category
    {
        if (!$max = Category::query()->max('right')) {
            return Category::query()->create([
                "parent_id" => null,
                "depth" => 1,
                "name" => $name,
                "slug" => Str::slug($name),
                "left" => 1,
                "right" => 1,
            ]);
        }
    }

    private function addNode(int $parentID, string $name): Category
    {
        $parent = Category::query()->where('id', $parentID)->first();
        Category::query()->where('left', '>=', $parent->right)->update(['left' => DB::raw('"left" + 2')]);
        Category::query()->where('right', '>=', $parent->right)->update(['right' => DB::raw('"right" + 2')]);

        return Category::query()->create([
            "parent_id" => $parentID,
            "depth" => 1,
            "name" => $name,
            "slug" => Str::slug($name),
            "left" => $parent->right,
            "right" => $parent->right + 1,
        ]);
    }

    public function addAttributeToCategory(int $id, Request $request): JsonResponse
    {
        Attribute::query()->create([
            'category_id' => $id,
            'name' => $request->name,
            'type' => $request->type,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json();
    }
}