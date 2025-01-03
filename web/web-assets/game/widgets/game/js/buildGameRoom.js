const gameVars = {
    initRetryCounter: 0,
    initRetryCounterLimit: 3,
    // gameType: 'base',
}

const gameFunctions = {
    joinGame: (id) => {
        gameVars.initRetryCounter = 0;
        $.ajax({
            url: '/game/room/join?id=' + id,
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                gameFunctions.initGame(id);
            },
            error: () => {
                roomFunctions.addError()
            },
        })
    },
    // pass id for a possibility of a player having many concurrent games at once
    initGame: (id, resetGameWrapper = true) => {
        console.log(id);
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
                console.log('init successful');
                // gameFunctions.fillRoomTemplates(response);
                gameFunctions.buildRoom(response);
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
        // gameWrapper.append($(gameTemplates.chatWrapper));
        // gameFunctions.initChat(response.id);
    },
    // todo: implement initChat
    initChat: (id) => {
        console.log('todo: implement initChat')
    },
    quitGame: (id) => {
        console.log(`quit game with id ${id}`);
        //todo: add AJAX to /game/room/leave
        buildListFunctions.initList();
    },
}
