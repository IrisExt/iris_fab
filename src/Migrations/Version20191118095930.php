<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118095930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE tl_mc_erc_proj ADD PRIMARY KEY (id_projet, id_mc_erc)');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE tl_proj_mc_ces ADD PRIMARY KEY (id_projet, id_mc_ces)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX tl_mc_erc_proj_pkey');
        $this->addSql('ALTER TABLE tl_mc_erc_proj ADD PRIMARY KEY (id_mc_erc, id_projet)');
        $this->addSql('DROP INDEX tl_proj_mc_ces_pkey');
        $this->addSql('ALTER TABLE tl_proj_mc_ces ADD PRIMARY KEY (id_mc_ces, id_projet)');
    }
}
