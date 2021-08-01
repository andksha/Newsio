<?php

namespace App\Http\API\Controllers;

use App\Model\Attribute;
use App\Model\ClosureCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ClosureTableController
{
    public function categories(ClosureCategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->getMany());
    }

    public function lastLvlCategories(ClosureCategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->lastLvl());
    }

    public function category(int $id, ClosureCategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->getOne($id));
    }

    public function add(Request $request, ClosureCategoryRepository $repository): JsonResponse
    {
        $parentID = $request->parent_id;
        $name = $request->name;

        $c = $repository->add($parentID, $name);
        return response()->json($c);
    }

    public function delete(int $id, ClosureCategoryRepository $repository): JsonResponse
    {
        $ok = $repository->delete($id);

        return response()->json($ok);
    }

    public function move(int $id, ?int $parentId, ClosureCategoryRepository $repository): JsonResponse
    {
        $ok = $repository->move($id, $parentId);

        return response()->json($ok);
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