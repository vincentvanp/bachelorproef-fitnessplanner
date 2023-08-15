<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815094553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_settings (id INT AUTO_INCREMENT NOT NULL, pt_updates TINYINT(1) NOT NULL, new_training TINYINT(1) NOT NULL, new_review TINYINT(1) NOT NULL, monthly_progress TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD account_settings_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64988D3648B FOREIGN KEY (account_settings_id) REFERENCES account_settings (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64988D3648B ON user (account_settings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64988D3648B');
        $this->addSql('DROP TABLE account_settings');
        $this->addSql('DROP INDEX UNIQ_8D93D64988D3648B ON `user`');
        $this->addSql('ALTER TABLE `user` DROP account_settings_id');
    }
}
