<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813122914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE register_token (id INT AUTO_INCREMENT NOT NULL, coach_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_893565C33C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE register_token ADD CONSTRAINT FK_893565C33C105691 FOREIGN KEY (coach_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE register_token DROP FOREIGN KEY FK_893565C33C105691');
        $this->addSql('DROP TABLE register_token');
    }
}
