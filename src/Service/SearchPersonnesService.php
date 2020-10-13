<?php


namespace App\Service;



use App\Entity\TgAdrMail;
use App\Entity\TgComite;
use App\Entity\TgFavoris;
use App\Entity\TgMcLibre;
use App\Entity\TgMotCleErc;
use App\Entity\TgOrganisme;
use App\Entity\TgParticipation;
use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use App\Entity\TlPersonneMcErc;
use App\Entity\TlPersonneMcLibre;
use App\Entity\TlPersOrg;
use App\Entity\TrGenre;
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


class SearchPersonnesService
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
    public function findPersonnes($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, $total)
    {
        if("email" == $columnName)  $columnName = 'tg_utilisateur.'.$columnName;
        if("lbNomUsage" == $columnName)  $columnName = 'tg_personne.'.$columnName;
        if("idGenre" == $columnName)  $columnName = 'tr_genre.lbLong';
        if("lbLangue" == $columnName)  $columnName = 'tr_langue.'.$columnName;
        if("lbAcr" == $columnName)  $columnName = 'tg_comite.'.$columnName;
        if("organismes" == $columnName)  $columnName = 'tg_organisme.lbNomFr';
        if("mcLibres" == $columnName)  $columnName = 'tg_mc_libre.lbNom';
        if("mcErcs" == $columnName)  $columnName = 'tg_mot_cle_erc.lbNomFr';


        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tg_personne')
            ->from(TgPersonne::class, 'tg_personne')
            ->leftJoin(
                User::class,
                'tg_utilisateur',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersonne = tg_utilisateur.idPersonne'
            )
            ->leftJoin(
                TlPersOrg::class,
                'tl_pers_org',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersonne = tl_pers_org.idPersonne'
            )
            ->leftJoin(
                TgParticipation::class,
                'tg_participation',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersonne = tg_participation.idPersonne'
            )
            ->leftJoin(
                TgPersCps::class,
                'tg_pers_cps',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersCps = tg_pers_cps.idPersCps'
            )
            ->leftJoin(
                TrLangue::class,
                'tr_langue',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_pers_cps.lbLangue = tr_langue.cdLangue'
            )
            ->leftJoin(
                TgComite::class,
                'tg_comite',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_participation.idComite = tg_comite.idComite'
            )
            ->leftJoin(
                TgOrganisme::class,
                'tg_organisme',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tl_pers_org.idOrganisme = tg_organisme.idOrganisme'
            )
            ->leftJoin(
                TlPersonneMcErc::class,
                'tl_personne_mc_erc',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersonne = tl_personne_mc_erc.idPersonne'
            )
            ->leftJoin(
                TlPersonneMcLibre::class,
                'tl_personne_mc_libre',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idPersonne = tl_personne_mc_libre.idPersonne'
            )
            ->leftJoin(
                TgMotCleErc::class,
                'tg_mot_cle_erc',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tl_personne_mc_erc.idMcErc = tg_mot_cle_erc.idMcErc'
            )
            ->leftJoin(
                TgMcLibre::class,
                'tg_mc_libre',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tl_personne_mc_libre.idMcLibre = tg_mc_libre.idMcLibre'
            )
            ->leftJoin(
                TrGenre::class,
                'tr_genre',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tg_personne.idGenre = tr_genre.idGenre'
            )
        ;

        if($filter) {
            $filterSql = $filter['sql'];
            $filterSql = str_replace(":tg_personne.lbNomUsage", ":lbNomUsage", $filterSql);
            $filterSql = str_replace(":tg_utilisateur.email", ":email", $filterSql);
            $filterSql = str_replace(":tr_genre.lbLong", ":lbLong", $filterSql);
            $filterSql = str_replace(":tr_langue.lbLangue", ":lbLangue", $filterSql);
            $filterSql = str_replace(":tg_comite.lbAcr", ":lbAcr", $filterSql);
            $filterSql = str_replace(":tg_organisme.lbNomFr", ":lbNomFr", $filterSql);
            $filterSql = str_replace(":tg_mc_libre.lbNom", ":lbNom", $filterSql);
            $filterSql = str_replace(":tg_mot_cle_erc.lbNomFr", ":lbNomFr", $filterSql);

            $qb->where($filterSql);

            if(array_key_exists('params', $filter)) {
                $filterParams = $filter['params'];

                foreach($filterParams as $key=>$value)
                {
                    // $key = substr($key, 0, -2);
                    $key = str_replace("tg_personne.lbNomUsage", "lbNomUsage", $key);
                    $key = str_replace("tg_utilisateur.email", "email", $key);
                    $key = str_replace("tr_genre.lbLong", "lbLong", $key);
                    $key = str_replace("tr_langue.lbLangue", "lbLangue", $key);
                    $key = str_replace("tg_comite.lbAcr", "lbAcr", $key);
                    $key = str_replace("tg_organisme.lbNomFr", "lbNomFr", $key);
                    $key = str_replace("tg_mc_libre.lbNom", "lbNom", $key);
                    $key = str_replace("tg_mot_cle_erc.lbNomFr", "lbNomFr", $key);

                    $qb->setParameter($key, $value);
                }
            }
        }

        if($searchValue) {
            $qb->andWhere('tg_personne.lbNomUsage like :searchNom 
            or tg_utilisateur.email like :searchNom 
            or tr_genre.lbLong like :searchNom 
            or tr_langue.lbLangue like :searchNom 
            or tg_comite.lbAcr like :searchNom 
            or tg_organisme.lbNomFr like :searchNom 
            or tg_mc_libre.lbNom like :searchNom 
            or tg_mot_cle_erc.lbNomFr like :searchNom 
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
        //  dd($query->getSQL());
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