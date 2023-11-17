<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\Config\ConfigInterface;

class CompileContext
{
    private array $configs = [];

    private array $addOns = [];


    public function __construct(private string $generationPath)
    {
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
