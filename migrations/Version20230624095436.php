<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624095436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Project table and define one-to-many relationships to client and vico tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, vico_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, rating INT DEFAULT NULL, review LONGTEXT DEFAULT NULL, communication_rating INT DEFAULT NULL, quality_of_work_rating INT DEFAULT NULL, value_for_money_rating INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_2FB3D0EE61220EA6 (creator_id), INDEX IDX_2FB3D0EE19F89217 (vico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE61220EA6 FOREIGN KEY (creator_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE61220EA6');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19F89217');
        $this->addSql('DROP TABLE project');
    }
}
