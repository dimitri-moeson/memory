<?php namespace app\controller ;


use app\model\Ladder;
use kernel\Builder;
use kernel\Controller;
use kernel\GlobalData;

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

            /**
             * on enregistre le resultat de la partie joué
             * @var Builder Ladder
             */
            Ladder::save($ladd);

            /** on recharge la page */
            header("location:?p=default.ladder");
        }
    }

    function ladder(){

        /**
         * @var Builder Ladder
         */
        $this->ladders = Ladder::select('*')->order("timer","ASC")->order("try",'DESC')->execute();
    }

    /**
     *
     */
    function index(){

        $this->render(  "game");

    }

    /**
     *
     */
    function game(){

        $this->render(  "game");
    }
}