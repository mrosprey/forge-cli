<?php

namespace Sven\ForgeCLI\Tests\Commands;

use Sven\ForgeCLI\Commands\Sites\All;
use Sven\ForgeCLI\Commands\Sites\Delete;
use Sven\ForgeCLI\Commands\Sites\Deploy;
use Sven\ForgeCLI\Commands\Sites\Make;
use Sven\ForgeCLI\Commands\Sites\Show;
use Sven\ForgeCLI\Commands\Sites\Update;
use Sven\ForgeCLI\Tests\TestCase;
use Themsaid\Forge\Resources\Site;

class SitesTest extends TestCase
{
    /** @test */
    public function it_lists_all_sites()
    {
        $this->forge->expects($this->once())
            ->method('sites')
            ->with('12345')
            ->willReturn([
                new Site(['id' => '12345', 'name' => 'A site name']),
            ]);

        $tester = $this->command(All::class);

        $tester->execute([
            'server' => 12345,
        ]);

        $output = $tester->getDisplay();

        $this->assertContains('A site name', $output);
        $this->assertContains('12345', $output);
    }

    /** @test */
    public function it_deletes_a_site()
    {
        $this->forge->expects($this->once())
            ->method('deleteSite')
            ->with('12345', '6789');

        $this->command(Delete::class)
            ->setInputs(['yes'])
            ->execute([
                'server' => 12345,
                'site' => 6789,
            ]);
    }

    /** @test */
    public function it_does_not_delete_a_site_if_no_is_answered()
    {
        $this->forge->expects($this->exactly(0))
            ->method('deleteSite')
            ->with('12345', '6789');

        $tester = $this->command(Delete::class)->setInputs(['no']);

        $tester->execute([
            'server' => 12345,
            'site' => 6789,
        ]);

        $this->assertContains('aborting', $tester->getDisplay());
    }

    /** @test */
    public function it_deploys_a_site()
    {
        $this->forge->expects($this->once())
            ->method('deploySite')
            ->with('12345', '6789');

        $this->command(Deploy::class)->execute([
            'server' => 12345,
            'site' => 6789,
        ]);
    }

    /** @test */
    public function it_creates_a_site()
    {
        $this->forge->expects($this->once())
            ->method('createSite')
            ->with(12345, [
                'domain' => 'example.com',
                'project_type' => 'symfony_dev',
                'directory' => '/public',
            ], false);

        $this->command(Make::class)->execute([
            'server' => 12345,
            '--domain' => 'example.com',
            '--type' => 'symfony_dev',
            '--directory' => '/public',
        ]);
    }

    /** @test */
    public function it_defaults_to_php_site_when_not_supplying_the_option()
    {
        $this->forge->expects($this->once())
            ->method('createSite')
            ->with(12345, [
                'domain' => 'example.com',
                'project_type' => 'php',
                'directory' => '/public_html',
            ], false);

        $this->command(Make::class)->execute([
            'server' => 12345,
            '--domain' => 'example.com',
            '--directory' => '/public_html',
        ]);
    }

    /** @test */
    public function it_defaults_to_public_directory_when_not_supplying_the_option()
    {
        $this->forge->expects($this->once())
            ->method('createSite')
            ->with(12345, [
                'domain' => 'example.com',
                'project_type' => 'Symfony',
                'directory' => '/public',
            ], false);

        $this->command(Make::class)->execute([
            'server' => 12345,
            '--domain' => 'example.com',
            '--type' => 'Symfony',
        ]);
    }

    /** @test */
    public function it_shows_information_about_a_site()
    {
        $this->forge->expects($this->once())
            ->method('site')
            ->with(12345, 6789)
            ->willReturn(new Site([], $this->forge));

        $this->command(Show::class)->execute([
            'server' => 12345,
            'site' => 6789,
        ]);
    }

    /** @test */
    public function it_updates_a_site()
    {
        $this->forge->expects($this->once())
            ->method('updateSite')
            ->with(12345, 6789, [
                'directory' => '/public_html',
            ]);

        $this->command(Update::class)->execute([
            'server' => 12345,
            'site' => 6789,
            '--directory' => '/public_html',
        ]);
    }
}
