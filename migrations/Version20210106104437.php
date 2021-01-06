<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106104437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin ADD adresse VARCHAR(255) NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, CHANGE type_magasin_id type_magasin_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin DROP adresse, DROP latitude, DROP longitude, CHANGE type_magasin_id type_magasin_id INT NOT NULL');
    }
}
