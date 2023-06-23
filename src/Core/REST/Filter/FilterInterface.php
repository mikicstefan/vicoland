<?php

namespace App\Core\REST\Filter;

use Symfony\Component\HttpFoundation\Request;

interface FilterInterface
{
  public const KEY = 'filter';

  public static function fromRequest(Request $request): self;
}
