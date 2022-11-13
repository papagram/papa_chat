<?php

namespace App\Services;

class ZocService
{
    private $positions;
    private $otherPositions;
    private $zoc;
    private $otherZoc;
    private $ret;

    public function __construct($positions, $otherPositions)
    {
        $this->positions = $positions;
        $this->otherPositions = $otherPositions;
    }

    public function handle()
    {
        foreach ($this->positions as $position) {
            $this->zoc[$position->hex_number] = [
                $position->hex_number - 1,
                $position->hex_number + 100,
                $position->hex_number + 101,
                $position->hex_number + 1,
                $position->hex_number - 99,
                $position->hex_number - 100,
            ];
        }

        foreach ($this->otherPositions as $position) {
            $this->otherZoc[$position->hex_number] = [
                $position->hex_number - 1,
                $position->hex_number + 100,
                $position->hex_number + 101,
                $position->hex_number + 1,
                $position->hex_number - 99,
                $position->hex_number - 100,
            ];
        }

        foreach ($this->zoc as $hexNumber => $zocs) {
            foreach ($this->otherZoc as $otherHexNumber => $otherZocs) {
                $intersect = collect($otherZocs)->intersect(collect($zocs));
                if ($intersect->isNotEmpty()) {
                    $this->ret[$hexNumber] = $intersect->values();
                }
            }
        }
        return $this->ret;
    }
}
