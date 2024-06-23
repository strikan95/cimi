<?php
namespace App\Core\Listing;

enum DraftBuildSteps: string
{
    case StructureType = 'structureType';
    case Title = 'title';
    case Description = 'description';
    case Location = 'location';
    case Image = 'image';
    case Amenities = 'amenities';
}
