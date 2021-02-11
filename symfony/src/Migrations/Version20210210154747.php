<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210154747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session_member_frage DROP INDEX UNIQ_E3ABFC357513DBE6, ADD INDEX IDX_E3ABFC357513DBE6 (frage_id)');
        $this->addSql('ALTER TABLE session_member_frage DROP INDEX UNIQ_E3ABFC35D0FBF6F6, ADD INDEX IDX_E3ABFC35D0FBF6F6 (sessionmember_id)');
        $this->addSql('ALTER TABLE session_member_frage CHANGE sessionmember_id sessionmember_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session_member_frage DROP INDEX IDX_E3ABFC35D0FBF6F6, ADD UNIQUE INDEX UNIQ_E3ABFC35D0FBF6F6 (sessionmember_id)');
        $this->addSql('ALTER TABLE session_member_frage DROP INDEX IDX_E3ABFC357513DBE6, ADD UNIQUE INDEX UNIQ_E3ABFC357513DBE6 (frage_id)');
        $this->addSql('ALTER TABLE session_member_frage CHANGE sessionmember_id sessionmember_id INT NOT NULL');
    }
}
