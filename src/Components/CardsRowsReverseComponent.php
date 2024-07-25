<?php

namespace App\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('cards_rows_reverse')]
class CardsRowsReverseComponent
{
    public string $title;
    public string $description;
    public string $image;
    public string $link;
    public string $button;
}