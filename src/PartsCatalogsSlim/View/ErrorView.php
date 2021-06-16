<?php

namespace PartsCatalogsSlim\View;

class ErrorView extends LayoutView
{
    private $code;
    private $message;
    /**
     * @var string
     */
    private $details;

    public function __construct(int $code, string $message, string $details = '')
    {
        $this->code    = $code;
        $this->message = $message;
        $this->details = $details;
    }


    public function code(): int
    {
        return $this->code;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function details(): string
    {
        return $this->details;
    }
}
