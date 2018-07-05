<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\CurrencyObject;
use App\Service\FixerIOService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Undocumented class
 */
class MainController extends Controller
{
    private $_fixerIOService;

    public function __construct(FixerIOService $fixerIOService)
    {
        $this->_fixerIOService = $fixerIOService;
    }

    public function index(Request $request)
    {    
        $cur = new CurrencyObject($this->_fixerIOService);        

        $currencies = $this->_fixerIOService->symbols();

        $form = $this->createFormBuilder($cur)
            ->add('amount', NumberType::class)
            ->add(
                'baseCurrency', 
                CurrencyType::class, array(
                    'placeholder' => false,
                    'choice_loader' => null,
                    'choices' => $currencies
                )
            )
            ->add(
                'targetCurrency', 
                CurrencyType::class, array(
                    'placeholder' => false,
                    'choice_loader' => null,
                    'choices' => $currencies
                )
            )
            ->add('convert', SubmitType::class, array('label' => "convert"))
            ->getForm();

        $form->handleRequest($request);


        $am = $cur->getTargetAmount();

        if ($cur->getErrorMessage() !== "")
        {
            $this->get('session')->getFlashBag()->set(
                'error',
                $cur->getErrorMessage()
            );            
        }

        return $this->render(
            'main/index.html.twig', 
            array(
                'target_amount' => $am,
                'form' => $form->createView(),
            )
        );
    }



}