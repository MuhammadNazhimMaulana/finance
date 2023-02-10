<?php

namespace App\Interfaces;

interface FeeRuleInterface
{
    public function index();

    public function store($request);

    public function destroy(int $id);
}
