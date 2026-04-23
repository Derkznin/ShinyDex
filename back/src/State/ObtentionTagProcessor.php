<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ObtentionTag;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ObtentionTagProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof ObtentionTag) {
            $obtention = $data->getObtention();

            // Zero Trust : vérifie que l'Obtention liée appartient au user connecté
            if (null === $obtention || $obtention->getUtilisateur() !== $this->security->getUser()) {
                throw new AccessDeniedHttpException('Vous ne pouvez pas associer un tag à une obtention qui ne vous appartient pas.');
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
