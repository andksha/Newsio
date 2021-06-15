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

    public function move(int $id, ?int $parentId): bool
    {
        $category = CategoryNestedSet::query()->where('id', $id)->first();
        $max = CategoryNestedSet::query()->max('right');

        if (!$parentId) {
            $category->update([
                'depth' => 1,
                'parent_id' => null,
                'left' => $max + 1,
                'right' => $max + 2
            ]);
        }

        if (!$parent = CategoryNestedSet::query()->where('id', $parentId)->first()) {
            return false;
        }

        if ($parent->right > $category->right) {
            CategoryNestedSet::query()->where('left', '>', $category->left)
                ->where('left', '<', $parent->right)
                ->update([
                    'left' => DB::raw('"left" - 2'),
                ]);
            CategoryNestedSet::query()->where('right', '>', $category->left)
                ->where('right', '<', $parent->right)
                ->update([
                    'right' => DB::raw('"right" - 2'),
                ]);

            $category->update([
                'depth' => $parent->depth + 1,
                'parent_id' => $parent->id,
                'left' => $parent->right - 2,
                'right' => $parent->right - 1,
            ]);
        } elseif ($parent->right < $category->right) {
            CategoryNestedSet::query()->where('left', '>', $parent->right)
                ->where('left', '<', $category->right)
                ->update([
                    'left' => DB::raw('"left" + 2'),
                ]);
            CategoryNestedSet::query()->where('right', '>=', $parent->right)
                ->where('right', '<', $category->right)
                ->update([
                    'right' => DB::raw('"right" + 2'),
                ]);

            $category->update([
                'depth' => $parent->depth + 1,
                'parent_id' => $parent->id,
                'left' => $parent->right,
                'right' => $parent->right + 1,
            ]);
        }

        return true;
    }
}