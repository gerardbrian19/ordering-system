<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOtpTokens extends AbstractMigration
{
    public function change(): void
    {
        $this->table('otp_tokens')
            ->addColumn('user_id',    'integer',   ['null' => false])
            ->addColumn('token',      'string',    ['limit' => 6, 'null' => false])
            ->addColumn('expires_at', 'datetime',  ['null' => false])
            ->addColumn('used_at',    'datetime',  ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
