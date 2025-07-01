<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701134221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6C1197C9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C53D045F6C1197C9 ON image
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image CHANGE project_id_id project_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F166D1F9C ON image (project_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP FOREIGN KEY FK_C53D045F166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C53D045F166D1F9C ON image
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image CHANGE project_id project_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F6C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F6C1197C9 ON image (project_id_id)
        SQL);
    }
}
