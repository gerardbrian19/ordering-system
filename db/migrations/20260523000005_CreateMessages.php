<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMessages extends AbstractMigration
{
    public function change(): void
    {
        $this->table('messages')
            ->addColumn('sender_id',   'integer',   ['null' => false])
            ->addColumn('receiver_id', 'integer',   ['null' => false])
            ->addColumn('subject',     'string',    ['limit' => 255, 'null' => true])
            ->addColumn('body',        'text',      ['null' => false])
            ->addColumn('is_read',     'boolean',   ['default' => false, 'null' => false])
            ->addColumn('created_at',  'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('sender_id',   'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('receiver_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
