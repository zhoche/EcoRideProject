<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250716143352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create/update schema for EcoRide application on PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // ride_user join table
        $this->addSql(<<<'SQL'
            CREATE TABLE ride_user (
                ride_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY (ride_id, user_id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_RIDE_USER_RIDE ON ride_user (ride_id)');
        $this->addSql('CREATE INDEX IDX_RIDE_USER_USER ON ride_user (user_id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user
              ADD CONSTRAINT FK_RIDE_USER_RIDE FOREIGN KEY (ride_id)
                REFERENCES ride (id) ON DELETE CASCADE,
              ADD CONSTRAINT FK_RIDE_USER_USER FOREIGN KEY (user_id)
                REFERENCES users (id) ON DELETE CASCADE
        SQL);

        // avis table
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (
                id SERIAL PRIMARY KEY,
                driver_id INT NOT NULL,
                passenger_id INT,
                ride_id INT,
                rating INT NOT NULL,
                comment TEXT,
                status VARCHAR(255),
                token VARCHAR(255),
                is_validated BOOLEAN NOT NULL
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_AVIS_DRIVER    ON avis (driver_id)');
        $this->addSql('CREATE INDEX IDX_AVIS_PASSENGER ON avis (passenger_id)');
        $this->addSql('CREATE INDEX IDX_AVIS_RIDE      ON avis (ride_id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE avis
              ADD CONSTRAINT FK_AVIS_DRIVER    FOREIGN KEY (driver_id)    REFERENCES users (id) ON DELETE SET NULL,
              ADD CONSTRAINT FK_AVIS_PASSENGER FOREIGN KEY (passenger_id) REFERENCES users (id) ON DELETE SET NULL,
              ADD CONSTRAINT FK_AVIS_RIDE      FOREIGN KEY (ride_id)      REFERENCES ride  (id) ON DELETE SET NULL
        SQL);

        // ride table
        $this->addSql(<<<'SQL'
            CREATE TABLE ride (
                id SERIAL PRIMARY KEY,
                driver_id INT NOT NULL,
                vehicle_id INT NOT NULL,
                departure VARCHAR(255) NOT NULL,
                arrival   VARCHAR(255) NOT NULL,
                date      TIMESTAMP   NOT NULL,
                available_seats INT     NOT NULL,
                price     DOUBLE PRECISION NOT NULL,
                initial_seats   INT     NOT NULL,
                extras    VARCHAR(255),
                status    VARCHAR(20) NOT NULL DEFAULT 'en cours'
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_RIDE_DRIVER  ON ride (driver_id)');
        $this->addSql('CREATE INDEX IDX_RIDE_VEHICLE ON ride (vehicle_id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE ride
              ADD CONSTRAINT FK_RIDE_DRIVER  FOREIGN KEY (driver_id)  REFERENCES users   (id),
              ADD CONSTRAINT FK_RIDE_VEHICLE FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)
        SQL);

        // users table
        $this->addSql(<<<'SQL'
            CREATE TABLE users (
                id SERIAL PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                pseudo VARCHAR(255) NOT NULL,
                roles JSONB NOT NULL,
                gender VARCHAR(2) NOT NULL,
                credits INT NOT NULL,
                driver_preferences JSONB,
                created_at TIMESTAMP NOT NULL,
                image VARCHAR(255),
                rating DOUBLE PRECISION,
                is_verified BOOLEAN NOT NULL
            )
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_EMAIL ON users (email)');

        // vehicle table
        $this->addSql(<<<'SQL'
            CREATE TABLE vehicle (
                id SERIAL PRIMARY KEY,
                owner_id INT NOT NULL,
                brand VARCHAR(255) NOT NULL,
                model VARCHAR(255) NOT NULL,
                is_electric BOOLEAN NOT NULL,
                plate_number VARCHAR(255) NOT NULL
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_VEHICLE_OWNER ON vehicle (owner_id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle
              ADD CONSTRAINT FK_VEHICLE_OWNER FOREIGN KEY (owner_id)
                REFERENCES users (id)
        SQL);

        // suspended_user table
        $this->addSql(<<<'SQL'
            CREATE TABLE suspended_user (
                id SERIAL PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                pseudo VARCHAR(255) NOT NULL,
                role VARCHAR(255) NOT NULL,
                suspended_at TIMESTAMP NOT NULL
            )
        SQL);

        // messenger_messages table
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
                id BIGSERIAL PRIMARY KEY,
                body TEXT NOT NULL,
                headers TEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at TIMESTAMP NOT NULL,
                available_at TIMESTAMP NOT NULL,
                delivered_at TIMESTAMP
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_MESSENGER_QUEUE      ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_MESSENGER_AVAILABLE  ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_MESSENGER_DELIVERED  ON messenger_messages (delivered_at)');

        // trigger function for messenger_messages
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS trigger AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages');
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger
            AFTER INSERT OR UPDATE ON messenger_messages
            FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
    }

    public function down(Schema $schema): void
    {
        // drop messenger trigger & function
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages');
        $this->addSql('DROP FUNCTION IF EXISTS notify_messenger_messages()');

        // drop all tables in reverse order
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
        $this->addSql('DROP TABLE IF EXISTS suspended_user');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT IF EXISTS FK_VEHICLE_OWNER');
        $this->addSql('DROP TABLE IF EXISTS vehicle');
        $this->addSql('DROP TABLE IF EXISTS users');
        $this->addSql('ALTER TABLE ride DROP CONSTRAINT IF EXISTS FK_RIDE_VEHICLE');
        $this->addSql('ALTER TABLE ride DROP CONSTRAINT IF EXISTS FK_RIDE_DRIVER');
        $this->addSql('DROP TABLE IF EXISTS ride');
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT IF EXISTS FK_AVIS_RIDE');
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT IF EXISTS FK_AVIS_PASSENGER');
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT IF EXISTS FK_AVIS_DRIVER');
        $this->addSql('DROP TABLE IF EXISTS avis');
        $this->addSql('ALTER TABLE ride_user DROP CONSTRAINT IF EXISTS FK_RIDE_USER_USER');
        $this->addSql('ALTER TABLE ride_user DROP CONSTRAINT IF EXISTS FK_RIDE_USER_RIDE');
        $this->addSql('DROP TABLE IF EXISTS ride_user');
    }
}
