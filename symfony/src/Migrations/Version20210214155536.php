<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214155536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session DROP INDEX UNIQ_D044D5D47513DBE6, ADD INDEX IDX_D044D5D47513DBE6 (frage_id)');
        $this->addSql('ALTER TABLE session DROP count_ratings');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session DROP INDEX IDX_D044D5D47513DBE6, ADD UNIQUE INDEX UNIQ_D044D5D47513DBE6 (frage_id)');
        $this->addSql('ALTER TABLE session ADD count_ratings INT DEFAULT NULL');
    }
}
