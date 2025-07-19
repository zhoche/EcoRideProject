<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719200841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ride_user (ride_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(ride_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C6ACE33D302A8A70 ON ride_user (ride_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C6ACE33DA76ED395 ON ride_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE suspended_user (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, suspended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33D302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ALTER is_validated DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP CONSTRAINT fk_ride_driver
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP CONSTRAINT fk_ride_vehicle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ALTER price TYPE DOUBLE PRECISION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3423909 FOREIGN KEY (driver_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER email TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER pseudo TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER credits DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER gender TYPE VARCHAR(2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER gender TYPE VARCHAR(2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER created_at DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER rating TYPE DOUBLE PRECISION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER is_verified DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX users_email_key RENAME TO UNIQ_1483A5E9E7927C74
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle DROP CONSTRAINT fk_vehicle_owner
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ALTER plate_number TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ALTER is_electric DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP CONSTRAINT FK_C6ACE33D302A8A70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP CONSTRAINT FK_C6ACE33DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ride_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE suspended_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP CONSTRAINT FK_9B3D7CD0C3423909
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP CONSTRAINT FK_9B3D7CD0545317D1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ALTER price TYPE NUMERIC(5, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT fk_ride_driver FOREIGN KEY (driver_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT fk_ride_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4867E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ALTER is_electric SET DEFAULT false
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ALTER plate_number TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ADD CONSTRAINT fk_vehicle_owner FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ALTER is_validated SET DEFAULT false
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER email TYPE VARCHAR(180)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER pseudo TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER gender TYPE CHAR(1)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER gender TYPE CHAR(1)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER credits SET DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER created_at SET DEFAULT 'now()'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER rating TYPE NUMERIC(2, 1)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER is_verified SET DEFAULT false
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO users_email_key
        SQL);
    }
}
