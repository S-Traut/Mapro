<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106085032 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin DROP INDEX UNIQ_54AF5F273D0ABCC6, ADD INDEX IDX_54AF5F273D0ABCC6 (type_magasin_id)');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F27C68BE09C');
        $this->addSql('DROP INDEX UNIQ_54AF5F27C68BE09C ON magasin');
        $this->addSql('ALTER TABLE magasin DROP localisation_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin DROP INDEX IDX_54AF5F273D0ABCC6, ADD UNIQUE INDEX UNIQ_54AF5F273D0ABCC6 (type_magasin_id)');
        $this->addSql('ALTER TABLE magasin ADD localisation_id INT NOT NULL');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F27C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54AF5F27C68BE09C ON magasin (localisation_id)');
    }
}
