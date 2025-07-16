<?php

namespace App\Command;

use App\Entity\Avis;
use App\Entity\Ride;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'create:test-avis',
    description: 'Crée des avis de test pour les trajets.',
)]
class CreateAvisCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rides    = $this->em->getRepository(Ride::class)->findAll();
        $allUsers = $this->em->getRepository(User::class)->findAll();

        // On ne retient comme passagers que les ROLE_USER
        $passengers = array_filter($allUsers, fn(User $u) => in_array('ROLE_USER', $u->getRoles()));

        if (empty($rides) || empty($passengers)) {
            $output->writeln('❌ Aucun trajet ou passager disponible.');
            return Command::FAILURE;
        }

        $statuses = ['en attente', 'à traiter', 'refusé', 'validé'];
        $comments = ['Super trajet !', 'Bonne ambiance.', 'Conduite agréable.', 'Un peu de retard.'];

        foreach ($rides as $ride) {
            $driver = $ride->getDriver();
            foreach ($passengers as $passenger) {
                // On n’émet pas d’avis de passager sur son propre trajet
                if ($driver && $driver->getId() === $passenger->getId()) {
                    continue;
                }

                $avis = new Avis();
                $avis->setRide($ride)
                     ->setDriver($driver)
                     ->setPassenger($passenger)
                     ->setRating(rand(3, 5))
                     ->setComment($comments[array_rand($comments)])
                     ->setStatus($statuses[array_rand($statuses)])
                     // Génère un token aléatoire hexadécimal de 16 caractères
                     ->setToken(bin2hex(random_bytes(8)))
                     // Validation aléatoire true/false
                     ->setIsValidated((bool) rand(0, 1));

                $this->em->persist($avis);

                $output->writeln(sprintf(
                    '✅ Avis #%d:%s → %s par %s (note : %d, statut : %s, validé : %s)',
                    $avis->getRide()?->getId(),
                    $ride->getDeparture(),
                    $ride->getArrival(),
                    $passenger->getPseudo(),
                    $avis->getRating(),
                    $avis->getStatus(),
                    $avis->isValidated() ? 'oui' : 'non'
                ));
            }
        }

        $this->em->flush();
        $output->writeln('🎉 Tous les avis ont été enregistrés.');

        return Command::SUCCESS;
    }
}
