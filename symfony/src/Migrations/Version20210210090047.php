<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210090047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4CDB7CAFF');
        $this->addSql('DROP INDEX UNIQ_D044D5D4CDB7CAFF ON session');
        $this->addSql('ALTER TABLE session CHANGE fragefrage_id frage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D47513DBE6 FOREIGN KEY (frage_id) REFERENCES frage (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D044D5D47513DBE6 ON session (frage_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D47513DBE6');
        $this->addSql('DROP INDEX UNIQ_D044D5D47513DBE6 ON session');
        $this->addSql('ALTER TABLE session CHANGE frage_id fragefrage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4CDB7CAFF FOREIGN KEY (fragefrage_id) REFERENCES frage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D044D5D4CDB7CAFF ON session (fragefrage_id)');
    }
}
