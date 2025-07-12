<?php

namespace App\Command;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(
    name: 'app:update-user-images',
    description: 'Met à jour les images de profil des utilisateurs.'
)]
class UpdateUserImagesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customImages = [
            'anthony' => 'Profil_Anthony.png',
            'fabienne' => 'Profil_Fabienne.png',
            'francky' => 'Profil_Francky.png',
            'jerome' => 'Profil_Jerome.png',
            'kati9' => 'Profil_Kati.png',
            'alicia152' => 'Profil_Alicia.png',
        ];
    
        $users = $this->em->getRepository(User::class)->findAll();
    
        foreach ($users as $user) {
            $pseudo = $user->getPseudo();
            if (!$pseudo) continue;
    
            $filename = $customImages[strtolower($pseudo)] ?? 'images/Profil_Base.png';
            $user->setImage($filename);
    
            $output->writeln("📸 $pseudo → $filename");
        }
    
        $this->em->flush();
        $output->writeln("✅ Images de profil mises à jour.");
        return Command::SUCCESS;
    }
    

}