<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126102220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON customer (email)');
        $this->addSql('ALTER TABLE role_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE role_user ADD PRIMARY KEY (user_id, role_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_81398E09E7927C74 ON customer');
        $this->addSql('ALTER TABLE role_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE role_user ADD PRIMARY KEY (role_id, user_id)');
    }
}
