<?php
namespace App\Backend\AMZ\StoreBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use App\Backend\AMZ\StoreBundle\Repository\StoreRepository;

class StoreExtension extends AbstractExtension
{
     public function __construct(
        private readonly StoreRepository $repository,
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
