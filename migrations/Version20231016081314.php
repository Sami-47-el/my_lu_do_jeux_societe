<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016081314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, description LONGTEXT NOT NULL, date DATE NOT NULL, picture VARCHAR(255) NOT NULL, number_player INT NOT NULL, age_player INT NOT NULL, time_game INT NOT NULL, link_game VARCHAR(255) NOT NULL, INDEX IDX_FF232B319D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B319D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category ADD games_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C197FFC673 FOREIGN KEY (games_id) REFERENCES games (id)');
        $this->addSql('CREATE INDEX IDX_64C19C197FFC673 ON category (games_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C197FFC673');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B319D86650F');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_64C19C197FFC673 ON category');
        $this->addSql('ALTER TABLE category DROP games_id');
    }
}
