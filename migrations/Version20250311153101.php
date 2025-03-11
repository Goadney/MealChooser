<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311153101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, name VARCHAR(90) NOT NULL, INDEX IDX_4B60114FBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, duree VARCHAR(50) NOT NULL, weekend TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas_ingredients (repas_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_44C73EFF1D236AAA (repas_id), INDEX IDX_44C73EFF3EC4DCE (ingredients_id), PRIMARY KEY(repas_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas_saisons (repas_id INT NOT NULL, saisons_id INT NOT NULL, INDEX IDX_627B51271D236AAA (repas_id), INDEX IDX_627B512798E2D5DF (saisons_id), PRIMARY KEY(repas_id, saisons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saisons (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT FK_4B60114FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE repas_ingredients ADD CONSTRAINT FK_44C73EFF1D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_ingredients ADD CONSTRAINT FK_44C73EFF3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_saisons ADD CONSTRAINT FK_627B51271D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_saisons ADD CONSTRAINT FK_627B512798E2D5DF FOREIGN KEY (saisons_id) REFERENCES saisons (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY FK_4B60114FBCF5E72D');
        $this->addSql('ALTER TABLE repas_ingredients DROP FOREIGN KEY FK_44C73EFF1D236AAA');
        $this->addSql('ALTER TABLE repas_ingredients DROP FOREIGN KEY FK_44C73EFF3EC4DCE');
        $this->addSql('ALTER TABLE repas_saisons DROP FOREIGN KEY FK_627B51271D236AAA');
        $this->addSql('ALTER TABLE repas_saisons DROP FOREIGN KEY FK_627B512798E2D5DF');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE repas');
        $this->addSql('DROP TABLE repas_ingredients');
        $this->addSql('DROP TABLE repas_saisons');
        $this->addSql('DROP TABLE saisons');
    }
}
