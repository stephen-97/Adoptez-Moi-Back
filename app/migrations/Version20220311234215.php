<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311234215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD answer_to_id INT DEFAULT NULL, ADD is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAB0FA336 FOREIGN KEY (answer_to_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AAB0FA336 ON comments (answer_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAB0FA336');
        $this->addSql('DROP INDEX IDX_5F9E962AAB0FA336 ON comments');
        $this->addSql('ALTER TABLE comments DROP answer_to_id, DROP is_read');
    }
}
