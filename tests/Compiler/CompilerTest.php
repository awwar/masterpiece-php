<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler;

use Awwar\MasterpiecePhp\AddOns\BasicNodes\BaseAddon;
use Awwar\MasterpiecePhp\Compiler\CompileContext;
use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Compiler\Util\ContractName;
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

        $settings->addAddOn(new BaseAddon());

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
                            'contract' =>  new ContractName('base', 'integer'),
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
                                'addon'   => 'base',
                                'pattern' => 'number',
                            ],
                        ],
                        'additional_node_1' => [
                            'option' => [],
                            'node'   => [
                                'addon'   => 'base',
                                'pattern' => 'addition',
                            ],
                        ],
                    ],
                ]
            )
        );

        $compiler->compile($settings);

        self::assertDirectoryExists($settings->getGenerationPath());

        include_once $settings->getGenerationPath() . '/app_my_test_flow_node.php';
        include_once $settings->getGenerationPath() . '/base_addition_node.php';
        include_once $settings->getGenerationPath() . '/base_number_node.php';
        include_once $settings->getGenerationPath() . '/base_integer_contract.php';

        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\app_my_test_flow_node::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_addition_node::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_number_node::class));
        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\App\base_integer_contract::class));

        $input = \Awwar\MasterpiecePhp\App\base_integer_contract::cast_from_mixed(10);

        $result = \Awwar\MasterpiecePhp\App\app_my_test_flow_node::execute_94def2b019f4c1e0453cc2c78ec275ba($input);

        self::assertSame(13, $result->getValue());
    }
}

