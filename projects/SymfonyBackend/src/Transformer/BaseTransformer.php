<?php
namespace App\Transformer;
use \League\Fractal\TransformerAbstract;
use PhpParser\Builder\Class_;

abstract class BaseTransformer extends TransformerAbstract
{
    public const BASIC = 'BASIC';
    public const FULL_TRANSFORM = 'FULL_TRANSFORM';

    private string $mode;

    public function __construct(string $mode = self::BASIC)
    {
        $this->mode = $mode;
    }

    public function transform(object $object): ?array
    {
        if ($this->mode === self::BASIC){
            return $this->basicTransform($object);
        } elseif ($this->mode === self::FULL_TRANSFORM){
            return $this->fullTransform($object);
        } else {
            throw new \InvalidArgumentException('Transformer mode is not supported');
        }
    }

    abstract protected function basicTransform($object): ?array;

    abstract protected function fullTransform($object): ?array;

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}
