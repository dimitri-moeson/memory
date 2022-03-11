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
             * @var Builder $ladd
             */
            Ladder::save($ladd);

            /** on recharge la page */
            header("location:?p=default.ladder");
        }
    }

    function ladder(){

        /**
         * @var Builder $ladd
         */
        $ladd = Ladder::init();

        $this->ladders = $ladd::select('*')->order("timer","ASC")->order("try",'DESC')->execute();
        $this->count = $ladd::count("id",true )->order("timer","ASC")->order("try",'DESC')->execute(true);
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