<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CategoryRepository
{
    public function getMany(): array
    {
        $c = Category::query()->orderBy('left')->get();

        return $this->mapCategories($c);
    }

    public function lastLvl(): array
    {
        return Category::query()->whereRaw('"right" - "left" = 1')->get()->toArray();
    }

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

        return Category::query()->create([
            "parent_id" => null,
            "depth" => 1,
            "name" => $name,
            "slug" => Str::slug($name),
            "left" => $max + 1,
            "right" => $max + 2,
        ]);
    }

    private function addNode(int $parentID, string $name): Category
    {
        $parent = Category::query()->where('id', $parentID)->first();
        Category::query()->where('left', '>=', $parent->right)->update(['left' => DB::raw('"left" + 2')]);
        Category::query()->where('right', '>=', $parent->right)->update(['right' => DB::raw('"right" + 2')]);

        return Category::query()->create([
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
        // select $c - category to delete
        $c = Category::query()->where('id', $id)->first();

        // assign $c's parent id to it's children, decrement left and right by one, decrement depth
        Category::query()->where('left', '>', $c->left)
            ->where('right', '<', $c->right)
            ->update([
                'parent_id' => $c->parent_id,
                'left' => DB::raw('"left" - 1'),
                'right' => DB::raw('"right" - 1'),
                'depth' => DB::raw('"depth" - 1'),
            ]);

        // decrement right by 2 where right > $c->right
        Category::query()->where('right', '>', $c->right)->update([
            'right' => DB::raw('"right" - 2'),
        ]);
        // decrement left by 2 where left > $c->right
        Category::query()->where('left', '>', $c->right)->update([
            'left' => DB::raw('"left" - 2'),
        ]);

        return $c->delete();
    }
}