<?php

namespace App\DTO;

class InfoDTO
{
    public function __construct(public array $data){}

    public function toArray(): array {
        return $this->data;
    }
}
