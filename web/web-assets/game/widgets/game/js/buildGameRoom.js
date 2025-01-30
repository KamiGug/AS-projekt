const gameVars = {
    initRetryCounter: 0,
    initRetryCounterLimit: 3,
    playerNumber: -1,
    maxRefreshTime: 3000,
    minRefreshTime: 500,
    failRefreshTimeMultiplier: 0.9,
    refreshTime: 3000,
    lastRefresh: null,
    // gameType: 'base',
    gameId: null,
    boardState: null,
    moveList: null,
    refreshCallback: null,
    board: null,
    game: null,
    takenSeats: null,
}
gameVars.refreshTime = gameVars.maxRefreshTime;


const gameFunctions = {
    joinGame: (id) => {
        gameVars.initRetryCounter = 0;
        $.ajax({
            url: '/game/room/join?id=' + id,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                gameVars.id = response.id
                gameFunctions.initGame(gameVars.id);
            },
            error: () => {
                // roomFunctions.addError()
            },
        })
    },
    // pass id for a possibility of a player having many concurrent games at once
    initGame: (id, resetGameWrapper = true) => {
        // buildListFunctions.refreshList();
        if (resetGameWrapper) {
            roomFunctions.resetGameWrapper();
        }
        $.ajax({
            url: '/game/room/init-room?id=' + id,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                gameVars.initRetryCounter = 0;
                // gameFunctions.fillRoomTemplates(response);
                console.log('response at init game', response);
                gameVars.gameId = response.id;
                gameVars.roomName = response.roomName;
                gameFunctions.buildRoom(response);
                if (startGame != null) {
                    startGame();
                } else {
                    console.log('missing startGame()');
                }
                gameFunctions.keepRefreshing();
            },
            error: () => {
                if (++gameVars.initRetryCounter > gameVars.initRetryCounterLimit) {
                    return gameFunctions.quitGame(id);
                } else {
                    setTimeout(() => {
                        gameFunctions.initGame(id, false)
                    }, 1000)
                }
            },
        });
    },
    buildRoom: (response) => {
        const gameWrapper = $('#game-wrapper');
        gameWrapper.append(response.board);
        console.log(gameWrapper);
        // gameWrapper.append($(gameTemplates.chatWrapper));
        // gameFunctions.initChat(response.id);
    },
    // todo: implement initChat
    initChat: (id) => {
        console.log('todo: implement initChat')
    },
    resetGameVars: () => {
        gameVars.gameId = null;
        gameVars.roomName = null;
        gameVars.lastRefresh = null;
        gameVars.refreshTime = gameVars.maxRefreshTime;
        gameVars.boardState = null;
        gameVars.moveList = null;
        gameVars.refreshCallback = null;
        gameVars.board = null;
        gameVars.game = null;
        gameVars.takenSeats = null;
    },
    quitGame: (id) => {

        $.ajax({
            url: '/game/room/leave?id=' + id,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                buildListFunctions.initList();
                gameFunctions.resetGameVars();
            },
            error: (response) => {
                console.log('error: ' + response);
                // roomFunctions.addError()
            },
        })
    },
    refreshVarsUpdate: (response) => {
        // send null if there were no changes since last request (to save bandwidth)
        if (
            response.playerNumber != null
            && response.boardState != null
            && response.moveList != null
            && response.activePlayerNumbers != null
        ) {
            gameVars.playerNumber = response.playerNumber;
            gameVars.boardState = JSON.parse(response.boardState);
            gameVars.moveList = JSON.parse(response.moveList);
            gameVars.lastRefresh = response.lastRefresh ?? gameVars.lastRefresh;
            gameVars.takenSeats = JSON.parse(response.activePlayerNumbers);
            console.log('gameVars.takenSeats', gameVars.takenSeats)
        }
        if (gameVars.refreshCallback !== null && gameVars.board !== null) {
            gameVars.refreshCallback(gameVars.board);
        }
        gameVars.refreshTime = gameVars.maxRefreshTime;
    },
    refresh: () => {
        $.ajax({
            url: '/game/room/refresh?id=' + gameVars.gameId,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({
                    // id: gameVars.id,
                    lastRefresh: gameVars.lastRefresh,
                }),
            success: function(response) {
                gameFunctions.refreshVarsUpdate(response);
            },
            error: () => {
                if (gameVars.refreshTime >= gameVars.minRefreshTime) {
                    gameVars.refreshTime *= gameVars.failRefreshTimeMultiplier;
                }
            },
        });
    },
    keepRefreshing: () => {
        if (gameVars.gameId !== null) {
            gameFunctions.refresh();
            setTimeout(gameFunctions.keepRefreshing, gameVars.refreshTime);
        }
    },
    makeMove: (move) => {
        gameVars.lastRefresh = null;
        $.ajax({
            url: '/game/room/move?id=' + gameVars.gameId,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({
                move: move,
            }),
            success: function(response) {
                gameFunctions.refreshVarsUpdate(response);
            },
            error: () => {
                if (gameVars.refreshTime >= gameVars.minRefreshTime) {
                    gameVars.refreshTime *= gameVars.failRefreshTimeMultiplier;
                }
            },
        });
    },
    changePlayerNumber: (newPlayerNumber) => {
        $.ajax({
            url: `/game/room/change-player-number?roomId=${gameVars.gameId}&playerNumber=${newPlayerNumber}`,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            // data: JSON.stringify({
            //     move: move,
            // }),
            success: function(response) {
                gameFunctions.refreshVarsUpdate(response);
            },
            error: (response) => {

            },
        });
    }
}
