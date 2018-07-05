<?php
// src/Entity/CurrencyObject.php
namespace App\Entity;

use App\Service\FixerIOService;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;

class CurrencyObject
{
    protected $baseCurrency = "EUR";
    protected $amount = 1;
    protected $targetCurrency = "USD";

    private $_fixerIOService;    

    public function __construct($fixerIOService)
    {
        $this->_fixerIOService = $fixerIOService;        
    }

    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;
    }

    public function getTargetCurrency()
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency($targetCurrency)
    {
        $this->targetCurrency = $targetCurrency;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getTargetAmount()
    {                
        return $this->_fixerIOService->convert($this->baseCurrency, $this->targetCurrency, $this->amount);
    }

    public function getErrorCode()
    {
        return $this->_fixerIOService->getErrorCode();
    }

    public function getErrorMessage()
    {
        return $this->_fixerIOService->getErrorMessage();
    }

}