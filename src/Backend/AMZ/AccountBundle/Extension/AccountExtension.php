<?php
namespace App\Backend\AMZ\AccountBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use App\Backend\AMZ\AccountBundle\Repository\AccountRepository;

class AccountExtension extends AbstractExtension
{
     public function __construct(
        private readonly AccountRepository $repository,
     )
    {
     
    }
    
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('testFunction',[$this,'testFunction']),
        );
    }
    
     public function getFilters(): array
    {
        return [
            new TwigFilter('testFilter',[$this,'testFilter']),
        ];
    }
    
    public function testFilter(): string
    {
        return 'testing filter';
    }
    
     public function testFunction(): string
    {
        return 'testing Function';
    }
}
