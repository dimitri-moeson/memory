<?php namespace app\controller ;

use app\model\Ladder;
use kernel\Builder;
use kernel\Controller;
use kernel\GlobalData;

/**
 * Class DefaultController
 * @package app\controller
 */
class DefaultController extends Controller
{
    /**
     *
     */
    function save()
    {
        /**
         *  si on soumet un formulaire
         */
        if (GlobalData::getInstance()->submitted()) {
            /**
             * on instancie un objet ladder
             * @var Ladder $ladd
             */
            $ladd = Ladder::init();

            /** on affecte les données du formulaire à l'objet Ladder */
            $ladd->setTimer(GlobalData::getInstance()->get('timer'));
            $ladd->setTry(GlobalData::getInstance()->get('try'));
            $ladd->setNom(GlobalData::getInstance()->get('nom'));
            $ladd->setStatus(GlobalData::getInstance()->get('status'));

            /** on enregistre le resultat de la partie joué */
            $ladd->save();

            /** on recharge la page */
            $this->redirect("default.ladder");
        }
    }

    /**
     *
     */
    function ladder(){

        /**
         * @var Builder $ladd
         */
        $ladd = Ladder::init();

        $this->ladders = $ladd::select('*')
            ->where("status = :_status")
            ->where("nom is not null")
            ->order("timer","ASC")
            ->order("try",'DESC')
            ->execute( false , array("_status" => "victory")); //

        $this->count = $ladd::count("id",true  , 'counter')
            ->where("status = :_status")
            ->where("nom is not null")
            ->order("timer","ASC")
            ->order("try",'DESC')
            ->execute( true , array("_status" => "victory"));
    }

    /**
     *
     */
    function index()
    {
        $this->ladder();
        $this->render(  "ladder");
    }

    /**
     *
     */
    function game()
    {
        $this->render(  "game");
    }
}