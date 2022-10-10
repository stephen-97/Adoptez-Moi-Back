<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423133517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8E962C16');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAB0FA336');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAB0FA336 FOREIGN KEY (answer_to_id) REFERENCES comments (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8E962C16');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAB0FA336');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAB0FA336 FOREIGN KEY (answer_to_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
