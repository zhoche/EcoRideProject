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
        $rides = $this->em->getRepository(Ride::class)->findAll();
        $allUsers = $this->em->getRepository(User::class)->findAll();

        // Filtrer manuellement les passagers ayant ROLE_USER
        $passengers = array_filter($allUsers, function (User $user) {
            return in_array('ROLE_USER', $user->getRoles());
        });

        if (!$rides || empty($passengers)) {
            $output->writeln('❌ Aucun trajet ou passager trouvé.');
            return Command::FAILURE;
        }

        $statuses = ['en attente', 'à traiter', 'refusé', 'validé'];
        $comments = ['Super trajet !', 'Bonne ambiance.', 'Conduite agréable.', 'Un peu de retard.'];

        foreach ($rides as $ride) {
            foreach ($passengers as $passenger) {
                if ($ride->getDriver()?->getId() === $passenger->getId()) {
                    continue;
                }

                $avis = new Avis();
                $avis->setRide($ride)
                     ->setDriver($ride->getDriver())
                     ->setPassenger($passenger)
                     ->setRating(rand(3, 5))
                     ->setComment($comments[array_rand($comments)])
                     ->setStatus($statuses[array_rand($statuses)]);

                $this->em->persist($avis);
                $output->writeln("✅ Avis ajouté pour {$passenger->getPseudo()} sur {$ride->getDeparture()} → {$ride->getArrival()}");
            }
        }

        $this->em->flush();
        $output->writeln("🎉 Tous les avis ont été enregistrés.");

        return Command::SUCCESS;
    }
}
