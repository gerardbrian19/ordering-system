<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddIndexes extends AbstractMigration
{
    public function change(): void
    {
        // Non-FK indexes for frequently filtered columns
        // (FK columns are automatically indexed by InnoDB when constraints are created)
        $this->table('orders')
            ->addIndex(['status'])
            ->save();

        $this->table('products')
            ->addIndex(['category'])
            ->save();
    }
}
