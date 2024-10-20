<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PackageTable extends AbstractMigration
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
		$table = $this->table('packages');
		$table->addColumn('name', 'string', [
			'limit' => 150,
			'null' => false,
		])
		->addColumn('total_travelers', 'integer', [
			'null' => false,
		])
		->addColumn('days', 'integer', [
			'null' => false,
		])
		->addColumn('nights', 'integer', [
			'null' => false,
		])
		->addColumn('price', 'decimal', [
			'precision' => 10,
			'scale' => 2,
			'null' => false,
		])
		->addColumn('deadline', 'datetime', [
			'null' => false,
		])
		->addColumn('thumbnail', 'string', [
			'limit' => 255,
			'null' => false,
		])
		->addColumn('discount', 'integer', [
			'null' => true,
			'default' => 0,
		])
		->addColumn('category', 'json', [
			'limit' => 255,
			'null' => true,
		])
		->addColumn('other_images', 'json', [
			'limit' => 255,
			'null' => true,

		])
		->addColumn('description', 'text')
		->addTimestamps(null, false)
		->create();


    }
}
