<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728151923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `employee_employee` (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(64) NOT NULL, pesel VARCHAR(11) NOT NULL, birth_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CDA02466E7927C74 (email), INDEX IDX_CDA02466708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `employee_gender` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `employee_jwt_refresh_token` (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_995ACB14C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `employee_employee` ADD CONSTRAINT FK_CDA02466708A0E0 FOREIGN KEY (gender_id) REFERENCES `employee_gender` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `employee_employee` DROP FOREIGN KEY FK_CDA02466708A0E0');
        $this->addSql('DROP TABLE `employee_employee`');
        $this->addSql('DROP TABLE `employee_gender`');
        $this->addSql('DROP TABLE `employee_jwt_refresh_token`');
    }
}
