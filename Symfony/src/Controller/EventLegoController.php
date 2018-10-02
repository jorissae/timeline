<?php
namespace App\Controller;


use App\Configurator\EventConfigurator as Configurator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The LEGO controller for Event
 * @Route("/admin/event")
 */
class EventLegoController extends Controller
{

    use \Idk\LegoBundle\Traits\ControllerTrait;
    const LEGO_CONFIGURATOR = Configurator::class;

}
