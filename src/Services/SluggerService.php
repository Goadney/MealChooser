<?php
namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;

class SluggerService 
{ 
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) // Injection correcte
    {
        $this->slugger = $slugger;
    }

    public function generateSlug(string $name): string
    {
        return $this->slugger->slug($name)->lower();
    }
}
