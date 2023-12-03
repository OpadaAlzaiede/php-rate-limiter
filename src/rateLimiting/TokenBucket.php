<?php

namespace RateLimiting;

class TokenBucket
{
    private int $maxNumberOfTokens;
    private int $numOfAvailableTokens;
    private int $lastRefillTime;
    private int $nextRefillTime;
    private int $refillWindow;
    private static self $object;

    private function __construct(int $maxNumberOfTokens, int $refillWindow)
    {
        $this->maxNumberOfTokens = $maxNumberOfTokens;
        $this->refillWindow = $refillWindow;
        $this->refill();
    }

    public static function getRateLimiter(): self {

        if(!isset(self::$object)) {

            self::$object = new self(CONFIG['MAX_NUMBER_OF_TOKENS'], CONFIG['REFILL_WINDOW']);
        }

        return self::$object;
    }

    public function getToken(): bool {

        $currentTime = time();

        if($currentTime < $this->nextRefillTime) {

            if($this->numOfAvailableTokens <= 0) return false;

            $this->numOfAvailableTokens--;
            return true;
        }

        $this->refill();
        $this->numOfAvailableTokens--;
        return true;

    }

    private function refill(): void {

        $this->numOfAvailableTokens = $this->maxNumberOfTokens;
        $this->lastRefillTime = time();
        $this->nextRefillTime = $this->lastRefillTime + $this->refillWindow;
    }
}
