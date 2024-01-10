<?php

namespace Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext;

use Awwar\MasterpiecePhp\AddOn\NodePatternObtainerInterface;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGeneratorInterface;

class EndpointBodyCompileContext
{
    public function __construct(private ClassGeneratorInterface $classGenerator, private NodePatternObtainerInterface $nodePatternObtainer)
    {
    }

    public function getClassGenerator(): ClassGeneratorInterface
    {
        return $this->classGenerator;
    }

    public function getNodePatternObtainer(): NodePatternObtainerInterface
    {
        return $this->nodePatternObtainer;
    }
}
