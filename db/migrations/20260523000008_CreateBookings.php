<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBookings extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bookings')
            ->addColumn('customer_id',  'integer',   ['null' => false])
            ->addColumn('service_type', 'string',    ['limit' => 100, 'null' => false])
            ->addColumn('notes',        'text',      ['null' => true])
            ->addColumn('scheduled_at', 'datetime',  ['null' => false])
            ->addColumn('status',       'enum',      ['values' => ['pending', 'confirmed', 'completed', 'cancelled'], 'default' => 'pending', 'null' => false])
            ->addColumn('created_at',   'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('customer_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
