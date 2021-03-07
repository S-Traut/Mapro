<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210306160533 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, magasin_id INT NOT NULL, type_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_23A0E6620096AE3 (magasin_id), INDEX IDX_23A0E66C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori_magasin (id INT AUTO_INCREMENT NOT NULL, id_utilisateur_id INT NOT NULL, id_magasin INT NOT NULL, INDEX IDX_3923FB00C6EE5C49 (id_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_C53D045F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, INDEX IDX_BFD3CE8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, type_magasin_id INT DEFAULT NULL, id_utilisateur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, siren VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_54AF5F273D0ABCC6 (type_magasin_id), INDEX IDX_54AF5F27C6EE5C49 (id_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistique_article (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, date DATE NOT NULL, nbvue INT NOT NULL, UNIQUE INDEX UNIQ_7BD87D827294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistique_magasin (id INT AUTO_INCREMENT NOT NULL, magasin_id INT NOT NULL, date DATE NOT NULL, nbvue INT NOT NULL, UNIQUE INDEX UNIQ_2D4D2CC320096AE3 (magasin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_magasin (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, is_verified VARCHAR(15) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6620096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C54C8C93 FOREIGN KEY (type_id) REFERENCES type_article (id)');
        $this->addSql('ALTER TABLE favori_magasin ADD CONSTRAINT FK_3923FB00C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F273D0ABCC6 FOREIGN KEY (type_magasin_id) REFERENCES type_magasin (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F27C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE statistique_article ADD CONSTRAINT FK_7BD87D827294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE statistique_magasin ADD CONSTRAINT FK_2D4D2CC320096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7294869C');
        $this->addSql('ALTER TABLE statistique_article DROP FOREIGN KEY FK_7BD87D827294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6620096AE3');
        $this->addSql('ALTER TABLE statistique_magasin DROP FOREIGN KEY FK_2D4D2CC320096AE3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C54C8C93');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F273D0ABCC6');
        $this->addSql('ALTER TABLE favori_magasin DROP FOREIGN KEY FK_3923FB00C6EE5C49');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FA76ED395');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F27C6EE5C49');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE favori_magasin');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE statistique_article');
        $this->addSql('DROP TABLE statistique_magasin');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE type_magasin');
        $this->addSql('DROP TABLE `user`');
    }
}
