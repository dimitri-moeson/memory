<?php namespace app\controller ;


use app\model\Ladder;
use kernel\Controller;
use kernel\GlobalData;

class DefaultController extends Controller
{
    function ladder(){

        /**
         * @var Ladder $ladd
         */
        $ladd = Ladder::init();

        if(GlobalData::getInstance()->submitted())
        {
            /**
             * on enregistre le resultat de la partie joué
             */
            $ladd->setTimer(GlobalData::getInstance()->get('timer'));
            $ladd->setTry(GlobalData::getInstance()->get('try'));
            $ladd->setNom(GlobalData::getInstance()->get('nom'));

            $ladd->save();

            /**
             * on recharge la page
             */
            header("location:?p=ladder");
        }

        $ladders = $ladd->ordering();

        /**
         * on compresse/compile les donnees
         */
        $this->render( "ladder", compact( 'ladders' , 'ladd' ));
    }

    function index(){

        $this->render(  "game");

    }

    function game(){

        $this->render(  "game");

    }
}