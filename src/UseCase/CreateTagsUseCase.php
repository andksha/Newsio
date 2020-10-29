<?php

namespace Newsio\UseCase;

use Newsio\Boundary\TagsBoundary;
use Newsio\Model\EventTag;
use Newsio\Model\Tag;

class CreateTagsUseCase
{
    public function createTags(TagsBoundary $tagsBoundary): CreateTagsUseCase
    {
        $existingTags = Tag::query()->whereIn('name', $tagsBoundary->getValues())->get();
        $tagsToInsert = array_filter($tagsBoundary->getValues(), function ($value) use ($existingTags) {
            return !$existingTags->firstWhere('name', $value);
        });

        Tag::query()->insert(array_map(fn($value) => ['name' => $value], $tagsToInsert));

        return $this;
    }

    public function createEventTags(int $eventId, TagsBoundary $tagsBoundary): CreateTagsUseCase
    {
        $newEventTags = Tag::query()->whereIn('name', $tagsBoundary->getValues())->get();

        EventTag::query()->insert(array_map(function ($value) use ($eventId, $newEventTags) {
            return ['event_id' => $eventId, 'tag_id' => $value];
        }, $newEventTags->pluck('id')->toArray()));

        return $this;
    }
}
