<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704131137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE layout_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_891391B4989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, template_id INT NOT NULL, position INT NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_2D737AEF166D1F9C (project_id), INDEX IDX_2D737AEF5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE section_image (section_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_526A633BD823E37A (section_id), INDEX IDX_526A633B3DA5256D (image_id), PRIMARY KEY(section_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section ADD CONSTRAINT FK_2D737AEF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section ADD CONSTRAINT FK_2D737AEF5DA0FB8 FOREIGN KEY (template_id) REFERENCES layout_template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section_image ADD CONSTRAINT FK_526A633BD823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section_image ADD CONSTRAINT FK_526A633B3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO layout_template (name, slug) VALUES
                ('Text', 'text'),
                ('Image', 'image'),
                ('Text and Image', 'text_image'),
                ('Image and Text', 'image_text'),
                ('Images grid', 'images_grid')
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section_image DROP FOREIGN KEY FK_526A633BD823E37A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE section_image DROP FOREIGN KEY FK_526A633B3DA5256D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE layout_template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE section
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE section_image
        SQL);
    }
}
