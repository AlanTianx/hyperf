<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as BaseCommand;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;

/**
 * @Command
 * Class GenerateModel
 * @package App\Command
 */
class GenerateModel extends BaseCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('gen:model:all');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Generate Model Command');
    }

    public function handle()
    {
        $db = env('DB_DATABASE');
        $prefix = env('DB_PREFIX');

        $tables = Db::select('show tables');
        foreach ($tables as $key => $table) {
            $table = $table['Tables_in_' . $db];
            $name = str_replace($prefix, '', $table);
            $class = Str::studly($name);
            $model = $this->template($class, $name, $prefix);
            file_put_contents(BASE_PATH . '/app/Model/' . $class . '.php', $model);
        }

        $models = glob(BASE_PATH . '/app/Module/Model/*.php');
        foreach ($models as $model) {
            $target = BASE_PATH . '/app/Model/' . basename($model);
            if (file_exists($target)) {
                rename($target, $model);
            }
        }

        $this->line('Done', 'info');
    }

    private function tableInfo($table)
    {
        $pk = null;
        $fields = '';

        $info = Db::select('desc ' . $table);

        foreach ($info as $key => $item) {
            if ($key > 0) {
                $fields .= ",\n";
            }
            $fields .= "        '{$item['Field']}'";

            if ($item['Key'] === 'PRI') {
                $pk = $item['Field'];
            }
        }

        return ['fillable' => $fields, 'pk' => $pk];
    }

    private function template($class, $table, $prefix)
    {
        $info = $this->tableInfo($prefix . $table);

        $tpl = <<<EOF
<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * Class {$class}
 * @package App\Model
 */
class {$class} extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected \$table = '{$table}';

EOF;

        if (!is_null($info['pk'])) {
            $tpl .= <<<EOF

    /**
     * @var string
     */
    protected \$primaryKey = '{$info['pk']}';

EOF;
        }

        $tpl .= <<<EOF

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected \$fillable = [
{$info['fillable']}
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected \$casts = [];
}

EOF;

        return $tpl;
    }
}
