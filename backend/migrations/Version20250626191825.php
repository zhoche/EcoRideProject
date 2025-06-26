<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626191825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3423909 FOREIGN KEY (driver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9B3D7CD0C3423909 ON ride (driver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP ride_ids
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1B80E4867E3C61F9 ON vehicle (owner_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C3423909
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9B3D7CD0C3423909 ON ride
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD ride_ids JSON DEFAULT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1B80E4867E3C61F9 ON vehicle
        SQL);
    }
}
