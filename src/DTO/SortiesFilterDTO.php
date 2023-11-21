<?php

namespace App\DTO;

use App\Entity\Campus;

class SortiesFilterDTO {
    public ?Campus $campus = null;
    public ?string $name_search = null;
    public ?\DateTimeInterface $range_start = null;
    public ?\DateTimeInterface $range_end = null;
    public ?bool $i_am_organisateur = false;
    public ?bool $i_am_subscribed = false;
    public ?bool $i_am_not_subscribed = false;
    public ?bool $show_closed_sorties = false;
}
