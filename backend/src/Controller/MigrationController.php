<?php

namespace App\Controller;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\MigratorConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MigrationController extends AbstractController
{
    private DependencyFactory $migrations;

    public function __construct(DependencyFactory $migrations)
    {
        $this->migrations = $migrations;
    }

    #[Route('/run-migrations', name: 'run_migrations')]
    public function run(): Response
    {
        $migrator = $this->migrations->getMigrator();
        $planCalculator = $this->migrations->getMigrationPlanCalculator();
        $aliasResolver = $this->migrations->getVersionAliasResolver();

        $latestVersion = $aliasResolver->resolveVersionAlias('latest');
        $plan = $planCalculator->getPlanUntilVersion($latestVersion);

        if ($plan->count() === 0) {
            return new Response('✅ Aucune migration à appliquer.');
        }

        $migrator->migrate($plan, new MigratorConfiguration());

        return new Response('✅ Migrations exécutées avec succès.');
    }
}
