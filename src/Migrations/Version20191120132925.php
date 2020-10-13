<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120132925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tg_resume_id_tg_resume_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE tl_mc_erc_proj ADD PRIMARY KEY (id_projet, id_mc_erc)');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE tl_proj_mc_ces ADD PRIMARY KEY (id_projet, id_mc_ces)');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE tg_resume ADD id_tg_resume BIGINT NOT NULL');
        $this->addSql('ALTER TABLE tg_resume ALTER id_projet DROP NOT NULL');
        $this->addSql('ALTER TABLE tg_resume ALTER id_langue DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX tg_resume_pk ON tg_resume (id_tg_resume)');
        $this->addSql('ALTER TABLE tg_resume ADD PRIMARY KEY (id_tg_resume)');
        $this->addSql('ALTER INDEX a_pour_langue_fk RENAME TO IDX_95F6C788B560C063');
        $this->addSql('ALTER INDEX projet_fk RENAME TO est_associe_a_projet_fk');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tg_resume_id_tg_resume_seq CASCADE');
        $this->addSql('DROP INDEX tl_mc_erc_proj_pkey');
        $this->addSql('ALTER TABLE tl_mc_erc_proj ADD PRIMARY KEY (id_mc_erc, id_projet)');
        $this->addSql('DROP INDEX tl_proj_mc_ces_pkey');
        $this->addSql('ALTER TABLE tl_proj_mc_ces ADD PRIMARY KEY (id_mc_ces, id_projet)');
        $this->addSql('DROP INDEX tg_resume_pk');
        $this->addSql('DROP INDEX tg_resume_pkey');
        $this->addSql('ALTER TABLE tg_resume DROP id_tg_resume');
        $this->addSql('ALTER TABLE tg_resume ALTER id_projet SET NOT NULL');
        $this->addSql('ALTER TABLE tg_resume ALTER id_langue SET NOT NULL');
        $this->addSql('ALTER TABLE tg_resume ADD PRIMARY KEY (id_projet, id_langue)');
        $this->addSql('ALTER INDEX idx_95f6c788b560c063 RENAME TO a_pour_langue_fk');
        $this->addSql('ALTER INDEX est_associe_a_projet_fk RENAME TO projet_fk');
    }
}
