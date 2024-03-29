<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221145138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carts ADD user_id INT DEFAULT NULL, CHANGE total total DOUBLE PRECISION DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT FK_4E004AACA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4E004AACA76ED395 ON carts (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY FK_4E004AACA76ED395');
        $this->addSql('DROP INDEX IDX_4E004AACA76ED395 ON carts');
        $this->addSql('ALTER TABLE carts DROP user_id, CHANGE total total DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }
}
