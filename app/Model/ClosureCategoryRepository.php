<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ClosureCategoryRepository
{
    public function getMany(): array
    {
        $c = ClosureCategory::query()->orderBy('parent_id')->get();

        return $this->mapCategories($c);
    }

    //    TODO: change to closure version
    public function lastLvl(): array
    {
        return Category::query()->whereRaw('"right" - "left" = 1')->get()->toArray();
    }

    //    TODO: change to closure version
    public function getOne(int $id): array
    {
        $c = Category::query()->where('id', $id)
            ->with(['attributes'])
            ->first();

        $categories = Category::query()->where('left', '<', $c->left)
            ->where('right', '>', $c->right)
            ->get()
            ->merge([$c])
            ->sortBy('left');

        return $this->mapCategories($categories);
    }

    //    TODO: change to closure version

    private function mapCategories(Collection $categories): array
    {
        $tree = new CategoryTree();
        $minDepth = $categories->min('depth');

        /** @var Category $value */
        foreach ($categories as $key => $value) {
            $node = new CategoryNode($value);
            $tree->setLastNodeOfLvl($node, $value->depth);

            if ($key === 0 || $value->depth === $minDepth) {
                $tree->addRoot($node);
                continue;
            }

            $tree->getLastNodeOfLvl($value->depth - 1)->addChild($node);
        }

        return $tree->toArray();
    }

    public function add(?int $parentID, string $name): Category
    {
        $category = new Category(['name' => $name]);
        $category->save();

        ClosureCategory::query()->insert($this->getInsertArray(
            $category->id,
            $category->id,
            $parentID ?? $category->id
        ));

        if (!$parentID) {
            return $category;
        }

        return $this->addAsChild($parentID, $category);
    }

    private function addAsChild(int $parentID, Category $category): Category
    {
        $parentsOfParents = ClosureCategory::query()
            ->where('child_id', $parentID)
            ->get();

        $toInsert = [];

        /** @var ClosureCategory $parentOfParent */
        foreach ($parentsOfParents as $parentOfParent) {
            $toInsert[] = $this->getInsertArray(
                $parentOfParent->parent_id,
                $category->id,
                $parentOfParent->immediate_parent_id,
            );
        }

        ClosureCategory::query()->insert($toInsert);

        return $category;
    }

    // delete category moving it's children up
    public function delete(int $id): bool
    {
        /** @var ClosureCategory $closureCategory */
        $closureCategory = ClosureCategory::query()->where('parent_id', $id)
            ->where('child_id', $id)
            ->first();
        if (!$closureCategory) {
            return false;
        }

        $this->updateChildrenImmediateParentId($closureCategory);

        return ClosureCategory::query()->where('parent_id', $closureCategory->parent_id)
            ->orWhere('child_id', $closureCategory->parent_id)
            ->delete();
    }

    private function updateChildrenImmediateParentId(ClosureCategory $oldParent): void
    {
        /** @var ClosureCategory $immediateParent */
        $immediateParent = ClosureCategory::query()->where('parent_id', $oldParent->immediate_parent_id)
            ->where('child_id', $oldParent->immediate_parent_id)
            ->where('immediate_parent_id', '!=', $oldParent->parent_id)
            ->first();
        if (!$immediateParent) {
            $newImmediateParentId = DB::raw('parent_id');
        } else {
            $newImmediateParentId = $immediateParent->parent_id;
        }

        ClosureCategory::query()->where('immediate_parent_id', $oldParent->parent_id)
            ->update(['immediate_parent_id' => $newImmediateParentId]);
    }

    //    TODO: change to closure version
    public function move(int $id, ?int $parentId): bool
    {
        $c = Category::query()->where('id', $id)->first();
        $max = Category::query()->max('right');

        if (!$parentId) {
            $c->update([
                'depth' => 1,
                'parent_id' => null,
                'left' => $max + 1,
                'right' => $max + 2
            ]);
        }

        if (!$parent = Category::query()->where('id', $parentId)->first()) {
            return false;
        }

        if ($parent->right > $c->right) {
            Category::query()->where('left', '>', $c->left)
                ->where('left', '<', $parent->right)
                ->update([
                    'left' => DB::raw('"left" - 2'),
                ]);
            Category::query()->where('right', '>', $c->left)
                ->where('right', '<', $parent->right)
                ->update([
                    'right' => DB::raw('"right" - 2'),
                ]);

            $c->update([
                'depth' => $parent->depth + 1,
                'parent_id' => $parent->id,
                'left' => $parent->right - 2,
                'right' => $parent->right - 1,
            ]);
        } elseif ($parent->right < $c->right) {
            Category::query()->where('left', '>', $parent->right)
                ->where('left', '<', $c->right)
                ->update([
                    'left' => DB::raw('"left" + 2'),
                ]);
            Category::query()->where('right', '>=', $parent->right)
                ->where('right', '<', $c->right)
                ->update([
                    'right' => DB::raw('"right" + 2'),
                ]);

            $c->update([
                'depth' => $parent->depth + 1,
                'parent_id' => $parent->id,
                'left' => $parent->right,
                'right' => $parent->right + 1,
            ]);
        }

        return true;
    }

    private function getInsertArray(int $parentId, int $childId, int $immediateParentId): array
    {
        return [
            'parent_id' => $parentId,
            'child_id' => $childId,
            'immediate_parent_id' => $immediateParentId,
        ];
    }
}