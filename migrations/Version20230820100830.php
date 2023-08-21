<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820100830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beer (id INT AUTO_INCREMENT NOT NULL, brewery_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, abv DOUBLE PRECISION NOT NULL, ibu INT NOT NULL, INDEX IDX_58F666ADD15C960 (brewery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brewery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE checkin (id INT AUTO_INCREMENT NOT NULL, beer_id INT NOT NULL, user_id INT NOT NULL, score DOUBLE PRECISION NOT NULL, INDEX IDX_E1631C91D0989053 (beer_id), INDEX IDX_E1631C91A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, username VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beer ADD CONSTRAINT FK_58F666ADD15C960 FOREIGN KEY (brewery_id) REFERENCES brewery (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE checkin ADD CONSTRAINT FK_E1631C91D0989053 FOREIGN KEY (beer_id) REFERENCES beer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE checkin ADD CONSTRAINT FK_E1631C91A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beer DROP FOREIGN KEY FK_58F666ADD15C960');
        $this->addSql('ALTER TABLE checkin DROP FOREIGN KEY FK_E1631C91D0989053');
        $this->addSql('ALTER TABLE checkin DROP FOREIGN KEY FK_E1631C91A76ED395');
        $this->addSql('DROP TABLE beer');
        $this->addSql('DROP TABLE brewery');
        $this->addSql('DROP TABLE checkin');
        $this->addSql('DROP TABLE user');
    }
}
