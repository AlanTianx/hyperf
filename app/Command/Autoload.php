<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as BaseCommand;
use Psr\Container\ContainerInterface;

/**
 * @Command
 * Class Autoload
 * @package App\Command
 */
class Autoload extends BaseCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('app:autoload');
    }

    public function configure()
    {
        $this->setDescription('Composer Autoload Command');
    }

    public function handle()
    {
        $path = BASE_PATH . '/vendor/hyperf/';

        $rules = [
            'database/src/Commands/Migrations/BaseCommand.php' => [
                '\'migrations\'' => '\'database/migrations\'',
            ],
            'database/src/Commands/Seeders/BaseCommand.php' => [
                '\'seeders\'' => '\'database/seeders\'',
            ],
            'http-server/src/Router/DispatcherFactory.php' => [
                'config/routes.php' => 'routes/route.php',
            ],
            'session/src/SessionManager.php' => [
                'HYPERF_SESSION_ID' => 'SESSION_ID',
            ],
        ];

        foreach ($rules as $file => $rule) {
            $content = file_get_contents($path . $file);

            $content = str_replace(array_keys($rule), array_values($rule), $content);

            file_put_contents($path . $file, $content);
        }
    }
}
