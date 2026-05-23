<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsers extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->addColumn('name',       'string',    ['limit' => 100, 'null' => false])
            ->addColumn('email',      'string',    ['limit' => 150, 'null' => false])
            ->addColumn('password',   'string',    ['limit' => 255, 'null' => false])
            ->addColumn('role',       'enum',      ['values' => ['customer', 'admin', 'staff'], 'default' => 'customer', 'null' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
