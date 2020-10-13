<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107130813 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE tg_resume_id_resume_seq CASCADE');
        $this->addSql('CREATE TABLE tg_resume (id_projet BIGINT NOT NULL, id_langue SMALLINT NOT NULL, lb_texte TEXT DEFAULT NULL, PRIMARY KEY(id_projet, id_langue))');
        $this->addSql('CREATE INDEX projet_fk ON tg_resume (id_projet)');
        $this->addSql('CREATE INDEX a_pour_langue_fk ON tg_resume (id_langue)');
        $this->addSql('COMMENT ON COLUMN tg_resume.id_projet IS \'identifiant du projet\'');
        $this->addSql('ALTER TABLE tg_resume ADD CONSTRAINT FK_95F6C78876222944 FOREIGN KEY (id_projet) REFERENCES tg_projet (id_projet) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tg_resume ADD CONSTRAINT FK_95F6C788B560C063 FOREIGN KEY (id_langue) REFERENCES tr_langue (id_langue) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE tg_resume_id_resume_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE tg_resume');
    }
}
