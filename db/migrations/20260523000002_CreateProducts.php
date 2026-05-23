<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProducts extends AbstractMigration
{
    public function change(): void
    {
        $this->table('products')
            ->addColumn('name',        'string',    ['limit' => 200, 'null' => false])
            ->addColumn('description', 'text',      ['null' => true])
            ->addColumn('price',       'decimal',   ['precision' => 10, 'scale' => 2, 'null' => false])
            ->addColumn('stock',       'integer',   ['default' => 0, 'null' => false])
            ->addColumn('image_url',   'string',    ['limit' => 255, 'null' => true])
            ->addColumn('category',    'string',    ['limit' => 100, 'null' => true])
            ->addColumn('created_at',  'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->create();
    }
}
