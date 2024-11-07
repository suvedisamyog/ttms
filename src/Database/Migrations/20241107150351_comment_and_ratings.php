<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CommentAndRatings extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('comments_and_ratings');

        $table->addColumn('package_id', 'biginteger', ['null' => false])
              ->addColumn('user_id', 'biginteger', ['null' => false])
              ->addColumn('rating', 'integer', ['null' => false])
              ->addColumn('comment', 'text', ['null' => false])
              ->addColumn('created_at', 'datetime', ['null' => false])
              ->create();
    }
}
