<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler;

use Awwar\MasterpiecePhp\AddOns\BasicNodes\BasicNodeAddon;
use Awwar\MasterpiecePhp\Compiler\CompileContext;
use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Config\Config;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Awwar\MasterpiecePhp\Tests\CaseWithContainer;
use Throwable;

class CompilerTest extends CaseWithContainer
{
    private const TEST_COMPILE_PATH = __DIR__ . '/../../var/test_compile';

    protected function setUp(): void
    {
        parent::setUp();

        $filesystem = $this->container->get(Filesystem::class);

        try {
            $filesystem->recursiveRemoveDirectory(self::TEST_COMPILE_PATH);
        } catch (Throwable) {

        }
    }

    public function testCompileWhenOk(): void
    {
        $compiler = $this->container->get(Compiler::class);

        $path = self::TEST_COMPILE_PATH . '/' . uniqid();

        $settings = new CompileContext($path);

        $settings->addAddOn(new BasicNodeAddon());

        $settings->addConfig(
            new Config(
                name: 'my_test_flow',
                type: 'flow',
                params: [
                    'input'   => [
                        [
                            'contract' => 'int',
                            'name'     => 'a',
                        ],
                    ],
                    'output'  => [
                        [
                            'contract' => 'int',
                            'name'     => 'b',
                        ],
                    ],
                    'sockets' => [
                        'socket_1' => [
                            'node_alias' => 'number_node_1',
                        ],
                        'socket_2' => [
                            'node_alias' => 'additional_node_1',
                            'input'      => [
                                [
                                    'variable' => 'a',
                                    'path'     => ['value'],
                                ],
                                [
                                    'node_alias' => 'socket_1',
                                    'path'       => ['value'],
                                ],
                            ],
                        ],
                        'socket_3' => [
                            'node_alias'  => 'output',
                            'input' => [
                                [
                                    'node_alias' => 'socket_2',
                                    'path'       => ['value'],
                                ],
                            ],
                        ],
                    ],
                    'map'     => [
                        'socket_1' => [
                            [
                                'condition' => true,
                                'socket'    => 'socket_2',
                            ],
                        ],
                        'socket_2' => [
                            [
                                'condition' => true,
                                'socket'    => 'socket_3',
                            ],
                        ],
                    ],
                    'nodes'   => [
                        'number_node_1'     => [
                            'option' => [
                                'value' => 3,
                            ],
                            'node'   => [
                                'addon'   => 'basic_node',
                                'pattern' => 'number',
                            ],
                        ],
                        'additional_node_1' => [
                            'option' => [],
                            'node'   => [
                                'addon'   => 'basic_node',
                                'pattern' => 'addition',
                            ],
                        ],
                    ],
                ]
            )
        );

        $compiler->compile($settings);

        self::assertDirectoryExists($settings->getGenerationPath());

        include_once $settings->getGenerationPath() . '/basic_node_addition.php';

        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Nodes\basic_node_addition::class));
    }
}

