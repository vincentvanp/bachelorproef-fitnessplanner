<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815134755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token_entity ADD made_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE token_entity ADD CONSTRAINT FK_F50EB6A90B9D269 FOREIGN KEY (made_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F50EB6A90B9D269 ON token_entity (made_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token_entity DROP FOREIGN KEY FK_F50EB6A90B9D269');
        $this->addSql('DROP INDEX IDX_F50EB6A90B9D269 ON token_entity');
        $this->addSql('ALTER TABLE token_entity DROP made_by_id');
    }
}
