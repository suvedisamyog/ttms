<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterBooking extends AbstractMigration
{
    public function change(): void
    {
        // Access the table 'bookings'
        $table = $this->table('bookings');

        // Modify the columns:
        $table->changeColumn('package_id', 'integer', ['null' => false])
              ->changeColumn('user_id', 'integer', ['null' => false])
              ->removeColumn('created_at')
              ->update();
    }
}
