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
        // 自軍情報
        $otherHexNumbers = $this->otherPositions->pluck('hex_number');

        foreach ($this->positions as $position) {
            $this->zoc[$position->fleet->player_id] = [];

            $numberOfDigits = strlen($position->hex_number);
            $numberOfHead = $numberOfDigits === 3 ? substr($position->hex_number, 0, 1) : substr($position->hex_number, 0, 2);
            if ($numberOfHead % 2 === 0) {
                // 偶数の時
                $this->zoc[$position->fleet->player_id][$position->hex_number] = [
                    $position->hex_number - 1,
                    $position->hex_number + 99,
                    $position->hex_number + 100,
                    $position->hex_number + 1,
                    $position->hex_number - 100,
                    $position->hex_number - 101,
                ];
            } else {
                // 奇数の時で計算方法が異なる
                $this->zoc[$position->fleet->player_id][$position->hex_number] = [
                    $position->hex_number - 1,
                    $position->hex_number + 100,
                    $position->hex_number + 101,
                    $position->hex_number + 1,
                    $position->hex_number - 99,
                    $position->hex_number - 100,
                ];
            }
            asort($this->zoc[$position->fleet->player_id][$position->hex_number]);
            $this->zoc[$position->fleet->player_id][$position->hex_number] = array_values($this->zoc[$position->fleet->player_id][$position->hex_number]);

            foreach ($this->zoc[$position->fleet->player_id][$position->hex_number] as $zocHexNumber) {
                if ($otherHexNumbers->containsStrict($zocHexNumber)) {
                    $this->ret[$position->fleet->player_id][$position->hex_number]['fleet_number'] = $position->fleet->number;
                    $this->ret[$position->fleet->player_id][$position->hex_number]['battle_hex_numbers'][] = $zocHexNumber;
                }
            }
        }
        ksort($this->ret);
        // 自軍情報ここまで

        // 敵軍情報
        $hexNumbers = $this->positions->pluck('hex_number');

        foreach ($this->otherPositions as $position) {
            $this->zoc[$position->fleet->player_id] = [];

            $numberOfDigits = strlen($position->hex_number);
            $numberOfHead = $numberOfDigits === 3 ? substr($position->hex_number, 0, 1) : substr($position->hex_number, 0, 2);
            if ($numberOfHead % 2 === 0) {
                // 偶数の時
                $this->zoc[$position->fleet->player_id][$position->hex_number] = [
                    $position->hex_number - 1,
                    $position->hex_number + 99,
                    $position->hex_number + 100,
                    $position->hex_number + 1,
                    $position->hex_number - 100,
                    $position->hex_number - 101,
                ];
            } else {
                // 奇数の時で計算方法が異なる
                $this->zoc[$position->fleet->player_id][$position->hex_number] = [
                    $position->hex_number - 1,
                    $position->hex_number + 100,
                    $position->hex_number + 101,
                    $position->hex_number + 1,
                    $position->hex_number - 99,
                    $position->hex_number - 100,
                ];
            }
            asort($this->zoc[$position->fleet->player_id][$position->hex_number]);
            $this->zoc[$position->fleet->player_id][$position->hex_number] = array_values($this->zoc[$position->fleet->player_id][$position->hex_number]);

            foreach ($this->zoc[$position->fleet->player_id][$position->hex_number] as $zocHexNumber) {
                if ($hexNumbers->containsStrict($zocHexNumber)) {
                    $this->ret[$position->fleet->player_id][$position->hex_number]['fleet_number'] = $position->fleet->number;
                    $this->ret[$position->fleet->player_id][$position->hex_number]['battle_hex_numbers'][] = $zocHexNumber;
                }
            }
        }
        ksort($this->ret);

        return $this->ret;
    }
}
