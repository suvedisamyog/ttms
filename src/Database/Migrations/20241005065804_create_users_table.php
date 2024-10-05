<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
	public function change()
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string', [
                    'limit' => 50,
                    'null' => false,
                ])
              ->addColumn('email', 'string', [
                    'limit' => 100,
                    'null' => false,
                ])
              ->addColumn('password', 'string', [
                    'limit' => 255,
                    'null' => false,
                ])
              ->addTimestamps(null, false)
              ->addColumn('role', 'string', [
                  'limit' => 20,
                  'null' => false,
                  'default' => 'user',
              ])
              ->addIndex(['email'], ['unique' => true])
              ->create();

        $this->execute('ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ("admin", "user"))');
    }
}
