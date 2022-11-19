<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119221443 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE substitution (id INT AUTO_INCREMENT NOT NULL, ean_code_to_replace VARCHAR(255) NOT NULL, ean_code_of_substitute VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substitution_user (substitution_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8D1A6DAFD7F487C9 (substitution_id), INDEX IDX_8D1A6DAFA76ED395 (user_id), PRIMARY KEY(substitution_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE substitution_user ADD CONSTRAINT FK_8D1A6DAFD7F487C9 FOREIGN KEY (substitution_id) REFERENCES substitution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substitution_user ADD CONSTRAINT FK_8D1A6DAFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE substitution_user DROP FOREIGN KEY FK_8D1A6DAFD7F487C9');
        $this->addSql('ALTER TABLE substitution_user DROP FOREIGN KEY FK_8D1A6DAFA76ED395');
        $this->addSql('DROP TABLE substitution');
        $this->addSql('DROP TABLE substitution_user');
    }
}
