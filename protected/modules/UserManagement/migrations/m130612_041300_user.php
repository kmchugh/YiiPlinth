<?php
// Updates the slug to be more descriptive
class m130612_041300_user extends CDbMigration
{
    public function safeUp()
    {
        // Create the column
        $this->alterColumn('{{User}}', 'Slug', 'title');
    }

    public function safeDown()
    {
        // Alter the column to not allow nulls
        $this->alterColumn('{{User}}', 'Slug', 'guid');
    }
}