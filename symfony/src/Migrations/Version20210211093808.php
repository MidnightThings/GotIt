<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211093808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE session_member_session_member_frage (session_member_id INT NOT NULL, session_member_frage_id INT NOT NULL, INDEX IDX_F31979BC367596F4 (session_member_id), INDEX IDX_F31979BCBDEC3679 (session_member_frage_id), PRIMARY KEY(session_member_id, session_member_frage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session_member_session_member_frage ADD CONSTRAINT FK_F31979BC367596F4 FOREIGN KEY (session_member_id) REFERENCES session_member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_member_session_member_frage ADD CONSTRAINT FK_F31979BCBDEC3679 FOREIGN KEY (session_member_frage_id) REFERENCES session_member_frage (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE session_member_session_member_frage');
    }
}
