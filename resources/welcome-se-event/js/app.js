$(document).ready(function() {
  let shownPlayers = [];
  let shownFRQs = [];

  function processQuestionChange(item) {
    let elm;
    if (!('answers' in item)) {
      return console.log(item);
    }
    item.answers.forEach((option) => {
      let counter = $('#fake_element_hack_sorry');
      if (option.answer.length == 1) {
        counter = $('#key-'+option.answer+'-count');
      }
      
      let frqs = $('#free_response');

      if (counter.length) {
        counter.html(option.answer_count);
      }
      else if (frqs.length) {
        item.answers.forEach((answer_obj, key) => {
          if (shownFRQs.indexOf(key) == -1) {
            shownFRQs.push(key);
            let frq = '<li>'+answer_obj.answer+'</li>';
            frqs.append(frq);
          }
        });
      }
      else {
        console.log('Cannot find option with key ' + option.answer);
      }
    });
  }

  function processPlayersChange(players) {
    if ($('#player-list').length) {
      if (!Array.isArray(players)) {
        return console.log(players);
      }
      else {
        players.forEach((player, key) => {
          if (shownPlayers.indexOf(key) == -1) {
            shownPlayers.push(key);
            let playerItem = '<li>'+player+'</li>';
            $('#player-list').append(playerItem);
          }
        });
      }
    }
  }

  // Function to get Sync token from Twilio Function

  function getSyncToken(callback) {
    $.getJSON('https://gameshow-9209.twil.io/sync_token_prod')
      .then(function(data) {
        callback(data);
      });
  }

  // Connect to Sync "MagicTexters" List

  function startSync(token) {
    var syncClient = new Twilio.Sync.Client(token);

    syncClient.on('tokenAboutToExpire', function() {
      var token = getSyncToken(function(token) {
        syncClient.updateToken(token);
      });
    });

    if ((window.game)) {
      syncClient.map('game-'+window.game).then(function(map) {
        console.log('Hooking to map item updated for game-'+window.game);

        map.get('game-'+window.game+'-players').then(function(item) {
          processPlayersChange(item.data.players);
        });

        if (window.question) {
          map.get('game-'+window.game+'-question-'+window.question).then(function(item) {
            processQuestionChange(item.data);
          })
        }

        map.on('itemUpdated', function(event) {
          let item = event.item.descriptor;

          if (window.question) {
            if (item.key == 'game-'+window.game+'-question-'+window.question) {
              processQuestionChange(item.data);
            }
          }

          if (item.key == 'game-'+window.game+'-players') {
            processPlayersChange(item.data.players);
          }

        })
      });
    }
  }

  getSyncToken(function(responseData) {
    startSync(responseData.token);
  });

});