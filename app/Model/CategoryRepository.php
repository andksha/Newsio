<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CategoryRepository
{
    public function getMany(): array
    {
        $category = CategoryNestedSet::query()->orderBy('left')->get();

        return $this->mapCategories($category);
    }

    public function lastLvl(): array
    {
        return CategoryNestedSet::query()->whereRaw('"right" - "left" = 1')->get()->toArray();
    }

    public function getOne(int $id): array
    {
        $category = CategoryNestedSet::query()->where('id', $id)
            ->with(['attributes'])
            ->first();

        $categories = CategoryNestedSet::query()->where('left', '<', $category->left)
            ->where('right', '>', $category->right)
            ->get()
            ->merge([$category])
            ->sortBy('left');

        return $this->mapCategories($categories);
    }

    private function mapCategories(Collection $categories): array
    {
        $tree = new CategoryTree();
        $minDepth = $categories->min('depth');

        /** @var CategoryNestedSet $value */
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

    public function add(?int $parentID, string $name): CategoryNestedSet
    {
        if (!$parentID) {
            return $this->addRoot($name);
        }

        return $this->addNode($parentID, $name);
    }

    private function addRoot(string $name): CategoryNestedSet
    {
        if (!$max = CategoryNestedSet::query()->max('right')) {
            return CategoryNestedSet::query()->create([
                "parent_id" => null,
                "depth" => 1,
                "name" => $name,
                "slug" => Str::slug($name),
                "left" => 1,
                "right" => 1,
            ]);
        }

        return CategoryNestedSet::query()->create([
            "parent_id" => null,
            "depth" => 1,
            "name" => $name,
            "slug" => Str::slug($name),
            "left" => $max + 1,
            "right" => $max + 2,
        ]);
    }

    private function addNode(int $parentID, string $name): CategoryNestedSet
    {
        $parent = CategoryNestedSet::query()->where('id', $parentID)->first();
        CategoryNestedSet::query()->where('left', '>=', $parent->right)->update(['left' => DB::raw('"left" + 2')]);
        CategoryNestedSet::query()->where('right', '>=', $parent->right)->update(['right' => DB::raw('"right" + 2')]);

        return CategoryNestedSet::query()->create([
            "parent_id" => $parentID,
            "depth" => $parent->depth + 1,
            "name" => $name,
            "slug" => Str::slug($name),
            "left" => $parent->right,
            "right" => $parent->right + 1,
        ]);
    }

    public function delete(int $id): bool
    {
        $category = CategoryNestedSet::query()->where('id', $id)->first();

        // assign $category's parent id to it's children, decrement left and right by one, decrement depth
        CategoryNestedSet::query()->where('left', '>', $category->left)
            ->where('right', '<', $category->right)
            ->update([
                'parent_id' => $category->parent_id,
                'left' => DB::raw('"left" - 1'),
                'right' => DB::raw('"right" - 1'),
                'depth' => DB::raw('"depth" - 1'),
            ]);

        // decrement right by 2 where right > $category->right
        CategoryNestedSet::query()->where('right', '>', $category->right)->update([
            'right' => DB::raw('"right" - 2'),
        ]);
        // decrement left by 2 where left > $category->right
        CategoryNestedSet::query()->where('left', '>', $category->right)->update([
            'left' => DB::raw('"left" - 2'),
        ]);

        return $category->delete();
    }

    public function move(int $id, ?int $newParentId): bool
    {
        $category = CategoryNestedSet::query()->where('id', $id)->first();

        if (!$newParentId) {
            CategoryNestedSet::query()->where('left', '>', $category->right)
                ->update([
                    'left' => DB::raw('"left" - 2'),
                ]);
            CategoryNestedSet::query()->where('right', '>', $category->right)
                ->update([
                    'right' => DB::raw('"right" - 2'),
                ]);
            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" - 1'),
                    'right' => DB::raw('"right" - 1'),
                    'parent_id' => $category->parent_id,
                ]);

            $max = CategoryNestedSet::query()->max('right');
            $category->update([
                'depth' => 1,
                'parent_id' => null,
                'left' => $max + 1,
                'right' => $max + 2
            ]);

            return true;
        }

        if (!$newParent = CategoryNestedSet::query()->where('id', $newParentId)->first()) {
            return false;
        }

        if ($newParent->right > $category->right) {
            // add 2 to left and right of all categories between $category->right and $newParent->right
            CategoryNestedSet::query()->where('left', '>', $category->right)
                ->where('left', '<', $newParent->right)
                ->update([
                    'left' => DB::raw('"left" - 2'),
                ]);
            CategoryNestedSet::query()->where('right', '>', $category->right)
                ->where('right', '<', $newParent->right)
                ->update([
                    'right' => DB::raw('"right" - 2'),
                ]);

            // add 1 to children of $category, update children's parent_id
            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" - 1'),
                    'right' => DB::raw('"right" - 1'),
                    'parent_id' => $category->parent_id,
                ]);

            $category->update([
                'depth' => $newParent->depth + 1,
                'parent_id' => $newParent->id,
                'left' => $newParent->right - 2,
                'right' => $newParent->right - 1,
            ]);
        } elseif ($newParent->right < $category->right) {
            // add 2 to left and right of all categories between $newParent->right and $category->left
            CategoryNestedSet::query()->where('left', '>', $newParent->right)
                ->where('left', '<', $category->left)
                ->update([
                    'left' => DB::raw('"left" + 2'),
                ]);
            CategoryNestedSet::query()->where('right', '>=', $newParent->right)
                ->where('right', '<', $category->left)
                ->update([
                    'right' => DB::raw('"right" + 2'),
                ]);

            // add 1 to children of $category, update children's parent_id
            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" + 1'),
                    'right' => DB::raw('"right" + 1'),
                    'parent_id' => $category->parent_id,
                ]);

            $category->update([
                'depth' => $newParent->depth + 1,
                'parent_id' => $newParent->id,
                'left' => $newParent->right,
                'right' => $newParent->right + 1,
            ]);
        }

        return true;
    }

    public function moveBranch(int $id, ?int $newParentId): bool
    {
        $category = CategoryNestedSet::query()->where('id', $id)->first();
        $oldLeftRightDiff = $category->right - $category->left;

        if (!$newParentId) {
            $maxRight = CategoryNestedSet::query()->max('right');
            $newMaxRight = $maxRight - $oldLeftRightDiff - 1;
            $newOldDiff = $newMaxRight + 1 - $category->left;

            $leftToUpdate = CategoryNestedSet::query()->where('left', '>', $category->right)->pluck('id');
            $rightToUpdate = CategoryNestedSet::query()->where('right', '>', $category->right)->pluck('id');

            $depthDiff = $category->depth - 1;

            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" + ' . $newOldDiff),
                    'right' => DB::raw('"right" + ' . $newOldDiff),
                    'depth' => DB::raw('"depth" - ' . $depthDiff)
                ]);
            $category->update([
                'depth' => 1,
                'parent_id' => null,
                'left' => $newMaxRight + 1,
                'right' => $newMaxRight + $oldLeftRightDiff + 1,
            ]);

            CategoryNestedSet::query()->whereIn('id', $leftToUpdate)->update([
                    'left' => DB::raw('"left" - ' . ($oldLeftRightDiff + 1)),
                ]);
            CategoryNestedSet::query()->whereIn('id', $rightToUpdate)->update([
                    'right' => DB::raw('"right" - ' . ($oldLeftRightDiff + 1)),
                ]);

            return true;
        }

        if (!$newParent = CategoryNestedSet::query()->where('id', $newParentId)->first()) {
            return false;
        }

        if ($newParent->right > $category->right) {
            $newOldDiff = $newParent->right - 1 - $category->right;
            $depthDiff = $newParent->depth + 1 - $category->depth;

            $leftToUpdate = CategoryNestedSet::query()->where('left', '>', $category->right)
                ->where('left', '<', $newParent->right)
                ->pluck('id');
            $rightToUpdate = CategoryNestedSet::query()->where('right', '>', $category->right)
                ->where('right', '<', $newParent->right)
                ->pluck('id');

            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" + ' . $newOldDiff),
                    'right' => DB::raw('"right" + ' . $newOldDiff),
                    'depth' => DB::raw('"depth" + ' . $depthDiff),
                ]);

            $category->update([
                'depth' => $newParent->depth + 1,
                'parent_id' => $newParent->id,
                'left' => $newParent->right - $oldLeftRightDiff - 1,
                'right' => $newParent->right - 1,
            ]);

            CategoryNestedSet::query()->whereIn('id', $leftToUpdate)->update([
                'left' => DB::raw('"left" - ' . ($oldLeftRightDiff + 1)),
            ]);
            CategoryNestedSet::query()->whereIn('id', $rightToUpdate)->update([
                'right' => DB::raw('"right" - ' . ($oldLeftRightDiff + 1)),
            ]);
        } elseif ($newParent->right < $category->right) {
            $newOldDiff = $category->left - $newParent->right;
            $depthDiff = ($newParent->depth + 1 - $category->depth) * ($newParent->depth > $category->depth ?: -1);

            $leftToUpdate = CategoryNestedSet::query()->where('left', '<', $category->left)
                ->where('left', '>', $newParent->right)
                ->pluck('id');
            $rightToUpdate = CategoryNestedSet::query()->where('right', '<', $category->left)
                ->where('right', '>=', $newParent->right)
                ->pluck('id');

            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('right', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" - ' . $newOldDiff),
                    'right' => DB::raw('"right" - ' . $newOldDiff),
                    'depth' => DB::raw('"depth" - ' . $depthDiff),
                ]);

            $category->update([
                'depth' => $newParent->depth + 1,
                'parent_id' => $newParent->id,
                'left' => $newParent->right,
                'right' => $newParent->right + $oldLeftRightDiff,
            ]);

            CategoryNestedSet::query()->whereIn('id', $leftToUpdate)->update([
                'left' => DB::raw('"left" + ' . ($oldLeftRightDiff + 1)),
            ]);
            CategoryNestedSet::query()->whereIn('id', $rightToUpdate)->update([
                'right' => DB::raw('"right" + ' . ($oldLeftRightDiff + 1)),
            ]);
        }

        return true;
    }
}