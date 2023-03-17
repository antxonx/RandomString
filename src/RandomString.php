<?php

namespace Antxonx\RandomString;

class RandomString
{

    private const BASE_SIZE = 10;

    private const MAX_CHUNK = 28;

    private int $size;

    private int $flags;

    private string $code;

    private const LETTERS_INDEX = [
        ['U', 'M'],
        ['A', 'Z', 'F'],
        ['G', 'J'],
        ['X', 'Q', 'E'],
        ['C', 'T', 'V'],
        ['I', 'K'],
        ['S', 'B', 'N'],
        ['D', 'H', 'L'],
        ['P', 'R', 'Y'],
        ['O', 'W'],
    ];

    public function __construct(int $size, int $flags = RandomStringFlags::ALL_CHARS)
    {
        $this->size = $size;
        $this->flags = $flags;
        $this->code = "";
    }

    public function setSize(int $size)
    {
        $this->size = $size;
    }

    public function setFlags(int $flags)
    {
        $this->flags = $flags;
    }

    public function getLastCode(): string
    {
        return $this->code;
    }

    public function gen(): string
    {
        $this->code = "";
        $mult =  floor($this->size / self::MAX_CHUNK);
        $rest = $this->size % self::MAX_CHUNK;
        for($i = 0; $i < $mult; $i++) {
            $this->genChunk(self::MAX_CHUNK);
        }
        $this->genChunk($rest);
        return $this->code;
    }

    private function genChunk(int $len)
    {
        if ($len < 1) {
            return "";
        }
        $time = number_format(microtime(true), $len - self::BASE_SIZE, '', '');
        if($len < self::BASE_SIZE) {
            $time = substr($time, self::BASE_SIZE-$len, $len);
        }
        $left = substr($time, 0, strlen($time) / 2);
        $right = substr($time, strlen($time) / 2, strlen($time));
        $leftleft = substr($left, 0, strlen($left) / 2);
        $leftright = substr($left, strlen($left) / 2, strlen($left));
        $rightleft = substr($right, 0, strlen($right) / 2);
        $rightright = substr($right, strlen($right) / 2, strlen($right));

        $chunk = $rightleft . $leftright . $leftleft . $rightright;

        switch($this->flags){
            case RandomStringFlags::NUMBERS_ONLY:
                $this->code .= $chunk;
                break;
            case RandomStringFlags::LETTERS_ONLY:
                $this->code .= $this->addLetters($chunk, true);
                break;
            case RandomStringFlags::ALL_CHARS:
                $this->code .= $this->addLetters($chunk, false);
                break;
            default: break;
        }
    }

    private function addLetters(string $chunk, bool $all = false): string
    {
        $splitted = str_split($chunk);
        $alteredChucnk = '';
        foreach ($splitted as $n) {
            if ($all) {
                $alteredChucnk .= $this->randomLetter($n);
            } else {
                $alteredChucnk .= $this->isRandomTime() ? $this->randomLetter($n) : $n;
            }
        }
        return $alteredChucnk;
    }

    private function isRandomTime(): bool
    {
        return $this->random(2) == 0;
    }

    private function randomLetter(int $pos): string
    {
        $set = self::LETTERS_INDEX[$pos];
        return $set[(int)$this->random(count($set))];
    }

    private function random(int $limit): float
    {
        return microtime(true) * 1000000 % $limit;
    }
}
