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

                    ->setTimer(GlobalData::getInstance()->get('timer',null))
                    ->setTry(GlobalData::getInstance()->get('try' ,null ))
                    ->setNom(GlobalData::getInstance()->get('nom', null))
                    ->setStatus(GlobalData::getInstance()->get('status' ,null))
                ;

                /** on enregistre le resultat de la partie joué */
                $ladd->save();

                /** on recharge la page */
                $this->redirect("ladder");
            }
        }

        /**
         * classement des joueurs
         * @throws \Exception
         */
        function ladder()
        {
            /**
             * @var Builder $ladd
             */
            $ladd = Ladder::init();

            /**
             * @var Builder $req
             */
            $req = Builder::init();

            try {

                /** @var Builder associé à une Entity */

                $this->ladders = $ladd::select('*')
                    ->where("nom is not null", "nom <> '' ","status = :status")
                    ->order("timer", "ASC")
                    ->order("try", 'DESC')
                    ->execute(false, array("status" => "victory"));

                /** @var Builder non-associé à une Entity => retourne un stdClass */

                $this->count = $req->count('id',true,'cnt')
                    ->from("ladder")
                    ->where("nom is not null", "nom <> '' ","status = :status")
                    ->order("timer", "ASC")
                    ->order("try", 'DESC')
                    ->execute(true , array("status" => "victory"));

            } catch (\Exception $e) {

                die( $e->getMessage() );
            }
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