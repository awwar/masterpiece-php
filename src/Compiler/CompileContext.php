<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\Config\ConfigInterface;
use SplStack;

class CompileContext
{
    private array $configs = [];

    private array $addOns = [];

    private string $generationPath;


    public function __construct(string $generationPath)
    {
        $this->generationPath = '';

        // Not the most efficient algorithm,
        // it is better to split by / and go from end to beginning, jumping to the next element if it occurs ".."
        // and skip if it occurs "." or ""
        // But let's leave this matter for the smart people from LeetCode
        $stack = new SplStack();

        foreach (explode('/', $generationPath) as $symbol) {
            if ($symbol === '.' || $symbol === '') {
                continue;
            }

            if ($symbol === '..') {
                $stack->pop();
            } else {
                $stack->push($symbol);
            }
        }

        foreach ($stack as $symbol) {
            $this->generationPath = '/' . $symbol . $this->generationPath;
        }
    }

    public function addConfig(ConfigInterface $masterpieceConfig): void
    {
        $this->configs[] = $masterpieceConfig;
    }

    /**
     * @return ConfigInterface[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    public function addAddOn(AddOnInterface $addOn): void
    {
        $this->addOns[] = $addOn;
    }

    /**
     * @return AddOnInterface[]
     */
    public function getAddOns(): array
    {
        return $this->addOns;
    }

    public function getGenerationPath(): string
    {
        return $this->generationPath;
    }
}
