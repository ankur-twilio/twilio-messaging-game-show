/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/welcome-se-event/js/app.js":
/*!**********************************************!*\
  !*** ./resources/welcome-se-event/js/app.js ***!
  \**********************************************/
/***/ (() => {

$(document).ready(function () {
  var shownPlayers = [];
  var shownFRQs = [];

  function processQuestionChange(item) {
    var elm;

    if (!('answers' in item)) {
      return console.log(item);
    }

    item.answers.forEach(function (option) {
      var counter = $('#fake_element_hack_sorry');

      if (option.answer.length == 1) {
        counter = $('#key-' + option.answer + '-count');
      }

      var frqs = $('#free_response');

      if (counter.length) {
        counter.html(option.answer_count);
      } else if (frqs.length) {
        item.answers.forEach(function (answer_obj, key) {
          if (shownFRQs.indexOf(key) == -1) {
            shownFRQs.push(key);
            var frq = '<li>' + answer_obj.answer + '</li>';
            frqs.append(frq);
          }
        });
      } else {
        console.log('Cannot find option with key ' + option.answer);
      }
    });
  }

  function processPlayersChange(players) {
    if ($('#player-list').length) {
      if (!Array.isArray(players)) {
        return console.log(players);
      } else {
        players.forEach(function (player, key) {
          if (shownPlayers.indexOf(key) == -1) {
            shownPlayers.push(key);
            var playerItem = '<li>' + player + '</li>';
            $('#player-list').append(playerItem);
          }
        });
      }
    }
  } // Function to get Sync token from Twilio Function


  function getSyncToken(callback) {
    $.getJSON('https://gameshow-9209.twil.io/sync_token_prod').then(function (data) {
      callback(data);
    });
  } // Connect to Sync "MagicTexters" List


  function startSync(token) {
    var syncClient = new Twilio.Sync.Client(token);
    syncClient.on('tokenAboutToExpire', function () {
      var token = getSyncToken(function (token) {
        syncClient.updateToken(token);
      });
    });

    if (window.game) {
      syncClient.map('game-' + window.game).then(function (map) {
        console.log('Hooking to map item updated for game-' + window.game);
        map.get('game-' + window.game + '-players').then(function (item) {
          processPlayersChange(item.data.players);
        });

        if (window.question) {
          map.get('game-' + window.game + '-question-' + window.question).then(function (item) {
            processQuestionChange(item.data);
          });
        }

        map.on('itemUpdated', function (event) {
          var item = event.item.descriptor;

          if (window.question) {
            if (item.key == 'game-' + window.game + '-question-' + window.question) {
              processQuestionChange(item.data);
            }
          }

          if (item.key == 'game-' + window.game + '-players') {
            processPlayersChange(item.data.players);
          }
        });
      });
    }
  }

  getSyncToken(function (responseData) {
    startSync(responseData.token);
  });
});

/***/ }),

/***/ "./resources/welcome-se-event/css/app.scss":
/*!*************************************************!*\
  !*** ./resources/welcome-se-event/css/app.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/welcome-se-event/js/app": 0,
/******/ 			"welcome-se-event/css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["welcome-se-event/css/app"], () => (__webpack_require__("./resources/welcome-se-event/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["welcome-se-event/css/app"], () => (__webpack_require__("./resources/welcome-se-event/css/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;