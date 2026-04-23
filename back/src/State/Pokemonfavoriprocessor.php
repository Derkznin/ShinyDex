<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Contract\UserOwnedInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Injecte automatiquement l'utilisateur connecté sur les entités UserOwnedInterface.
 * Utilisé par PokemonFavori — distinct de ObtentionProcessor pour rester sémantiquement correct.
 */
class PokemonFavoriProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof UserOwnedInterface) {
            $data->setUtilisateur($this->security->getUser());
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}