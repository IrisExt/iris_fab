<?php


namespace App\Service;



use App\Entity\TgAdrMail;
use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgCoordinationProj;
use App\Entity\TgFavoris;
use App\Entity\TgMcLibre;
use App\Entity\TgMotCleErc;
use App\Entity\TgOrganisme;
use App\Entity\TgPartenariat;
use App\Entity\TgParticipation;
use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Entity\TlPersonneMcErc;
use App\Entity\TlPersonneMcLibre;
use App\Entity\TlPersOrg;
use App\Entity\TrGenre;
use App\Entity\TrInstFi;
use App\Entity\TrLangue;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class SearchProjetsService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        TranslatorInterface $translator,
        ValidatorInterface $validator,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->registry = $registry;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->session = $session;
    }

    public function rulesAdd($nameRules, $rules, $tgUtilisateur, $typFavoris)
    {
        try {
            $tgFavoris = new TgFavoris();
            $tgFavoris->setLbNom($nameRules);
            $tgFavoris->setIdUser($tgUtilisateur);
            $tgFavoris->setParametre($rules);
            $tgFavoris->setTypFavoris($typFavoris);

            $em = $this->registry->getEntityManager();
            $em->persist($tgFavoris);
            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param $partenaireId
     * @return QueryBuilder
     */
    public function findProjets($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, $total)
    {
        if("lbAppel" == $columnName)  $columnName = 'tg_appel_proj.'.$columnName;
        if("lbAcro" == $columnName)  $columnName = 'tg_projet.lbAcro';
        if("coord" == $columnName)  $columnName = 'tg_personne_coord.lbNomUsage';
        if("lbAcr" == $columnName)  $columnName = 'tg_comite.'.$columnName;
        if("cps" == $columnName)  $columnName = 'tg_personne_cps.lbNomUsage';
        if("idInfraFi" == $columnName)  $columnName = 'tr_inst_fi.lbNom';
        if("mcLibres" == $columnName)  $columnName = 'tg_mc_libre.lbNom';
        if("mcErcs" == $columnName)  $columnName = 'tg_mot_cle_erc.lbNomFr';
        if("partenaires" == $columnName)  $columnName = 'tg_organisme.lbNomFr';
        if("respsc" == $columnName)  $columnName = 'tg_personne_respsc.lbNomUsage';


        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tg_projet')
            ->from(TgProjet::class, 'tg_projet')
            ->leftJoin(
                TgAppelProj::class,
                'tg_appel_proj',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idAppel = tg_appel_proj.idAppel'
            )
            ->leftJoin(
                TgComite::class,
                'tg_comite',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idComite = tg_comite.idComite'
            )
            ->leftJoin(
                'tg_projet.porteur',
                'porteur'
            )
            ->leftJoin(
                TgCoordinationProj::class,
                'tg_coordination_proj',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idProjet = tg_coordination_proj.idProjet'
            )
            ->leftJoin(
                TgPersonne::class,
                'tg_personne_coord',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_coordination_proj.idPersonne = tg_personne_coord.idPersonne'
            )
            ->leftJoin(
                TgPersonne::class,
                'tg_personne_cps',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'porteur.idPersonne = tg_personne_cps.idPersonne'
            )
            ->leftJoin(
                TgPersCps::class,
                'tg_pers_cps',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne_cps.idPersCps = tg_pers_cps.idPersCps'
            )
            ->leftJoin(
                'tg_projet.idMcErc',
                'tl_mc_erc_proj'
            )
            ->leftJoin(
                TgMotCleErc::class,
                'tg_mot_cle_erc',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tl_mc_erc_proj.idMcErc = tg_mot_cle_erc.idMcErc'
            )
            ->leftJoin(
                TgMcLibre::class,
                'tg_mc_libre',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idProjet = tg_mc_libre.idProjet'
            )
            ->leftJoin(
                TrInstFi::class,
                'tr_inst_fi',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idInfraFi = tr_inst_fi.idInstFi'
            )
            ->leftJoin(
                TgPartenariat::class,
                'tg_partenariat',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_projet.idProjet = tg_partenariat.idProjet'
            )
            ->leftJoin(
                TgPersonne::class,
                'tg_personne_respsc',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_partenariat.respScient = tg_personne_respsc.idPersonne'
            )
            ->leftJoin(
                TgOrganisme::class,
                'tg_organisme',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_partenariat.hebergeur = tg_organisme.idOrganisme'
            );
        ;

        if($filter) {
            $filterSql = $filter['sql'];
            $filterSql = str_replace(":tg_appel_proj.lbAppel", ":lbAppel", $filterSql);
            $filterSql = str_replace(":tg_projet.lbAcro", ":lbAcro", $filterSql);
            $filterSql = str_replace(":tg_personne_cps.lbNomUsage", ":lbNomUsage", $filterSql);
            $filterSql = str_replace(":tg_personne_coord.lbNomUsage", ":lbNomUsage", $filterSql);
            $filterSql = str_replace(":tg_personne_respsc.lbNomUsage", ":lbNomUsage", $filterSql);
            $filterSql = str_replace(":tg_comite.lbAcr", ":lbAcr", $filterSql);
            $filterSql = str_replace(":tg_organisme.lbNomFr", ":lbNomFr", $filterSql);
            $filterSql = str_replace(":tg_mc_libre.lbNom", ":lbNom", $filterSql);
            $filterSql = str_replace(":tg_mot_cle_erc.lbMcErc", ":lbNomFr", $filterSql);
            $filterSql = str_replace(":tr_inst_fi.lbNom", ":lbNom", $filterSql);

            $qb->where($filterSql);

            if(array_key_exists('params', $filter)) {
                $filterParams = $filter['params'];

                foreach($filterParams as $key=>$value)
                {
                    //$key = substr($key, 0, -2);
                    $key = str_replace("tg_appel_proj.lbAppel", "lbAppel", $key);
                    $key = str_replace("tg_projet.lbAcro", "lbAcro", $key);
                    $key = str_replace("tg_personne_cps.lbNomUsage", "lbNomUsage", $key);
                    $key = str_replace("tg_personne_coord.lbNomUsage", "lbNomUsage", $key);
                    $key = str_replace("tg_personne_respsc.lbNomUsage", "lbNomUsage", $key);
                    $key = str_replace("tg_comite.lbAcr", "lbAcr", $key);
                    $key = str_replace("tg_organisme.lbNomFr", "lbNomFr", $key);
                    $key = str_replace("tg_mc_libre.lbNom", "lbNom", $key);
                    $key = str_replace("tg_mot_cle_erc.lbMcErc", "lbNomFr", $key);
                    $key = str_replace("tr_inst_fi.lbNom", "lbNom", $key);

                    $qb->setParameter($key, $value);
                }
            }
        }

        if($searchValue) {
            $qb->andWhere('tg_appel_proj.lbAppel like :searchNom 
              or tg_projet.lbAcro like :searchNom 
              or tg_personne_cps.lbNomUsage like :searchNom
              or tg_personne_coord.lbNomUsage like :searchNom
              or tg_personne_respsc.lbNomUsage like :searchNom
              or tg_mc_libre.lbNom like :searchNom
              or tg_mot_cle_erc.lbNomFr like :searchNom
              or tg_comite.lbAcr like :searchNom
              or tr_inst_fi.lbNom like :searchNom
              or tg_organisme.lbNomFr like :searchNom
            ')

                ->setParameter(':searchNom', '%'.$searchValue.'%');
        }

        if ($searchQuery) {
            $qb->andWhere($searchQuery);
        }

        if (0 === $total) {
            $qb->orderBy($columnName, $columnSortOrder)
                ->setFirstResult($row)
                ->setMaxResults($rowperpage)
            ;
        }

        $query = $qb->getQuery();
        // dd($query->getSQL());
        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;

    }

    /**
     * @param $tgFavoris
     * @param $name
     * @param $action
     */
    public function UpdateFavoris($tgFavoris, $name, $action)
    {
        if ("edit" == $action) {
            try {
                $tgFavoris->setLbNom($name);
                $em = $this->registry->getEntityManager();
                $em->persist($tgFavoris);
                $em->flush();

                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.favoris'));
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.favoris'));
            }
        }
        if ("delete" == $action) {
            try {
                $em = $this->registry->getEntityManager();
                $em->remove($tgFavoris);
                $em->flush();

                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.favoris'));
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.favoris'));
            }
        }

    }

}