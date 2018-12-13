<?php



namespace App\Tests\Command;



use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class QuoteRandomCommandTest extends KernelTestCase
{
    public function testExecute(){
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:quote-random');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('OK', $output);
    }
}