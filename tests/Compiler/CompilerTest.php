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
                name: 'base_endpoint',
                type: 'endpoint',
                params: [
                    'flow' => 'my_test_flow'
                ]
            )
        );

        $settings->addConfig(
            new Config(
                name: 'my_test_flow',
                type: 'flow',
                params: [
                    'input'   => [
                        [
                            'contract' =>  'basic_node_integer',
                            'name'     => 'a',
                        ],
                    ],
                    'output'  => [
                        [
                            'contract' => 'basic_node_integer',
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
                                ],
                                [
                                    'node_alias' => 'socket_1',
                                ],
                            ],
                        ],
                        'socket_3' => [
                            'node_alias'  => 'output',
                            'input' => [
                                [
                                    'node_alias' => 'socket_2',
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

        include_once $settings->getGenerationPath() . '/app_my_test_flow.php';
        include_once $settings->getGenerationPath() . '/basic_node_addition.php';
        include_once $settings->getGenerationPath() . '/basic_node_number.php';
        include_once $settings->getGenerationPath() . '/basic_node_integer.php';

        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Nodes\app_my_test_flow::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Nodes\basic_node_addition::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Nodes\basic_node_number::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Contracts\basic_node_integer::class));

        $input = \Awwar\MasterpiecePhp\Contracts\basic_node_integer::cast_from_mixed(10);

        $result = \Awwar\MasterpiecePhp\Nodes\app_my_test_flow::execute_d5841bc1419883204f3eb470796b977cf1e0dd65($input);

        self::assertSame(13, $result->getValue());
    }
}

