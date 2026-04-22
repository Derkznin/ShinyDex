<?php

namespace App\Entity\Contract;

use App\Entity\Utilisateur;

interface UserOwnedInterface
{
    public function setUtilisateur(?Utilisateur $utilisateur): static;
}
