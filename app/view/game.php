<form id="form-game" method="post" action="?p=save" >

    <input type="hidden" id="input-timer" name="timer"/>
    <input type="hidden" id="input-try" name="try"/>
    <input type="hidden" id="input-nom" name="nom"/>
    <input type="hidden" id="input-status" name="status"/>
    <input type="hidden" id="record" name="record" value="1"/>

</form>

<div id="app">

    <div v-for="(card, index) in cards" :key="index" :class="[{'down': card.down && !card.matched, 'up': !card.down, 'matched': card.matched}, ' card']" v-on:click="handleClick(card)">
        <i :class="'fas ' + card.icon"></i>
    </div>


    <p class="victoryState" v-if="victory">&nbsp;</p>

    <div id="tryCount"></div>

</div>



​<div id="myProgress">
    <div id="myBar"></div>
</div>


<script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-1b93190375e9ccc259df3a57c1abc0e64599724ae30d7ea4c6877eb615f89387.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17-beta.0/vue.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js'></script>
<script src="scripts/scripts.js" id="rendered-js" ></script>

