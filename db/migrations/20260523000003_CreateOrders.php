<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrders extends AbstractMigration
{
    public function change(): void
    {
        $this->table('orders')
            ->addColumn('user_id',    'integer',   ['null' => false])
            ->addColumn('total',      'decimal',   ['precision' => 10, 'scale' => 2, 'null' => false])
            ->addColumn('status',     'enum',      ['values' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled'], 'default' => 'pending', 'null' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
