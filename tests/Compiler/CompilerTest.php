<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler;

use Awwar\MasterpiecePhp\AddOns\BasicNodes\BaseAddon;
use Awwar\MasterpiecePhp\Compiler\CompileContext;
use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Config\Config;
use Awwar\MasterpiecePhp\Config\ContractName;
use Awwar\MasterpiecePhp\Config\EndpointName;
use Awwar\MasterpiecePhp\Config\NodeFullName;
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

        $settings->addAddOn(new BaseAddon());

        $settings->addConfig(
            new Config(
                name: 'base_endpoint',
                type: 'endpoint',
                params: [
                    'template' => new EndpointName('base', 'wrap'),
                    'node' => new NodeFullName(addonName: 'app', nodeTemplateName: 'my_test_node'),
                ]
            )
        );

        $settings->addConfig(
            new Config(
                name: 'my_test_node',
                type: 'node',
                params: [
                    'input'   => [
                        [
                            'contract' => new ContractName('base', 'integer'),
                            'name'     => 'a',
                        ],
                    ],
                    'output'  => [
                        [
                            'contract' => new ContractName('base', 'integer'),
                            'name'     => 'b',
                        ],
                    ],
                    'sockets' => [
                        'socket_1' => [
                            'node_alias' => 'number_node_1',
                            'transition' => [
                                [
                                    'condition' => true,
                                    'socket'    => 'socket_2',
                                ],
                            ],
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
                            'transition' => [
                                [
                                    'condition' => true,
                                    'socket'    => 'socket_3',
                                ],
                            ],
                        ],
                        'socket_3' => [
                            'node_alias' => 'if_node_1',
                            'input'      => [
                                [
                                    'node_alias' => 'socket_2',
                                ],
                            ],
                            'transition' => [
                                [
                                    'condition' => true,
                                    'socket'    => 'socket_6',
                                ],
                                [
                                    'condition' => false,
                                    'socket'    => 'socket_4',
                                ],
                            ],
                        ],
                        'socket_4' => [
                            'node_alias' => 'number_node_2',
                            'transition' => [
                                [
                                    'condition' => true,
                                    'socket'    => 'socket_5',
                                ],
                            ],
                        ],
                        'socket_5' => [
                            // this is fragment
                            'node_alias' => 'output',
                            'input'      => [
                                [
                                    'node_alias' => 'socket_4',
                                ],
                            ],
                        ],
                        'socket_6' => [
                            // this is fragment
                            'node_alias' => 'output',
                            'input'      => [
                                [
                                    'node_alias' => 'socket_2',
                                ],
                            ],
                        ],
                    ],
                    'nodes'   => [
                        'number_node_1'     => [
                            'option' => [
                                'value' => 3,
                            ],
                            'node'   => new NodeFullName(addonName: 'base', nodeTemplateName: 'number'),
                        ],
                        'number_node_2'     => [
                            'option' => [
                                'value' => 0,
                            ],
                            'node'   => new NodeFullName(addonName: 'base', nodeTemplateName: 'number'),
                        ],
                        'additional_node_1' => [
                            'option' => [],
                            'node'   => new NodeFullName(addonName: 'base', nodeTemplateName: 'addition'),
                        ],
                        'output'            => [
                            'option' => [],
                            'node'   => new NodeFullName(addonName: 'base', nodeTemplateName: 'output'),
                        ],
                        'if_node_1'         => [
                            'option' => [
                                'condition' => "$0 > 0",
                            ],
                            'node'   => new NodeFullName(addonName: 'base', nodeTemplateName: 'if'),
                        ],
                    ],
                ]
            )
        );

        $compiler->compile($settings);

        self::assertDirectoryExists($settings->getGenerationPath());

        include_once $settings->getGenerationPath() . '/app_my_test_node_node.php';
        include_once $settings->getGenerationPath() . '/base_addition_node.php';
        include_once $settings->getGenerationPath() . '/base_integer_contract.php';
        include_once $settings->getGenerationPath() . '/base_wrap_endpoint.php';

        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_addition_node::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_integer_contract::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_wrap_endpoint::class));

        $input = \Awwar\MasterpiecePhp\App\base_integer_contract::cast_from_mixed(10);

        $result = \Awwar\MasterpiecePhp\App\base_wrap_endpoint::execute_for_base_endpoint($input);

        self::assertSame(13, $result->getValue());
    }
}

