<?php
namespace Migrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
/**
 * A migration class. It either upgrades the databases schema (moves it to new state)
 * or downgrades it to the previous state.
 */
class Version20181127112350 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This is the initial migration which creates the user table.';
        return $description;
    }

    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create 'user' table
        $table = $schema->createTable('booking');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('email', 'string', ['notnull'=>true, 'length'=>512]);
        $table->addColumn('image', 'string', ['notnull'=>true, 'length'=>512]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
        // Create 'guest' table
        $table = $schema->createTable('category');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('is_main', 'string', ['notnull'=>true, 'length'=>512]);
        $table->addColumn('image', 'string', ['notnull'=>true, 'length'=>512]);
        $table->addColumn('category', 'string', ['notnull'=>true, 'length'=>512]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('booking');
        $schema->dropTable('category');
    }
}