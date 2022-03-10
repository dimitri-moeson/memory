// Liste des balises CSS utilisant les illustrations
		const icons = [

			'mem-icon pomme-rouge',
            'mem-icon orange',
/**			'mem-icon banane',
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
			'mem-icon cerise-jaune'**/

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

var tryCount = 0 ;
var progress = 0 ;
var totalSeconds = 0;
var interval;
var minutes = 1;
var seconds = 5;

new Vue({
  el: "#app",
  data: {
    cards: _.range(0, icons.length * 2),
    runing: false },

  methods: {
    // Create cards array based on icons, shuffle them
    cardsShuffle() {
      // prep objects
      this.cards.forEach((card, index) => {
        this.cards[index] = {
          icon: '',
          down: true,
          matched: false };

      });
      // input every icon two times
      icons.forEach((icon, index) => {
        this.cards[index].icon = icon;
        this.cards[index + icons.length].icon = icon;
      });
      this.cards = _.shuffle(this.cards);
	  
	   tryCount = 0 ;
		progress = 0 ;
    },

    // record game ...
    gameRecord() {

          nom = prompt("Votre nom ?");

          document.getElementById("input-nom").value = nom ;
            document.getElementById("input-timer").value = totalSeconds ;
        document.getElementById("input-try").value = tryCount ;
        document.getElementById("input-status").value = status ;

        document.getElementById("form-game").submit();

    },

    handleClick(cardClicked) {
      if (!this.runing) {
        // turn card up
        if (!cardClicked.matched && this.cardCount.cardsUp < 2) {
          cardClicked.down = false;
        }
        // when two cards are up, check if they match or turn them down
        if (this.cardCount.cardsUp === 2) {
          this.runing = true;
		  
		   setInterval(this.setTime, 1000);

		  tryCount++;

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
	
	// gestion du chronometre...
	setTime ()
    {
             ++totalSeconds;
    },

      frame() {

          if(progress < percent){
              progress++;
              document.getElementById("myBar").style.width = progress + "%";
              document.getElementById("myBar").innerHTML = progress  + "%";
          }
      },

      countdown() {
          interval = setInterval(function() {

              if(seconds == 0) {
                  if(minutes == 0) {
                      //el.innerHTML = "countdown's over!";
                      clearInterval(interval);
                      return;
                  } else {
                      minutes--;
                      seconds = 60;
                  }
              }

              var minute_text ;
              var second_text;

              if(minutes >= 10) {
                  minute_text = minutes ;
              } else if(minutes < 10 && minutes > 0) {
                  minute_text = "0"+minutes ;
              } else {
                  minute_text = '00';
              }

              if(seconds >= 10) {
                  second_text = seconds ;
              } else if(seconds < 10 && seconds > 0) {
                  second_text = "0"+seconds ;
              } else {
                  second_text = '00';
              }

              document.getElementById('countdown').innerHTML = minute_text + ':' + second_text ;
              seconds--;
          }, 1000);
      }

        
  },



  mounted() {
    this.cardsShuffle();
  },
  computed: {
    // make a count of cards up and cards matched, keep icons of cards to check in array
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
		let percent = parseFloat((cardMatchedCount / this.cards.length)*100) ;
	  				  
		document.getElementById("tryCount").innerHTML = tryCount+" essais";

		setInterval(this.frame, 100);

		this.countdown();

      return {
        cardsUp: cardUpCount,
        cardsMatched: cardMatchedCount,
        icons: icons };
    },
    // update victory state
    victory () {
      if (this.cardCount.cardsMatched === this.cards.length) {
	  
		this.setTime();
        return true;
      }
    } } });
//# sourceURL=pen.js