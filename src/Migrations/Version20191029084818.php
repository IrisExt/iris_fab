<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029084818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create schema for postgres database';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_adr_mail (id_adr_mail BIGINT NOT NULL, id_personne BIGINT DEFAULT NULL, adr_mail VARCHAR(40) NOT NULL, adr_pref BOOLEAN DEFAULT NULL, PRIMARY KEY(id_adr_mail))');
        $this->addSql('CREATE UNIQUE INDEX adr_mail_pk ON tg_adr_mail (adr_mail, id_adr_mail)');
        $this->addSql('CREATE INDEX tl_pers_adr_mail_fk ON tg_adr_mail (id_personne)');
        $this->addSql('COMMENT ON COLUMN tg_adr_mail.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_comite_dep (id_comite BIGINT NOT NULL, id_departement BIGINT NOT NULL, PRIMARY KEY(id_comite, id_departement))');
        $this->addSql('CREATE INDEX idx_ab3309dfa367f72 ON tl_comite_dep (id_comite)');
        $this->addSql('CREATE INDEX idx_ab3309dd9649694 ON tl_comite_dep (id_departement)');
        $this->addSql('COMMENT ON COLUMN tl_comite_dep.id_departement IS \'Identifiant du département \'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_compte_bancaire (id_compte BIGINT NOT NULL, iban VARCHAR(40) DEFAULT NULL, rib VARCHAR(40) DEFAULT NULL, banque VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id_compte))');
        $this->addSql('CREATE UNIQUE INDEX compte_bancaire_pk ON tg_compte_bancaire (id_compte)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_bloc (id_bloc BIGINT NOT NULL, parent BIGINT DEFAULT NULL, lb_bloc VARCHAR(50) NOT NULL, typ_bloc VARCHAR(3) DEFAULT NULL, id_bloc_eval BIGINT DEFAULT NULL, class_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_bloc))');
        $this->addSql('CREATE UNIQUE INDEX tg_bloc_pk ON tg_bloc (id_bloc)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_habilitation (id_habilitation BIGINT NOT NULL, id_profil BIGINT DEFAULT NULL, id_phase BIGINT DEFAULT NULL, id_comite BIGINT DEFAULT NULL, id_personne BIGINT DEFAULT NULL, id_appel BIGINT DEFAULT NULL, dh_maj DATE DEFAULT NULL, lb_resp_maj VARCHAR(50) DEFAULT NULL, bl_supprime SMALLINT DEFAULT NULL, PRIMARY KEY(id_habilitation))');
        $this->addSql('CREATE INDEX association_98_fk ON tg_habilitation (id_personne)');
        $this->addSql('CREATE UNIQUE INDEX tg_habilitation_pk ON tg_habilitation (id_habilitation)');
        $this->addSql('CREATE INDEX idx_40709a82c0e1077a ON tg_habilitation (id_profil)');
        $this->addSql('CREATE INDEX association_100_fk ON tg_habilitation (id_phase)');
        $this->addSql('CREATE INDEX association_99_fk ON tg_habilitation (id_appel)');
        $this->addSql('CREATE INDEX association_97_fk ON tg_habilitation (id_comite)');
        $this->addSql('COMMENT ON COLUMN tg_habilitation.id_profil IS \'idnetifiant du profil de l\'\'utilisateur\'');
        $this->addSql('COMMENT ON COLUMN tg_habilitation.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_habilitation.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->addSql('COMMENT ON COLUMN tg_habilitation.bl_supprime IS \'booleen qui indique que la participation de la personne au comité a été supprimée\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_mc_erc (id_mc_erc BIGINT NOT NULL, id_appel BIGINT DEFAULT NULL, id_disc_erc BIGINT DEFAULT NULL, lb_mc_erc VARCHAR(50) NOT NULL, PRIMARY KEY(id_mc_erc))');
        $this->addSql('CREATE INDEX a_pour_millesime_fk ON tg_mc_erc (id_appel)');
        $this->addSql('CREATE UNIQUE INDEX mot_cle_erc_pk ON tg_mc_erc (id_mc_erc)');
        $this->addSql('CREATE INDEX mot_cle_fait_partie_fk ON tg_mc_erc (id_disc_erc)');
        $this->addSql('COMMENT ON COLUMN tg_mc_erc.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_mc_erc_proj (id_mc_erc BIGINT NOT NULL, id_projet BIGINT NOT NULL, PRIMARY KEY(id_mc_erc, id_projet))');
        $this->addSql('CREATE INDEX idx_5032a015e9cd38fe ON tl_mc_erc_proj (id_mc_erc)');
        $this->addSql('CREATE INDEX idx_5032a01576222944 ON tl_mc_erc_proj (id_projet)');
        $this->addSql('COMMENT ON COLUMN tl_mc_erc_proj.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_mc_libre (id_mc_libre BIGINT NOT NULL, id_projet BIGINT DEFAULT NULL, lb_nom VARCHAR(50) NOT NULL, lb_nom_en VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_mc_libre))');
        $this->addSql('CREATE INDEX est_associe_a_projet_fk ON tg_mc_libre (id_projet)');
        $this->addSql('CREATE UNIQUE INDEX tg_mc_libre_pk ON tg_mc_libre (id_mc_libre)');
        $this->addSql('COMMENT ON COLUMN tg_mc_libre.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_message (id_message BIGINT NOT NULL, destinataire BIGINT DEFAULT NULL, emetteur BIGINT DEFAULT NULL, id_comite BIGINT DEFAULT NULL, id_participation BIGINT DEFAULT NULL, dh_envoi TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, texte VARCHAR(200) NOT NULL, PRIMARY KEY(id_message))');
        $this->addSql('CREATE INDEX idx_83fdaf92157d332a ON tg_message (id_participation)');
        $this->addSql('CREATE INDEX est_emetteur_fk ON tg_message (emetteur)');
        $this->addSql('CREATE INDEX est_dest_fk ON tg_message (destinataire)');
        $this->addSql('CREATE UNIQUE INDEX tg2_message_pk ON tg_message (id_message)');
        $this->addSql('CREATE INDEX pour_fk ON tg_message (id_comite)');
        $this->addSql('COMMENT ON COLUMN tg_message.destinataire IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_message.emetteur IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_mot_cle_cps (id_mc_cps BIGINT NOT NULL, id_personne BIGINT DEFAULT NULL, lb_mc_cps_fr VARCHAR(200) DEFAULT NULL, lb_mc_cps_en VARCHAR(200) DEFAULT NULL, PRIMARY KEY(id_mc_cps))');
        $this->addSql('CREATE INDEX association_80_fk ON tg_mot_cle_cps (id_personne)');
        $this->addSql('CREATE UNIQUE INDEX tg_mot_cle_cps_pk ON tg_mot_cle_cps (id_mc_cps)');
        $this->addSql('COMMENT ON COLUMN tg_mot_cle_cps.id_mc_cps IS \'identifiant du mot cle\'');
        $this->addSql('COMMENT ON COLUMN tg_mot_cle_cps.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_mot_cle_cv (id_mc_cv BIGINT NOT NULL, id_personne BIGINT DEFAULT NULL, lb_mc_fr VARCHAR(255) DEFAULT NULL, lb_mc_en VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_mc_cv))');
        $this->addSql('CREATE INDEX mot_cle_par_cv_fk ON tg_mot_cle_cv (id_personne)');
        $this->addSql('COMMENT ON COLUMN tg_mot_cle_cv.id_mc_cv IS \'identifiant du mot cle\'');
        $this->addSql('COMMENT ON COLUMN tg_mot_cle_cv.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_mot_cle_cv.lb_mc_fr IS \'libellé du mot clé\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_parametre (id_parametre BIGINT NOT NULL, id_appel BIGINT DEFAULT NULL, lb_code VARCHAR(20) NOT NULL, lb_valeur VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_parametre))');
        $this->addSql('CREATE UNIQUE INDEX tg_parametre_pk ON tg_parametre (id_parametre)');
        $this->addSql('CREATE INDEX sont_relatif_a_fk ON tg_parametre (id_appel)');
        $this->addSql('COMMENT ON COLUMN tg_parametre.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_pers_cps (id_pers_cps BIGINT NOT NULL, id_genre BIGINT DEFAULT NULL, lb_web_perso VARCHAR(100) DEFAULT NULL, bl_sexe BOOLEAN DEFAULT NULL, lb_langue VARCHAR(50) DEFAULT NULL, lb_nom_fr VARCHAR(50) DEFAULT NULL, lb_prenom VARCHAR(50) DEFAULT NULL, lb_adr_mail VARCHAR(255) DEFAULT NULL, lb_ville_heberg VARCHAR(100) DEFAULT NULL, lb_organisme VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id_pers_cps))');
        $this->addSql('CREATE INDEX idx_926f6c96dd572c8 ON tg_pers_cps (id_genre)');
        $this->addSql('CREATE UNIQUE INDEX tg_pers_cps_pk ON tg_pers_cps (id_pers_cps)');
        $this->addSql('COMMENT ON COLUMN tg_pers_cps.lb_web_perso IS \'Site web peson de la personne saisi par le cps\'');
        $this->addSql('COMMENT ON COLUMN tg_pers_cps.lb_prenom IS \'Prénom d\'\'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_personne (id_personne BIGINT NOT NULL, id_genre BIGINT DEFAULT NULL, id_civilite BIGINT DEFAULT NULL, id_pers_cps BIGINT DEFAULT NULL, lb_nom_usage VARCHAR(50) NOT NULL, lb_prenom VARCHAR(50) NOT NULL, cd_francophone VARCHAR(3) DEFAULT NULL, lb_web_perso VARCHAR(100) DEFAULT NULL, fonction VARCHAR(50) DEFAULT NULL, orcid VARCHAR(30) DEFAULT NULL, dt_soutenance_these BIGINT DEFAULT NULL, cv_renseigne BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id_personne))');
        $this->addSql('CREATE INDEX a_pour_genre_fk ON tg_personne (id_genre)');
        $this->addSql('CREATE INDEX est_saisi_par_cps_fk ON tg_personne (id_pers_cps)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d14017b58773c406 ON tg_personne (id_pers_cps)');
        $this->addSql('CREATE UNIQUE INDEX tg_personne_pk ON tg_personne (id_personne)');
        $this->addSql('CREATE INDEX a_pour_civilite_fk ON tg_personne (id_civilite)');
        $this->addSql('COMMENT ON COLUMN tg_personne.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_personne.lb_nom_usage IS \'Nom d\'\'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_personne.lb_prenom IS \'Prénom d\'\'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_phase (id_phase BIGINT NOT NULL, id_appel BIGINT DEFAULT NULL, lb_phase VARCHAR(50) NOT NULL, ord_phase INT NOT NULL, bl_phase_courante BOOLEAN DEFAULT NULL, PRIMARY KEY(id_phase))');
        $this->addSql('CREATE INDEX projet_phase_fk ON tg_phase (id_appel)');
        $this->addSql('CREATE UNIQUE INDEX tg_phase_pk ON tg_phase (id_phase)');
        $this->addSql('COMMENT ON COLUMN tg_phase.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_pers_org (id_personne BIGINT NOT NULL, id_organisme BIGINT NOT NULL, PRIMARY KEY(id_personne, id_organisme))');
        $this->addSql('CREATE INDEX idx_d03042a55d3af914 ON tl_pers_org (id_organisme)');
        $this->addSql('CREATE INDEX idx_d03042a55f15257a ON tl_pers_org (id_personne)');
        $this->addSql('COMMENT ON COLUMN tl_pers_org.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_pol_proj (id_projet BIGINT NOT NULL, id_pole_comp BIGINT NOT NULL, PRIMARY KEY(id_projet, id_pole_comp))');
        $this->addSql('CREATE INDEX idx_24df23ef76222944 ON tl_pol_proj (id_projet)');
        $this->addSql('CREATE INDEX idx_24df23efdb76890f ON tl_pol_proj (id_pole_comp)');
        $this->addSql('COMMENT ON COLUMN tl_pol_proj.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_proj_non_souhait (id_projet BIGINT NOT NULL, id_non_souhaite BIGINT NOT NULL, PRIMARY KEY(id_projet, id_non_souhaite))');
        $this->addSql('CREATE INDEX idx_9696db6a270597fc ON tl_proj_non_souhait (id_non_souhaite)');
        $this->addSql('CREATE INDEX idx_9696db6a76222944 ON tl_proj_non_souhait (id_projet)');
        $this->addSql('COMMENT ON COLUMN tl_proj_non_souhait.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_co_fi_proj (id_projet BIGINT NOT NULL, id_co_fi BIGINT NOT NULL, PRIMARY KEY(id_projet, id_co_fi))');
        $this->addSql('CREATE INDEX idx_88bdd44cf55920b5 ON tl_co_fi_proj (id_co_fi)');
        $this->addSql('CREATE INDEX idx_88bdd44c76222944 ON tl_co_fi_proj (id_projet)');
        $this->addSql('COMMENT ON COLUMN tl_co_fi_proj.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_infra_proj (id_projet BIGINT NOT NULL, id_inf_rech BIGINT NOT NULL, PRIMARY KEY(id_projet, id_inf_rech))');
        $this->addSql('CREATE INDEX idx_6da4222076222944 ON tl_infra_proj (id_projet)');
        $this->addSql('CREATE INDEX idx_6da42220c7a9bb5b ON tl_infra_proj (id_inf_rech)');
        $this->addSql('COMMENT ON COLUMN tl_infra_proj.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_reunion (id_reunion BIGINT NOT NULL, id_type_reunion BIGINT DEFAULT NULL, id_phase BIGINT DEFAULT NULL, id_comite BIGINT DEFAULT NULL, lb_titre VARCHAR(50) NOT NULL, tx_comment TEXT DEFAULT NULL, dt_deb_periode DATE DEFAULT NULL, dt_fin_periode DATE DEFAULT NULL, nb_duree_max INT DEFAULT NULL, bl_obligatoire BOOLEAN DEFAULT NULL, bl_actif BIGINT NOT NULL, cle VARCHAR(255) NOT NULL, PRIMARY KEY(id_reunion))');
        $this->addSql('CREATE INDEX reunion_prevue_pour_phase_fk ON tg_reunion (id_phase)');
        $this->addSql('CREATE UNIQUE INDEX tg_reunion_pk ON tg_reunion (id_reunion)');
        $this->addSql('CREATE INDEX tl_com_reu_fk ON tg_reunion (id_comite)');
        $this->addSql('CREATE INDEX a_pour_type_reunion_fk ON tg_reunion (id_type_reunion)');
        $this->addSql('COMMENT ON COLUMN tg_reunion.id_reunion IS \'identifiant de la réunion du comité\'');
        $this->addSql('COMMENT ON COLUMN tg_reunion.id_type_reunion IS \'identifiant de la réunion\'');
        $this->addSql('COMMENT ON COLUMN tg_reunion.lb_titre IS \'Titre du comité\'');
        $this->addSql('COMMENT ON COLUMN tg_reunion.dt_fin_periode IS \'Date de fin de période pour programmer les réunions\'');
        $this->addSql('COMMENT ON COLUMN tg_reunion.bl_actif IS \'Précise si la reunion est actif ou supprimé (valeur 0)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_cat_rd (id_cat_rd BIGINT NOT NULL, lb_cat_rd VARCHAR(50) NOT NULL, PRIMARY KEY(id_cat_rd))');
        $this->addSql('CREATE UNIQUE INDEX tr_cat_rd_pk ON tr_cat_rd (id_cat_rd)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_ag_fi (id_agence_fi BIGINT NOT NULL, lb_agenc_fi VARCHAR(50) NOT NULL, PRIMARY KEY(id_agence_fi))');
        $this->addSql('CREATE UNIQUE INDEX agence_de_financement_pk ON tr_ag_fi (id_agence_fi)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_co_fi (id_co_fi BIGINT NOT NULL, lb_co_fi VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_co_fi))');
        $this->addSql('CREATE UNIQUE INDEX tr_co_fi_pk ON tr_co_fi (id_co_fi)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_seance (id_seance BIGINT NOT NULL, id_reunion BIGINT DEFAULT NULL, matin BOOLEAN DEFAULT NULL, apres_midi BOOLEAN DEFAULT NULL, dt_seance DATE NOT NULL, PRIMARY KEY(id_seance))');
        $this->addSql('CREATE UNIQUE INDEX date_reunion_pk ON tg_seance (id_seance)');
        $this->addSql('CREATE INDEX idx_2a4aea26859d0df2 ON tg_seance (id_reunion)');
        $this->addSql('COMMENT ON COLUMN tg_seance.id_reunion IS \'identifiant de la réunion du comité\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_bloc_form (id_formulaire BIGINT NOT NULL, id_bloc BIGINT NOT NULL, rang INT NOT NULL, PRIMARY KEY(id_formulaire, id_bloc))');
        $this->addSql('CREATE INDEX formulaire_fk ON tl_bloc_form (id_formulaire)');
        $this->addSql('CREATE INDEX a_pour_bloc_fk ON tl_bloc_form (id_bloc)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_lang_pers (id_lang_pers BIGINT NOT NULL, id_niveau SMALLINT DEFAULT NULL, id_personne BIGINT DEFAULT NULL, id_langue SMALLINT DEFAULT NULL, cd_pratique BOOLEAN DEFAULT NULL, PRIMARY KEY(id_lang_pers))');
        $this->addSql('CREATE UNIQUE INDEX tl_lang_pers_pk ON tl_lang_pers (id_lang_pers, id_personne, id_langue)');
        $this->addSql('CREATE UNIQUE INDEX uniq_6d43b5aeb560c063 ON tl_lang_pers (id_langue)');
        $this->addSql('CREATE INDEX personne_pratique_fk ON tl_lang_pers (id_personne)');
        $this->addSql('CREATE INDEX a_pour_maitrise_en_langue_fk ON tl_lang_pers (id_niveau)');
        $this->addSql('CREATE INDEX pratique_langue_fk ON tl_lang_pers (id_langue)');
        $this->addSql('COMMENT ON COLUMN tl_lang_pers.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tl_lang_pers.cd_pratique IS \'Code précisant le niveau de pratique de la langue:lu, parlé, écrite..\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_pers_part (id_partenaire BIGINT NOT NULL, id_personne BIGINT NOT NULL, PRIMARY KEY(id_partenaire, id_personne))');
        $this->addSql('CREATE UNIQUE INDEX tl_pers_part_pk ON tl_pers_part (id_partenaire, id_personne)');
        $this->addSql('CREATE INDEX tl_pers_part_fk ON tl_pers_part (id_partenaire)');
        $this->addSql('CREATE INDEX tl_pers_part2_fk ON tl_pers_part (id_personne)');
        $this->addSql('COMMENT ON COLUMN tl_pers_part.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_reu_pers (id_personne BIGINT NOT NULL, id_seance BIGINT NOT NULL, bl_present INT NOT NULL, PRIMARY KEY(id_personne, id_seance))');
        $this->addSql('CREATE INDEX a_pour_seance_fk ON tl_reu_pers (id_seance)');
        $this->addSql('CREATE INDEX participation_fk ON tl_reu_pers (id_personne)');
        $this->addSql('COMMENT ON COLUMN tl_reu_pers.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_categorie_erc (id_cat_erc BIGINT NOT NULL, lb_cat_erc VARCHAR(50) NOT NULL, PRIMARY KEY(id_cat_erc))');
        $this->addSql('CREATE UNIQUE INDEX categorie_erc_pk ON tr_categorie_erc (id_cat_erc)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_info (cd_info VARCHAR(20) NOT NULL, lb_info TEXT NOT NULL, PRIMARY KEY(cd_info))');
        $this->addSql('CREATE UNIQUE INDEX tr_info_pk ON tr_info (cd_info)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_profil (id_profil BIGINT NOT NULL, lb_profil VARCHAR(50) NOT NULL, cd_profil VARCHAR(5) NOT NULL, lb_role VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_profil))');
        $this->addSql('CREATE UNIQUE INDEX tr_profil_pk ON tr_profil (id_profil)');
        $this->addSql('COMMENT ON COLUMN tr_profil.id_profil IS \'idnetifiant du profil de l\'\'utilisateur\'');
        $this->addSql('COMMENT ON COLUMN tr_profil.lb_profil IS \'désignation du profil de l\'\'utilisateur\'');
        $this->addSql('COMMENT ON COLUMN tr_profil.cd_profil IS \'désignation du profil de l\'\'utilisateur accronyme\'');
        $this->addSql('COMMENT ON COLUMN tr_profil.lb_role IS \'désignation du role de l\'\'utilisateur pour symfony\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_disc_erc (id_disc_erc BIGINT NOT NULL, id_cat_erc BIGINT DEFAULT NULL, lb_disc_erc VARCHAR(50) NOT NULL, PRIMARY KEY(id_disc_erc))');
        $this->addSql('CREATE UNIQUE INDEX discipline_erc_pk ON tr_disc_erc (id_disc_erc)');
        $this->addSql('CREATE INDEX discipline_fait_partie_fk ON tr_disc_erc (id_cat_erc)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_etat_sol (cd_etat_sollicitation BIGINT NOT NULL, lb_etat_sollicitation VARCHAR(40) NOT NULL, PRIMARY KEY(cd_etat_sollicitation))');
        $this->addSql('CREATE UNIQUE INDEX tr_etat_sol_pk ON tr_etat_sol (cd_etat_sollicitation)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_inst_fi (id_infra_fi BIGINT NOT NULL, lb_inst_fi VARCHAR(50) NOT NULL, mnt_max NUMERIC(10, 0) DEFAULT NULL, mnt_min NUMERIC(10, 0) DEFAULT NULL, PRIMARY KEY(id_infra_fi))');
        $this->addSql('CREATE UNIQUE INDEX tr_inst_fi_pk ON tr_inst_fi (id_infra_fi)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_pol_comp (id_pole_comp BIGINT NOT NULL, lb_pol_comp VARCHAR(40) NOT NULL, PRIMARY KEY(id_pole_comp))');
        $this->addSql('CREATE UNIQUE INDEX tr_pol_comp_pk ON tr_pol_comp (id_pole_comp)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_inf_rech (id_inf_rech BIGINT NOT NULL, lb_inf_rech VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_inf_rech))');
        $this->addSql('CREATE UNIQUE INDEX tr_inf_rech_pk ON tr_inf_rech (id_inf_rech)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_niveau_langue (id_niveau SMALLINT NOT NULL, lb_niveau VARCHAR(40) NOT NULL, PRIMARY KEY(id_niveau))');
        $this->addSql('CREATE UNIQUE INDEX tr_niveau_langue_pk ON tr_niveau_langue (id_niveau)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_langue (id_langue SMALLINT NOT NULL, lb_langue VARCHAR(50) NOT NULL, PRIMARY KEY(id_langue))');
        $this->addSql('CREATE UNIQUE INDEX tr_langue_pk ON tr_langue (id_langue)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_mc (id_mc_ces BIGINT NOT NULL, id_comite BIGINT DEFAULT NULL, lb_mc_ces VARCHAR(50) NOT NULL, PRIMARY KEY(id_mc_ces))');
        $this->addSql('CREATE INDEX mc_associe_au_ces_fk ON tr_mc (id_comite)');
        $this->addSql('CREATE UNIQUE INDEX tr_mc_ces_pk ON tr_mc (id_mc_ces)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tl_proj_mc_ces (id_mc_ces BIGINT NOT NULL, id_projet BIGINT NOT NULL, PRIMARY KEY(id_mc_ces, id_projet))');
        $this->addSql('CREATE INDEX idx_81ad4bbf76222944 ON tl_proj_mc_ces (id_projet)');
        $this->addSql('CREATE INDEX idx_81ad4bbff574d0be ON tl_proj_mc_ces (id_mc_ces)');
        $this->addSql('COMMENT ON COLUMN tl_proj_mc_ces.id_projet IS \'identifiant du projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_pays (cd_pays VARCHAR(5) NOT NULL, lb_pays VARCHAR(100) NOT NULL, PRIMARY KEY(cd_pays))');
        $this->addSql('CREATE UNIQUE INDEX tr_pays_pk ON tr_pays (cd_pays)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_adresse (id_adresse BIGINT NOT NULL, cd_pays VARCHAR(5) DEFAULT NULL, id_personne BIGINT DEFAULT NULL, lb_adresse VARCHAR(50) NOT NULL, lb_compl_adresses VARCHAR(100) NOT NULL, cd VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, bl_adr_princ BOOLEAN DEFAULT NULL, typ_adr VARCHAR(3) NOT NULL, PRIMARY KEY(id_adresse))');
        $this->addSql('CREATE INDEX adr_pays_fk ON tg_adresse (cd_pays)');
        $this->addSql('CREATE INDEX idx_f61f97fb5f15257a ON tg_adresse (id_personne)');
        $this->addSql('CREATE UNIQUE INDEX tg_adresse_pk ON tg_adresse (id_adresse)');
        $this->addSql('COMMENT ON COLUMN tg_adresse.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_appel_proj (id_appel BIGINT NOT NULL, pilote BIGINT DEFAULT NULL, id_formulaire BIGINT DEFAULT NULL, dt_millesime INT NOT NULL, lb_appel VARCHAR(255) NOT NULL, lb_acronyme VARCHAR(255) NOT NULL, dt_clo_fin DATE DEFAULT NULL, PRIMARY KEY(id_appel))');
        $this->addSql('CREATE INDEX est_pilote_fk ON tg_appel_proj (pilote)');
        $this->addSql('CREATE INDEX est_prevu_pour_l_appel_fk ON tg_appel_proj (id_formulaire)');
        $this->addSql('CREATE UNIQUE INDEX tg_appel_proj_g_pk ON tg_appel_proj (id_appel)');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.pilote IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.dt_millesime IS \'millesime de l\'\'appel à projet; communement aussi appelé edition  par le metier\'');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.lb_appel IS \'Désignation de l\'\'appel a projet\'');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.lb_acronyme IS \'Désignation abrégée du projet \'');
        $this->addSql('COMMENT ON COLUMN tg_appel_proj.dt_clo_fin IS \'Date de cloture de l\'\'appel à projet\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_formulaire (id_formulaire BIGINT NOT NULL, lb_formulaire VARCHAR(50) NOT NULL, PRIMARY KEY(id_formulaire))');
        $this->addSql('CREATE UNIQUE INDEX formulaire_pk ON tg_formulaire (id_formulaire)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_comite (id_comite BIGINT NOT NULL, id_appel BIGINT DEFAULT NULL, lb_acr VARCHAR(10) NOT NULL, lb_titre VARCHAR(255) NOT NULL, bl_actif BIGINT NOT NULL, lb_desc VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id_comite))');
        $this->addSql('CREATE UNIQUE INDEX tg_comite_pk ON tg_comite (id_comite)');
        $this->addSql('CREATE INDEX est_constitue_dans_le_cadre_fk ON tg_comite (id_appel)');
        $this->addSql('COMMENT ON COLUMN tg_comite.id_appel IS \'identifiant de l\'\'appel à projet\'');
        $this->addSql('COMMENT ON COLUMN tg_comite.lb_acr IS \'acronyme , Code unique du comité ex: CE01, CE02 \'');
        $this->addSql('COMMENT ON COLUMN tg_comite.lb_titre IS \'Titre du comité\'');
        $this->addSql('COMMENT ON COLUMN tg_comite.bl_actif IS \'Précise si le comité est actif ou supprimé (valeur 0)\'');
        $this->addSql('COMMENT ON COLUMN tg_comite.lb_desc IS \'Description du contenu du comité\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_departement (id_departement BIGINT NOT NULL, lb_court VARCHAR(255) NOT NULL, lb_long VARCHAR(50) NOT NULL, PRIMARY KEY(id_departement))');
        $this->addSql('CREATE UNIQUE INDEX tr_departement_pk ON tr_departement (id_departement)');
        $this->addSql('COMMENT ON COLUMN tr_departement.id_departement IS \'Identifiant du département \'');
        $this->addSql('COMMENT ON COLUMN tr_departement.lb_court IS \'Désignation du département\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_participation (id_participation BIGINT NOT NULL, cd_etat_sollicitation BIGINT DEFAULT NULL, id_habilitation BIGINT DEFAULT NULL, lb_groupe VARCHAR(400) DEFAULT NULL, prio_grp SMALLINT DEFAULT NULL, bl_supprime SMALLINT DEFAULT NULL, PRIMARY KEY(id_participation))');
        $this->addSql('CREATE UNIQUE INDEX tg_participation_pk ON tg_participation (id_participation)');
        $this->addSql('CREATE INDEX idx_eb6d2594847d521a ON tg_participation (id_habilitation)');
        $this->addSql('CREATE INDEX idx_eb6d2594f8a69e8a ON tg_participation (cd_etat_sollicitation)');
        $this->addSql('COMMENT ON COLUMN tg_participation.bl_supprime IS \'booleen qui indique que la participation de la personne au comité a été supprimée\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_organisme (id_organisme BIGINT NOT NULL, id_compte BIGINT DEFAULT NULL, id_adresse BIGINT DEFAULT NULL, cd_rnsr VARCHAR(20) DEFAULT NULL, lb_nom_fr VARCHAR(50) NOT NULL, siret VARCHAR(50) DEFAULT NULL, sigle VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id_organisme))');
        $this->addSql('CREATE UNIQUE INDEX tg_organisme_pk ON tg_organisme (id_organisme)');
        $this->addSql('CREATE INDEX idx_566f73071dc2a166 ON tg_organisme (id_adresse)');
        $this->addSql('CREATE INDEX idx_566f7307e9c1e78d ON tg_organisme (id_compte)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_genre (id_genre BIGINT NOT NULL, cd_genre VARCHAR(3) NOT NULL, lb_long VARCHAR(50) NOT NULL, PRIMARY KEY(id_genre))');
        $this->addSql('CREATE UNIQUE INDEX tr_genre_pk ON tr_genre (id_genre)');
        $this->addSql('COMMENT ON COLUMN tr_genre.lb_long IS \'valeur littérales: homme, femme, ....\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_civilite (id_civilite BIGINT NOT NULL, civilite_longue VARCHAR(20) NOT NULL, civilite_court VARCHAR(4) NOT NULL, PRIMARY KEY(id_civilite))');
        $this->addSql('CREATE UNIQUE INDEX tr_civilite_pk ON tr_civilite (id_civilite)');
        $this->addSql('COMMENT ON COLUMN tr_civilite.civilite_longue IS \'Code civilité long: Monsieur, Madame , mademoiselle pour la gestion des courriers\'');
        $this->addSql('COMMENT ON COLUMN tr_civilite.civilite_court IS \'Code civilité court: M;, Me, Mlle. \'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tr_type_reunion (id_type_reunion BIGINT NOT NULL, lb_type_reunion VARCHAR(50) NOT NULL, PRIMARY KEY(id_type_reunion))');
        $this->addSql('CREATE UNIQUE INDEX tr_type_reunion_pk ON tr_type_reunion (id_type_reunion)');
        $this->addSql('COMMENT ON COLUMN tr_type_reunion.id_type_reunion IS \'identifiant de la réunion\'');
        $this->addSql('COMMENT ON COLUMN tr_type_reunion.lb_type_reunion IS \'Désignation de la réunion\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_utilisateur (id INT NOT NULL, id_personne BIGINT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX tg_utilisateur_pk ON tg_utilisateur (id_personne, id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_698c8ebdc05fb297 ON tg_utilisateur (confirmation_token)');
        $this->addSql('CREATE INDEX donne_fk ON tg_utilisateur (id_personne)');
        $this->addSql('CREATE UNIQUE INDEX uniq_698c8ebda0d96fbf ON tg_utilisateur (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX uniq_698c8ebd92fc23a8 ON tg_utilisateur (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX uniq_698c8ebd5f15257a ON tg_utilisateur (id_personne)');
        $this->addSql('COMMENT ON COLUMN tg_utilisateur.id_personne IS \'Identifiant d\'\' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)\'');
        $this->addSql('COMMENT ON COLUMN tg_utilisateur.roles IS \'(DC2Type:array)\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_projet (id_projet BIGINT NOT NULL, id_cat_rd BIGINT DEFAULT NULL, id_comite BIGINT DEFAULT NULL, id_agence_fi BIGINT DEFAULT NULL, id_infra_fi BIGINT DEFAULT NULL, lb_acro VARCHAR(10) DEFAULT NULL, lb_titre_fr VARCHAR(50) DEFAULT NULL, classement_global INT DEFAULT NULL, lb_titre_en VARCHAR(50) DEFAULT NULL, no_duree SMALLINT DEFAULT NULL, mnt_aide_prev BIGINT DEFAULT NULL, an_these DATE DEFAULT NULL, bl_mult_fi BOOLEAN DEFAULT NULL, bl_dem_label BOOLEAN DEFAULT NULL, bl_dem_cofi BOOLEAN DEFAULT NULL, ord_phase INT DEFAULT NULL, bl_infra_recherche BOOLEAN DEFAULT NULL, lb_preproposition VARCHAR(255) DEFAULT NULL, lb_annx_ppp VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_projet))');
        $this->addSql('CREATE UNIQUE INDEX tg_projet_pk ON tg_projet (id_projet)');
        $this->addSql('CREATE INDEX utilise_l_instrument_fk ON tg_projet (id_infra_fi)');
        $this->addSql('CREATE INDEX eval_comite_fk ON tg_projet (id_comite)');
        $this->addSql('CREATE INDEX a_pour_categorie_fk ON tg_projet (id_cat_rd)');
        $this->addSql('CREATE INDEX se_finance_par_fk ON tg_projet (id_agence_fi)');
        $this->addSql('COMMENT ON COLUMN tg_projet.id_projet IS \'identifiant du projet\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.lb_acro IS \'acronyme du projet\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.lb_titre_en IS \'titre du projet en anglais\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.no_duree IS \'durée du projet exprimé en mois\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.mnt_aide_prev IS \'Montant d\'\'aide prévisionnel\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.an_these IS \'obligatoire si l’instrument de financement sélectionné est « JCJC ».\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.bl_dem_label IS \'souhait du porteur de labelisation du projet\'');
        $this->addSql('COMMENT ON COLUMN tg_projet.lb_annx_ppp IS \'Libéllé annexe préproposition\'');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tg_non_souhaite (id_non_souhaite BIGINT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, organisme VARCHAR(50) DEFAULT NULL, courriel VARCHAR(50) DEFAULT NULL, lb_motif VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_non_souhaite))');
        $this->addSql('CREATE UNIQUE INDEX tg_non_souhaite_pk ON tg_non_souhaite (id_non_souhaite)');
        $this->addSql('COMMENT ON COLUMN tg_non_souhaite.prenom IS \'Prénom d\'\'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_adr_mail');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_comite_dep');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_compte_bancaire');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_bloc');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_habilitation');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_mc_erc');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_mc_erc_proj');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_mc_libre');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_message');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_mot_cle_cps');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_mot_cle_cv');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_parametre');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_pers_cps');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_personne');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_phase');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_pers_org');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_pol_proj');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_proj_non_souhait');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_co_fi_proj');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_infra_proj');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_reunion');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_cat_rd');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_ag_fi');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_co_fi');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_seance');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_bloc_form');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_lang_pers');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_pers_part');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_reu_pers');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_categorie_erc');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_info');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_profil');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_disc_erc');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_etat_sol');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_inst_fi');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_pol_comp');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_inf_rech');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_niveau_langue');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_langue');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_mc');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tl_proj_mc_ces');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_pays');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_adresse');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_appel_proj');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_formulaire');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_comite');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_departement');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_participation');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_organisme');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_genre');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_civilite');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tr_type_reunion');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_utilisateur');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_projet');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE tg_non_souhaite');
    }
}
