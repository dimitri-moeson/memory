# memory
web memory game 

- Le endpoint est situé dans le dossier `public`

- Plusieurs namespaces/dossiers :
   + `\kernel` : le noyau , 
   + `\app\controller` : les contrôleurs, 
   + `\app\model` : les classes, 

- Routes : `{cnt_name}.{action}`

- Controllers :
    + appel à la base (2 méthodes) : 
        - SQL natif
            + `$this->DBprepare(requete)`
        - requete sur les classes:
            + `Builder->execute()`

- Vues/Templates :
    + dans le dossier `app/view/`
    + les données du controller sont "_extract_" en variables simples 
        - `$this->variable` {controller} devient `$variable` {view}
    + modifier la vue par defaut dans un contrôleur :
       ` $this->render('view');` 

- BDD :
    + Fichier de config `config/db.ini`
       
- Installation
    + Créer les tables à partir du fichier `config\db.sql`
    + Modifier le fichier `config/db.ini`
