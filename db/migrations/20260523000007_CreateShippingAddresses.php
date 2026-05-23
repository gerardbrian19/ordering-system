<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateShippingAddresses extends AbstractMigration
{
    public function change(): void
    {
        $this->table('shipping_addresses')
            ->addColumn('user_id',     'integer',   ['null' => false])
            ->addColumn('name',        'string',    ['limit' => 100, 'null' => false])
            ->addColumn('address',     'string',    ['limit' => 255, 'null' => false])
            ->addColumn('city',        'string',    ['limit' => 100, 'null' => false])
            ->addColumn('province',    'string',    ['limit' => 100, 'null' => false])
            ->addColumn('postal_code', 'string',    ['limit' => 10,  'null' => false])
            ->addColumn('phone',       'string',    ['limit' => 20,  'null' => false])
            ->addColumn('is_default',  'boolean',   ['default' => false, 'null' => false])
            ->addColumn('created_at',  'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
