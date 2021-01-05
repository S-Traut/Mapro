<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105102740 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_23A0E66C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_C53D045F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, INDEX IDX_BFD3CE8FFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, type_magasin_id INT NOT NULL, image_id INT NOT NULL, localisation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, siren VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_54AF5F273D0ABCC6 (type_magasin_id), UNIQUE INDEX UNIQ_54AF5F273DA5256D (image_id), UNIQUE INDEX UNIQ_54AF5F27C68BE09C (localisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistique_article (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, date DATE NOT NULL, nbvue INT NOT NULL, UNIQUE INDEX UNIQ_7BD87D827294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistique_magasin (id INT AUTO_INCREMENT NOT NULL, magasin_id INT NOT NULL, date DATE NOT NULL, nbvue INT NOT NULL, UNIQUE INDEX UNIQ_2D4D2CC320096AE3 (magasin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_magasin (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, motdepasse VARCHAR(255) NOT NULL, role INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C54C8C93 FOREIGN KEY (type_id) REFERENCES type_article (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F273D0ABCC6 FOREIGN KEY (type_magasin_id) REFERENCES type_magasin (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F273DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F27C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
        $this->addSql('ALTER TABLE statistique_article ADD CONSTRAINT FK_7BD87D827294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE statistique_magasin ADD CONSTRAINT FK_2D4D2CC320096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7294869C');
        $this->addSql('ALTER TABLE statistique_article DROP FOREIGN KEY FK_7BD87D827294869C');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F273DA5256D');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F27C68BE09C');
        $this->addSql('ALTER TABLE statistique_magasin DROP FOREIGN KEY FK_2D4D2CC320096AE3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C54C8C93');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F273D0ABCC6');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FFB88E14F');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE statistique_article');
        $this->addSql('DROP TABLE statistique_magasin');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE type_magasin');
        $this->addSql('DROP TABLE utilisateur');
    }
}
