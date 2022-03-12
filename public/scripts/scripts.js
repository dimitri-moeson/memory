
    /**
     * Liste des balises CSS utilisant les illustrations
     * @type {string[]}
     */
    const icons = [

        'mem-icon pomme-rouge',
        'mem-icon orange',
		'mem-icon banane',
        'mem-icon citron-vert',
        'mem-icon goyave',
        'mem-icon abricot',
        'mem-icon citron-jaune',
        'mem-icon fraise',
        'mem-icon pomme-verte',
        'mem-icon peche',
        'mem-icon raisin',
        'mem-icon pasteque',
        'mem-icon prune',
        'mem-icon poire',
        'mem-icon cerise-rouge',
        'mem-icon framboise',
        'mem-icon mangue',
        'mem-icon cerise-jaune'

    ];



    // Duplicate elements of an array
    const duplicate = arr => {
        return arr.concat(arr).sort();
    };

    // Check if two cards are a match
    const checkMatch = icons => {
        if (icons[0] === icons[1]) {
            console.log("it's a match");
            return true;
        }
    };

    /**
     *
     * @type {number} compte le nombre de paire retournée
     */
    var tryCount = 0 ;

    /**
     *
     * @type {number} temps imparti de 5 minutes
     */
    var maxTimes = 300  ;

    /**
     * gestionnaire du compte à rebours
     */
    var interval ;

    /**
     *
     * @type {number} temps écoulés
     */
    var restTimes = maxTimes ;

    new Vue({

        el: "#app",

        data: {
            cards: _.range(0, icons.length * 2),
            runing: false
        },

        methods: {

            /** Create cards array based on icons, shuffle them **/
            cardsShuffle() {

                // prep objects
                this.cards.forEach((card, index) => {
                    this.cards[index] = {
                        icon: '',
                        down: true,
                        matched: false
                    };
                });

                // input every icon two times
                icons.forEach((icon, index) => {
                    this.cards[index].icon = icon;
                    this.cards[index + icons.length].icon = icon;
                });
                this.cards = _.shuffle(this.cards);

                // on demarre les compteurs
	            tryCount = 0 ;

                // on lance les timers
                this.countdown();
            },

            /** click sur une carte **/
            handleClick(cardClicked)
            {
                if (!this.runing) {

                    // turn card up
                    if (!cardClicked.matched && this.cardCount.cardsUp < 2) {
                        cardClicked.down = false;
                    }

                    // when two cards are up, check if they match or turn them down
                    if (this.cardCount.cardsUp === 2) {

		                // on incremente le nombre d'essais
		                tryCount++;
		                document.getElementById("tryCount").innerHTML = tryCount+" essais";

                        this.runing = true;

		                setTimeout(() => {
                            let match = checkMatch(this.cardCount.icons);
                            this.cards.forEach(card => {
                                if (match && !card.down && !card.matched) {
                                    card.matched = true;
                                } else {
                                    card.down = true;
                                }
                            });

                            this.runing = false;
                        }, 500);
                    }
                }
            },



            /** gestion du chronometre et de la progress bar **/
            countdown() {

                interval = setInterval( function() {

                    restTimes--;

                    let totalSeconds =  parseInt(maxTimes) - parseInt(restTimes) ;

                    /** progress bar */
                    let progress = (totalSeconds/maxTimes)*100;

                     if(progress >= 66 && progress < 99 ) document.getElementById("myBar").style.backgroundColor = 'red';
                else if(progress >= 33 && progress < 66 ) document.getElementById("myBar").style.backgroundColor = 'orange';
                else if(progress >=  0 && progress < 33 ) document.getElementById("myBar").style.backgroundColor = 'green';

                    document.getElementById("myBar").innerHTML = pad(parseInt(restTimes / 60)) + ":" + pad(restTimes % 60) ;
                    document.getElementById("myBar").style.width = progress + "%";

                    /** formate les timers **/
                    function pad(val)
                    {
                        let valString = val + "";
                        if(valString.length < 2)
                        {
                            return "0" + valString;
                        }
                        else
                        {
                            return valString;
                        }
                    }

                    if (restTimes === 0) {

                        clearInterval(interval);

                        let nom = prompt("Fin du temps imparti !"+"\n"+" Votre nom ?");

                        document.getElementById("input-nom").value = nom ;
                        document.getElementById("input-timer").value = totalSeconds ;
                        document.getElementById("input-try").value = tryCount ;
                        document.getElementById("input-status").value = "failure";
                        document.getElementById("form-game").submit();
                    }

                }, 1000);
            },
        },

        mounted() {
            this.cardsShuffle();
        },

        computed: {

            /** make a count of cards up and cards matched, keep icons of cards to check in array */
            cardCount () {

                let cardUpCount = 0;
                let cardMatchedCount = 0;
                let icons = [];

                this.cards.forEach(card => {
                    if (!card.down && !card.matched) {
                        cardUpCount++;
                        icons.push(card.icon);
                    }
                    if (card.matched) {
                        cardMatchedCount++;
                    }
                });
	  				  
		        return {
                    cardsUp: cardUpCount,
                    cardsMatched: cardMatchedCount,
                    icons: icons
		        };
            },

            /** update victory state **/
            victory () {

                if (restTimes > 0 && this.cardCount.cardsMatched === this.cards.length) {

                    let nom = prompt("Victoire !"+"\n"+"Votre nom ?");
                    let totalSeconds =  parseInt(maxTimes) - parseInt(restTimes) ;

                    /** record game ... **/

                    document.getElementById("input-status").value = "victory" ;
                    document.getElementById("input-nom").value = nom ;
                    document.getElementById("input-timer").value = totalSeconds ;
                    document.getElementById("input-try").value = tryCount ;

                    document.getElementById("form-game").submit();
                }
            }
        }
    });