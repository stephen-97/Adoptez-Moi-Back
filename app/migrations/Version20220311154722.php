<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311154722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A5EB747A3');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6061F7CF');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ABE20CAB0');
        $this->addSql('DROP INDEX IDX_5F9E962A5EB747A3 ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962A6061F7CF ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962ABE20CAB0 ON comments');
        $this->addSql('ALTER TABLE comments ADD animal_id INT NOT NULL, ADD sender_id INT NOT NULL, ADD receiver_id INT NOT NULL, DROP animal_id_id, DROP sender_id_id, DROP receiver_id_id');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A8E962C16 ON comments (animal_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AF624B39D ON comments (sender_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962ACD53EDB6 ON comments (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8E962C16');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF624B39D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ACD53EDB6');
        $this->addSql('DROP INDEX IDX_5F9E962A8E962C16 ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962AF624B39D ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962ACD53EDB6 ON comments');
        $this->addSql('ALTER TABLE comments ADD animal_id_id INT NOT NULL, ADD sender_id_id INT NOT NULL, ADD receiver_id_id INT NOT NULL, DROP animal_id, DROP sender_id, DROP receiver_id');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A5EB747A3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6061F7CF FOREIGN KEY (sender_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ABE20CAB0 FOREIGN KEY (receiver_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5F9E962A5EB747A3 ON comments (animal_id_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A6061F7CF ON comments (sender_id_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962ABE20CAB0 ON comments (receiver_id_id)');
    }
}
