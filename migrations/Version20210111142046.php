<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111142046 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP INDEX UNIQ_23A0E66C54C8C93, ADD INDEX IDX_23A0E66C54C8C93 (type_id)');
        $this->addSql('ALTER TABLE magasin ADD adresse VARCHAR(255) NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL, CHANGE latitude latitude DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP INDEX IDX_23A0E66C54C8C93, ADD UNIQUE INDEX UNIQ_23A0E66C54C8C93 (type_id)');
        $this->addSql('ALTER TABLE magasin DROP adresse, CHANGE latitude latitude VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE longitude longitude VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
