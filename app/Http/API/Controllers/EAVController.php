<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Attribute;
use App\Model\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class EAVController extends Controller
{
    public function categories(CategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->getMany());
    }

    public function lastLvlCategories(CategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->lastLvl());
    }

    public function category(int $id, CategoryRepository $repository): JsonResponse
    {
        return response()->json($repository->getOne($id));
    }

    public function add(Request $request, CategoryRepository $repository): JsonResponse
    {
        $parentID = $request->parent_id;
        $name = $request->name;

        $c = $repository->add($parentID, $name);
        return response()->json($c);
    }

    public function delete(int $id, CategoryRepository $repository): JsonResponse
    {
        $ok = $repository->delete($id);

        return response()->json($ok);
    }

    public function move(int $id, CategoryRepository $repository, ?int $parentId = null): JsonResponse
    {
        $ok = $repository->move($id, $parentId);

        return response()->json($ok);
    }

    public function moveBranch(int $id, CategoryRepository $repository, ?int $parentId = null): JsonResponse
    {
        $ok = $repository->moveBranch($id, $parentId);

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