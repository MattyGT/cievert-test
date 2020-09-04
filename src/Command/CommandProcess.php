<?php
namespace App\Command;

use App\Interfaces\CommandProcessInterface;
use App\Services\WebsiteCheck;
use App\Services\Communication;
use App\Services\CommunicationSMS;
use App\Services\CommunicationEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandProcess extends Command {

  protected function configure() {
    $this->setName('test:checkwebsite')
         ->setDescription('Check a website and send notifications using arguments')
         ->addArgument('url', InputArgument::REQUIRED, 'Please enter the website URL?')
         ->addOption('status', null, InputOption::VALUE_REQUIRED, 'This option will return the website status.')
         ->addOption('title', null, InputOption::VALUE_NONE, 'This option will return the website title.');
  }

  public function execute(InputInterface $input, OutputInterface $output) {
    $url = $input->getArgument('url');
    $output->writeln(' - Initiating connection to ' . $url . '.');
    $webResult = new Communication($url);
    if ( $input->getOption('title') ) {
      $output->writeln(' - Website title returned: ' . $webResult->getTitle() . '.');
    } else {
      $code = $webResult->getCode();
      $output->writeln(' - Status code returned: ' . $code . '.');
      if ( (int)$code != (int)$input->getOption('status') ) {
        $types = $commResults = [];
        $types[] = new CommunicationSMS(); 
        $types[] = new CommunicationEmail(); 
        if (isset($types) && count($types)) {
          $commResults[] = $webResult->sendMessages($types);
        } 
        if ( isset($commResults) && count($commResults) > 0 ) {
          foreach ( $commResults as $commResult ) {
            $output->writeln($commResult);
          }
        }
      }
    }
    return Command::SUCCESS;
  }
  
}
?>