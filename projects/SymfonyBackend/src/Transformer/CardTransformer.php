<?php

namespace App\Transformer;
class CardTransformer extends BaseTransformer
{
    public function __construct(string $mode)
    {
        parent::__construct($mode);
    }

    public function basicTransform($card): array
    {
        return [
            'id' => $card->getId(),
            'number' => $card->getNumber(),
            'suit' => $card->getSuit()
        ];
    }

    public function fullTransform($card): array
    {
        return $this->basicTransform($card);
    }
}
