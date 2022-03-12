<?php namespace app\controller {

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
         * enregistrement de la partie puis redirection vers le classement
         */
        function save()
        {
            /**
             *  si on soumet un formulaire
             */
            if (GlobalData::getInstance()->submitted())
            {
                /**
                 * on instancie un objet ladder
                 * @var Ladder $ladd
                 */
                $ladd = Ladder::init()

                    /** on affecte les données du formulaire à l'objet Ladder */

                    ->setTimer(GlobalData::getInstance()->get('timer'))
                    ->setTry(GlobalData::getInstance()->get('try'))
                    ->setNom(GlobalData::getInstance()->get('nom'))
                    ->setStatus(GlobalData::getInstance()->get('status'))
                ;

                /** on enregistre le resultat de la partie joué */
                $ladd->save();

                /** on recharge la page */
                $this->redirect("default.ladder");
            }
        }

        /**
         * classement des joueurs
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

            $this->count = count($this->ladders);
        }

        /**
         * page par defaut : classement des joueurs
         */
        function index()
        {
            $this->ladder();
            $this->render(  "ladder");
        }

        /**
         * page du jeu
         */
        function game()
        {
            $this->render(  "game");
        }
    }
}