<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Obtention;
use App\Entity\ObtentionTag;
use App\Entity\PokemonFavori;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    // Appelée sur GetCollection → liste de ressources
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->addUserFilter($queryBuilder, $resourceClass);
    }

    // Appelée sur Get → une seule ressource par id
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?Operation $operation = null, array $context = []): void
    {
        $this->addUserFilter($queryBuilder, $resourceClass);
    }

    private function addUserFilter(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        // On ne filtre que les entités concernées
        if (!in_array($resourceClass, [Obtention::class, ObtentionTag::class, PokemonFavori::class])) {
            return;
        }

        $user = $this->security->getUser();

        // Zero Trust : si pas d'utilisateur connecté, aucune donnée
        if (null === $user) {
            $queryBuilder->andWhere('1 = 0');

            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        // ObtentionTag n'a pas de champ utilisateur direct → on joint via Obtention
        if (ObtentionTag::class === $resourceClass) {
            $queryBuilder
                ->join(sprintf('%s.obtention', $rootAlias), 'o')
                ->andWhere('o.utilisateur = :current_user')
                ->setParameter('current_user', $user);
        } else {
            $queryBuilder
                ->andWhere(sprintf('%s.utilisateur = :current_user', $rootAlias))
                ->setParameter('current_user', $user);
        }
    }
}
