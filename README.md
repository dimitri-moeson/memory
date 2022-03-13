# memory
web memory game 

- Le endpoint est situé dans le dossier `public`

- Plusieurs namespaces/dossiers :
   + le noyau : `\kernel`
   + les contrôleurs : `\app\controller` 
   + les classes : `\app\model`

- Routes (3 méthodes) : 
    - basique : `?p={cnt_name}.{action}`
    - rewrite : `/{cnt_name}/{action}`
    - docblock : `@Route('/{alias_cnt}/{alias_act}')`

- Controllers :
    + appel à la base (2 méthodes) : 
        - SQL natif
            + `$this->DBprepare(requete)`
        - requete sur les classes:
            + `Builder->execute()`
    + modifier la vue par defaut  :
       `$this->render('view');` 

- Vues/Templates :
    + dans le dossier `app/view/`
    + les données du controller sont "_extract_" en variables simples 
        - `$this->variable` {controller} devient `$variable` {view}

- BDD :
    + Fichier de config `config/db.ini`
       
- Installation
    + Créer les tables à partir du fichier `config\db.sql`
    + Modifier le fichier `config/db.ini`
