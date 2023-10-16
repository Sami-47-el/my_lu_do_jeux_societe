<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016082251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loaning (id INT AUTO_INCREMENT NOT NULL, game_id_id INT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(100) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, UNIQUE INDEX UNIQ_38DDD8D04D77E7D8 (game_id_id), UNIQUE INDEX UNIQ_38DDD8D09D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loaning ADD CONSTRAINT FK_38DDD8D04D77E7D8 FOREIGN KEY (game_id_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE loaning ADD CONSTRAINT FK_38DDD8D09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loaning DROP FOREIGN KEY FK_38DDD8D04D77E7D8');
        $this->addSql('ALTER TABLE loaning DROP FOREIGN KEY FK_38DDD8D09D86650F');
        $this->addSql('DROP TABLE loaning');
    }
}
