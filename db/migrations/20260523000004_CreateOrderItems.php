<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrderItems extends AbstractMigration
{
    public function change(): void
    {
        $this->table('order_items')
            ->addColumn('order_id',   'integer', ['null' => false])
            ->addColumn('product_id', 'integer', ['null' => false])
            ->addColumn('quantity',   'integer', ['null' => false])
            ->addColumn('unit_price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false])
            ->addForeignKey('order_id',   'orders',   'id', ['delete' => 'CASCADE',  'update' => 'NO_ACTION'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->create();
    }
}
